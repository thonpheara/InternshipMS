<x-layout>
    <div class="space-y-6">

        <!-- Page Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#111827]">User Management</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Manage and monitor registered student candidates and host company partner accounts.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-semibold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-user-plus w-4 h-4"></i>
                    <span>Add New User</span>
                </a>
            </div>
        </div>

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Accounts</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-users w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $counts['total'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Total registered users</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Student Interns</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $counts['students'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Enrolled university candidates</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Host Companies</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-building w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $counts['companies'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Registered employer partners</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Active Status</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-circle-check w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#059669]">{{ $counts['active'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Active verified accounts</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-4 shadow-xs space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4">
            
            <!-- Segmented Role Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <a href="{{ route('admin.users.index', ['role' => 'all', 'status' => $status, 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'all' ? 'bg-[#059669] text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    All Users
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'student', 'status' => $status, 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'student' ? 'bg-[#059669] text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    Students ({{ $counts['students'] }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'company', 'status' => $status, 'search' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'company' ? 'bg-[#059669] text-white shadow-xs' : 'bg-[#F3F4F6] text-gray-700 hover:bg-gray-200' }}">
                    Companies ({{ $counts['companies'] }})
                </a>
            </div>

            <!-- Search & Status Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="role" value="{{ $role }}">

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="py-2 pl-3 pr-8 text-xs rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] font-medium text-gray-700">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>

                <!-- Search Input -->
                <div class="relative min-w-[200px] sm:min-w-[240px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Search name, email, ID..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] text-[#111827] placeholder-gray-400">
                </div>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] transition-colors cursor-pointer">
                    Search
                </button>

                @if(!empty($search) || $status !== 'all' || $role !== 'all')
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Users Table Container -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
            @if ($users->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 bg-[#F9FAFB] text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                <th class="py-3.5 px-6">User / Account</th>
                                <th class="py-3.5 px-4">Role & Identity</th>
                                <th class="py-3.5 px-4">Profile Details</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Joined Date</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($users as $u)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <!-- User Column -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                {{ strtoupper(substr($u->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.users.show', $u) }}" class="font-extrabold text-[#111827] text-sm hover:text-[#059669] transition-colors line-clamp-1">
                                                    {{ $u->name }}
                                                </a>
                                                <span class="text-gray-500 text-xs block truncate">{{ $u->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role Column -->
                                    <td class="py-4 px-4">
                                        @if ($u->isStudent())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">
                                                <i class="fa-solid fa-graduation-cap w-3 h-3"></i>
                                                Student
                                            </span>
                                        @elseif ($u->isCompany())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <i class="fa-solid fa-building w-3 h-3"></i>
                                                Company
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ ucfirst($u->role) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Profile Specifics Column -->
                                    <td class="py-4 px-4 text-gray-600">
                                        @if ($u->isStudent() && $u->studentProfile)
                                            <div>
                                                <span class="font-semibold text-[#111827]">{{ $u->studentProfile->student_id_number ?: 'ID: Unassigned' }}</span>
                                                <span class="block text-[11px] text-gray-500">{{ $u->studentProfile->major ?: 'No major specified' }}</span>
                                                @if($u->studentProfile->gpa !== null)
                                                    <span class="block text-[10px] text-[#059669] font-bold">GPA: {{ number_format($u->studentProfile->gpa, 2) }}</span>
                                                @endif
                                            </div>
                                        @elseif ($u->isCompany() && $u->companyProfile)
                                            <div>
                                                <span class="font-semibold text-[#111827]">{{ $u->companyProfile->company_name }}</span>
                                                <span class="block text-[11px] text-gray-500">{{ $u->companyProfile->industry ?: 'General Industry' }}</span>
                                                <span class="block text-[10px] text-gray-400">{{ $u->companyProfile->location ?: 'Location N/A' }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">No extra profile</span>
                                        @endif
                                    </td>

                                    <!-- Status Column -->
                                    <td class="py-4 px-4">
                                        @if ($u->isActive())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#059669]"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Created Date -->
                                    <td class="py-4 px-4 text-gray-500 text-[11px]">
                                        {{ $u->created_at ? $u->created_at->format('M d, Y') : 'N/A' }}
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- View Profile -->
                                            <a href="{{ route('admin.users.show', $u) }}" 
                                               title="View Full Profile" 
                                               class="p-1.5 text-gray-500 hover:text-[#059669] hover:bg-[#D1FAE5]/60 rounded-lg transition-colors">
                                                <i class="fa-solid fa-eye w-4 h-4"></i>
                                            </a>

                                            <!-- Edit User -->
                                            <a href="{{ route('admin.users.edit', $u) }}" 
                                               title="Edit Account & Profile" 
                                               class="p-1.5 text-gray-500 hover:text-[#111827] hover:bg-gray-100 rounded-lg transition-colors">
                                                <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                            </a>

                                            <!-- Delete User (Protected against self) -->
                                            @if ($u->id !== Auth::id())
                                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to permanently delete the {{ $u->role }} account \'{{ $u->name }}\'? This will also delete their profile and records.');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            title="Delete User" 
                                                            class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                                        <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if ($users->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-[#F9FAFB]">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center text-gray-400 text-xs">
                    <div class="w-12 h-12 rounded-2xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-user-xmark w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#111827]">No Users Found</h3>
                    <p class="text-gray-500 mt-1">No user accounts matched the given search and filter criteria.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-semibold transition-colors">
                            Clear Filters
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-layout>
