<x-layout title="Company Profile — Internship Management System">
    <div class="space-y-4">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
            <div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">Host Company Profile & Settings</h2>
                <p class="text-xs text-slate-600">Manage your organization credentials, hiring contacts, and branding displayed to student applicants.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Please correct the errors below</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Left Column: Branding & Quick Stats (col-span-4) -->
                <div class="lg:col-span-4 space-y-4">
                    <!-- Company Overview Card -->
                    <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i data-lucide="building" class="w-4 h-4 text-emerald-600"></i>
                                Partner Status
                            </h3>
                            <x-status-badge :status="$company->verification_status ?? 'verified'" />
                        </div>

                        <!-- Brand Logo Display -->
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            @if ($company->logo_path)
                                <img src="/storage/{{ $company->logo_path }}" alt="{{ $company->company_name }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-200 mb-2 shadow-xs">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-emerald-600/10 text-emerald-600 flex items-center justify-center font-black text-2xl mb-2 border border-emerald-500/20">
                                    {{ strtoupper(substr($company->company_name ?? 'CO', 0, 2)) }}
                                </div>
                            @endif
                            <h4 class="font-bold text-sm text-slate-900 truncate max-w-full">{{ $company->company_name ?: 'Your Company' }}</h4>
                            <span class="text-[11px] text-slate-500">{{ $company->industry ?: 'Sector not specified' }}</span>
                        </div>

                        <!-- Quick Meta -->
                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-500">Location</span>
                                <span class="font-bold text-slate-900">{{ $company->location ?: 'Not specified' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-500">Contact Person</span>
                                <span class="font-bold text-slate-900">{{ $company->contact_person ?: 'Not specified' }}</span>
                            </div>
                            @if($company->website)
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-[10px] uppercase font-bold text-slate-500">Website</span>
                                    <a href="{{ $company->website }}" target="_blank" class="font-bold text-emerald-600 hover:underline flex items-center gap-1 text-[11px]">
                                        <span>Visit Link</span>
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Logo Upload Input -->
                        <div class="pt-2 border-t border-slate-100">
                            <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">
                                {{ $company->logo_path ? 'Change Company Logo (PNG/JPG/SVG, max 2MB)' : 'Upload Company Logo (PNG/JPG/SVG, max 2MB)' }}
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   accept="image/*"
                                   class="block w-full text-xs text-slate-600 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Right Column: Profile Form (col-span-8) -->
                <div class="lg:col-span-8 p-4 sm:p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-6 flex flex-col justify-between">
                    <div class="space-y-6">
                        
                        <!-- Section 1: Company Profile Information (matches screenshot) -->
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-600 uppercase tracking-wider pb-2.5 border-b border-slate-100 mb-3.5">
                                <i data-lucide="building" class="w-4 h-4"></i>
                                <span>Company Profile Information</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="company_name" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Company / Organization Name *</label>
                                    <input type="text" 
                                           id="company_name" 
                                           name="company_name" 
                                           value="{{ old('company_name', $company->company_name) }}" 
                                           required
                                           placeholder="e.g. Acme Innovations Inc."
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="industry" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Industry Sector *</label>
                                    <input type="text" 
                                           id="industry" 
                                           name="industry" 
                                           value="{{ old('industry', $company->industry ?? 'Software & Cloud Engineering') }}" 
                                           placeholder="e.g. Software & Cloud Engineering, Fintech"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="location" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">City / Location *</label>
                                    <input type="text" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location', $company->location) }}" 
                                           placeholder="e.g. Austin, TX"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="contact_person" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Hiring Contact Person *</label>
                                    <input type="text" 
                                           id="contact_person" 
                                           name="contact_person" 
                                           value="{{ old('contact_person', $company->contact_person) }}" 
                                           placeholder="e.g. Sarah Jenkins (Talent Lead)"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="contact_phone" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Contact Phone Number *</label>
                                    <input type="text" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone', $company->contact_phone) }}" 
                                           placeholder="+1 (555) 019-2834"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Online Presence & Headquarters -->
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900 uppercase tracking-wider pb-2.5 border-b border-slate-100 mb-3.5">
                                <i data-lucide="globe" class="w-4 h-4 text-emerald-600"></i>
                                <span>Corporate Presence & Description</span>
                            </div>

                            <div class="space-y-3.5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div>
                                        <label for="website" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Website URL</label>
                                        <input type="url" 
                                               id="website" 
                                               name="website" 
                                               value="{{ old('website', $company->website) }}" 
                                               placeholder="https://company.com"
                                               class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                    </div>

                                    <div>
                                        <label for="user_email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Account Login Email</label>
                                        <input type="email" id="user_email" value="{{ $user->email }}" disabled class="w-full p-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                                    </div>
                                </div>

                                <div>
                                    <label for="address" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Headquarters / Street Address</label>
                                    <input type="text" 
                                           id="address" 
                                           name="address" 
                                           value="{{ old('address', $company->address) }}" 
                                           placeholder="Suite 400, 100 Innovation Way"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="description" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">About Company & Work Culture</label>
                                    <textarea id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Briefly describe what your organization does, your team culture, and what interns will learn..."
                                              class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">{{ old('description', $company->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100 mt-4">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2 cursor-pointer hover:translate-y-[-1px]">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Save Company Profile</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</x-layout>
