<x-layout title="Placements & Supervisors — Internship Management System">
    <div class="space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Internship Placements & Faculty Allocation</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Assign academic supervisors and monitor formal university-employer internship contracts.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.placements.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('unassigned') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    All Placements
                </a>
                <a href="{{ route('admin.placements.index', ['unassigned' => 1]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('unassigned') ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    ⚠️ Unassigned Supervisors
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($placements->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Student Intern</th>
                                <th class="py-3.5 px-4">Host Company & Role</th>
                                <th class="py-3.5 px-4">Duration & Hours</th>
                                <th class="py-3.5 px-4">Assigned Supervisor</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($placements as $pl)
                                <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ openAssign: false }">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $pl->studentProfile->user->name }}</div>
                                        <span class="text-slate-600 font-mono text-[11px]">{{ $pl->studentProfile->student_id_number }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <div class="font-bold text-slate-900">{{ $pl->companyProfile->company_name }}</div>
                                        <span>{{ $pl->internshipPost->title }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <div>{{ \Carbon\Carbon::parse($pl->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($pl->end_date)->format('M d, Y') }}</div>
                                        <span class="font-bold text-emerald-600">{{ number_format($pl->totalHoursLogged(), 1) }} / {{ $pl->total_hours_required }}h ({{ $pl->completionPercentage() }}%)</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if ($pl->supervisor)
                                            <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100 text-xs inline-block">
                                                {{ $pl->supervisor->name }}
                                            </span>
                                        @else
                                            <span class="font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 text-[11px] inline-block">
                                                ⚠️ Needs Assignment
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$pl->status" />
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <button @click="openAssign = !openAssign" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 text-xs font-bold transition-all inline-flex items-center gap-1">
                                            <span>Manage</span>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- Assignment Drawer -->
                                        <div x-show="openAssign" x-transition class="mt-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-2">
                                            <form action="{{ route('admin.placements.update', $pl) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Assign Faculty Supervisor</label>
                                                    <select name="supervisor_id" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold">
                                                        <option value="">-- Select Faculty Supervisor --</option>
                                                        @foreach ($supervisors as $sup)
                                                            <option value="{{ $sup->id }}" {{ $pl->supervisor_id == $sup->id ? 'selected' : '' }}>
                                                                {{ $sup->name }} ({{ ucfirst($sup->role) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mt-2">
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Placement Status</label>
                                                    <select name="status" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold">
                                                        <option value="active" {{ $pl->status === 'active' ? 'selected' : '' }}>Active Agreement</option>
                                                        <option value="completed" {{ $pl->status === 'completed' ? 'selected' : '' }}>Completed / Passed</option>
                                                        <option value="terminated" {{ $pl->status === 'terminated' ? 'selected' : '' }}>Terminated Early</option>
                                                    </select>
                                                </div>

                                                <div class="mt-2">
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Completion Remarks</label>
                                                    <input type="text" name="completion_remarks" value="{{ $pl->completion_remarks }}" placeholder="e.g. Completed with honors; 480 hours verified" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                                </div>

                                                <div class="mt-3 flex justify-end gap-2">
                                                    <button type="button" @click="openAssign = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Cancel</button>
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $placements->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i data-lucide="folder" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No placements in this category</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">All active placements have assigned faculty coordinators.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
