@php
    $user = Auth::user();
    $role = $user?->role ?? 'student';

    $roleLabel = match($role) {
        'admin' => 'University Admin',
        'company' => 'Host Company',
        default => 'Student Intern',
    };

    $activeClass = 'bg-[#059669] text-white font-semibold shadow-xs';
    $inactiveClass = 'text-gray-700 hover:text-[#111827] hover:bg-gray-200/70 font-medium transition-all';
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-68 bg-[#F3F4F6] border-r border-[#E5E7EB] flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 shadow-sm text-[#111827]">
    
    <!-- Brand Logo -->
    <div class="h-16 px-6 flex items-center justify-between border-b border-[#E5E7EB] bg-white/60">
        <a href="/" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-xl bg-[#059669] flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-graduation-cap w-5 h-5 text-base font-bold"></i>
            </div>
            <div>
                <span class="text-lg font-extrabold tracking-tight text-[#111827] flex items-center gap-1">
                    Intern<span class="text-[#059669]">ship</span>
                </span>
                <span class="block text-[10px] font-semibold text-gray-500 tracking-wider uppercase">Management System</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-[#111827] p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-xmark w-5 h-5"></i>
        </button>
    </div>

    <!-- Active User Role Indicator -->
    <div class="px-5 py-3 border-b border-[#E5E7EB] bg-white/30">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500">Workspace</span>
            <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">
                {{ $roleLabel }}
            </span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
        @if ($role === 'student')
            <!-- Student Navigation -->
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('student.dashboard') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-chart-pie w-4.5 h-4.5"></i>
                Overview Dashboard
            </a>
            <a href="{{ route('student.posts.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('student.posts.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-briefcase w-4.5 h-4.5"></i>
                Browse Internships
            </a>
            <a href="{{ route('student.applications.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('student.applications.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-file-circle-check w-4.5 h-4.5"></i>
                My Applications
            </a>
            <a href="{{ route('student.messages.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('student.messages.*') ? $activeClass : $inactiveClass }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-comment w-4.5 h-4.5"></i>
                    <span>Direct Messages</span>
                </div>
                @php $unread = $user->unreadMessagesCount(); @endphp
                @if($unread > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ request()->routeIs('student.messages.*') ? 'bg-[#D1FAE5] text-[#065F46]' : 'bg-[#059669] text-white' }}">
                        {{ $unread }}
                    </span>
                @endif
            </a>
            <a href="{{ route('student.profile') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('student.profile*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-user-graduate w-4.5 h-4.5"></i>
                Student Profile
            </a>

        @elseif ($role === 'company')
            <!-- Company Navigation -->
            <a href="{{ route('company.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('company.dashboard') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-chart-pie w-4.5 h-4.5"></i>
                Overview Dashboard
            </a>
            <a href="{{ route('company.posts.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('company.posts.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-file-circle-plus w-4.5 h-4.5"></i>
                Manage Listings
            </a>
            <a href="{{ route('company.applicants.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('company.applicants.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-users w-4.5 h-4.5"></i>
                Applicant Pipeline
            </a>
            <a href="{{ route('company.messages.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('company.messages.*') ? $activeClass : $inactiveClass }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-comment w-4.5 h-4.5"></i>
                    <span>Messages</span>
                </div>
                @php $unread = $user->unreadMessagesCount(); @endphp
                @if($unread > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ request()->routeIs('company.messages.*') ? 'bg-[#D1FAE5] text-[#065F46]' : 'bg-[#059669] text-white' }}">
                        {{ $unread }}
                    </span>
                @endif
            </a>
            <a href="{{ route('company.profile') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('company.profile') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-city w-4.5 h-4.5"></i>
                Company Profile
            </a>

        @else
            <!-- Admin Navigation -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-gauge w-4.5 h-4.5"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.approvals.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.approvals.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-square-check w-4.5 h-4.5"></i>
                Job Post Moderation
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-users-gear w-4.5 h-4.5"></i>
                User Management
            </a>
        @endif
    </nav>

    <!-- Bottom User Section & Logout -->
    <div class="p-3 border-t border-[#E5E7EB] bg-white/50">
        <div class="p-2 rounded-xl bg-white border border-[#E5E7EB] shadow-xs flex items-center justify-between">
            @php
                $profileRoute = match($role) {
                    'student' => route('student.profile'),
                    'company' => route('company.profile'),
                    default => null,
                };
                $displayName = match($role) {
                    'company' => $user->companyProfile?->company_name ?: $user->name,
                    default => $user->name,
                };
                $logoPath = $role === 'company' ? $user->companyProfile?->logo_path : null;
                $hasLogo = $logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);
                $initials = strtoupper(substr($displayName, 0, 2));
            @endphp
            @if($profileRoute)
                <a href="{{ $profileRoute }}" title="View Profile" class="flex items-center gap-2.5 min-w-0 flex-1 hover:opacity-80 transition-opacity">
                    @if($hasLogo)
                        <img src="{{ asset('storage/' . ltrim($logoPath, '/')) }}" alt="{{ $displayName }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200 shadow-xs">
                    @else
                        <div class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-[#111827] truncate" title="{{ $displayName }}">{{ $displayName }}</p>
                        <p class="text-[11px] text-gray-500 truncate" title="{{ $user->email }}">{{ $user->email }}</p>
                    </div>
                </a>
            @else
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-[#111827] truncate" title="{{ $displayName }}">{{ $displayName }}</p>
                        <p class="text-[11px] text-gray-500 truncate" title="{{ $user->email }}">{{ $user->email }}</p>
                    </div>
                </div>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Sign out" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
