<x-layout>
    <div class="space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Internship Posting Moderation</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Ensure all host company opportunities align with university curriculum and labor standards.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.approvals.index', ['status' => 'pending_approval']) }}" 
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status', 'pending_approval') === 'pending_approval' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Pending Review
                </a>
                <a href="{{ route('admin.approvals.index', ['status' => 'approved']) }}" 
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Approved
                </a>
                <a href="{{ route('admin.approvals.index', ['status' => 'rejected']) }}" 
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Rejected
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($posts->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Post Details</th>
                                <th class="py-3.5 px-4">Company Organization</th>
                                <th class="py-3.5 px-4">Stipend</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6 text-right">Moderator Decision</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ openDecision: false }">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $post->title }}</div>
                                        <div class="text-slate-600 max-w-sm line-clamp-2 mt-0.5">{{ $post->description }}</div>
                                        <span class="text-[11px] text-slate-600 mt-1 block">Duration: {{ $post->duration_weeks }} weeks • Slots: {{ $post->slots }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <div class="font-bold text-slate-900">{{ $post->companyProfile->company_name }}</div>
                                        <span>{{ $post->companyProfile->industry }}</span>
                                        <span class="block text-[11px] text-slate-600">{{ $post->location }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        @if ($post->category)
                                            <span class="font-bold text-[10px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $post->category }}</span>
                                        @endif
                                        <span class="block font-bold text-emerald-600 mt-1">{{ $post->stipend ? '$' . number_format($post->stipend, 0) . '/mo' : 'Standard Stipend' }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$post->status" />
                                        @if ($post->rejection_reason)
                                            <span class="text-[10px] text-rose-600 block mt-1 italic">Reason: {{ $post->rejection_reason }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        @if ($post->status === 'pending_approval')
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.approvals.update', $post) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition-colors">
                                                        Approve
                                                    </button>
                                                </form>

                                                <button @click="openDecision = !openDecision" class="px-3 py-1.5 text-xs font-bold rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors">
                                                    Reject...
                                                </button>
                                            </div>

                                            <!-- Rejection Reason Drawer -->
                                            <div x-show="openDecision" x-transition class="mt-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-2">
                                                <form action="{{ route('admin.approvals.update', $post) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="rejected">
                                                    
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600">Rejection Reason for Employer</label>
                                                    <input type="text" name="rejection_reason" required placeholder="e.g. Requires more details on student mentoring and weekly deliverables" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500">
                                                    
                                                    <div class="mt-2 flex justify-end gap-2">
                                                        <button type="button" @click="openDecision = false" class="px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Cancel</button>
                                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg">Confirm Rejection</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-600">Moderated</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i class="fa-solid fa-inbox w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No posts under this filter</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">All postings in this status bucket have been processed.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
