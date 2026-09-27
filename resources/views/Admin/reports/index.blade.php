<x-layout>
    <div class="space-y-6">

        <!-- Page Header & Export Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#111827]">Reports</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Comprehensive placement analytics, employer hiring pipelines, and student outcomes.</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2">
                <!-- Export to CSV / Excel -->
                <a href="{{ route('admin.reports.exportCsv', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-file-excel w-4 h-4"></i>
                    <span>Export CSV (Excel)</span>
                </a>

                <!-- Print / PDF View -->
                <a href="{{ route('admin.reports.print', request()->query()) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-print w-4 h-4"></i>
                    <span>Print / PDF</span>
                </a>
            </div>
        </div>

        <!-- KPI Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Applications -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-file-lines w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Total Applications</span>
                    <div class="text-xl font-black text-[#111827] mt-0.5">{{ number_format($stats['total_applications']) }}</div>
                </div>
            </div>

            <!-- Accepted Placements -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-circle-check w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Accepted Placements</span>
                    <div class="text-xl font-black text-[#059669] mt-0.5">{{ number_format($stats['total_accepted']) }}</div>
                </div>
            </div>

            <!-- Placement Rate -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-chart-pie w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Placement Success Rate</span>
                    <div class="text-xl font-black text-[#111827] mt-0.5">{{ $stats['placement_rate'] }}%</div>
                </div>
            </div>

            <!-- Hiring Partner Companies -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-building-user w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Active Hiring Partners</span>
                    <div class="text-xl font-black text-[#111827] mt-0.5">{{ number_format($stats['total_companies_hiring']) }}</div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-4 shadow-xs">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <!-- Status Filter Dropdown -->
                <div class="flex items-center gap-1.5"
                     x-data="{
                         open: false,
                         statusVal: '{{ $status }}',
                         options: [
                             { value: 'all', label: 'All Statuses', dot: 'bg-slate-400' },
                             { value: 'accepted', label: 'Accepted (Placed)', dot: 'bg-emerald-500 ring-2 ring-emerald-100' },
                             { value: 'shortlisted', label: 'Shortlisted', dot: 'bg-indigo-500 ring-2 ring-indigo-100' },
                             { value: 'interviewed', label: 'Interviewed', dot: 'bg-amber-500 ring-2 ring-amber-100' },
                             { value: 'under_review', label: 'Under Review', dot: 'bg-purple-500 ring-2 ring-purple-100' },
                             { value: 'pending', label: 'Pending', dot: 'bg-sky-500 ring-2 ring-sky-100' },
                             { value: 'rejected', label: 'Rejected', dot: 'bg-rose-500 ring-2 ring-rose-100' }
                         ],
                         get currentOption() {
                             return this.options.find(o => o.value === this.statusVal) || this.options[0];
                         },
                         select(val) {
                             this.statusVal = val;
                             this.open = false;
                             $nextTick(() => {
                                 $el.closest('form').submit();
                             });
                         }
                     }">
                    <label class="text-[11px] font-bold text-gray-500 uppercase">Status:</label>
                    <div class="relative">
                        <input type="hidden" name="status" :value="statusVal">
                        <button type="button" 
                                @click="open = !open" 
                                class="inline-flex items-center justify-between gap-2.5 py-1.5 pl-3 pr-2.5 text-xs rounded-xl bg-white hover:bg-slate-50 border border-[#E5E7EB] hover:border-[#059669] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] font-medium text-gray-700 transition-all cursor-pointer shadow-2xs">
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
                             class="absolute left-0 top-full z-40 mt-1.5 w-52 rounded-2xl bg-white border border-slate-200 shadow-xl p-1.5 space-y-0.5"
                             style="display: none;">
                            <template x-for="item in options" :key="item.value">
                                <button type="button" 
                                        @click="select(item.value)" 
                                        class="w-full flex items-center justify-between px-3 py-1.5 text-xs rounded-xl transition-all cursor-pointer text-left"
                                        :class="statusVal === item.value ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-gray-700 hover:bg-slate-50 font-medium'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full shrink-0" :class="item.dot"></span>
                                        <span x-text="item.label"></span>
                                    </div>
                                    <i x-show="statusVal === item.value" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Department Filter Dropdown -->
                @if($departments->isNotEmpty())
                    <div class="flex items-center gap-1.5"
                         x-data="{
                             open: false,
                             deptVal: '{{ $department }}',
                             get currentLabel() {
                                 return this.deptVal === 'all' ? 'All Departments' : this.deptVal;
                             },
                             select(val) {
                                 this.deptVal = val;
                                 this.open = false;
                                 $nextTick(() => {
                                     $el.closest('form').submit();
                                 });
                             }
                         }">
                        <label class="text-[11px] font-bold text-gray-500 uppercase">Department:</label>
                        <div class="relative">
                            <input type="hidden" name="department" :value="deptVal">
                            <button type="button" 
                                    @click="open = !open" 
                                    class="inline-flex items-center justify-between gap-2.5 py-1.5 pl-3 pr-2.5 text-xs rounded-xl bg-white hover:bg-slate-50 border border-[#E5E7EB] hover:border-[#059669] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] font-medium text-gray-700 transition-all cursor-pointer shadow-2xs max-w-[220px]">
                                <span class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-building-columns text-[11px] text-gray-400"></i>
                                    <span class="truncate font-semibold text-slate-800" x-text="currentLabel"></span>
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
                                        @click="select('all')" 
                                        class="w-full flex items-center justify-between px-3 py-1.5 text-xs rounded-xl transition-all cursor-pointer text-left"
                                        :class="deptVal === 'all' ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-gray-700 hover:bg-slate-50 font-medium'">
                                    <span class="truncate">All Departments</span>
                                    <i x-show="deptVal === 'all'" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                                </button>
                                @foreach($departments as $dept)
                                    <button type="button" 
                                            @click="select('{{ addslashes($dept) }}')" 
                                            class="w-full flex items-center justify-between px-3 py-1.5 text-xs rounded-xl transition-all cursor-pointer text-left"
                                            :class="deptVal === '{{ addslashes($dept) }}' ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-gray-700 hover:bg-slate-50 font-medium'">
                                        <span class="truncate">{{ $dept }}</span>
                                        <i x-show="deptVal === '{{ addslashes($dept) }}'" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Year Filter Dropdown -->
                @if($years->isNotEmpty())
                    <div class="flex items-center gap-1.5"
                         x-data="{
                             open: false,
                             yearVal: '{{ $year }}',
                             get currentLabel() {
                                 return this.yearVal === 'all' ? 'All Years' : this.yearVal;
                             },
                             select(val) {
                                 this.yearVal = val;
                                 this.open = false;
                                 $nextTick(() => {
                                     $el.closest('form').submit();
                                 });
                             }
                         }">
                        <label class="text-[11px] font-bold text-gray-500 uppercase">Year:</label>
                        <div class="relative">
                            <input type="hidden" name="year" :value="yearVal">
                            <button type="button" 
                                    @click="open = !open" 
                                    class="inline-flex items-center justify-between gap-2.5 py-1.5 pl-3 pr-2.5 text-xs rounded-xl bg-white hover:bg-slate-50 border border-[#E5E7EB] hover:border-[#059669] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] font-medium text-gray-700 transition-all cursor-pointer shadow-2xs min-w-[105px]">
                                <span class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar text-[11px] text-gray-400"></i>
                                    <span class="font-semibold text-slate-800" x-text="currentLabel"></span>
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
                                 class="absolute left-0 top-full z-40 mt-1.5 w-36 max-h-48 overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-xl p-1.5 space-y-0.5"
                                 style="display: none;">
                                <button type="button" 
                                        @click="select('all')" 
                                        class="w-full flex items-center justify-between px-3 py-1.5 text-xs rounded-xl transition-all cursor-pointer text-left"
                                        :class="yearVal === 'all' ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-gray-700 hover:bg-slate-50 font-medium'">
                                    <span>All Years</span>
                                    <i x-show="yearVal === 'all'" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                                </button>
                                @foreach($years as $yr)
                                    <button type="button" 
                                            @click="select('{{ $yr }}')" 
                                            class="w-full flex items-center justify-between px-3 py-1.5 text-xs rounded-xl transition-all cursor-pointer text-left"
                                            :class="yearVal === '{{ $yr }}' ? 'bg-emerald-50 text-[#065F46] font-bold' : 'text-gray-700 hover:bg-slate-50 font-medium'">
                                        <span>{{ $yr }}</span>
                                        <i x-show="yearVal === '{{ $yr }}'" class="fa-solid fa-check text-[10px] text-[#059669]"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search candidate, ID, company..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-[#E5E7EB] rounded-xl focus:ring-2 focus:ring-[#059669] focus:outline-hidden">
                </div>

                <!-- Submit & Reset -->
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                    Apply Filter
                </button>
                @if($status !== 'all' || $department !== 'all' || $year !== 'all' || !empty($search))
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Reports Data Table -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
            @if ($applications->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 bg-[#F9FAFB] text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                <th class="py-3.5 px-6">Candidate / Student</th>
                                <th class="py-3.5 px-4">Academic Program</th>
                                <th class="py-3.5 px-4">Host Company</th>
                                <th class="py-3.5 px-4">Internship Role & Stipend</th>
                                <th class="py-3.5 px-4">Placement Status</th>
                                <th class="py-3.5 px-6 text-right">Applied Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($applications as $app)
                                @php
                                    $student = $app->studentProfile;
                                    $user = $student?->user;
                                    $post = $app->internshipPost;
                                    $company = $post?->companyProfile;
                                    $initials = strtoupper(substr($user?->name ?: 'ST', 0, 2));
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <!-- Candidate -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                {{ $initials }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-extrabold text-[#111827] text-sm">{{ $user?->name ?? 'N/A' }}</div>
                                                <span class="text-gray-500 text-xs block truncate">{{ $user?->email ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Academic Program -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-[#111827]">{{ $student?->student_id_number ?: 'ID Unassigned' }}</div>
                                        <span class="text-xs text-gray-600 block">{{ $student?->major ?: 'General' }}</span>
                                        <span class="text-[11px] text-gray-400 block">{{ $student?->department ?: 'N/A' }} • GPA: {{ $student?->gpa !== null ? number_format($student->gpa, 2) : 'N/A' }}</span>
                                    </td>

                                    <!-- Company -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-[#111827]">{{ $company?->company_name ?: 'Unnamed Company' }}</div>
                                        <span class="text-gray-500 text-[11px] block">{{ $company?->industry ?: 'N/A' }}</span>
                                        <span class="text-gray-400 text-[10px] block">{{ $company?->location }}</span>
                                    </td>

                                    <!-- Role & Stipend -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-gray-900 text-xs line-clamp-1">{{ $post?->title }}</div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            @if($post?->category)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                                                    {{ $post->category }}
                                                </span>
                                            @endif
                                            <span class="text-[11px] font-bold text-[#059669]">
                                                {{ $post?->stipend ? '$' . number_format($post->stipend, 0) . '/mo' : 'Standard' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Placement Status -->
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$app->status" />
                                    </td>

                                    <!-- Applied Date -->
                                    <td class="py-4 px-6 text-right text-gray-500 text-[11px]">
                                        <div>{{ $app->applied_at ? $app->applied_at->format('M d, Y') : ($app->created_at ? $app->created_at->format('M d, Y') : 'N/A') }}</div>
                                        @if($app->reviewed_at)
                                            <span class="text-[10px] text-gray-400 block">Reviewed: {{ $app->reviewed_at->format('M d') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-16 text-center text-gray-400 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-file-invoice w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#111827]">No Placement Records Found</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">No candidate placement records match the current filter selection.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
