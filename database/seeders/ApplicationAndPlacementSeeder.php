<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Evaluation;
use App\Models\InternshipPost;
use App\Models\Placement;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\WeeklyLog;
use Illuminate\Database\Seeder;

class ApplicationAndPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coordinator = User::where('email', 'coordinator@smartintern.edu')->first();

        $alex = StudentProfile::whereHas('user', fn($q) => $q->where('email', 'student1@smartintern.edu'))->first();
        $samantha = StudentProfile::whereHas('user', fn($q) => $q->where('email', 'student2@smartintern.edu'))->first();
        $michael = StudentProfile::whereHas('user', fn($q) => $q->where('email', 'student3@smartintern.edu'))->first();
        $emily = StudentProfile::whereHas('user', fn($q) => $q->where('email', 'student4@smartintern.edu'))->first();

        $laravelPost = InternshipPost::where('title', 'like', '%Full-Stack Laravel%')->first();
        $cloudPost = InternshipPost::where('title', 'like', '%Cloud Infrastructure%')->first();
        $mobilePost = InternshipPost::where('title', 'like', '%Mobile App%')->first();
        $cyberPost = InternshipPost::where('title', 'like', '%Cybersecurity%')->first();

        // 1. Applications
        if ($alex && $laravelPost) {
            $alexApp = Application::create([
                'internship_post_id' => $laravelPost->id,
                'student_profile_id' => $alex->id,
                'cover_letter' => "Dear Hiring Team at NexaTech,\n\nI am eager to apply for the Full-Stack Laravel position. Over the past year, I have built complete MVC web applications in Laravel with MySQL and Tailwind CSS. I would love to contribute to your engineering team while sharpening my architectural skills.",
                'status' => 'accepted',
                'company_notes' => 'Outstanding portfolio and technical interview performance.',
                'applied_at' => now()->subWeeks(5),
                'reviewed_at' => now()->subWeeks(4),
            ]);

            // 2. Active Placement for Alex
            $placement = Placement::create([
                'student_profile_id' => $alex->id,
                'company_profile_id' => $laravelPost->company_profile_id,
                'internship_post_id' => $laravelPost->id,
                'application_id' => $alexApp->id,
                'supervisor_id' => $coordinator?->id,
                'start_date' => now()->subWeeks(4)->toDateString(),
                'end_date' => now()->addWeeks(12)->toDateString(),
                'total_hours_required' => 480,
                'status' => 'active',
                'completion_remarks' => null,
            ]);

            // 3. Weekly Logs for Placement
            $logs = [
                [
                    'placement_id' => $placement->id,
                    'week_number' => 1,
                    'start_date' => now()->subWeeks(4)->toDateString(),
                    'end_date' => now()->subWeeks(3)->subDay()->toDateString(),
                    'hours_completed' => 35.00,
                    'tasks_summary' => 'Onboarding, workstation setup, local Docker development environment configuration, and codebase architecture review.',
                    'learnings_challenges' => 'Familiarized myself with the internal modular architecture and CI/CD git hooks.',
                    'status' => 'approved',
                    'company_feedback' => 'Great start! Transitioned into the codebase very quickly.',
                    'supervisor_feedback' => 'Satisfactory onboarding progress.',
                    'approved_at' => now()->subWeeks(3),
                ],
                [
                    'placement_id' => $placement->id,
                    'week_number' => 2,
                    'start_date' => now()->subWeeks(3)->toDateString(),
                    'end_date' => now()->subWeeks(2)->subDay()->toDateString(),
                    'hours_completed' => 38.50,
                    'tasks_summary' => 'Implemented customer notification events and listeners; refactored email templates with Blade components.',
                    'learnings_challenges' => 'Gained deep understanding of Laravel Queue Workers and Redis caching.',
                    'status' => 'approved',
                    'company_feedback' => 'Clean code and comprehensive unit tests included.',
                    'supervisor_feedback' => 'Good technical depth demonstrated.',
                    'approved_at' => now()->subWeeks(2),
                ],
                [
                    'placement_id' => $placement->id,
                    'week_number' => 3,
                    'start_date' => now()->subWeeks(2)->toDateString(),
                    'end_date' => now()->subWeeks(1)->subDay()->toDateString(),
                    'hours_completed' => 40.00,
                    'tasks_summary' => 'Designed database migrations and Eloquent models for analytics reporting; created export API to generate Excel spreadsheets.',
                    'learnings_challenges' => 'Optimized memory usage when exporting large dataset chunks using Laravel chunking.',
                    'status' => 'approved',
                    'company_feedback' => 'Excellent memory optimization work on the export service.',
                    'supervisor_feedback' => 'Reviewed and approved.',
                    'approved_at' => now()->subWeeks(1),
                ],
                [
                    'placement_id' => $placement->id,
                    'week_number' => 4,
                    'start_date' => now()->subWeeks(1)->toDateString(),
                    'end_date' => now()->toDateString(),
                    'hours_completed' => 36.00,
                    'tasks_summary' => 'Integrated Stripe webhook handlers and updated subscription checkout UI components.',
                    'learnings_challenges' => 'Testing idempotency on webhook events during simulated network retries.',
                    'status' => 'submitted',
                    'company_feedback' => null,
                    'supervisor_feedback' => null,
                    'approved_at' => null,
                ],
            ];

            foreach ($logs as $logData) {
                WeeklyLog::create($logData);
            }

            // 4. Midterm Evaluation
            $companyUser = User::where('email', 'techcorp@example.com')->first();
            if ($companyUser) {
                Evaluation::create([
                    'placement_id' => $placement->id,
                    'evaluator_id' => $companyUser->id,
                    'type' => 'midterm',
                    'performance_rating' => 5,
                    'technical_skills_rating' => 5,
                    'soft_skills_rating' => 4,
                    'attendance_punctuality_rating' => 5,
                    'comments' => 'Alex has exceeded our expectations for a junior engineer. He grasps domain concepts swiftly, writes self-documenting code, and collaborates constructively with the team.',
                    'recommendation' => 'outstanding',
                    'submitted_at' => now()->subDays(2),
                ]);
            }
        }

        // Additional Applications
        if ($samantha && $cloudPost) {
            Application::create([
                'internship_post_id' => $cloudPost->id,
                'student_profile_id' => $samantha->id,
                'cover_letter' => "Dear CloudScale Team,\n\nI have been managing Linux servers and containerizing applications in Docker for the past 2 years. I am passionate about SRE and Kubernetes automation.",
                'status' => 'shortlisted',
                'company_notes' => 'Strong homelab experience. Schedule technical interview for next Tuesday.',
                'applied_at' => now()->subDays(10),
                'reviewed_at' => now()->subDays(2),
            ]);
        }

        if ($michael && $mobilePost) {
            Application::create([
                'internship_post_id' => $mobilePost->id,
                'student_profile_id' => $michael->id,
                'cover_letter' => "Dear NexaTech Mobile Team,\n\nI have published two Android apps on GitHub and love building fluid UI in Flutter. I would be thrilled to contribute to your mobile clients.",
                'status' => 'pending',
                'company_notes' => null,
                'applied_at' => now()->subDays(3),
                'reviewed_at' => null,
            ]);
        }

        if ($emily && $cyberPost) {
            Application::create([
                'internship_post_id' => $cyberPost->id,
                'student_profile_id' => $emily->id,
                'cover_letter' => 'Applying for cybersecurity internship opportunity.',
                'status' => 'rejected',
                'company_notes' => 'Candidate background is predominantly data analytics; looking for dedicated networking/security majors.',
                'applied_at' => now()->subDays(14),
                'reviewed_at' => now()->subDays(7),
            ]);
        }
    }
}
