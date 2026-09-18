<x-layout>
    <div class="min-h-screen flex items-center justify-center p-6 sm:p-12 bg-slate-50" x-data="{ role: '{{ old('role', 'student') }}' }">
        <div class="w-full max-w-xl space-y-6 py-8">
            
            <!-- Header -->
            <div class="text-center">
                <a href="/" class="inline-flex items-center gap-2.5 mb-6 group">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                        <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                    </div>
                    <span class="text-2xl font-black tracking-tight text-slate-900">Intern<span class="text-indigo-600">ship</span></span>
                </a>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Create your account</h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-600">Select your account type and enter your credentials to get started.</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span>Registration failed</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <form action="{{ route('register.submit') }}" method="POST" class="space-y-6 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                    @csrf

                    <!-- Role Selector Card Buttons (Default: Student) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Select Account Role *
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Student Role Card -->
                            <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                   :class="role === 'student' ? 'border-indigo-600 bg-indigo-50/60 ring-2 ring-indigo-600/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                <input type="radio" 
                                       name="role" 
                                       value="student" 
                                       x-model="role" 
                                       class="sr-only">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xl">🎓</span>
                                    <span x-show="role === 'student'" class="w-4 h-4 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px]">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                </div>
                                <span class="text-xs font-extrabold text-slate-900">Student Intern</span>
                                <span class="text-[10px] text-slate-600 mt-0.5">Degree candidate seeking internships</span>
                            </label>

                            <!-- Company Role Card -->
                            <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                   :class="role === 'company' ? 'border-emerald-600 bg-emerald-50/60 ring-2 ring-emerald-600/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                <input type="radio" 
                                       name="role" 
                                       value="company" 
                                       x-model="role" 
                                       class="sr-only">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xl">🏢</span>
                                    <span x-show="role === 'company'" class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                </div>
                                <span class="text-xs font-extrabold text-slate-900">Host Company</span>
                                <span class="text-[10px] text-slate-600 mt-0.5">Employer hiring student interns</span>
                            </label>
                        </div>
                    </div>

                    <!-- Common Basic Account Fields -->
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Full Name *
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       placeholder="e.g. Your name"
                                       class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Email Address *
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       placeholder="email@gmail.com"
                                       class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Password *
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       placeholder="Minimum 8 characters"
                                       class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Confirm Password *
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       placeholder="Re-enter password"
                                       class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2 hover:translate-y-[-1px]"
                            :class="role === 'student' ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/25' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/25'">
                        <span>Create Account</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>

                    <!-- Sign In Link -->
                    <p class="text-center text-xs text-slate-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">
                            Sign in to your portal
                        </a>
                    </p>
                </form>

            </div>
        </div>
</x-layout>
