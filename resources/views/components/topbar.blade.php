@php
    $user = Auth::user();
    $role = $user?->role ?? 'student';
@endphp

<header class="h-16 bg-white/90 backdrop-blur-md border-b border-[#E5E7EB] sticky top-0 z-30 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <!-- Mobile menu toggle -->
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 hover:text-[#111827] rounded-lg hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-bars w-5 h-5"></i>
        </button>

        <div>
            <h1 class="text-base sm:text-lg font-bold text-[#111827] leading-tight">
                {{ $header ?? 'Dashboard' }}
            </h1>
            <p class="text-xs text-gray-500 hidden sm:block">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Role Status Indicator Pill -->
        <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-[#F3F4F6] text-[#111827] text-xs font-medium border border-[#E5E7EB]">
            <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
            <span>Signed in as {{ ucfirst($role) }}</span>
        </div>

        <!-- Role Action Shortcut -->
        @if ($role === 'student')
            <a href="{{ route('student.posts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] shadow-xs transition-all">
                <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Find Internships</span>
            </a>
            <a href="{{ route('student.profile') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 text-xs font-semibold border border-slate-200/80 transition-all">
                <i class="fa-solid fa-user-graduate w-3.5 h-3.5 text-emerald-600"></i>
                <span class="hidden md:inline">Profile</span>
            </a>
        @elseif ($role === 'company')
            <a href="{{ route('company.posts.index', ['create' => 1]) }}" 
               @click="if (window.location.pathname.endsWith('/company/posts')) { $event.preventDefault(); window.dispatchEvent(new CustomEvent('open-create-modal')); }"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] shadow-xs transition-all cursor-pointer">
                <i class="fa-solid fa-plus w-3.5 h-3.5"></i>
                <span>Post Job</span>
            </a>
        @endif
    </div>
</header>

