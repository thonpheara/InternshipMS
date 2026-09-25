<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResumeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_student_can_upload_and_preview_profile_resume(): void
    {
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'eligibility_status' => 'eligible',
        ]);

        $file = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');

        $response = $this->actingAs($studentUser)->post(route('student.profile.update'), [
            'student_id_number' => 'STU-100',
            'department' => 'CS',
            'major' => 'Software',
            'cohort_year' => 2025,
            'gpa' => 3.8,
            'resume' => $file,
        ]);

        $response->assertRedirect(route('student.profile', ['tab' => 'resume']));
        $response->assertSessionHas('success');

        $studentProfile->refresh();
        $this->assertNotNull($studentProfile->resume_path);
        $this->assertTrue(Storage::disk('public')->exists($studentProfile->resume_path));

        // Test preview
        $previewResponse = $this->actingAs($studentUser)->get(route('student.resume.preview'));
        $previewResponse->assertOk();
    }

    public function test_student_can_delete_resume(): void
    {
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $file = UploadedFile::fake()->create('delete_me.pdf', 300, 'application/pdf');
        $stored = $file->store('resumes/1', 'public');

        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'resume_path' => $stored,
            'eligibility_status' => 'eligible',
        ]);

        $this->assertTrue(Storage::disk('public')->exists($stored));

        $response = $this->actingAs($studentUser)->delete(route('student.resume.delete'));
        $response->assertRedirect(route('student.profile', ['tab' => 'resume']));

        $studentProfile->refresh();
        $this->assertNull($studentProfile->resume_path);
        $this->assertFalse(Storage::disk('public')->exists($stored));
    }

    public function test_student_applies_and_company_can_view_resume(): void
    {
        // Company
        $companyUser = User::factory()->create(['role' => 'company', 'status' => 'active']);
        $company = CompanyProfile::create([
            'user_id' => $companyUser->id,
            'company_name' => 'Acme Tech',
            'verification_status' => 'verified',
        ]);

        $post = InternshipPost::create([
            'company_profile_id' => $company->id,
            'title' => 'Laravel Intern',
            'slug' => 'laravel-intern',
            'description' => 'Test job description for student intern',
            'location' => 'Phnom Penh',
            'location_type' => 'on_site',
            'duration_weeks' => 12,
            'vacancies_count' => 2,
            'status' => 'approved',
            'deadline' => now()->addMonth(),
        ]);

        // Student with uploaded profile resume
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $file = UploadedFile::fake()->create('profile_resume.pdf', 400, 'application/pdf');
        $storedPath = $file->storeAs('resumes/test', 'profile_resume.pdf', 'public');

        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'resume_path' => $storedPath,
            'eligibility_status' => 'eligible',
        ]);

        // Student applies without uploading new resume (uses profile resume)
        $applyResponse = $this->actingAs($studentUser)->post(route('student.posts.apply', $post), [
            'cover_letter' => 'I am very excited to apply for the Laravel Intern position at Acme Tech.',
        ]);

        $applyResponse->assertRedirect(route('student.applications.index'));

        $application = Application::where('student_profile_id', $studentProfile->id)
            ->where('internship_post_id', $post->id)
            ->first();

        $this->assertNotNull($application);
        $this->assertEquals($storedPath, $application->custom_resume_path);

        // Company views applicants board
        $boardResponse = $this->actingAs($companyUser)->get(route('company.applicants.index'));
        $boardResponse->assertOk();
        $boardResponse->assertSee('profile_resume.pdf');

        // Company views resume
        $companyResumeResponse = $this->actingAs($companyUser)->get(route('company.applicants.resume', $application));
        $companyResumeResponse->assertOk();
    }
}
