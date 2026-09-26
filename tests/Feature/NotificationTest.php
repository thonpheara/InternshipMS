<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
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
}
