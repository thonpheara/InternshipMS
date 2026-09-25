<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access users index.
     */
    public function test_admin_can_view_users_index(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('User Management');
    }

    /**
     * Test admin can view dashboard with applicant growth analytics chart data.
     */
    public function test_admin_can_view_dashboard_with_chart_analytics(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Application & Placement Trends', false);
        $response->assertSee('Total Applied');
        $response->assertSee('Accepted Placements');
        $response->assertViewHas('chartData');
    }

    /**
     * Test admin can create a student user.
     */
    public function test_admin_can_create_student(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'John Student Test',
            'email' => 'john.student.test@example.com',
            'password' => 'secret12345',
            'role' => 'student',
            'status' => 'active',
            'student_id_number' => 'STU-TEST-001',
            'major' => 'Software Engineering',
            'gpa' => 3.75,
        ]);

        $response->assertRedirect(route('admin.users.index', ['role' => 'student']));
        $this->assertDatabaseHas('users', ['email' => 'john.student.test@example.com']);
        $this->assertDatabaseHas('student_profiles', ['student_id_number' => 'STU-TEST-001']);

        // Clean up
        $user = User::where('email', 'john.student.test@example.com')->first();
        if ($user) {
            $user->studentProfile()->forceDelete();
            $user->forceDelete();
        }
    }

    /**
     * Test admin can create a company user.
     */
    public function test_admin_can_create_company(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Alice Recruiter',
            'email' => 'alice.recruiter.test@example.com',
            'password' => 'secret12345',
            'role' => 'company',
            'status' => 'active',
            'company_name' => 'Acme Test Corp',
            'industry' => 'Cybersecurity',
            'location' => 'Phnom Penh',
        ]);

        $response->assertRedirect(route('admin.users.index', ['role' => 'company']));
        $this->assertDatabaseHas('users', ['email' => 'alice.recruiter.test@example.com']);
        $this->assertDatabaseHas('company_profiles', ['company_name' => 'Acme Test Corp']);
    }

    /**
     * Test admin can update an existing student user.
     */
    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $studentUser->studentProfile()->create([
            'student_id_number' => 'STU-ORIGINAL',
            'major' => 'Computer Science',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $studentUser), [
            'name' => 'Updated Student Name',
            'email' => $studentUser->email,
            'status' => 'inactive',
            'student_id_number' => 'STU-MODIFIED',
            'major' => 'Data Science',
        ]);

        $response->assertRedirect(route('admin.users.index', ['role' => 'student']));
        $this->assertDatabaseHas('users', [
            'id' => $studentUser->id,
            'name' => 'Updated Student Name',
            'status' => 'inactive',
        ]);
        $this->assertDatabaseHas('student_profiles', [
            'user_id' => $studentUser->id,
            'student_id_number' => 'STU-MODIFIED',
            'major' => 'Data Science',
        ]);
    }

    /**
     * Test admin can delete a user.
     */
    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create(['role' => 'student']);
        $targetUser->studentProfile()->create(['student_id_number' => 'STU-DELETE-ME']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $targetUser));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', ['id' => $targetUser->id]);
    }

    /**
     * Test admin cannot delete their own account.
     */
    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertSessionHas('error', 'You cannot delete your own administrator account.');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * Test non-admin users cannot access admin portal.
     */
    public function test_non_admin_cannot_access_admin_portal(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $company = User::factory()->create(['role' => 'company']);

        $studentResponse = $this->actingAs($student)->get(route('admin.dashboard'));
        $studentResponse->assertRedirect(route('student.dashboard'));

        $companyResponse = $this->actingAs($company)->get(route('admin.dashboard'));
        $companyResponse->assertRedirect(route('company.dashboard'));
    }
}
