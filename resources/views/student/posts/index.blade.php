<x-layout title="Browse Approved Internships — Internship Management System">
    <div class="space-y-6">

        <!-- Page Header & Filters -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Internship Vacancies</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Explore university-vetted opportunities from registered host companies.</p>
            </div>

            <!-- Search & Filters Form -->
            <form action="{{ route('student.posts.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="relative min-w-[240px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search role, skills, company..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <select name="type" onchange="this.form.submit()" class="py-2 pl-3 pr-8 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700">
                    <option value="">All Work Modes</option>
                    <option value="remote" {{ request('type') === 'remote' ? 'selected' : '' }}>Remote</option>
                    <option value="on_site" {{ request('type') === 'on_site' ? 'selected' : '' }}>On-Site</option>
                    <option value="hybrid" {{ request('type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Post Cards Grid -->
        @if ($posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($posts as $post)
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ ucfirst($post->type) }}
                                </span>
                                <span class="text-xs font-bold text-emerald-600">
                                    @if ($post->stipend)
                                        ${{ number_format($post->stipend, 0) }}/mo
                                    @else
                                        Standard Stipend
                                    @endif
                                </span>
                            </div>

                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-xs font-semibold text-slate-600 mt-0.5">
                                    {{ $post->companyProfile->company_name }}
                                </p>
                            </div>

                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $post->description }}
                            </p>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="text-[11px] text-slate-600">
                                <span><i data-lucide="map-pin" class="w-3.5 h-3.5 inline text-slate-400"></i> {{ $post->location }}</span>
                                <span class="block text-slate-600 font-medium mt-0.5">Deadline: {{ \Carbon\Carbon::parse($post->deadline)->format('M d') }}</span>
                            </div>

                            <a href="{{ route('student.posts.show', $post) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 group-hover:bg-indigo-600 group-hover:text-white text-slate-700 text-xs font-bold transition-all flex items-center gap-1">
                                <span>Details</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $posts->links() }}
            </div>
        @else
            <div class="p-12 rounded-3xl bg-white border border-slate-200/80 text-center space-y-3">
                <i data-lucide="search-x" class="w-10 h-10 mx-auto text-slate-300"></i>
                <h3 class="text-base font-bold text-slate-800">No internship listings match your filter</h3>
                <p class="text-xs text-slate-600 max-w-sm mx-auto">Try clearing your search query or selecting "All Work Modes" to view available positions.</p>
                <a href="{{ route('student.posts.index') }}" class="inline-block px-4 py-2 rounded-xl bg-slate-100 text-xs font-bold text-slate-700 hover:bg-slate-200">
                    Reset Filter
                </a>
            </div>
        @endif

    </div>
</x-layout>
