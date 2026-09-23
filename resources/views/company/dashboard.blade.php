<x-layout>
    <div class="space-y-6">

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Active Listings</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-list-check w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['active_posts'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Vetted by university</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Applicants</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-user-group w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['total_applicants'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Received candidacies</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Pending Reviews</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                        <i class="fa-solid fa-hourglass-half w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-amber-600">{{ $stats['pending_review'] }}</h3>
                    <p class="text-xs text-amber-700 font-semibold mt-1">Action required</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Active Interns</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-id-card-clip w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['active_interns'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Currently placed</p>
                </div>
            </div>
        </div>

        <!-- Recent Applicants Section -->
        <div class="p-6 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <h4 class="text-sm font-bold text-[#111827] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-users w-4 h-4 text-[#059669]"></i>
                    Recent Candidate Submissions
                </h4>
                <a href="{{ route('company.applicants.index') }}" class="text-xs font-semibold text-[#059669] hover:underline">Pipeline Board</a>
            </div>

            @if ($recentApplicants->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach ($recentApplicants as $app)
                        <div class="py-3.5 flex items-start justify-between gap-4">
                            <div>
                                <h5 class="text-xs font-bold text-[#111827]">{{ $app->studentProfile->user->name }}</h5>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $app->internshipPost->title }}</p>
                                <span class="text-[11px] text-gray-400 mt-0.5 block">GPA: {{ $app->studentProfile->gpa !== null ? number_format($app->studentProfile->gpa, 2) : 'N/A' }} • Applied {{ \Carbon\Carbon::parse($app->applied_at)->diffForHumans() }}</span>
                            </div>
                            <x-status-badge :status="$app->status" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-gray-400 text-xs">
                    No recent applications received yet.
                </div>
            @endif
        </div>

    </div>
</x-layout>

