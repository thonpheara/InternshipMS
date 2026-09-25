<x-layout>
    @php
        $companyName = $company->company_name ?: ($user->name ?: 'Company');
        $nameParts = array_filter(explode(' ', trim($companyName)));
        $initials = count($nameParts) >= 2 
            ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
            : strtoupper(substr($companyName, 0, 2));
    @endphp

    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ tab: 'all' }">

        <!-- Top Header -->
        <div class="pb-2">
            <div class="flex items-center gap-2 sm:hidden mb-2">
                <button @click="sidebarOpen = true" class="p-1.5 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                    <i class="fa-solid fa-bars w-5 h-5"></i>
                </button>
                <span class="text-xs font-semibold text-slate-500">Internship Management System</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                Host Company Profile
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Manage your organization credentials, hiring contacts, and branding displayed to student applicants.
            </p>
        </div>

        <!-- Sub-navigation Tabs Bar (Matching Photo) -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200/70 overflow-x-auto text-xs">
            <div class="flex items-center gap-2 shrink-0">
                <!-- All Details Tab -->
                <button @click="tab = 'all'" 
                        :class="tab === 'all' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-grip w-3.5 h-3.5"></i>
                    <span>All Details</span>
                </button>

                <!-- Company Info Tab -->
                <button @click="tab = 'company'" 
                        :class="tab === 'company' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-building w-3.5 h-3.5"></i>
                    <span>Company Info</span>
                </button>

                <!-- Hiring Contact Tab -->
                <button @click="tab = 'contact'" 
                        :class="tab === 'contact' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-user-check w-3.5 h-3.5"></i>
                    <span>Hiring Contact</span>
                </button>

                <!-- Online Presence Tab -->
                <button @click="tab = 'online'" 
                        :class="tab === 'online' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-globe w-3.5 h-3.5"></i>
                    <span>Corporate Presence</span>
                </button>
            </div>

            <!-- Auto-saved session indicator -->
            <div class="hidden sm:flex items-center gap-1.5 text-slate-400 text-xs font-normal shrink-0">
                <i class="fa-solid fa-circle-info w-3.5 h-3.5"></i>
                <span>Auto-saved session</span>
            </div>
        </div>

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

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Company Overview & Quick Info (4 Cols - Sticky on scroll) -->
            <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-6 lg:self-start">
                
                <!-- Company Overview Card (Matching Photo) -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col items-center text-center space-y-4">
                    <!-- Squircle Logo/Avatar with Green Online Dot -->
                    <div class="relative group">
                        @if ($company->logo_path)
                            <img src="{{ asset('storage/' . ltrim($company->logo_path, '/')) }}" 
                                 alt="{{ $company->company_name }}" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 class="w-20 h-20 rounded-2xl object-cover shadow-md shadow-[#059669]/20 border-2 border-white">
                            <div style="display: none;" class="w-20 h-20 rounded-2xl bg-[#059669] text-white text-2xl font-black items-center justify-center shadow-md shadow-[#059669]/20">
                                {{ $initials }}
                            </div>
                        @else
                            <div class="w-20 h-20 rounded-2xl bg-[#059669] text-white text-2xl font-black flex items-center justify-center shadow-md shadow-[#059669]/20">
                                {{ $initials }}
                            </div>
                        @endif
                        <span class="w-4 h-4 rounded-full bg-emerald-500 border-2 border-white absolute -bottom-1 -right-1"></span>
                    </div>

                    <!-- Company Name & Email -->
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">
                            {{ $company->company_name ?: 'Your Company' }}
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $company->industry ?: 'Sector not specified' }}
                        </p>
                    </div>

                    <!-- Role & Status Badges -->
                    <div class="flex items-center justify-center gap-2 pt-1">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0] flex items-center gap-1">
                            <i class="fa-solid fa-building w-3 h-3"></i>
                            <span>Company Portal</span>
                        </span>
                        <x-status-badge :status="$company->verification_status ?? 'verified'" />
                    </div>

                    <!-- Account Overview Section -->
                    <div class="w-full border-t border-slate-100 pt-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 text-left w-full block mb-3">
                            Company Overview
                        </span>

                        <div class="space-y-3 text-xs w-full">
                            <!-- Location -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-location-dot w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Location</span>
                                </div>
                                <span class="font-medium text-slate-700 truncate max-w-[140px]">{{ $company->location ?: 'Not specified' }}</span>
                            </div>

                            <!-- Contact Person -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-user w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Hiring Lead</span>
                                </div>
                                <span class="font-medium text-slate-700 truncate max-w-[130px]">{{ $company->contact_person ?: 'Not specified' }}</span>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-phone w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Phone</span>
                                </div>
                                <span class="font-medium text-slate-700">{{ $company->contact_phone ?: 'Not specified' }}</span>
                            </div>

                            <!-- Website -->
                            @if ($company->website)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <i class="fa-solid fa-globe w-3.5 h-3.5 text-slate-400"></i>
                                        <span>Website</span>
                                    </div>
                                    <a href="{{ $company->website }}" target="_blank" class="font-semibold text-[#059669] hover:underline flex items-center gap-1 text-[11px]">
                                        <span>Visit</span>
                                        <i class="fa-solid fa-up-right-from-square w-3 h-3"></i>
                                    </a>
                                </div>
                            @endif

                            <!-- Verification -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-shield-halved w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Status</span>
                                </div>
                                <span class="font-semibold text-emerald-600 flex items-center gap-1">
                                    <i class="fa-solid fa-check w-3 h-3"></i> Verified
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recruiter Tip Box (Matching Photo) -->
                <div class="bg-[#D1FAE5]/60 border border-[#A7F3D0] rounded-2xl p-4.5 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-lg bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-lightbulb w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-emerald-900">Recruiter Tip</h4>
                        <p class="text-xs text-emerald-800/80 leading-relaxed mt-1">
                            Complete organization details and verified contact info boost candidate application rates by over 40%.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Right Column: Forms Stack (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Card 1: Company Profile Information -->
                    <div x-show="tab === 'all' || tab === 'company'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                        <!-- Card Header -->
                        <div class="flex items-start gap-3.5 pb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-building w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Company Information</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Primary organization name, industry sector, city location, and corporate logo.
                                </p>
                            </div>
                        </div>

                        <!-- Logo Upload Row -->
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <div class="relative shrink-0">
                                @if ($company->logo_path)
                                    <img src="{{ asset('storage/' . ltrim($company->logo_path, '/')) }}" 
                                         alt="{{ $company->company_name }}" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                         class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-xs">
                                    <div style="display: none;" class="w-16 h-16 rounded-2xl bg-[#059669] text-white font-black text-xl items-center justify-center shadow-xs">
                                        {{ $initials }}
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-[#059669] text-white font-black text-xl flex items-center justify-center shadow-xs">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="block text-xs font-bold text-slate-900">Organization Logo</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5">Upload a square image (PNG, JPG, WEBP, SVG, max 2MB).</span>
                                <div class="mt-2">
                                    <input type="file" 
                                           name="logo" 
                                           accept="image/*"
                                           class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-[#059669] file:text-white hover:file:bg-[#047857] cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <!-- Form Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Company Name -->
                            <div>
                                <label for="company_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Company / Organization Name <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-building w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="company_name" 
                                           name="company_name" 
                                           value="{{ old('company_name', $company->company_name) }}" 
                                           required
                                           placeholder="e.g. Acme Innovations Inc."
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Industry -->
                            <div>
                                <label for="industry" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Industry Sector <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-briefcase w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="industry" 
                                           name="industry" 
                                           value="{{ old('industry', $company->industry ?? 'Software & Cloud Engineering') }}" 
                                           placeholder="e.g. Software & Cloud Engineering, Fintech"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- City / Location -->
                            <div class="sm:col-span-2">
                                <label for="location" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    City / Location <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-location-dot w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location', $company->location) }}" 
                                           placeholder="e.g. Phnom Penh, Cambodia"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px]">
                                <i class="fa-solid fa-bookmark w-4 h-4 stroke-[2.2]"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 2: Hiring Contact & Recruitment Details -->
                    <div x-show="tab === 'all' || tab === 'contact'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                        <!-- Card Header -->
                        <div class="flex items-start gap-3.5 pb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-user-check w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Hiring Contact Person</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Contact point for student applicants and university administrator correspondence.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Contact Person -->
                            <div>
                                <label for="contact_person" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Hiring Contact Person <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-user w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="contact_person" 
                                           name="contact_person" 
                                           value="{{ old('contact_person', $company->contact_person) }}" 
                                           placeholder="e.g. Sarah Jenkins (Talent Lead)"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Contact Phone Number <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-phone w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone', $company->contact_phone) }}" 
                                           placeholder="+855 12 345 678"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Account Email (Read-only) -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Account Login Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-envelope w-4 h-4"></i>
                                    </div>
                                    <input type="email" value="{{ $user->email }}" disabled class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200/80 bg-slate-50 text-xs text-slate-500 font-medium cursor-not-allowed">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px]">
                                <i class="fa-solid fa-bookmark w-4 h-4 stroke-[2.2]"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 3: Corporate Presence & Description -->
                    <div x-show="tab === 'all' || tab === 'online'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                        <!-- Card Header -->
                        <div class="flex items-start gap-3.5 pb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-globe w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Corporate Presence &amp; Description</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Official company website, headquarters street address, and company culture overview.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Website URL -->
                            <div>
                                <label for="website" class="block text-xs font-semibold text-slate-700 mb-1.5">Official Website URL</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-globe w-4 h-4"></i>
                                    </div>
                                    <input type="url" 
                                           id="website" 
                                           name="website" 
                                           value="{{ old('website', $company->website) }}" 
                                           placeholder="https://company.com"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">Headquarters / Street Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-location-dot w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="address" 
                                           name="address" 
                                           value="{{ old('address', $company->address) }}" 
                                           placeholder="e.g. Building 12, Preah Monivong Blvd, Phnom Penh"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- About & Work Culture -->
                            <div>
                                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">About Company &amp; Work Culture</label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Briefly describe what your organization does, your team culture, and what interns will learn..."
                                          class="w-full p-3 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">{{ old('description', $company->description) }}</textarea>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px]">
                                <i class="fa-solid fa-bookmark w-4 h-4 stroke-[2.2]"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-layout>
