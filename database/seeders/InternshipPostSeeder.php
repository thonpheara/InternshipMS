<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InternshipPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nexatech = CompanyProfile::where('company_name', 'like', '%NexaTech%')->first();
        $cloudscale = CompanyProfile::where('company_name', 'like', '%CloudScale%')->first();
        $biohealth = CompanyProfile::where('company_name', 'like', '%BioHealth%')->first();

        $posts = [
            [
                'company_profile_id' => $nexatech?->id,
                'title' => 'Full-Stack Laravel & Vue.js Intern',
                'slug' => Str::slug('Full-Stack Laravel Vuejs Intern NexaTech') . '-' . rand(100, 999),
                'description' => 'Join our product team to build high-performance web applications using modern Laravel, Vue 3, and Tailwind CSS. You will work side-by-side with senior architects on real customer-facing features.',
                'responsibilities' => "- Develop and maintain RESTful API endpoints.\n- Collaborate on frontend components using Vue.js and Tailwind.\n- Write unit and feature tests with PHPUnit.\n- Participate in daily agile standups and code reviews.",
                'requirements' => "- Strong fundamentals in PHP, OOP, and relational databases (MySQL).\n- Familiarity with MVC frameworks, ideally Laravel.\n- Experience with Git version control.\n- Good communication skills.",
                'location' => 'San Francisco, CA (Hybrid)',
                'type' => 'hybrid',
                'duration_weeks' => 16,
                'stipend' => 2500.00,
                'is_stipend_disclosed' => true,
                'slots' => 2,
                'deadline' => now()->addDays(30),
                'status' => 'approved',
            ],
            [
                'company_profile_id' => $cloudscale?->id,
                'title' => 'Cloud Infrastructure & DevOps Intern',
                'slug' => Str::slug('Cloud Infrastructure DevOps Intern CloudScale') . '-' . rand(100, 999),
                'description' => 'Help automate and scale production cloud workloads. Gain practical experience with Terraform, Docker containers, Kubernetes clusters, and GitHub Actions CI/CD pipelines.',
                'responsibilities' => "- Assist in building automated CI/CD deployment pipelines.\n- Monitor system metrics and uptime alerts via Prometheus and Grafana.\n- Document infrastructure runbooks and security compliance checks.",
                'requirements' => "- Familiarity with Linux command line and shell scripting.\n- Understanding of cloud basics (AWS/GCP/Azure) and Docker containers.\n- Eagerness to learn site reliability engineering principles.",
                'location' => 'Austin, TX (On-Site)',
                'type' => 'on_site',
                'duration_weeks' => 14,
                'stipend' => 2800.00,
                'is_stipend_disclosed' => true,
                'slots' => 1,
                'deadline' => now()->addDays(20),
                'status' => 'approved',
            ],
            [
                'company_profile_id' => $nexatech?->id,
                'title' => 'Mobile App Developer Intern (Flutter & Android)',
                'slug' => Str::slug('Mobile App Developer Intern Flutter NexaTech') . '-' . rand(100, 999),
                'description' => 'Collaborate with our mobile team in crafting sleek, responsive cross-platform mobile apps using Flutter and Android native SDK.',
                'responsibilities' => "- Implement clean UI widgets according to Figma specifications.\n- Integrate backend GraphQL and REST APIs.\n- Test app on various screen sizes and Android OS versions.",
                'requirements' => "- Experience with Dart/Flutter or Kotlin/Java for Android.\n- Understanding of mobile state management.\n- Portfolio of at least one working mobile app project.",
                'location' => 'Remote',
                'type' => 'remote',
                'duration_weeks' => 12,
                'stipend' => 2200.00,
                'is_stipend_disclosed' => true,
                'slots' => 2,
                'deadline' => now()->addDays(25),
                'status' => 'approved',
            ],
            [
                'company_profile_id' => $biohealth?->id,
                'title' => 'Health Data Analytics & Machine Learning Intern',
                'slug' => Str::slug('Health Data Analytics ML Intern BioHealth') . '-' . rand(100, 999),
                'description' => 'Work with anonymized patient cohort data to uncover statistical trends and build exploratory regression and classification models.',
                'responsibilities' => "- Clean and transform multimodal clinical datasets.\n- Perform exploratory data analysis and visualize key metrics.\n- Assist in training and evaluating baseline machine learning models.",
                'requirements' => "- Proficiency with Python (NumPy, Pandas, Matplotlib, Scikit-learn).\n- Strong understanding of statistical inference.\n- Interest in biomedical informatics.",
                'location' => 'Boston, MA (On-Site)',
                'type' => 'on_site',
                'duration_weeks' => 12,
                'stipend' => 2400.00,
                'is_stipend_disclosed' => true,
                'slots' => 1,
                'deadline' => now()->addDays(40),
                'status' => 'pending_approval',
            ],
            [
                'company_profile_id' => $cloudscale?->id,
                'title' => 'Cybersecurity & Vulnerability Assessment Intern',
                'slug' => Str::slug('Cybersecurity Vulnerability Intern CloudScale') . '-' . rand(100, 999),
                'description' => 'Join our security operations center to conduct penetration testing drills, static code vulnerability scans, and identity access management audits.',
                'responsibilities' => "- Perform periodic vulnerability assessments.\n- Review access control lists and principle-of-least-privilege policies.\n- Help coordinate security awareness simulations.",
                'requirements' => "- Understanding of OWASP Top 10 vulnerabilities.\n- Network security fundamentals (TCP/IP, SSL/TLS, firewalls).\n- Ethical hacking curiosity and integrity.",
                'location' => 'Austin, TX (Hybrid)',
                'type' => 'hybrid',
                'duration_weeks' => 16,
                'stipend' => 2600.00,
                'is_stipend_disclosed' => true,
                'slots' => 1,
                'deadline' => now()->addDays(15),
                'status' => 'approved',
            ],
            [
                'company_profile_id' => $biohealth?->id,
                'title' => 'UI/UX Product Design Intern',
                'slug' => Str::slug('UI UX Product Design Intern BioHealth') . '-' . rand(100, 999),
                'description' => 'Redesigning clinician dashboard interfaces for intuitive workflow and accessibility.',
                'responsibilities' => "- Conduct user research interviews.\n- Create wireframes and interactive prototypes in Figma.",
                'requirements' => "- Figma proficiency.\n- Strong visual design portfolio.",
                'location' => 'Remote',
                'type' => 'remote',
                'duration_weeks' => 10,
                'stipend' => 1800.00,
                'is_stipend_disclosed' => true,
                'slots' => 1,
                'deadline' => now()->subDays(5),
                'status' => 'closed',
            ],
        ];

        foreach ($posts as $postData) {
            if ($postData['company_profile_id']) {
                InternshipPost::create($postData);
            }
        }
    }
}
