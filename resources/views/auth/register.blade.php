<x-layout>
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-[#f0f2f5]" x-data="{ role: '{{ old('role', 'student') }}', showPass: false }">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8 sm:p-10">
            
            <!-- Top Brand & Header -->
            <div class="text-center space-y-1.5">
                <!-- Squircle Icon -->
                <div class="w-12 h-12 rounded-2xl bg-[#059669] flex items-center justify-center text-white mx-auto shadow-md shadow-[#059669]/20">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>

                <!-- Subtitle Pill / System Title -->
                <p class="pt-2 text-[11px] font-bold uppercase tracking-wider text-[#059669]">
                    INTERNSHIP MANAGEMENT SYSTEM
                </p>

                <!-- Page Heading -->
                <h1 class="text-2xl font-black tracking-tight text-slate-900">
                    Create Account
                </h1>

                <!-- Helper Text -->
                <p class="text-xs text-slate-500">
                    Register as a Student or a Company
                </p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="mt-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
                    <div class="flex items-center gap-2 font-bold text-rose-800">
                        <i class="fa-solid fa-circle-exclamation w-3.5 h-3.5 shrink-0"></i>
                        <span>Registration failed</span>
                    </div>
                    @foreach ($errors->all() as $error)
                        <p class="pl-5">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Register Form -->
            <form action="{{ route('register.submit') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <!-- Role Selector (Segmented Tabs) -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        REGISTER AS
                    </label>
                    <input type="hidden" name="role" :value="role">
                    <div class="bg-[#f0f2f5] p-1 rounded-xl grid grid-cols-2 gap-1 select-none">
                        <!-- Student Option -->
                        <button type="button" 
                                @click="role = 'student'" 
                                :class="role === 'student' ? 'bg-white text-[#059669] font-bold shadow-xs border border-slate-200/60' : 'text-slate-500 hover:text-slate-700 font-medium'"
                                class="flex items-center justify-center gap-2 py-2 px-4 rounded-lg text-xs transition-all cursor-pointer">
                            <span class="text-sm">🎓</span>
                            <span>Student</span>
                        </button>

                        <!-- Company Option -->
                        <button type="button" 
                                @click="role = 'company'" 
                                :class="role === 'company' ? 'bg-white text-[#059669] font-bold shadow-xs border border-slate-200/60' : 'text-slate-500 hover:text-slate-700 font-medium'"
                                class="flex items-center justify-center gap-2 py-2 px-4 rounded-lg text-xs transition-all cursor-pointer">
                            <span class="text-sm">🏢</span>
                            <span>Company</span>
                        </button>
                    </div>
                </div>

                <!-- 2-Column Input Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            FULL NAME
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Enter full name"
                               class="w-full px-4 py-2.5 sm:py-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#059669] focus:ring-2 focus:ring-[#059669]/20 text-slate-800 placeholder-slate-400 transition-all">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            EMAIL ADDRESS
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="Enter your email"
                               class="w-full px-4 py-2.5 sm:py-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#059669] focus:ring-2 focus:ring-[#059669]/20 text-slate-800 placeholder-slate-400 transition-all">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            PASSWORD
                        </label>
                        <div class="relative flex items-center">
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Create a password"
                                   class="w-full pl-4 pr-16 py-2.5 sm:py-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#059669] focus:ring-2 focus:ring-[#059669]/20 text-slate-800 placeholder-slate-400 transition-all">
                            <button type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute right-4 text-xs font-semibold text-slate-400 hover:text-slate-600 select-none cursor-pointer transition-colors" 
                                    x-text="showPass ? 'Hide' : 'Show'">
                                Show
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            CONFIRM PASSWORD
                        </label>
                        <input :type="showPass ? 'text' : 'password'" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               placeholder="Confirm password"
                               class="w-full px-4 py-2.5 sm:py-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#059669] focus:ring-2 focus:ring-[#059669]/20 text-slate-800 placeholder-slate-400 transition-all">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-3">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#059669]/20 transition-all cursor-pointer">
                        Create Account
                    </button>
                </div>

                <!-- Login Link -->
                <p class="pt-2 text-center text-xs text-slate-500">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-[#059669] hover:underline">
                        Login
                    </a>
                </p>
            </form>

        </div>
    </div>
</x-layout>
