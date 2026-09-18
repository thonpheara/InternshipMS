<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Management System</title>
    <!-- Google Fonts: Kantumruy Pro (Khmer & English) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, button, input, optgroup, select, textarea {
            font-family: "Kantumruy Pro", sans-serif;
        }
    </style>
</head>
<body class="h-full text-slate-100 selection:bg-indigo-500 selection:text-white antialiased">

    <!-- Top Navigation Header -->
    <nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-sky-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <span class="text-xl font-black tracking-tight text-white">Intern<span class="text-indigo-400">ship</span></span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('register') }}" class="px-4 py-2.5 rounded-xl border border-slate-700 hover:border-indigo-500 text-slate-200 hover:text-white font-bold text-xs transition-all">
                    Register
                </a>
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                    <span>Sign In</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-20 pb-28 overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-400/30 text-indigo-300 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>University-Accredited Internship Management System (IMS)</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight">
                Bridge the Gap from <br class="hidden sm:inline">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-sky-300 to-emerald-400">
                    Campus to Industry.
                </span>
            </h1>

            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                A unified ecosystem connecting students, host organizations, and university coordinators to streamline applications, weekly logbook sign-offs, and final evaluations.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/30 transition-all flex items-center gap-2.5">
                    <span>Launch Portal Dashboard</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Quick Access Test Accounts Switcher Banner -->
            <div class="pt-10 max-w-3xl mx-auto">
                <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700/80 backdrop-blur-md text-left">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-400 mb-3 flex items-center gap-1.5">
                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                        Pre-Configured Demo Test Logins (Password for all: password)
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                        <a href="{{ route('login') }}" class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-700 hover:border-indigo-500 transition-colors block">
                            <span class="font-bold text-white block">🎓 Student</span>
                            <span class="text-[10px] text-slate-400 truncate block">student1@internship.edu</span>
                        </a>
                        <a href="{{ route('login') }}" class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-700 hover:border-emerald-500 transition-colors block">
                            <span class="font-bold text-white block">🏢 Host Company</span>
                            <span class="text-[10px] text-slate-400 truncate block">techcorp@example.com</span>
                        </a>
                        <a href="{{ route('login') }}" class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-700 hover:border-purple-500 transition-colors block">
                            <span class="font-bold text-white block">👩‍🏫 Coordinator</span>
                            <span class="text-[10px] text-slate-400 truncate block">coordinator@internship.edu</span>
                        </a>
                        <a href="{{ route('login') }}" class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-700 hover:border-amber-500 transition-colors block">
                            <span class="font-bold text-white block">🛡️ Admin</span>
                            <span class="text-[10px] text-slate-400 truncate block">admin@internship.edu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Three Core Role Pillars -->
    <section class="py-20 bg-slate-950 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-400">Institutional Workflows</h2>
                <h3 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Built for Every Role</h3>
                <p class="text-sm text-slate-400">Isolated dashboard experiences tailored to students, host employers, and university administration.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Student Card -->
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 space-y-4 hover:border-indigo-500/50 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white">Student Portal</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Discover vetted opportunities, submit applications with resume and cover letters, and log weekly timesheets towards the 480-hour degree accreditation.
                    </p>
                    <ul class="text-xs text-slate-300 space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-400"></i> Browse Remote / Hybrid / On-Site Jobs</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-400"></i> Real-time Application Status Tracker</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-400"></i> Weekly Activity & Hours Logbook</li>
                    </ul>
                </div>

                <!-- Company Card -->
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 space-y-4 hover:border-emerald-500/50 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                        <i data-lucide="building" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white">Host Organization Portal</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Publish company listings, manage candidate interview pipelines, sign off on weekly intern deliverables, and submit structured midterm evaluations.
                    </p>
                    <ul class="text-xs text-slate-300 space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i> 1-Click Applicant Shortlisting & Offers</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i> Timesheet Weekly Verification</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i> Quantitative Performance Rubrics</li>
                    </ul>
                </div>

                <!-- Coordinator Card -->
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 space-y-4 hover:border-amber-500/50 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white">Faculty Coordinator Portal</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Verify student eligibility prerequisites, moderate corporate job descriptions, allocate faculty supervisors, and review cohort completion statistics.
                    </p>
                    <ul class="text-xs text-slate-300 space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-amber-400"></i> Institutional Accreditation Dashboard</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-amber-400"></i> Employer Post Moderation Queue</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-amber-400"></i> Faculty Supervisor Assignment Matrix</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-slate-900 border-t border-slate-800 text-center text-xs text-slate-400">
        <p>Internship Management System &copy; {{ date('Y') }}. Production-Ready Laravel 12 Architecture.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>