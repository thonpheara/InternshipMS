<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F9FAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Internship Management System</title>
    <!-- Google Fonts: Plus Jakarta Sans & Kantumruy Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js for lightweight dropdowns/modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, button, input, optgroup, select, textarea {
            font-family: "Plus Jakarta Sans", "Kantumruy Pro", sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-[#111827] bg-[#F9FAFB] selection:bg-[#D1FAE5] selection:text-[#065F46]" x-data="{ sidebarOpen: false }">

    @auth
        <div class="min-h-screen flex bg-[#F9FAFB]">
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden" 
                 style="display: none;"></div>

            <!-- Sidebar Navigation -->
            @include('components.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 lg:pl-68">
                <!-- Top Navbar (Only visible on Dashboard pages) -->
                @if (request()->routeIs('*.dashboard') || request()->routeIs('dashboard'))
                    @include('components.topbar')
                @else
                    <!-- Minimal Mobile Toggle for non-dashboard pages -->
                    <div class="lg:hidden h-14 bg-[#F3F4F6]/95 backdrop-blur-md border-b border-[#E5E7EB] px-4 flex items-center justify-between sticky top-0 z-30">
                        <button @click="sidebarOpen = true" class="p-2 text-gray-600 hover:text-[#111827] rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-bars w-5 h-5"></i>
                        </button>
                        <span class="text-sm font-bold text-[#111827]">Intern<span class="text-[#059669]">ship</span></span>
                        <div class="w-8"></div>
                    </div>
                @endif

                <!-- Flash Notifications -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center justify-between p-4 rounded-2xl bg-[#D1FAE5] border border-emerald-300 text-emerald-950 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#059669] text-white font-bold flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-check w-4 h-4"></i>
                                </div>
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-emerald-800 hover:text-emerald-950 cursor-pointer">
                                <i class="fa-solid fa-xmark w-4 h-4"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center justify-between p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-exclamation w-4 h-4"></i>
                                </div>
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-rose-600 hover:text-rose-800 cursor-pointer">
                                <i class="fa-solid fa-xmark w-4 h-4"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation w-4 h-4"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-rose-900">Please check the submitted form:</h4>
                            </div>
                            <ul class="list-disc list-inside text-xs space-y-1 pl-11 text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Page Body Slot -->
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        <!-- Guest View Layout (e.g. Login) -->
        <main class="min-h-screen">
            {{ $slot }}
        </main>
    @endauth

</body>
</html>