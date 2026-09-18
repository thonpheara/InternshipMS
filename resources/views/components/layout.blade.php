<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Internship Management System</title>
    <!-- Google Fonts: Kantumruy Pro (Khmer & English) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js for lightweight dropdowns/modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, button, input, optgroup, select, textarea {
            font-family: "Kantumruy Pro", sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">

    @auth
        <div class="min-h-screen flex bg-slate-50">
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden" 
                 style="display: none;"></div>

            <!-- Sidebar Navigation -->
            @include('components.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 lg:pl-68">
                <!-- Top Navbar -->
                @include('components.topbar')

                <!-- Flash Notifications -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-5 h-5"></i>
                                </div>
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                </div>
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-rose-600 hover:text-rose-800">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-sm font-semibold">Please check the submitted form:</h4>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>