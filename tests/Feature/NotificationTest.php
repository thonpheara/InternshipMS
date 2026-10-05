<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Conversation;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
            'status'   => 'active',
        ]);

        $this->student = User::create([
            'name'     => 'Sokha Chan',
            'email'    => 'sokha@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'student',
            'status'   => 'active',
        ]);
    }

    public function test_user_can_receive_and_view_notification(): void
    {
        $this->admin->notify(new AppNotification(
            title: 'Test Notification',
            message: 'This is a test notification message.',
            actionUrl: route('admin.dashboard'),
            icon: 'fa-solid fa-bell',
            color: 'emerald'
        ));

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Test Notification');
        $response->assertSee('This is a test notification message.');
    }

    public function test_user_can_mark_notification_as_read_and_redirect(): void
    {
        $this->admin->notify(new AppNotification(
            title: 'Actionable Notification',
            message: 'Click to go somewhere.',
            actionUrl: route('admin.companies.index'),
            icon: 'fa-solid fa-bell',
            color: 'amber'
        ));

        $notification = $this->admin->notifications()->first();
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($this->admin)->get(route('notifications.read', $notification->id));

        $response->assertRedirect(route('admin.companies.index'));
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $this->admin->notify(new AppNotification('Alert 1', 'Message 1'));
        $this->admin->notify(new AppNotification('Alert 2', 'Message 2'));

        $this->assertEquals(2, $this->admin->unreadNotifications()->count());

        $response = $this->actingAs($this->admin)->post(route('notifications.markAllRead'));

        $response->assertRedirect();
        $this->assertEquals(0, $this->admin->unreadNotifications()->count());
    }

    public function test_user_can_delete_notification(): void
    {
        $this->admin->notify(new AppNotification('Alert to delete', 'Will be deleted'));

        $notification = $this->admin->notifications()->first();
        $this->assertNotNull($notification);

        $response = $this->actingAs($this->admin)->delete(route('notifications.destroy', $notification->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    public function test_user_can_clear_all_notifications(): void
    {
        $this->admin->notify(new AppNotification('Alert A', 'Msg A'));
        $this->admin->notify(new AppNotification('Alert B', 'Msg B'));

        $this->assertEquals(2, $this->admin->notifications()->count());

        $response = $this->actingAs($this->admin)->delete(route('notifications.clearAll'));

        $response->assertRedirect();
        $this->assertEquals(0, $this->admin->notifications()->count());
    }

    public function test_student_sending_message_notifies_company(): void
    {
        $studentProfile = StudentProfile::create([
            'user_id' => $this->student->id,
            'eligibility_status' => 'eligible',
        ]);

        $companyUser = User::create([
            'name'     => 'Tech Corp',
            'email'    => 'hr@techcorp.com',
            'password' => bcrypt('password123'),
            'role'     => 'company',
            'status'   => 'active',
        ]);

        $companyProfile = CompanyProfile::create([
            'user_id'              => $companyUser->id,
            'company_name'         => 'Tech Corp',
            'industry'             => 'Technology',
            'verification_status'  => 'verified',
        ]);

        $post = InternshipPost::create([
            'company_profile_id' => $companyProfile->id,
            'title'              => 'Laravel Intern',
            'slug'               => 'laravel-intern-' . uniqid(),
            'description'        => 'Test job description for student intern',
            'location'           => 'Siem Reap',
            'location_type'      => 'on_site',
            'duration_weeks'     => 12,
            'vacancies_count'    => 2,
            'status'             => 'approved',
            'deadline'           => now()->addMonth(),
        ]);

        $application = Application::create([
            'internship_post_id' => $post->id,
            'student_profile_id' => $studentProfile->id,
            'status'             => 'pending',
        ]);

        $conversation = Conversation::create([
            'student_profile_id' => $studentProfile->id,
            'company_profile_id' => $companyProfile->id,
            'application_id'     => $application->id,
            'subject'            => 'Application: Laravel Intern',
            'last_message_at'    => now(),
        ]);

        $response = $this->actingAs($this->student)->post(route('student.messages.store', $conversation), [
            'body' => 'Hello, I am excited about this internship!',
        ]);

        $response->assertRedirect(route('student.messages.index', ['conversation_id' => $conversation->id]));

        // Assert company received a notification
        $this->assertEquals(1, $companyUser->unreadNotifications()->count());
        $notification = $companyUser->unreadNotifications()->first();
        $this->assertEquals('New Message from Sokha Chan', $notification->data['title']);
        $this->assertStringContainsString('Hello, I am excited about this internship!', $notification->data['message']);
        $this->assertEquals(route('company.messages.index', ['conversation_id' => $conversation->id]), $notification->data['action_url']);

        // Assert that when company views the conversation, notification is marked as read
        $this->actingAs($companyUser)->get(route('company.messages.index', ['conversation_id' => $conversation->id]));
        $this->assertEquals(0, $companyUser->unreadNotifications()->count());
    }

    public function test_company_sending_message_notifies_student(): void
    {
        $studentProfile = StudentProfile::create([
            'user_id' => $this->student->id,
            'eligibility_status' => 'eligible',
        ]);

        $companyUser = User::create([
            'name'     => 'Tech Corp',
            'email'    => 'hr@techcorp.com',
            'password' => bcrypt('password123'),
            'role'     => 'company',
            'status'   => 'active',
        ]);

        $companyProfile = CompanyProfile::create([
            'user_id'              => $companyUser->id,
            'company_name'         => 'Tech Corp',
            'industry'             => 'Technology',
            'verification_status'  => 'verified',
        ]);

        $post = InternshipPost::create([
            'company_profile_id' => $companyProfile->id,
            'title'              => 'Laravel Intern',
            'slug'               => 'laravel-intern-' . uniqid(),
            'description'        => 'Test job description for student intern',
            'location'           => 'Siem Reap',
            'location_type'      => 'on_site',
            'duration_weeks'     => 12,
            'vacancies_count'    => 2,
            'status'             => 'approved',
            'deadline'           => now()->addMonth(),
        ]);

        $application = Application::create([
            'internship_post_id' => $post->id,
            'student_profile_id' => $studentProfile->id,
            'status'             => 'pending',
        ]);

        $conversation = Conversation::create([
            'student_profile_id' => $studentProfile->id,
            'company_profile_id' => $companyProfile->id,
            'application_id'     => $application->id,
            'subject'            => 'Application: Laravel Intern',
            'last_message_at'    => now(),
        ]);

        $response = $this->actingAs($companyUser)->post(route('company.messages.store', $conversation), [
            'body' => 'We would love to invite you for an interview.',
        ]);

        $response->assertRedirect(route('company.messages.index', ['conversation_id' => $conversation->id]));

        // Assert student received a notification
        $this->assertEquals(1, $this->student->unreadNotifications()->count());
        $notification = $this->student->unreadNotifications()->first();
        $this->assertEquals('New Message from Tech Corp', $notification->data['title']);
        $this->assertStringContainsString('We would love to invite you for an interview.', $notification->data['message']);
        $this->assertEquals(route('student.messages.index', ['conversation_id' => $conversation->id]), $notification->data['action_url']);

        // Assert that when student views the conversation, notification is marked as read
        $this->actingAs($this->student)->get(route('student.messages.index', ['conversation_id' => $conversation->id]));
        $this->assertEquals(0, $this->student->unreadNotifications()->count());
    }
}
