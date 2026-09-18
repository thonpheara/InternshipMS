<x-layout title="Student Eligibility — Internship Management System">
    <div class="space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Student Cohort Eligibility Management</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Control internship readiness based on academic prerequisites and credit completion.</p>
            </div>

            <!-- Search and Filter -->
            <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search name, ID, or major..." 
                       class="py-2 px-3 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500">

                <select name="status" onchange="this.form.submit()" class="py-2 pl-3 pr-8 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 font-semibold text-slate-700">
                    <option value="">All Eligibility States</option>
                    <option value="eligible" {{ request('status') === 'eligible' ? 'selected' : '' }}>Eligible</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="ineligible" {{ request('status') === 'ineligible' ? 'selected' : '' }}>Ineligible</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($students->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Student Information</th>
                                <th class="py-3.5 px-4">Academic Details</th>
                                <th class="py-3.5 px-4">GPA</th>
                                <th class="py-3.5 px-4">Current Eligibility</th>
                                <th class="py-3.5 px-6">Coordinator Notes</th>
                                <th class="py-3.5 px-6 text-right">Update Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($students as $student)
                                <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ openEdit: false }">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $student->user->name }}</div>
                                        <div class="text-slate-600">{{ $student->user->email }}</div>
                                        <span class="text-[11px] text-slate-600 mt-0.5 block font-mono">{{ $student->student_id_number }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <div class="font-bold text-slate-900">{{ $student->major }}</div>
                                        <span>Cohort {{ $student->cohort_year }}</span>
                                    </td>
                                    <td class="py-4 px-4 font-black text-slate-900">
                                        {{ $student->gpa ? number_format($student->gpa, 2) : 'N/A' }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$student->eligibility_status" />
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 max-w-xs">
                                        {{ $student->eligibility_notes ?: 'No special notes recorded' }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <button @click="openEdit = !openEdit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-amber-600 hover:text-white text-slate-700 text-xs font-bold transition-all inline-flex items-center gap-1">
                                            <span>Edit</span>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- Eligibility form drawer -->
                                        <div x-show="openEdit" x-transition class="mt-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-2">
                                            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Set Eligibility Status</label>
                                                    <select name="eligibility_status" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 font-semibold">
                                                        <option value="eligible" {{ $student->eligibility_status === 'eligible' ? 'selected' : '' }}>✅ Eligible for Placement</option>
                                                        <option value="pending" {{ $student->eligibility_status === 'pending' ? 'selected' : '' }}>⏳ Pending Verification</option>
                                                        <option value="ineligible" {{ $student->eligibility_status === 'ineligible' ? 'selected' : '' }}>❌ Ineligible (Requires Prerequisites)</option>
                                                    </select>
                                                </div>

                                                <div class="mt-2">
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Coordinator Advisory Note</label>
                                                    <input type="text" name="eligibility_notes" value="{{ $student->eligibility_notes }}" placeholder="e.g. Cleared 60 credits, prerequisites verified" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                                                </div>

                                                <div class="mt-3 flex justify-end gap-2">
                                                    <button type="button" @click="openEdit = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Cancel</button>
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-xs">Save Status</button>
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
                    {{ $students->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i data-lucide="user-x" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No students found</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Try refining your search parameters or eligibility filter.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
