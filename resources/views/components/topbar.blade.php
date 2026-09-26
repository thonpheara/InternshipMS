@php
    $user = Auth::user();
    $role = $user?->role ?? 'student';
    $notifications = $user?->notifications()->take(8)->get() ?? collect();
    $unreadCount = $user?->unreadNotifications()->count() ?? 0;
@endphp

<header class="h-16 bg-white/90 backdrop-blur-md border-b border-[#E5E7EB] sticky top-0 z-30 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <!-- Mobile menu toggle -->
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 hover:text-[#111827] rounded-lg hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-bars w-5 h-5"></i>
        </button>

        <div>
            <h1 class="text-base sm:text-lg font-bold text-[#111827] leading-tight">
                {{ $header ?? (request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'Dashboard' : '') }}
            </h1>
            <p class="text-xs text-gray-500 hidden sm:block">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- In-App Notification Bell -->
        <div class="relative" x-data="{ notifOpen: false }">
            <button @click="notifOpen = !notifOpen" 
                    type="button"
                    class="relative p-2 rounded-xl text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors cursor-pointer"
                    title="Notifications">
                <i class="fa-solid fa-bell w-4.5 h-4.5"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-rose-600 text-[9px] font-bold text-white">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    </span>
                @endif
            </button>

            <!-- Floating Notification Menu -->
            <div x-show="notifOpen" 
                 @click.outside="notifOpen = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                 style="display: none;">
                
                <!-- Notification Header -->
                <div class="p-3.5 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xs font-bold text-[#111827] uppercase tracking-wider">Notifications</h4>
                        @if($unreadCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200">
                                {{ $unreadCount }} new
                            </span>
                        @endif
                    </div>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[11px] font-semibold text-[#059669] hover:underline cursor-pointer">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Notifications List -->
                <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                    @forelse($notifications as $notif)
                        @php
                            $data = $notif->data;
                            $isUnread = is_null($notif->read_at);
                            $iconColor = match($data['color'] ?? 'emerald') {
                                'amber' => 'bg-amber-50 text-amber-600',
                                'rose' => 'bg-rose-50 text-rose-600',
                                'blue' => 'bg-blue-50 text-blue-600',
                                'indigo' => 'bg-indigo-50 text-indigo-600',
                                default => 'bg-emerald-50 text-[#059669]',
                            };
                        @endphp
                        <div class="p-3 hover:bg-gray-50/80 transition-colors {{ $isUnread ? 'bg-emerald-50/20' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl {{ $iconColor }} flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="{{ $data['icon'] ?? 'fa-solid fa-bell' }} text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <h5 class="text-xs font-bold text-[#111827] truncate {{ $isUnread ? 'font-black' : '' }}">
                                            {{ $data['title'] ?? 'Notification' }}
                                        </h5>
                                        <span class="text-[10px] text-gray-400 shrink-0">
                                            {{ $notif->created_at->diffForHumans(null, true, true) }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-600 mt-0.5 line-clamp-2 leading-relaxed">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                    <div class="flex items-center justify-between mt-2 pt-1">
                                        @if(!empty($data['action_url']))
                                            <a href="{{ route('notifications.read', $notif->id) }}" 
                                               class="text-[11px] font-bold text-[#059669] hover:underline inline-flex items-center gap-1">
                                                View details <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('notifications.read', $notif->id) }}" class="text-[11px] font-semibold text-gray-500 hover:text-gray-700">
                                                Mark as read
                                            </a>
                                        @endif

                                        <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-rose-500 p-0.5 transition-colors cursor-pointer" title="Delete">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-xs space-y-2">
                            <i class="fa-regular fa-bell-slash text-2xl text-gray-300"></i>
                            <p>No notifications yet</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer Clear All -->
                @if($notifications->isNotEmpty())
                    <div class="p-2.5 bg-gray-50/60 border-t border-gray-100 text-center">
                        <form action="{{ route('notifications.clearAll') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[11px] font-semibold text-gray-500 hover:text-rose-600 transition-colors cursor-pointer">
                                Clear all notifications
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Role Status Indicator Pill -->
        <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-[#F3F4F6] text-[#111827] text-xs font-medium border border-[#E5E7EB]">
            <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
            <span>Signed in as {{ ucfirst($role) }}</span>
        </div>

        <!-- Role Action Shortcut -->
        @if ($role === 'student')
            <a href="{{ route('student.posts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] shadow-xs transition-all">
                <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Find Internships</span>
            </a>
            <a href="{{ route('student.profile') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 text-xs font-semibold border border-slate-200/80 transition-all">
                <i class="fa-solid fa-user-graduate w-3.5 h-3.5 text-emerald-600"></i>
                <span class="hidden md:inline">Profile</span>
            </a>
        @elseif ($role === 'company')
            <a href="{{ route('company.posts.index', ['create' => 1]) }}" 
               @click="if (window.location.pathname.endsWith('/company/posts')) { $event.preventDefault(); window.dispatchEvent(new CustomEvent('open-create-modal')); }"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] shadow-xs transition-all cursor-pointer">
                <i class="fa-solid fa-plus w-3.5 h-3.5"></i>
                <span>Post Job</span>
            </a>
        @endif
    </div>
</header>
