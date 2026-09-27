<x-layout>
    <div class="flex flex-col h-[calc(100vh-6rem)] sm:h-[calc(100vh-7rem)] lg:h-[calc(100vh-8rem)]"
         x-data="{
             statusModalOpen: false,
             statusApp: {
                 id: '',
                 candidateName: '',
                 roleTitle: '',
                 status: 'pending',
                 companyNotes: '',
                 updateUrl: ''
             },
             openStatusModal(data) {
                 this.statusApp = { ...data };
                 this.statusModalOpen = true;
             }
         }">

        <!-- Page Header & Filter Form -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 shrink-0">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Applicant Review Board</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Review student resumes, shortlist profiles, and issue placement offers.</p>
            </div>

            <form action="{{ route('company.applicants.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 shrink-0">
                <!-- Job Post Filter Dropdown -->
                <div class="relative"
                     x-data="{
                         open: false,
                         postId: '{{ request('post_id', '') }}',
                         postTitle: '{{ $companyPosts->firstWhere('id', request('post_id'))?->title ?? 'All Job Listings' }}',
                         selectPost(id, title) {
                             this.postId = id;
                             this.postTitle = title;
                             this.open = false;
                             $nextTick(() => {
                                 $el.closest('form').submit();
                             });
                         }
                     }">
                    <input type="hidden" name="post_id" :value="postId">
                    <button type="button" 
                            @click="open = !open" 
                            class="inline-flex items-center justify-between gap-2.5 py-2 pl-3.5 pr-3 text-xs rounded-xl bg-white hover:bg-slate-50 border border-slate-200 hover:border-[#059669] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] font-medium text-slate-700 transition-all cursor-pointer shadow-2xs max-w-[240px]">
                        <span class="flex items-center gap-2 truncate">
                            <i class="fa-solid fa-briefcase text-[11px] text-gray-400"></i>
                            <span class="truncate font-semibold text-slate-800" x-text="postTitle"></span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200 shrink-0"
                           :class="open ? 'rotate-180 text-[#059669]' : ''"></i>
                    </button>

                    <div x-show="open" 
                         x-cloak
                         @click.outside="open = false"
                         @keydown.escape.window="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute left-0 top-full z-40 mt-1.5 w-64 max-h-60 overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-xl p-1.5 space-y-0.5"
                         style="display: none;">
                        <button type="button" 
                                @click="selectPost('', 'All Job Listings')" 
                                class="w-full flex items-center justify-between px-3 py-2 text-xs rounded-xl transition-all cursor-pointer text-left"
                                :class="postId === '' ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'">
                            <span class="truncate">All Job Listings</span>
                            <i x-show="postId === ''" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                        </button>
                        @foreach ($companyPosts as $p)
                            <button type="button" 
                                    @click="selectPost('{{ $p->id }}', '{{ addslashes($p->title) }}')" 
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs rounded-xl transition-all cursor-pointer text-left"
                                    :class="postId == '{{ $p->id }}' ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'">
                                <span class="truncate">{{ $p->title }}</span>
                                <i x-show="postId == '{{ $p->id }}'" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Candidacy Stage Filter Dropdown -->
                <div class="relative"
                     x-data="{
                         open: false,
                         statusVal: '{{ request('status', '') }}',
                         options: [
                             { value: '', label: 'All Candidacy Stages', dot: 'bg-slate-400' },
                             { value: 'pending', label: 'Pending Review', dot: 'bg-sky-500 ring-2 ring-sky-100' },
                             { value: 'under_review', label: 'Under Review', dot: 'bg-purple-500 ring-2 ring-purple-100' },
                             { value: 'shortlisted', label: 'Shortlisted', dot: 'bg-indigo-500 ring-2 ring-indigo-100' },
                             { value: 'interviewed', label: 'Interviewed', dot: 'bg-amber-500 ring-2 ring-amber-100' },
                             { value: 'accepted', label: 'Accepted (Hired)', dot: 'bg-emerald-500 ring-2 ring-emerald-100' },
                             { value: 'rejected', label: 'Rejected', dot: 'bg-rose-500 ring-2 ring-rose-100' }
                         ],
                         get currentOption() {
                             return this.options.find(o => o.value === this.statusVal) || this.options[0];
                         },
                         selectStatus(val) {
                             this.statusVal = val;
                             this.open = false;
                             $nextTick(() => {
                                 $el.closest('form').submit();
                             });
                         }
                     }">
                    <input type="hidden" name="status" :value="statusVal">
                    <button type="button" 
                            @click="open = !open" 
                            class="inline-flex items-center justify-between gap-2.5 py-2 pl-3.5 pr-3 text-xs rounded-xl bg-white hover:bg-slate-50 border border-slate-200 hover:border-[#059669] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] font-medium text-slate-700 transition-all cursor-pointer shadow-2xs">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full shrink-0" :class="currentOption.dot"></span>
                            <span class="font-semibold text-slate-800" x-text="currentOption.label"></span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200"
                           :class="open ? 'rotate-180 text-[#059669]' : ''"></i>
                    </button>

                    <div x-show="open" 
                         x-cloak
                         @click.outside="open = false"
                         @keydown.escape.window="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 sm:left-0 top-full z-40 mt-1.5 w-52 rounded-2xl bg-white border border-slate-200 shadow-xl p-1.5 space-y-0.5"
                         style="display: none;">
                        <template x-for="item in options" :key="item.value">
                            <button type="button" 
                                    @click="selectStatus(item.value)" 
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs rounded-xl transition-all cursor-pointer text-left"
                                    :class="statusVal === item.value ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2 h-2 rounded-full shrink-0" :class="item.dot"></span>
                                    <span x-text="item.label"></span>
                                </div>
                                <i x-show="statusVal === item.value" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                            </button>
                        </template>
                    </div>
                </div>
            </form>
        </div>

        <!-- Applicants Grid / Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between flex-1 min-h-0">
            @if ($applications->isNotEmpty())
                <div class="w-full overflow-auto flex-1 min-h-0">
                    <table class="w-full text-left border-collapse text-xs table-fixed">
                        <thead class="sticky top-0 z-10 bg-slate-50/95 backdrop-blur-xs">
                            <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3 px-6 w-[24%]">Student Candidate</th>
                                <th class="py-3 px-4 w-[22%]">Role Applied</th>
                                <th class="py-3 px-4 w-[14%] whitespace-nowrap">Status</th>
                                <th class="py-3 px-4 w-[26%]">Statement / Cover Letter</th>
                                <th class="py-3 px-6 text-right w-[14%] whitespace-nowrap">Review Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 px-6 min-w-0">
                                        <div class="font-extrabold text-slate-900 text-sm truncate">{{ $app->studentProfile->user->name }}</div>
                                        <div class="text-slate-600 font-semibold truncate">{{ $app->studentProfile->major }} (GPA: {{ $app->studentProfile->gpa ?? 'N/A' }})</div>
                                        @if (is_array($app->studentProfile->skills))
                                            <div class="flex flex-wrap gap-1 mt-1.5">
                                                @foreach (array_slice($app->studentProfile->skills, 0, 3) as $skill)
                                                    <span class="px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold truncate max-w-[100px]">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 min-w-0">
                                        <div class="font-bold text-slate-900 truncate" title="{{ $app->internshipPost->title }}">{{ $app->internshipPost->title }}</div>
                                        <span class="text-[11px] text-slate-600 block truncate">Applied {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <x-status-badge :status="$app->status" />
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 min-w-0">
                                        <p class="line-clamp-2 text-xs break-all break-words leading-relaxed">{{ $app->cover_letter }}</p>
                                        @if ($app->company_notes)
                                            <div class="mt-1 text-[11px] text-emerald-700 font-medium truncate break-all break-words">Note: {{ $app->company_notes }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            @php
                                                $appResumePath = $app->getEffectiveResumePath();
                                                $resumeExists = $appResumePath && Storage::disk('public')->exists($appResumePath);
                                            @endphp
                                            @if($resumeExists)
                                                @php
                                                    $resumeFileName = basename($appResumePath);
                                                    $resumeExt = strtolower(pathinfo($resumeFileName, PATHINFO_EXTENSION));
                                                @endphp
                                                @if(in_array($resumeExt, ['pdf', 'png', 'jpg', 'jpeg']))
                                                    <a href="{{ route('company.applicants.resume', $app) }}" 
                                                       target="_blank"
                                                       class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                                       title="Preview {{ $resumeFileName }}">
                                                        <i class="fa-solid fa-file-lines text-xs text-emerald-600"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('company.applicants.resume', [$app, 'download' => 1]) }}" 
                                                   download="{{ $resumeFileName }}"
                                                   class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                                   title="Download {{ $resumeFileName }}">
                                                    <i class="fa-solid fa-download text-xs text-slate-600"></i>
                                                </a>
                                            @else
                                                <span class="w-8 h-8 flex items-center justify-center text-slate-300 rounded-lg border border-dashed border-slate-200" title="No resume document attached">
                                                    <i class="fa-solid fa-file-circle-xmark text-xs text-slate-300"></i>
                                                </span>
                                            @endif
                                            <a href="{{ route('company.messages.start', $app) }}" 
                                                class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                                title="Chat with Candidate">
                                                <i class="fa-solid fa-comment-dots text-xs"></i>
                                            </a>
                                            <button type="button"
                                                    @click="openStatusModal({
                                                        id: {{ $app->id }},
                                                        candidateName: @js($app->studentProfile->user->name),
                                                        roleTitle: @js($app->internshipPost->title),
                                                        status: @js($app->status),
                                                        companyNotes: @js($app->company_notes ?? ''),
                                                        updateUrl: @js(route('company.applicants.update', $app)),
                                                        hasResume: @js($resumeExists),
                                                        resumeFileName: @js($resumeExists ? basename($appResumePath) : ''),
                                                        resumePreviewUrl: @js($resumeExists && in_array(strtolower(pathinfo(basename($appResumePath), PATHINFO_EXTENSION)), ['pdf', 'png', 'jpg', 'jpeg']) ? route('company.applicants.resume', $app) : ''),
                                                        resumeDownloadUrl: @js($resumeExists ? route('company.applicants.resume', [$app, 'download' => 1]) : '')
                                                    })"
                                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                                    title="Update Candidacy Status">
                                                <i class="fa-solid fa-sliders text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-white shrink-0">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-16 text-center text-slate-600 space-y-3">
                    <i class="fa-solid fa-users w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No applicants match this filter</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Select a different role or reset filters to review incoming candidate profiles.</p>
                </div>
            @endif
        </div>

        {{-- Update Candidacy Status Modal --}}
        @include('company.applicants.modal-status')

    </div>
</x-layout>
