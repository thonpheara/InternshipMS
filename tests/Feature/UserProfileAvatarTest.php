<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_avatar_and_initials_accessors(): void
    {
        $user = User::factory()->create([
            'name' => 'Johnathan Doe',
            'role' => 'student',
            'avatar_path' => null,
        ]);

        $this->assertEquals('JD', $user->initials);
        $this->assertEquals('Johnathan Doe', $user->display_name);
        $this->assertEquals('Student Intern', $user->role_label);
        $this->assertNull($user->avatar_url);
        $this->assertFalse($user->has_avatar);

        // Test with avatar on public disk
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = $file->store('student-avatars', 'public');
        $user->update(['avatar_path' => $path]);

        $this->assertTrue($user->fresh()->has_avatar);
        $this->assertStringContainsString('student-avatars', $user->fresh()->avatar_url);
    }

    public function test_company_profile_logo_accessors(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Corporate Rep',
            'role' => 'company',
        ]);

        $company = CompanyProfile::create([
            'user_id' => $user->id,
            'company_name' => 'Acme Technologies',
            'industry' => 'Software',
            'verification_status' => 'verified',
        ]);

        $this->assertEquals('AT', $company->initials);
        $this->assertEquals('Acme Technologies', $user->fresh()->display_name);
        $this->assertNull($company->logo_url);
        $this->assertFalse($company->has_logo);

        $logoFile = UploadedFile::fake()->image('logo.png');
        $logoPath = $logoFile->store('company-logos', 'public');
        $company->update(['logo_path' => $logoPath]);

        $this->assertTrue($company->fresh()->has_logo);
        $this->assertTrue($user->fresh()->has_avatar);
        $this->assertStringContainsString('company-logos', $user->fresh()->avatar_url);
    }

    public function test_admin_can_view_profile_and_upload_avatar(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.profile'));
        $response->assertStatus(200);
        $response->assertSee('Administrator Profile &amp; Settings', false);
        $response->assertSee('System Administrator');

        // Upload avatar
        $avatar = UploadedFile::fake()->image('admin_photo.png');
        $updateResponse = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Senior Administrator',
            'email' => 'admin@gmail.com',
            'avatar' => $avatar,
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Senior Administrator',
        ]);
        $this->assertNotNull($admin->fresh()->avatar_path);
        $this->assertTrue($admin->fresh()->has_avatar);
    }

    public function test_admin_can_update_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
    }

    public function test_non_admin_cannot_access_admin_profile(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get(route('admin.profile'));
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_topbar_renders_user_profile_information_on_pages(): void
    {
        $student = User::factory()->create([
            'name' => 'Sophea Sok',
            'role' => 'student',
        ]);
        StudentProfile::create([
            'user_id' => $student->id,
            'major' => 'Computer Science',
        ]);

        $response = $this->actingAs($student)->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Sophea Sok');
        $response->assertSee('Student Intern');
        $response->assertSee('Profile &amp; Settings', false);
        $response->assertSee('Sign out');
    }
}
