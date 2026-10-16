<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Conversation;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RealtimeBroadcastingTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;
    protected StudentProfile $studentProfile;
    protected User $companyUser;
    protected CompanyProfile $companyProfile;
    protected InternshipPost $post;
    protected Application $application;
    protected Conversation $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create student
        $this->studentUser = User::create([
            'name'     => 'Realtime Student',
            'email'    => 'student.realtime@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'student',
            'status'   => 'active',
        ]);

        $this->studentProfile = StudentProfile::create([
            'user_id'            => $this->studentUser->id,
            'student_id_number'  => 'ST-2026-001',
            'major'              => 'Computer Science',
            'university'         => 'USEA',
            'eligibility_status' => 'eligible',
        ]);

        // Create company
        $this->companyUser = User::create([
            'name'     => 'Tech Corp Recruiter',
            'email'    => 'company.realtime@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'company',
            'status'   => 'active',
        ]);

        $this->companyProfile = CompanyProfile::create([
            'user_id'         => $this->companyUser->id,
            'company_name'    => 'Tech Corp Innovations',
            'industry'        => 'Software',
            'location'        => 'Siem Reap',
            'is_verified'     => true,
        ]);

        // Create post
        $this->post = InternshipPost::create([
            'company_profile_id' => $this->companyProfile->id,
            'title'              => 'Laravel & Android Intern',
            'slug'               => 'laravel-android-intern',
            'category'           => 'Engineering',
            'description'        => 'Internship description for testing.',
            'requirements'       => 'Basic PHP and Kotlin knowledge.',
            'type'               => 'hybrid',
            'location'           => 'Siem Reap',
            'stipend'            => 250,
            'slots'              => 2,
            'status'             => 'approved',
            'deadline'           => now()->addMonth(),
        ]);

        // Create application
        $this->application = Application::create([
            'internship_post_id' => $this->post->id,
            'student_profile_id' => $this->studentProfile->id,
            'status'             => 'under_review',
            'cover_letter'       => 'I am eager to join.',
            'applied_at'         => now(),
        ]);

        // Create conversation
        $this->conversation = Conversation::create([
            'student_profile_id' => $this->studentProfile->id,
            'company_profile_id' => $this->companyProfile->id,
            'application_id'     => $this->application->id,
            'subject'            => 'Interview Schedule',
            'last_message_at'    => now(),
        ]);
    }

    public function test_student_message_broadcasts_message_sent_event_and_returns_json(): void
    {
        Event::fake([MessageSent::class]);

        $response = $this->actingAs($this->studentUser)
            ->postJson(route('student.messages.store', $this->conversation), [
                'body' => 'Hello recruiter, I am ready for the interview!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status'  => 'success',
            'message' => [
                'body'        => 'Hello recruiter, I am ready for the interview!',
                'sender_name' => 'Realtime Student',
                'is_me'       => true,
            ],
        ]);

        Event::assertDispatched(MessageSent::class, function (MessageSent $event) {
            $channels = $event->broadcastOn();
            $channelNames = array_map(fn($c) => $c->name, $channels);

            return $event->message->body === 'Hello recruiter, I am ready for the interview!'
                && in_array('private-conversation.' . $this->conversation->id, $channelNames)
                && in_array('private-App.Models.User.' . $this->companyUser->id, $channelNames);
        });
    }

    public function test_company_message_broadcasts_message_sent_event_and_returns_json(): void
    {
        Event::fake([MessageSent::class]);

        $response = $this->actingAs($this->companyUser)
            ->postJson(route('company.messages.store', $this->conversation), [
                'body' => 'Great! Your interview is set for tomorrow at 2 PM.',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status'  => 'success',
            'message' => [
                'body'        => 'Great! Your interview is set for tomorrow at 2 PM.',
                'sender_name' => 'Tech Corp Recruiter',
                'is_me'       => true,
            ],
        ]);

        Event::assertDispatched(MessageSent::class, function (MessageSent $event) {
            $channels = $event->broadcastOn();
            $channelNames = array_map(fn($c) => $c->name, $channels);

            return $event->message->body === 'Great! Your interview is set for tomorrow at 2 PM.'
                && in_array('private-conversation.' . $this->conversation->id, $channelNames)
                && in_array('private-App.Models.User.' . $this->studentUser->id, $channelNames);
        });
    }

    public function test_app_notification_implements_should_broadcast_now(): void
    {
        $notification = new AppNotification(
            title: 'Real-Time Alert',
            message: 'Application has been accepted!',
            actionUrl: '/student/applications',
            icon: 'fa-solid fa-circle-check',
            color: 'emerald'
        );

        $this->assertInstanceOf(ShouldBroadcastNow::class, $notification);
        $this->assertContains('broadcast', $notification->via($this->studentUser));
        $this->assertContains('database', $notification->via($this->studentUser));

        $broadcastData = $notification->toBroadcast($this->studentUser)->data;
        $this->assertEquals('Real-Time Alert', $broadcastData['title']);
        $this->assertEquals('Application has been accepted!', $broadcastData['message']);
        $this->assertEquals('/student/applications', $broadcastData['action_url']);
    }

    public function test_broadcasting_auth_authorizes_conversation_participant(): void
    {
        // Participant student is authorized
        $this->assertTrue($this->conversation->isParticipant($this->studentUser));

        // Participant company is authorized
        $this->assertTrue($this->conversation->isParticipant($this->companyUser));

        // Stranger user is denied
        $stranger = User::create([
            'name'     => 'Stranger',
            'email'    => 'stranger@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'student',
            'status'   => 'active',
        ]);
        StudentProfile::create([
            'user_id'            => $stranger->id,
            'student_id_number'  => 'ST-STRANGER',
            'university'         => 'USEA',
        ]);

        $this->assertFalse($this->conversation->isParticipant($stranger));

        // Admin is authorized to monitor conversations
        $admin = User::create([
            'name'     => 'Admin Monitor',
            'email'    => 'admin.monitor@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
            'status'   => 'active',
        ]);
        $this->assertTrue($this->conversation->isParticipant($admin));
    }
}
