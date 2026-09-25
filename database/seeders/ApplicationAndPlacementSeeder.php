<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationAndPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            Application::create([
                'internship_post_id' => $laravelPost->id,
                'student_profile_id' => $alex->id,
                'cover_letter' => "Dear Hiring Team at NexaTech,\n\nI am eager to apply for the Full-Stack Laravel position. Over the past year, I have built complete MVC web applications in Laravel with MySQL and Tailwind CSS. I would love to contribute to your engineering team while sharpening my architectural skills.",
                'status' => 'accepted',
                'company_notes' => 'Outstanding portfolio and technical interview performance.',
                'applied_at' => now()->subWeeks(5),
                'reviewed_at' => now()->subWeeks(4),
            ]);
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
