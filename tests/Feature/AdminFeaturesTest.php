<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $companyUser;
    protected CompanyProfile $companyProfile;
    protected User $studentUser;
    protected StudentProfile $studentProfile;
    protected InternshipPost $post;
    protected Application $application;

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

        $this->companyUser = User::create([
            'name'     => 'Acme Corp',
            'email'    => 'acme@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'company',
            'status'   => 'active',
        ]);

        $this->companyProfile = CompanyProfile::create([
            'user_id'             => $this->companyUser->id,
            'company_name'        => 'Acme Technologies',
            'industry'            => 'Software',
            'location'            => 'Siem Reap',
            'verification_status' => 'pending',
        ]);

        $this->studentUser = User::create([
            'name'     => 'Sokha Chan',
            'email'    => 'sokha@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'student',
            'status'   => 'active',
        ]);

        $this->studentProfile = StudentProfile::create([
            'user_id'            => $this->studentUser->id,
            'student_id_number'  => 'USEA-2026-001',
            'department'         => 'Information Technology',
            'major'              => 'Computer Science',
            'gpa'                => 3.75,
            'eligibility_status' => 'eligible',
        ]);

        $this->post = InternshipPost::create([
            'company_profile_id' => $this->companyProfile->id,
            'title'              => 'Web Developer Intern',
            'slug'               => 'web-developer-intern-acme',
            'category'           => 'Technology',
            'description'        => 'A great internship opportunity for software developers.',
            'location'           => 'Siem Reap',
            'type'               => 'on_site',
            'duration_weeks'     => 12,
            'stipend'            => 300,
            'slots'              => 2,
            'deadline'           => now()->addDays(30),
            'status'             => 'approved',
        ]);

        $this->application = Application::create([
            'internship_post_id' => $this->post->id,
            'student_profile_id' => $this->studentProfile->id,
            'cover_letter'       => 'I am eager to apply for this internship.',
            'status'             => 'accepted',
            'applied_at'         => now(),
        ]);
    }

    public function test_admin_can_view_company_verification_queue(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.index'));

        $response->assertStatus(200);
        $response->assertSee('Company Verification Queue');
        $response->assertSee('Acme Technologies');
    }

    public function test_admin_can_verify_pending_company(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.companies.update', $this->companyProfile), [
            'verification_status' => 'verified',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('company_profiles', [
            'id'                  => $this->companyProfile->id,
            'verification_status' => 'verified',
            'rejection_reason'    => null,
        ]);
    }

    public function test_admin_can_reject_company_with_reason(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.companies.update', $this->companyProfile), [
            'verification_status' => 'rejected',
            'rejection_reason'    => 'Incomplete business registration license.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('company_profiles', [
            'id'                  => $this->companyProfile->id,
            'verification_status' => 'rejected',
            'rejection_reason'    => 'Incomplete business registration license.',
        ]);
    }

    public function test_admin_can_view_placement_reports(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Reports');
        $response->assertSee('Sokha Chan');
        $response->assertSee('Acme Technologies');
        $response->assertSee('Accepted Placements');
    }

    public function test_admin_can_filter_placement_reports(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'status' => 'accepted',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Sokha Chan');
    }

    public function test_admin_can_export_csv_placement_report(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.exportCsv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('internship_placement_report_', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_view_printable_placement_report(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.print'));

        $response->assertStatus(200);
        $response->assertSee('Official Internship Placement Report');
        $response->assertSee('University of South-East Asia');
        $response->assertSee('Sokha Chan');
    }
}
