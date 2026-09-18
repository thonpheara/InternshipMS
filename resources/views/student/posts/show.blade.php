<x-layout :title="$post->title . ' — Internship Management System'">
    <div class="space-y-6 max-w-5xl mx-auto">
        
        <!-- Back Link -->
        <div>
            <a href="{{ route('student.posts.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-indigo-600 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to all internships</span>
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Left Main Column (2 cols) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Header Card -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ ucfirst($post->type) }}
                        </span>
                        <span class="text-sm font-extrabold text-emerald-600">
                            {{ $post->stipend ? '$' . number_format($post->stipend, 2) . '/month' : 'Standard University Stipend' }}
                        </span>
                    </div>

                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                            {{ $post->title }}
                        </h2>
                        <p class="text-sm font-semibold text-slate-600 mt-1 flex items-center gap-1.5">
                            <i data-lucide="building-2" class="w-4 h-4 text-slate-400"></i>
                            {{ $post->companyProfile->company_name }} • {{ $post->location }}
                        </p>
                    </div>

                    <!-- Meta Tags Row -->
                    <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="block text-slate-600 text-[10px] uppercase font-bold">Duration</span>
                            <span class="font-bold text-slate-900">{{ $post->duration_weeks }} Weeks</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="block text-slate-600 text-[10px] uppercase font-bold">Open Slots</span>
                            <span class="font-bold text-slate-900">{{ $post->slots }} Candidate(s)</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="block text-slate-600 text-[10px] uppercase font-bold">Application Cutoff</span>
                            <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($post->deadline)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Detailed Description -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-6 text-slate-700 text-sm leading-relaxed">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Role Overview</h3>
                        <p class="whitespace-pre-line text-slate-600">{{ $post->description }}</p>
                    </div>

                    @if ($post->responsibilities)
                        <div class="pt-4 border-t border-slate-100">
                            <h3 class="text-base font-bold text-slate-900 mb-2">Key Responsibilities</h3>
                            <div class="whitespace-pre-line text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 font-mono text-xs">
                                {{ $post->responsibilities }}
                            </div>
                        </div>
                    @endif

                    @if ($post->requirements)
                        <div class="pt-4 border-t border-slate-100">
                            <h3 class="text-base font-bold text-slate-900 mb-2">Technical & Educational Requirements</h3>
                            <div class="whitespace-pre-line text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 font-mono text-xs">
                                {{ $post->requirements }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Sidebar Column (1 col) -->
            <div class="space-y-6">

                <!-- Application Submission Panel -->
                <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="send" class="w-4.5 h-4.5 text-indigo-600"></i>
                        Application Submission
                    </h3>

                    @if ($existingApplication)
                        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-900">Application Status</span>
                                <x-status-badge :status="$existingApplication->status" />
                            </div>
                            <p class="text-xs text-indigo-700">
                                You submitted your candidacy on {{ \Carbon\Carbon::parse($existingApplication->applied_at)->format('M d, Y') }}.
                            </p>
                            @if ($existingApplication->company_notes)
                                <div class="p-3 bg-white rounded-xl text-xs text-slate-700 border border-indigo-100">
                                    <strong class="block text-[11px] uppercase font-bold text-slate-600 mb-1">Company Note:</strong>
                                    {{ $existingApplication->company_notes }}
                                </div>
                            @endif
                        </div>
                    @elseif ($student && !$student->isEligible())
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-800 space-y-2">
                            <p class="font-bold flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600"></i>
                                Eligibility Review Required
                            </p>
                            <p>Your institutional eligibility status is currently <span class="font-bold">{{ ucfirst($student->eligibility_status) }}</span>. Please reach out to your faculty supervisor before submitting applications.</p>
                        </div>
                    @else
                        <!-- Application Form -->
                        <form action="{{ route('student.posts.apply', $post) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="cover_letter" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Statement of Intent / Cover Letter
                                </label>
                                <textarea id="cover_letter" 
                                          name="cover_letter" 
                                          rows="5" 
                                          required 
                                          placeholder="Describe your technical background, relevant projects, and motivation for joining {{ $post->companyProfile->company_name }}..."
                                          class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">{{ old('cover_letter') }}</textarea>
                                <p class="text-[10px] text-slate-600 mt-1">Minimum 30 characters. Your stored profile resume will be attached automatically.</p>
                            </div>

                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                <span>Submit Official Application</span>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Host Organization Profile Card -->
                <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600">About the Host Organization</h4>
                    <h5 class="text-sm font-extrabold text-slate-900">{{ $post->companyProfile->company_name }}</h5>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $post->companyProfile->description }}</p>
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                        <div>Industry: <strong class="text-slate-800">{{ $post->companyProfile->industry }}</strong></div>
                        @if ($post->companyProfile->website)
                            <div>Website: <a href="{{ $post->companyProfile->website }}" target="_blank" class="text-indigo-600 hover:underline">{{ $post->companyProfile->website }}</a></div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-layout>
