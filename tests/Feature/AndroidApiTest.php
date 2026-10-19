<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Conversation;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AndroidApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_returns_token_on_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'student@usea.edu.kh',
            'password' => Hash::make('password123'),
            'role'     => 'student',
            'status'   => 'active',
        ]);
        StudentProfile::create(['user_id' => $user->id]);

        $response = $this->postJson('/api/auth/login', [
            'email'       => 'student@usea.edu.kh',
            'password'    => 'password123',
            'device_name' => 'Android Samsung A54',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'avatar_url',
                        'display_name',
                    ],
                ],
            ]);
    }

    public function test_api_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'student@usea.edu.kh',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'student@usea.edu.kh',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_api_registration_for_student(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Sok Chantha',
            'email'                 => 'chantha@student.usea.edu.kh',
            'password'              => 'securePass123',
            'password_confirmation' => 'securePass123',
            'role'                  => 'student',
            'student_id_number'     => 'STU-9921',
            'major'                 => 'Computer Science',
            'phone'                 => '012345678',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email', 'role'],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'chantha@student.usea.edu.kh',
            'role'  => 'student',
        ]);

        $this->assertDatabaseHas('student_profiles', [
            'student_id_number' => 'STU-9921',
            'major'             => 'Computer Science',
        ]);
    }

    public function test_api_me_endpoint_requires_auth(): void
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);
    }

    public function test_api_student_can_fetch_dashboard_and_posts(): void
    {
        $studentUser = User::factory()->create([
            'role'   => 'student',
            'status' => 'active',
        ]);
        $student = StudentProfile::create(['user_id' => $studentUser->id]);

        $companyUser = User::factory()->create(['role' => 'company', 'status' => 'active']);
        $company = CompanyProfile::create([
            'user_id'             => $companyUser->id,
            'company_name'        => 'Smart Axiata Co.',
            'verification_status' => 'verified',
        ]);

        $post = InternshipPost::create([
            'company_profile_id' => $company->id,
            'title'              => 'Android Developer Intern',
            'slug'               => 'android-developer-intern-test',
            'category'           => 'Technology',
            'type'               => 'on_site',
            'location'           => 'Phnom Penh',
            'duration_weeks'     => 12,
            'slots'              => 2,
            'deadline'           => now()->addMonths(1),
            'description'        => 'Develop native Android apps with Java/Kotlin.',
            'requirements'       => 'Basic Java knowledge.',
            'status'             => 'approved',
        ]);

        $token = $studentUser->createToken('test-token')->plainTextToken;

        // Test Dashboard
        $dashboardResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/student/dashboard');

        $dashboardResponse->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['stats', 'featured_posts']]);

        // Test Browse Posts
        $postsResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/student/posts?search=Android');

        $postsResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertCount(1, $postsResponse->json('data.data'));
        $this->assertEquals('Android Developer Intern', $postsResponse->json('data.data.0.title'));
    }

    public function test_api_student_can_apply_for_internship(): void
    {
        Storage::fake('local');

        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = StudentProfile::create(['user_id' => $studentUser->id]);

        $companyUser = User::factory()->create(['role' => 'company', 'status' => 'active']);
        $company = CompanyProfile::create([
            'user_id'             => $companyUser->id,
            'company_name'        => 'Cellcard Telecommunications',
            'verification_status' => 'verified',
        ]);

        $post = InternshipPost::create([
            'company_profile_id' => $company->id,
            'title'              => 'Mobile App QA Intern',
            'slug'               => 'mobile-app-qa-intern-test',
            'category'           => 'Technology',
            'type'               => 'remote',
            'location'           => 'Siem Reap',
            'duration_weeks'     => 16,
            'slots'              => 1,
            'deadline'           => now()->addWeeks(4),
            'description'        => 'Perform testing on Android devices.',
            'requirements'       => 'Android phone.',
            'status'             => 'approved',
        ]);

        $token = $studentUser->createToken('test-token')->plainTextToken;

        $pdfResume = UploadedFile::fake()->create('chantha_cv.pdf', 300, 'application/pdf');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/student/posts/{$post->id}/apply", [
                'cover_letter' => 'I am very enthusiastic about this Android QA position and eager to learn.',
                'resume'       => $pdfResume,
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('applications', [
            'internship_post_id' => $post->id,
            'student_profile_id' => $student->id,
            'status'             => 'pending',
        ]);
    }

    public function test_api_company_cannot_access_student_endpoints(): void
    {
        $companyUser = User::factory()->create(['role' => 'company', 'status' => 'active']);
        CompanyProfile::create(['user_id' => $companyUser->id]);

        $token = $companyUser->createToken('company-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/student/dashboard');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_api_chat_and_notifications(): void
    {
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = StudentProfile::create(['user_id' => $studentUser->id]);

        $companyUser = User::factory()->create(['role' => 'company', 'status' => 'active']);
        $company = CompanyProfile::create(['user_id' => $companyUser->id, 'company_name' => 'Tech Corp']);

        $conversation = Conversation::create([
            'student_profile_id' => $student->id,
            'company_profile_id' => $company->id,
            'subject'            => 'Interview Invitation',
        ]);

        $token = $studentUser->createToken('student-token')->plainTextToken;

        // Send a message
        $msgResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/conversations/{$conversation->id}/messages", [
                'body' => 'Hello, thank you for considering my application!',
            ]);

        $msgResponse->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'body'            => 'Hello, thank you for considering my application!',
            'sender_id'       => $studentUser->id,
        ]);

        // Company should have received notification
        $this->assertEquals(1, $companyUser->notifications()->count());
    }
}
