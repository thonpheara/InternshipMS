<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostStatusSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $companyUser;
    protected CompanyProfile $companyProfile;
    protected InternshipPost $pendingPost;
    protected InternshipPost $approvedPost;

    protected function setUp(): void
    {
        parent::setUp();

        $this->companyUser = User::create([
            'name'     => 'Tech Solution',
            'email'    => 'tech@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'company',
            'status'   => 'active',
        ]);

        $this->companyProfile = CompanyProfile::create([
            'user_id'             => $this->companyUser->id,
            'company_name'        => 'Tech Solution Ltd',
            'industry'            => 'Software',
            'location'            => 'Phnom Penh',
            'verification_status' => 'verified',
        ]);

        $this->pendingPost = InternshipPost::create([
            'company_profile_id' => $this->companyProfile->id,
            'title'              => 'Web Developer Intern',
            'slug'               => 'web-developer-intern-1001',
            'category'           => 'Web Development',
            'description'        => 'This is a description with more than thirty characters.',
            'location'           => 'Phnom Penh',
            'duration_weeks'     => 12,
            'slots'              => 2,
            'deadline'           => now()->addDays(30)->toDateString(),
            'status'             => 'pending_approval',
        ]);

        $this->approvedPost = InternshipPost::create([
            'company_profile_id' => $this->companyProfile->id,
            'title'              => 'Mobile Developer Intern',
            'slug'               => 'mobile-developer-intern-1002',
            'category'           => 'Mobile Development',
            'description'        => 'This is a description with more than thirty characters for mobile.',
            'location'           => 'Phnom Penh',
            'duration_weeks'     => 16,
            'slots'              => 1,
            'deadline'           => now()->addDays(45)->toDateString(),
            'status'             => 'approved',
        ]);
    }

    public function test_company_cannot_self_approve_pending_post(): void
    {
        $payload = [
            'title'          => 'Hacked Title',
            'category'       => 'Web Development',
            'description'    => 'Valid description with more than thirty characters.',
            'location'       => 'Phnom Penh',
            'duration_weeks' => 12,
            'slots'          => 2,
            'deadline'       => now()->addDays(30)->toDateString(),
            'status'         => 'approved', // Attacker trying to self-approve
        ];

        $response = $this->actingAs($this->companyUser)
            ->put(route('company.posts.update', $this->pendingPost), $payload);

        $response->assertSessionHasErrors(['status']);
        $this->pendingPost->refresh();
        $this->assertEquals('pending_approval', $this->pendingPost->status);
    }

    public function test_company_cannot_set_post_to_rejected(): void
    {
        $payload = [
            'title'          => 'Web Developer Intern',
            'category'       => 'Web Development',
            'description'    => 'Valid description with more than thirty characters.',
            'location'       => 'Phnom Penh',
            'duration_weeks' => 12,
            'slots'          => 2,
            'deadline'       => now()->addDays(30)->toDateString(),
            'status'         => 'rejected', // Reserved for admin
        ];

        $response = $this->actingAs($this->companyUser)
            ->put(route('company.posts.update', $this->pendingPost), $payload);

        $response->assertSessionHasErrors(['status']);
        $this->pendingPost->refresh();
        $this->assertEquals('pending_approval', $this->pendingPost->status);
    }

    public function test_company_can_change_post_to_draft_or_closed(): void
    {
        // Change pending to closed
        $payload = [
            'title'          => 'Web Developer Intern',
            'category'       => 'Web Development',
            'description'    => 'Valid description with more than thirty characters.',
            'location'       => 'Phnom Penh',
            'duration_weeks' => 12,
            'slots'          => 2,
            'deadline'       => now()->addDays(30)->toDateString(),
            'status'         => 'closed',
        ];

        $response = $this->actingAs($this->companyUser)
            ->put(route('company.posts.update', $this->pendingPost), $payload);

        $response->assertSessionHasNoErrors();
        $this->pendingPost->refresh();
        $this->assertEquals('closed', $this->pendingPost->status);

        // Change to draft
        $payload['status'] = 'draft';
        $response = $this->actingAs($this->companyUser)
            ->put(route('company.posts.update', $this->pendingPost), $payload);

        $response->assertSessionHasNoErrors();
        $this->pendingPost->refresh();
        $this->assertEquals('draft', $this->pendingPost->status);
    }

    public function test_company_can_keep_already_approved_post_approved(): void
    {
        $payload = [
            'title'          => 'Mobile Developer Intern (Updated)',
            'category'       => 'Mobile Development',
            'description'    => 'Valid description with more than thirty characters for mobile.',
            'location'       => 'Phnom Penh',
            'duration_weeks' => 16,
            'slots'          => 3,
            'deadline'       => now()->addDays(50)->toDateString(),
            'status'         => 'approved',
        ];

        $response = $this->actingAs($this->companyUser)
            ->put(route('company.posts.update', $this->approvedPost), $payload);

        $response->assertSessionHasNoErrors();
        $this->approvedPost->refresh();
        $this->assertEquals('approved', $this->approvedPost->status);
        $this->assertEquals(3, $this->approvedPost->slots);
    }
}
