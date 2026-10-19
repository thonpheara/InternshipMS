<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConversationApiController extends BaseApiController
{
    /**
     * List all conversations for the authenticated user (student or company).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Conversation::with([
            'studentProfile.user',
            'companyProfile.user',
            'latestMessage',
            'application.internshipPost',
        ]);

        if ($user->isStudent()) {
            $studentId = $user->studentProfile?->id;
            if (!$studentId) {
                return $this->sendResponse([], 'No conversations found.');
            }
            $query->where('student_profile_id', $studentId);
        } elseif ($user->isCompany()) {
            $companyId = $user->companyProfile?->id;
            if (!$companyId) {
                return $this->sendResponse([], 'No conversations found.');
            }
            $query->where('company_profile_id', $companyId);
        }

        $conversations = $query->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        // Enhance list items with unread count and recipient info for mobile
        $data = $conversations->map(function ($convo) use ($user) {
            $recipient = $convo->getRecipientFor($user);

            return [
                'id'                 => $convo->id,
                'subject'            => $convo->subject,
                'application_id'     => $convo->application_id,
                'last_message_at'    => $convo->last_message_at,
                'unread_count'       => $convo->unreadCountFor($user),
                'recipient'          => [
                    'id'           => $recipient?->id,
                    'name'         => $recipient?->name,
                    'display_name' => $recipient?->display_name,
                    'avatar_url'   => $recipient?->avatar_url,
                    'role'         => $recipient?->role,
                ],
                'latest_message'     => $convo->latestMessage ? [
                    'id'         => $convo->latestMessage->id,
                    'body'       => $convo->latestMessage->body,
                    'sender_id'  => $convo->latestMessage->sender_id,
                    'is_me'      => $convo->latestMessage->sender_id === $user->id,
                    'created_at' => $convo->latestMessage->created_at,
                ] : null,
                'post_title'         => $convo->application?->internshipPost?->title,
            ];
        });

        return $this->sendResponse($data, 'Conversations retrieved successfully.');
    }

    /**
     * Get single conversation details and chat messages.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $conversation = Conversation::with([
            'studentProfile.user',
            'companyProfile.user',
            'application.internshipPost',
        ])->find($id);

        if (!$conversation) {
            return $this->sendError('Conversation not found.', [], 404);
        }

        if (!$conversation->isParticipant($user)) {
            return $this->sendError('Unauthorized conversation access.', [], 403);
        }

        // Mark incoming unread messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->get()
            ->map(function ($msg) use ($user) {
                return [
                    'id'               => $msg->id,
                    'conversation_id'  => $msg->conversation_id,
                    'sender_id'        => $msg->sender_id,
                    'sender_name'      => $msg->sender?->display_name ?? $msg->sender?->name,
                    'body'             => $msg->body,
                    'is_read'          => (bool) $msg->is_read,
                    'created_at'       => $msg->created_at,
                    'created_at_time'  => $msg->created_at?->format('h:i A'),
                    'is_me'            => $msg->sender_id === $user->id,
                ];
            });

        $recipient = $conversation->getRecipientFor($user);

        return $this->sendResponse([
            'conversation' => [
                'id'             => $conversation->id,
                'subject'        => $conversation->subject,
                'application_id' => $conversation->application_id,
                'post_title'     => $conversation->application?->internshipPost?->title,
                'recipient'      => [
                    'id'           => $recipient?->id,
                    'name'         => $recipient?->name,
                    'display_name' => $recipient?->display_name,
                    'avatar_url'   => $recipient?->avatar_url,
                    'role'         => $recipient?->role,
                ],
            ],
            'messages'     => $messages,
        ], 'Messages loaded successfully.');
    }

    /**
     * Send a new message inside a conversation.
     */
    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $conversation = Conversation::find($id);

        if (!$conversation) {
            return $this->sendError('Conversation not found.', [], 404);
        }

        if (!$conversation->isParticipant($user)) {
            return $this->sendError('Unauthorized conversation access.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Message body is required', $validator->errors(), 422);
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body'      => trim($request->body),
            'is_read'   => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Real-time broadcast
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            // Broadcast failure shouldn't fail HTTP request
        }

        // Notify other user
        $recipient = $conversation->getRecipientFor($user);
        if ($recipient) {
            $actionUrl = $recipient->isStudent()
                ? route('student.messages.index', ['conversation_id' => $conversation->id])
                : route('company.messages.index', ['conversation_id' => $conversation->id]);

            $recipient->notify(new AppNotification(
                title: 'New Message from ' . $user->display_name,
                message: Str::limit(trim($request->body), 60),
                actionUrl: $actionUrl,
                icon: 'fa-solid fa-comments',
                color: 'blue'
            ));
        }

        return $this->sendResponse([
            'id'              => $message->id,
            'conversation_id' => $conversation->id,
            'sender_id'       => $message->sender_id,
            'sender_name'     => $user->display_name,
            'body'            => $message->body,
            'is_read'         => false,
            'created_at'      => $message->created_at,
            'created_at_time' => $message->created_at->format('h:i A'),
            'is_me'           => true,
        ], 'Message sent successfully.', 201);
    }

    /**
     * Start or retrieve a conversation linked to an application.
     */
    public function startFromApplication(Request $request, int $applicationId): JsonResponse
    {
        $user = $request->user();

        $application = Application::with(['internshipPost.companyProfile', 'studentProfile.user'])
            ->find($applicationId);

        if (!$application) {
            return $this->sendError('Application not found.', [], 404);
        }

        $studentProfile = $application->studentProfile;
        $companyProfile = $application->internshipPost?->companyProfile;

        if (!$studentProfile || !$companyProfile) {
            return $this->sendError('Application participant profile missing.', [], 422);
        }

        // Must be either student or company owner
        $isStudentOwner = $user->isStudent() && $user->studentProfile?->id === $studentProfile->id;
        $isCompanyOwner = $user->isCompany() && $user->companyProfile?->id === $companyProfile->id;

        if (!$isStudentOwner && !$isCompanyOwner && !$user->isAdmin()) {
            return $this->sendError('Unauthorized to start this conversation.', [], 403);
        }

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'application_id'     => $application->id,
                'student_profile_id' => $studentProfile->id,
                'company_profile_id' => $companyProfile->id,
            ],
            [
                'subject'            => $application->internshipPost?->title ?? 'Internship Application',
                'last_message_at'    => now(),
            ]
        );

        return $this->sendResponse([
            'conversation_id' => $conversation->id,
            'subject'         => $conversation->subject,
        ], 'Conversation ready.');
    }
}
