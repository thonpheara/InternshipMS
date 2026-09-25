<x-layout>
    <div class="space-y-6">

        <!-- Header Banner with Profile Quick Action -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Manage your internship search, track applications, and update your academic credentials.</p>
            </div>
            <a href="{{ route('student.profile') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 text-xs font-bold shadow-2xs transition-all w-fit">
                <i class="fa-solid fa-user-graduate text-emerald-600"></i>
                <span>Manage Profile</span>
            </a>
        </div>

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Applications</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['total_applications'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Submitted positions</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Shortlisted</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-wand-magic-sparkles w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['shortlisted'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Interview invitations</p>
                </div>
            </div>
        </div>

        <!-- Applications Tracking Section -->
        <div class="p-6 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <h4 class="text-sm font-bold text-[#111827] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane w-4 h-4 text-[#059669]"></i>
                    Application Status Tracker
                </h4>
                <a href="{{ route('student.applications.index') }}" class="text-xs font-semibold text-[#059669] hover:underline">View All</a>
            </div>

            @if ($recentApplications->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach ($recentApplications as $app)
                        <div class="py-3.5 flex items-start justify-between gap-4">
                            <div>
                                <h5 class="text-xs font-bold text-[#111827]">{{ $app->internshipPost->title }}</h5>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $app->internshipPost->companyProfile->company_name }}</p>
                                <span class="text-[11px] text-gray-400 mt-1 block">Applied: {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                            </div>
                            <x-status-badge :status="$app->status" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-gray-400 text-xs">
                    <i class="fa-solid fa-file-circle-question w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                    You haven't applied for any positions yet.
                </div>
            @endif
        </div>

    </div>
</x-layout>
