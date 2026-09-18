<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display student messaging inbox and active chat thread.
     */
    public function index(Request $request): View
    {
        $student = Auth::user()->studentProfile;
        abort_if(!$student, 403, 'Student profile not found.');

        $conversations = Conversation::with(['companyProfile.user', 'latestMessage', 'application.internshipPost'])
            ->where('student_profile_id', $student->id)
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
                abort_if($activeConversation->student_profile_id !== $student->id, 403, 'Unauthorized conversation.');

                // Mark unread messages sent by company as read
                Message::where('conversation_id', $activeConversation->id)
                    ->where('sender_id', '!=', Auth::id())
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);

                $messages = $activeConversation->messages()
                    ->with('sender')
                    ->oldest()
                    ->get();
            }
        }

        return view('student.messages.index', compact('conversations', 'activeConversation', 'messages'));
    }

    /**
     * Send a new message within an existing conversation.
     */
    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $student = Auth::user()->studentProfile;
        abort_if(!$student || $conversation->student_profile_id !== $student->id, 403, 'Unauthorized.');

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

        return redirect()->route('student.messages.index', ['conversation_id' => $conversation->id]);
    }

    /**
     * Start or locate conversation thread directly from an internship application.
     */
    public function startFromApplication(Application $application): RedirectResponse
    {
        $student = Auth::user()->studentProfile;
        abort_if(!$student || $application->student_profile_id !== $student->id, 403, 'Unauthorized application.');

        $conversation = Conversation::firstOrCreate(
            [
                'student_profile_id' => $student->id,
                'company_profile_id' => $application->internshipPost->company_profile_id,
                'application_id'     => $application->id,
            ],
            [
                'subject'         => 'Application: ' . $application->internshipPost->title,
                'last_message_at' => now(),
            ]
        );

        return redirect()->route('student.messages.index', ['conversation_id' => $conversation->id]);
    }
}
