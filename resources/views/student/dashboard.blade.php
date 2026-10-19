<x-layout>
    <div class="flex flex-col h-[calc(100vh-6rem)] sm:h-[calc(100vh-7rem)] lg:h-[calc(100vh-8rem)] space-y-4 sm:space-y-5">

        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shrink-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Manage your internship search, track applications, and update your academic credentials.</p>
            </div>
            <a href="{{ route('student.posts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-colors shadow-xs self-start sm:self-auto shrink-0">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>Find Internships</span>
            </a>
        </div>

        <!-- Metrics Overview Grid (4 KPI Cards) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 shrink-0">
            <!-- 1. Total Applications -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-500">Total Applications</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-paper-plane text-xs sm:text-sm"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-xl sm:text-2xl font-black text-[#111827]">{{ $stats['total_applications'] }}</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Submitted positions</p>
                </div>
            </div>

            <!-- 2. Under Review / Pending -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-amber-400/60 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-500">Under Review</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-hourglass-half text-xs sm:text-sm"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-xl sm:text-2xl font-black text-amber-600">{{ $stats['pending'] }}</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Awaiting review</p>
                </div>
            </div>

            <!-- 3. Shortlisted & Interviews -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 truncate mr-1">Interviews & Shortlisted</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-calendar-check text-xs sm:text-sm"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-xl sm:text-2xl font-black text-[#111827]">{{ $stats['shortlisted'] }}</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Active interviews</p>
                </div>
            </div>

            <!-- 4. Offers Accepted -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-500">Offers Accepted</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-trophy text-xs sm:text-sm text-emerald-600"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-700">{{ $stats['accepted'] }}</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Confirmed placements</p>
                </div>
            </div>
        </div>

        <!-- 2-Column Section: Application Tracker & Recommended Internships (Full remaining height) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 flex-1 min-h-0">

            <!-- Left: Applications Tracking Section (7 cols) -->
            <div class="lg:col-span-7 p-5 sm:p-6 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs flex flex-col h-full min-h-0 overflow-hidden">
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] shrink-0">
                    <h4 class="text-sm font-bold text-[#111827] uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane w-4 h-4 text-[#059669]"></i>
                        Application Status Tracker
                    </h4>
                    <a href="{{ route('student.applications.index') }}" class="text-xs font-semibold text-[#059669] hover:underline flex items-center gap-1">
                        <span>View All</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if ($recentApplications->isNotEmpty())
                    <div class="divide-y divide-gray-100 flex-1 min-h-0 overflow-y-auto pr-1">
                        @foreach ($recentApplications as $app)
                            <div class="py-3 sm:py-3.5 flex items-start justify-between gap-4 group">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('student.posts.show', $app->internship_post_id) }}" class="text-xs font-bold text-[#111827] group-hover:text-[#059669] transition-colors line-clamp-1">
                                        {{ $app->internshipPost->title }}
                                    </a>
                                    <p class="text-xs text-gray-600 mt-0.5 truncate">{{ $app->internshipPost->companyProfile->company_name }}</p>
                                    <div class="flex items-center gap-2 text-[11px] text-gray-400 mt-1">
                                        <span><i class="fa-regular fa-clock mr-1 text-[10px]"></i>Applied: {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                                        @if($app->internshipPost->location)
                                            <span>•</span>
                                            <span class="truncate"><i class="fa-solid fa-location-dot mr-1 text-[10px]"></i>{{ $app->internshipPost->location }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 pt-0.5">
                                    <x-status-badge :status="$app->status" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-gray-400 text-xs my-auto">
                        <i class="fa-solid fa-file-circle-question text-3xl mx-auto mb-2 text-gray-300"></i>
                        <p class="font-medium text-gray-500">You haven't applied for any positions yet.</p>
                        <a href="{{ route('student.posts.index') }}" class="mt-3 inline-block px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100 transition-colors text-xs">
                            Browse Open Roles
                        </a>
                    </div>
                @endif
            </div>

            <!-- Right: Recommended Internships Section (5 cols) -->
            <div class="lg:col-span-5 p-5 sm:p-6 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs flex flex-col h-full min-h-0 overflow-hidden">
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] shrink-0">
                    <h4 class="text-sm font-bold text-[#111827] uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles w-4 h-4 text-[#059669]"></i>
                        Recommended Internships
                    </h4>
                    <a href="{{ route('student.posts.index') }}" class="text-xs font-semibold text-[#059669] hover:underline flex items-center gap-1">
                        <span>Explore All</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if (isset($recommendedPosts) && $recommendedPosts->isNotEmpty())
                    <div class="space-y-3 flex-1 min-h-0 overflow-y-auto pr-1">
                        @foreach ($recommendedPosts as $post)
                            @php
                                $comp = $post->companyProfile;
                                $compLogo = $comp?->logo_url;
                                $compHasLogo = $comp?->has_logo;
                                $compInitials = $comp?->initials ?? 'CO';
                            @endphp
                            <div class="p-3 sm:p-3.5 rounded-2xl border border-gray-100 hover:border-[#059669]/40 hover:bg-emerald-50/20 transition-all flex flex-col gap-2.5 group">
                                <div class="flex items-center gap-3">
                                    @if($compHasLogo && $compLogo)
                                        <img src="{{ $compLogo }}" alt="{{ $comp?->company_name }}" class="w-9 h-9 rounded-xl object-cover shrink-0 border border-slate-200">
                                    @else
                                        <div class="w-9 h-9 rounded-xl bg-teal-700 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                            {{ $compInitials }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('student.posts.show', $post) }}" class="text-xs font-bold text-[#111827] group-hover:text-[#059669] transition-colors truncate block">
                                            {{ $post->title }}
                                        </a>
                                        <p class="text-[11px] text-gray-500 truncate">{{ $comp?->company_name }} • {{ $post->location }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-2 border-t border-gray-100/80 text-[11px]">
                                    <span class="font-bold text-[#059669]">
                                        @if ($post->stipend)
                                            ${{ number_format($post->stipend, 0) }}/mo
                                        @else
                                            Standard Stipend
                                        @endif
                                    </span>
                                    <a href="{{ route('student.posts.show', $post) }}" class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-[#059669] font-bold text-[11px] transition-colors">
                                        View Details &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-gray-400 text-xs my-auto">
                        <i class="fa-solid fa-briefcase text-3xl mx-auto mb-2 text-gray-300"></i>
                        <p class="font-medium text-gray-500">No recommended openings at the moment.</p>
                        <a href="{{ route('student.posts.index') }}" class="mt-3 inline-block px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100 transition-colors text-xs">
                            View All Opportunities
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout>
