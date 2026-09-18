@php
    $user = Auth::user();
    $role = $user?->role ?? 'student';
@endphp

<header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <!-- Mobile menu toggle -->
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <div>
            <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">
                {{ $header ?? 'Dashboard' }}
            </h1>
            <p class="text-xs text-slate-600 hidden sm:block">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Role Status Indicator Pill -->
        <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
            <span class="w-2 h-2 rounded-full {{ $role === 'student' ? 'bg-indigo-500' : ($role === 'company' ? 'bg-emerald-500' : 'bg-amber-500') }} animate-pulse"></span>
            <span>Signed in as {{ ucfirst($role) }}</span>
        </div>

        <!-- Role Action Shortcut -->
        @if ($role === 'student')
            <a href="{{ route('student.posts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 shadow-xs shadow-indigo-600/20 transition-colors">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Find Internships</span>
            </a>
        @elseif ($role === 'company')
            <a href="{{ route('company.posts.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 shadow-xs shadow-emerald-600/20 transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Post Job</span>
            </a>
        @endif
    </div>
</header>
