<x-layout>
    <div class="space-y-6"
         x-data="{
             detailModalOpen: false,
             rejectModalOpen: false,
             activeCompany: {},
             rejectActionUrl: '',
             openDetailModal(comp) {
                 this.activeCompany = { ...comp };
                 this.detailModalOpen = true;
             },
             openRejectModal(comp, actionUrl) {
                 this.activeCompany = { ...comp };
                 this.rejectActionUrl = actionUrl;
                 this.rejectModalOpen = true;
             }
         }">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#111827]">Company Verification Queue</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Authenticate host company partner profiles before they publish vacancies to university students.</p>
            </div>

            <!-- Quick Action Counters -->
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
                    <i class="fa-solid fa-clock w-3.5 h-3.5 text-amber-600"></i>
                    {{ $counts['pending'] }} Pending Review
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                    <i class="fa-solid fa-circle-check w-3.5 h-3.5 text-[#059669]"></i>
                    {{ $counts['verified'] }} Verified
                </span>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-4 shadow-xs space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <a href="{{ route('admin.companies.index', ['status' => 'pending', 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    Pending Review ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('admin.companies.index', ['status' => 'verified', 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'verified' ? 'bg-[#059669] text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    Verified ({{ $counts['verified'] }})
                </a>
                <a href="{{ route('admin.companies.index', ['status' => 'rejected', 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    Rejected ({{ $counts['rejected'] }})
                </a>
                <a href="{{ route('admin.companies.index', ['status' => 'all', 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'all' ? 'bg-slate-800 text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    All Companies ({{ $counts['all'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.companies.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search company, contact..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-[#E5E7EB] rounded-xl focus:ring-2 focus:ring-[#059669] focus:outline-hidden">
                </div>
                <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                    Search
                </button>
                @if(!empty($search) || $status !== 'pending')
                    <a href="{{ route('admin.companies.index') }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Companies Table Container -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
            @if ($companies->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 bg-[#F9FAFB] text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                <th class="py-3.5 px-6">Company / Organization</th>
                                <th class="py-3.5 px-4">Contact Person</th>
                                <th class="py-3.5 px-4">Industry & Location</th>
                                <th class="py-3.5 px-4">Activity Stats</th>
                                <th class="py-3.5 px-4">Verification Status</th>
                                <th class="py-3.5 px-6 text-right">Moderator Decision</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($companies as $comp)
                                @php
                                    $hasLogo = $comp->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($comp->logo_path);
                                    $initials = strtoupper(substr($comp->company_name ?: ($comp->user?->name ?: 'CO'), 0, 2));
                                    $compData = [
                                        'id' => $comp->id,
                                        'company_name' => $comp->company_name ?: 'Unnamed Company',
                                        'user_name' => $comp->user?->name ?? 'N/A',
                                        'email' => $comp->user?->email ?? 'N/A',
                                        'industry' => $comp->industry ?: 'Not specified',
                                        'location' => $comp->location ?: 'Not specified',
                                        'address' => $comp->address ?: 'Not provided',
                                        'website' => $comp->website ?: '',
                                        'contact_person' => $comp->contact_person ?: 'Not provided',
                                        'contact_phone' => $comp->contact_phone ?: 'Not provided',
                                        'description' => $comp->description ?: 'No corporate description provided.',
                                        'verification_status' => $comp->verification_status,
                                        'rejection_reason' => $comp->rejection_reason,
                                        'posts_count' => $comp->internship_posts_count,
                                        'applications_count' => $comp->applications_count,
                                        'created_at_formatted' => $comp->created_at ? $comp->created_at->format('M d, Y') : 'N/A',
                                        'logo_url' => $hasLogo ? asset('storage/' . ltrim($comp->logo_path, '/')) : null,
                                        'updateUrl' => route('admin.companies.update', $comp),
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <!-- Company Column -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if($hasLogo)
                                                <img src="{{ asset('storage/' . ltrim($comp->logo_path, '/')) }}" 
                                                     alt="{{ $comp->company_name }}" 
                                                     class="w-10 h-10 rounded-xl object-cover shrink-0 border border-gray-200 shadow-xs cursor-pointer hover:opacity-90"
                                                     @click="openDetailModal(@js($compData))">
                                            @else
                                                <div @click="openDetailModal(@js($compData))" 
                                                     class="w-10 h-10 rounded-xl bg-teal-700 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs cursor-pointer hover:opacity-90">
                                                    {{ $initials }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <button type="button" 
                                                        @click="openDetailModal(@js($compData))" 
                                                        class="font-extrabold text-[#111827] text-sm hover:text-[#059669] transition-colors text-left line-clamp-1 cursor-pointer">
                                                    {{ $comp->company_name ?: ($comp->user?->name ?: 'Unnamed Company') }}
                                                </button>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="text-gray-500 text-xs truncate">{{ $comp->user?->email }}</span>
                                                    @if($comp->website)
                                                        <a href="{{ $comp->website }}" target="_blank" rel="noopener noreferrer" 
                                                           class="text-[10px] text-[#059669] hover:underline inline-flex items-center gap-1 font-semibold shrink-0">
                                                            <i class="fa-solid fa-arrow-up-right-from-square w-2.5 h-2.5"></i>
                                                            Website
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contact Person -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-[#111827]">{{ $comp->contact_person ?: 'Unassigned' }}</div>
                                        <span class="text-gray-500 text-[11px] block mt-0.5">
                                            <i class="fa-solid fa-phone w-3 h-3 mr-1 text-gray-400"></i>
                                            {{ $comp->contact_phone ?: 'No phone' }}
                                        </span>
                                    </td>

                                    <!-- Industry & Location -->
                                    <td class="py-4 px-4 text-gray-600">
                                        @if($comp->industry)
                                            <span class="font-bold text-[10px] px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 border border-teal-200">
                                                {{ $comp->industry }}
                                            </span>
                                        @endif
                                        <span class="block text-[11px] text-gray-500 mt-1">
                                            <i class="fa-solid fa-location-dot w-3 h-3 mr-1 text-gray-400"></i>
                                            {{ $comp->location ?: 'Location unlisted' }}
                                        </span>
                                    </td>

                                    <!-- Stats -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <span class="text-xs font-bold text-gray-800">{{ $comp->internship_posts_count }}</span>
                                        <span class="text-[11px] text-gray-500">posts</span>
                                        <span class="mx-1 text-gray-300">•</span>
                                        <span class="text-xs font-bold text-[#059669]">{{ $comp->applications_count }}</span>
                                        <span class="text-[11px] text-gray-500">applicants</span>
                                    </td>

                                    <!-- Status Column -->
                                    <td class="py-4 px-4">
                                        @if ($comp->verification_status === 'verified')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">
                                                <i class="fa-solid fa-circle-check w-3 h-3 text-[#059669]"></i>
                                                Verified Partner
                                            </span>
                                        @elseif ($comp->verification_status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-circle-xmark w-3 h-3 text-rose-600"></i>
                                                Rejected
                                            </span>
                                            @if ($comp->rejection_reason)
                                                <span class="text-[10px] text-rose-600 block mt-1 italic line-clamp-1" title="{{ $comp->rejection_reason }}">
                                                    {{ $comp->rejection_reason }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                <i class="fa-solid fa-clock w-3 h-3 text-amber-600"></i>
                                                Pending Review
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Inspect Profile Details Modal -->
                                            <button type="button" 
                                                    @click="openDetailModal(@js($compData))"
                                                    class="p-1.5 text-gray-500 hover:text-[#059669] hover:bg-[#D1FAE5]/60 rounded-lg transition-colors cursor-pointer"
                                                    title="View Full Organization Profile">
                                                <i class="fa-solid fa-eye w-4 h-4"></i>
                                            </button>

                                            @if ($comp->verification_status !== 'verified')
                                                <!-- Verify Button -->
                                                <form action="{{ route('admin.companies.update', $comp) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="verification_status" value="verified">
                                                    <button type="submit" 
                                                            class="px-3 py-1.5 text-xs font-bold rounded-xl bg-[#059669] hover:bg-[#047857] text-white shadow-xs transition-colors cursor-pointer inline-flex items-center gap-1">
                                                        <i class="fa-solid fa-check w-3 h-3"></i>
                                                        Verify
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($comp->verification_status !== 'rejected')
                                                <!-- Reject Trigger -->
                                                <button type="button" 
                                                        @click="openRejectModal(@js($compData), '{{ route('admin.companies.update', $comp) }}')"
                                                        class="px-2.5 py-1.5 text-xs font-bold rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors cursor-pointer inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-xmark w-3 h-3"></i>
                                                    Reject
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-16 text-center text-gray-400 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-building-circle-check w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#111827]">No Companies In This Filter</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">All host company partner registrations under this filter status have been processed.</p>
                </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- VIEW DETAILS MODAL                         -->
        <!-- ========================================== -->
        <div x-show="detailModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-xs" @click="detailModalOpen = false"></div>

                <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-gray-100 z-10 space-y-6">
                    <!-- Modal Header -->
                    <div class="flex items-start justify-between border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-3">
                            <template x-if="activeCompany.logo_url">
                                <img :src="activeCompany.logo_url" class="w-12 h-12 rounded-2xl object-cover border border-gray-200">
                            </template>
                            <template x-if="!activeCompany.logo_url">
                                <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white flex items-center justify-center font-bold text-sm">
                                    <span x-text="activeCompany.company_name ? activeCompany.company_name.substring(0, 2).toUpperCase() : 'CO'"></span>
                                </div>
                            </template>
                            <div>
                                <h3 class="text-lg font-black text-[#111827]" x-text="activeCompany.company_name"></h3>
                                <p class="text-xs text-gray-500" x-text="activeCompany.email"></p>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 cursor-pointer">
                            <i class="fa-solid fa-xmark w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Modal Body Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3 bg-gray-50 rounded-2xl space-y-1">
                            <span class="text-gray-400 uppercase font-bold text-[10px]">Contact Person</span>
                            <div class="font-bold text-[#111827]" x-text="activeCompany.contact_person"></div>
                            <div class="text-gray-500" x-text="activeCompany.contact_phone"></div>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-2xl space-y-1">
                            <span class="text-gray-400 uppercase font-bold text-[10px]">Industry & Location</span>
                            <div class="font-bold text-[#111827]" x-text="activeCompany.industry"></div>
                            <div class="text-gray-500" x-text="activeCompany.location"></div>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-2xl space-y-1 sm:col-span-2">
                            <span class="text-gray-400 uppercase font-bold text-[10px]">Office Address</span>
                            <div class="text-gray-700 font-medium" x-text="activeCompany.address"></div>
                        </div>

                        <template x-if="activeCompany.website">
                            <div class="p-3 bg-gray-50 rounded-2xl space-y-1 sm:col-span-2">
                                <span class="text-gray-400 uppercase font-bold text-[10px]">Website</span>
                                <div>
                                    <a :href="activeCompany.website" target="_blank" class="text-[#059669] hover:underline font-semibold flex items-center gap-1">
                                        <span x-text="activeCompany.website"></span>
                                        <i class="fa-solid fa-arrow-up-right-from-square w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        </template>

                        <div class="p-3 bg-gray-50 rounded-2xl space-y-1 sm:col-span-2">
                            <span class="text-gray-400 uppercase font-bold text-[10px]">Company Bio & Overview</span>
                            <p class="text-gray-700 leading-relaxed" x-text="activeCompany.description"></p>
                        </div>

                        <template x-if="activeCompany.rejection_reason">
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl space-y-1 sm:col-span-2">
                                <span class="text-rose-700 uppercase font-bold text-[10px]">Rejection Reason</span>
                                <p class="text-rose-900 font-medium" x-text="activeCompany.rejection_reason"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Modal Footer Actions -->
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                        <button type="button" @click="detailModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold text-xs hover:bg-gray-200 cursor-pointer">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- REJECTION REASON MODAL                     -->
        <!-- ========================================== -->
        <div x-show="rejectModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-xs" @click="rejectModalOpen = false"></div>

                <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100 z-10 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2 text-rose-600">
                            <i class="fa-solid fa-triangle-exclamation w-5 h-5"></i>
                            <h3 class="font-bold text-sm text-[#111827]">Reject Company Verification</h3>
                        </div>
                        <button type="button" @click="rejectModalOpen = false" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            <i class="fa-solid fa-xmark w-4 h-4"></i>
                        </button>
                    </div>

                    <p class="text-xs text-gray-500">
                        Please specify the reason why <strong class="text-gray-900" x-text="activeCompany.company_name"></strong> is not eligible for institutional partnership.
                    </p>

                    <form :action="rejectActionUrl" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="verification_status" value="rejected">

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Rejection Reason</label>
                            <textarea name="rejection_reason" rows="3" required 
                                      placeholder="e.g. Invalid corporate tax ID, incomplete contact details, or unrecognized company entity..."
                                      class="w-full p-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:bg-white focus:outline-hidden"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="rejectModalOpen = false" class="px-3.5 py-1.5 text-xs text-gray-600 hover:bg-gray-100 rounded-xl cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition-colors cursor-pointer">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layout>
