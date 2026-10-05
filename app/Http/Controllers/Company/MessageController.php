<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\AppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display company inbox and active chat thread.
     */
    public function index(Request $request): View
    {
        $company = Auth::user()->companyProfile;
        abort_if(!$company, 403, 'Company profile not found.');

        $conversations = Conversation::with(['studentProfile.user', 'latestMessage', 'application.internshipPost'])
            ->where('company_profile_id', $company->id)
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        $activeConversation = null;
        $messages = collect();

        if ($conversations->isNotEmpty()) {
            $selectedId = $request->query('conversation_id');
            $activeConversation = $selectedId
                ? $conversations->firstWhere('id', $selectedId)
                : $conversations->first();

            if (!$activeConversation) {
                $activeConversation = $conversations->first();
            }

            if ($activeConversation) {
                abort_if($activeConversation->company_profile_id !== $company->id, 403, 'Unauthorized conversation.');

                // Mark unread messages sent by student as read
                Message::where('conversation_id', $activeConversation->id)
                    ->where('sender_id', '!=', Auth::id())
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);

                // Also mark related bell notifications as read
                Auth::user()->unreadNotifications
                    ->filter(fn ($n) => str_contains($n->data['action_url'] ?? '', 'conversation_id=' . $activeConversation->id))
                    ->markAsRead();

                $messages = $activeConversation->messages()
                    ->with('sender')
                    ->oldest()
                    ->get();
            }
        }

        return view('company.messages.index', compact('conversations', 'activeConversation', 'messages'));
    }

    /**
     * Send a new message as company recruiter.
     */
    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $company = Auth::user()->companyProfile;
        abort_if(!$company || $conversation->company_profile_id !== $company->id, 403, 'Unauthorized.');

        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body'      => trim($validated['body']),
            'is_read'   => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Send In-App Notification to student applicant
        $recipient = $conversation->getRecipientFor(Auth::user());
        if ($recipient) {
            $senderName = $company->company_name ?: Auth::user()->name;
            $recipient->notify(new AppNotification(
                title: 'New Message from ' . $senderName,
                message: Str::limit(trim($validated['body']), 60),
                actionUrl: route('student.messages.index', ['conversation_id' => $conversation->id]),
                icon: 'fa-solid fa-comments',
                color: 'blue'
            ));
        }

        return redirect()->route('company.messages.index', ['conversation_id' => $conversation->id]);
    }

    /**
     * Quick-launch or locate chat thread with an applicant.
     */
    public function startFromApplication(Application $application): RedirectResponse
    {
        $company = Auth::user()->companyProfile;
        abort_if(!$company || $application->internshipPost->company_profile_id !== $company->id, 403, 'Unauthorized application.');

        $conversation = Conversation::firstOrCreate(
            [
                'student_profile_id' => $application->student_profile_id,
                'company_profile_id' => $company->id,
                'application_id'     => $application->id,
            ],
            [
                'subject'         => 'Applicant: ' . $application->studentProfile->user->name . ' (' . $application->internshipPost->title . ')',
                'last_message_at' => now(),
            ]
        );

        return redirect()->route('company.messages.index', ['conversation_id' => $conversation->id]);
    }
}
