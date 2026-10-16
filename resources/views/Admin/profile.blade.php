<x-layout>
    @php
        $initials = $user->initials;
        $hasAvatar = $user->has_avatar;
        $avatarUrl = $user->avatar_url;
    @endphp

    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ tab: '{{ $errors->has('current_password') || $errors->has('password') || request('tab') === 'security' ? 'security' : request('tab', 'all') }}' }">

        <!-- Top Header -->
        <div class="pb-2">
            <div class="flex items-center gap-2 sm:hidden mb-2">
                <button @click="sidebarOpen = true" class="p-1.5 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                    <i class="fa-solid fa-bars w-5 h-5"></i>
                </button>
                <span class="text-xs font-semibold text-slate-500">Internship Management System</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                Administrator Profile &amp; Settings
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Manage your university administrator account credentials, profile photo, and password security.
            </p>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Alerts -->
        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Please correct the errors below</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Navigation Tabs Bar -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200/70 overflow-x-auto text-xs">
            <div class="flex items-center gap-2 shrink-0">
                <button @click="tab = 'all'" 
                        :class="tab === 'all' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors cursor-pointer">
                    <i class="fa-solid fa-grip w-3.5 h-3.5"></i>
                    <span>All Details</span>
                </button>

                <button @click="tab = 'profile'" 
                        :class="tab === 'profile' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors cursor-pointer">
                    <i class="fa-solid fa-user-shield w-3.5 h-3.5"></i>
                    <span>Profile &amp; Avatar</span>
                </button>

                <button @click="tab = 'security'" 
                        :class="tab === 'security' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors cursor-pointer">
                    <i class="fa-solid fa-shield-halved w-3.5 h-3.5"></i>
                    <span>Security &amp; Password</span>
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Overview Summary Card -->
            <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-6 lg:self-start">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col items-center text-center space-y-4">
                    <!-- Squircle Avatar with Status Dot -->
                    <div class="relative group">
                        @if ($hasAvatar && $avatarUrl)
                            <img src="{{ $avatarUrl }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-24 h-24 rounded-2xl object-cover shadow-md shadow-[#059669]/20 border-2 border-white">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-slate-800 text-white text-3xl font-black flex items-center justify-center shadow-md shadow-slate-800/20">
                                {{ $initials }}
                            </div>
                        @endif
                        <span class="w-4 h-4 rounded-full bg-emerald-500 border-2 border-white absolute -bottom-1 -right-1" title="Online Active"></span>
                    </div>

                    <!-- User Name & Email -->
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">
                            {{ $user->name }}
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $user->email }}
                        </p>
                    </div>

                    <!-- Role Badge -->
                    <div class="flex items-center justify-center gap-2 pt-1">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                            <span>University Administrator</span>
                        </span>
                    </div>

                    <!-- Account Metadata -->
                    <div class="w-full border-t border-slate-100 pt-3 text-xs space-y-2.5 text-left">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Access Level</span>
                            <span class="font-bold text-emerald-600">Super Admin (Root)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Account Status</span>
                            <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Member Since</span>
                            <span class="font-medium text-slate-700">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Oct 2026' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Profile & Password Cards -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Card 1: Administrator Information & Avatar Upload -->
                <div x-show="tab === 'all' || tab === 'profile'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <div class="flex items-start gap-3.5 pb-2">
                        <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-shield w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Administrator Information &amp; Avatar</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Update your official university name, contact email, and administrator profile picture.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Photo Row -->
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <div class="relative shrink-0">
                                @if ($hasAvatar && $avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-xs">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-[#059669] text-white font-black text-xl flex items-center justify-center shadow-xs">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="block text-xs font-bold text-slate-900">Profile Picture / Avatar</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5">Upload a clean square photo (PNG, JPG, WEBP, max 2MB).</span>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <input type="file" 
                                           name="avatar" 
                                           accept="image/*"
                                           class="block w-full sm:w-auto text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-[#059669] file:text-white hover:file:bg-[#047857] cursor-pointer">
                                    @if($hasAvatar)
                                        <button type="submit" 
                                                name="delete_avatar" 
                                                value="1" 
                                                onclick="return confirm('Remove your profile picture?');"
                                                class="px-2.5 py-1.5 text-[11px] font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-200 transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-user w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Official University Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-envelope w-4 h-4"></i>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px] cursor-pointer">
                                <i class="fa-solid fa-bookmark w-3.5 h-3.5"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card 2: Security & Password Update (Matching Screenshot) -->
                <div x-show="tab === 'all' || tab === 'security'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    <div class="flex items-start gap-3.5 pb-2">
                        <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-shield-halved w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Change Account Password</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ensure your administrator account remains protected by updating your password regularly.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 max-w-xl">
                            <!-- Current Password -->
                            <div>
                                <label for="admin_current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Current Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-lock w-4 h-4"></i>
                                    </div>
                                    <input :type="showCurrent ? 'text' : 'password'" 
                                           id="admin_current_password" 
                                           name="current_password" 
                                           required
                                           placeholder="Enter your current password"
                                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-300 ring-rose-200' : 'border-slate-200' }} bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                    <button type="button" 
                                            @click="showCurrent = !showCurrent" 
                                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                                        <i class="fa-solid text-xs" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-[11px] text-rose-600 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="admin_password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    New Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-key w-4 h-4"></i>
                                    </div>
                                    <input :type="showNew ? 'text' : 'password'" 
                                           id="admin_password" 
                                           name="password" 
                                           required
                                           minlength="8"
                                           placeholder="Minimum 8 characters"
                                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 ring-rose-200' : 'border-slate-200' }} bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                    <button type="button" 
                                            @click="showNew = !showNew" 
                                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                                        <i class="fa-solid text-xs" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-[11px] text-rose-600 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="admin_password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Confirm New Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-circle-check w-4 h-4"></i>
                                    </div>
                                    <input :type="showConfirm ? 'text' : 'password'" 
                                           id="admin_password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           placeholder="Re-type your new password"
                                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                    <button type="button" 
                                            @click="showConfirm = !showConfirm" 
                                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                                        <i class="fa-solid text-xs" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px] cursor-pointer">
                                <i class="fa-solid fa-lock w-3.5 h-3.5"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>
</x-layout>
