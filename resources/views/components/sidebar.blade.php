@php
    $user = Auth::user();
    $role = $user?->role ?? 'student';

    $roleTheme = match($role) {
        'admin', 'coordinator' => [
            'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
            'label' => $role === 'admin' ? 'University Admin' : 'Faculty Coordinator',
            'accent' => 'text-amber-500',
            'active_bg' => 'bg-amber-500/10 text-amber-600 font-semibold',
        ],
        'company' => [
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'label' => 'Host Company',
            'accent' => 'text-emerald-500',
            'active_bg' => 'bg-emerald-500/10 text-emerald-600 font-semibold',
        ],
        default => [
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'label' => 'Student Intern',
            'accent' => 'text-indigo-500',
            'active_bg' => 'bg-indigo-500/10 text-indigo-600 font-semibold',
        ],
    };
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-68 bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0">
    
    <!-- Brand Logo -->
    <div class="h-16 px-6 flex items-center justify-between border-b border-slate-100">
        <a href="/" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-sky-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-lg font-extrabold tracking-tight text-slate-900 flex items-center gap-1">
                    Intern<span class="text-indigo-600">ship</span>
                </span>
                <span class="block text-[10px] font-semibold text-slate-600 tracking-wider uppercase">Management System</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-600 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Active User Role Indicator -->
    <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-600">Workspace</span>
            <span class="px-2 py-0.5 text-[11px] font-bold rounded-md border {{ $roleTheme['badge'] }}">
                {{ $roleTheme['label'] }}
            </span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @if ($role === 'student')
            <!-- Student Navigation -->
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('student.dashboard') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="layout-dashboard" class="w-4.5 h-4.5"></i>
                Dashboard
            </a>
            <a href="{{ route('student.posts.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('student.posts.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="briefcase" class="w-4.5 h-4.5"></i>
                Browse Internships
            </a>
            <a href="{{ route('student.applications.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('student.applications.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="send" class="w-4.5 h-4.5"></i>
                My Applications
            </a>
            <a href="{{ route('student.logs.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('student.logs.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="calendar-check" class="w-4.5 h-4.5"></i>
                Weekly Logs & Hours
            </a>
            <a href="{{ route('student.messages.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('student.messages.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="message-square" class="w-4.5 h-4.5"></i>
                    <span>Messages</span>
                </div>
                @php $unread = $user->unreadMessagesCount(); @endphp
                @if($unread > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-600 text-white">
                        {{ $unread }}
                    </span>
                @endif
            </a>
            <a href="{{ route('student.profile') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('student.profile') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
                Profile & Resume
            </a>

        @elseif ($role === 'company')
            <!-- Company Navigation -->
            <a href="{{ route('company.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.dashboard') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="layout-dashboard" class="w-4.5 h-4.5"></i>
                Overview Dashboard
            </a>
            <a href="{{ route('company.posts.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.posts.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="file-plus" class="w-4.5 h-4.5"></i>
                Manage Listings
            </a>
            <a href="{{ route('company.applicants.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.applicants.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="users" class="w-4.5 h-4.5"></i>
                Applicant Pipeline
            </a>
            <a href="{{ route('company.logs.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.logs.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="clock" class="w-4.5 h-4.5"></i>
                Approve Intern Logs
            </a>
            <a href="{{ route('company.messages.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.messages.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="message-square" class="w-4.5 h-4.5"></i>
                    <span>Messages</span>
                </div>
                @php $unread = $user->unreadMessagesCount(); @endphp
                @if($unread > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-600 text-white">
                        {{ $unread }}
                    </span>
                @endif
            </a>
            <a href="{{ route('company.evaluations.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.evaluations.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="award" class="w-4.5 h-4.5"></i>
                Evaluations
            </a>
            <a href="{{ route('company.profile') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('company.profile') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="building-2" class="w-4.5 h-4.5"></i>
                Company Profile
            </a>

        @else
            <!-- Admin & Coordinator Navigation -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="gauge" class="w-4.5 h-4.5"></i>
                Institution KPIs
            </a>
            <a href="{{ route('admin.approvals.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.approvals.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="check-square" class="w-4.5 h-4.5"></i>
                Job Post Moderation
            </a>
            <a href="{{ route('admin.students.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.students.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
                Student Eligibility
            </a>
            <a href="{{ route('admin.placements.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.placements.*') ? $roleTheme['active_bg'] : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i data-lucide="git-pull-request" class="w-4.5 h-4.5"></i>
                Placements & Faculty
            </a>
        @endif
    </nav>

    <!-- Bottom User Section & Logout -->
    <div class="p-3 border-t border-slate-100">
        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
            @php
                $profileRoute = match($role) {
                    'student' => route('student.profile'),
                    'company' => route('company.profile'),
                    default => null,
                };
            @endphp
            @if($profileRoute)
                <a href="{{ $profileRoute }}" title="View Profile" class="flex items-center gap-2.5 min-w-0 flex-1 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-slate-600 truncate">{{ $user->email }}</p>
                    </div>
                </a>
            @else
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-slate-600 truncate">{{ $user->email }}</p>
                    </div>
                </div>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Sign out" class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
