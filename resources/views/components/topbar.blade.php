@php
    $user = Auth::user();
    $role = $user?->role ?? 'student';
    $notifications = $user?->notifications()->take(8)->get() ?? collect();
    $unreadCount = $user?->unreadNotifications()->count() ?? 0;
    $hasAvatar = $user?->has_avatar ?? false;
    $avatarUrl = $user?->avatar_url;
    $initials = $user?->initials ?? 'US';
    $displayName = $user?->display_name ?? 'User';
    $profileRoute = match($role) {
        'student' => route('student.profile'),
        'company' => route('company.profile'),
        'admin'   => route('admin.profile'),
        default   => null,
    };
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
                <span id="notif-badge-wrapper" class="{{ $unreadCount > 0 ? '' : 'hidden' }} absolute top-1.5 right-1.5 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span id="notif-badge-count" class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-rose-600 text-[9px] font-bold text-white">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                </span>
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
                        <span id="notif-header-badge" class="{{ $unreadCount > 0 ? '' : 'hidden' }} px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200">
                            <span id="notif-header-count">{{ $unreadCount }}</span> new
                        </span>
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
                <div id="notif-list-container" class="max-h-80 overflow-y-auto divide-y divide-gray-100">
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
                        <div class="notif-item p-3 hover:bg-gray-50/80 transition-colors {{ $isUnread ? 'bg-emerald-50/20' : '' }}">
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
                        <div id="notif-empty-state" class="py-8 text-center text-gray-400 text-xs space-y-2">
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

        <!-- Real-Time Floating Toast Notification Box -->
        <div id="realtime-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm pointer-events-none"></div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const userIdMeta = document.querySelector('meta[name="user-id"]');
                const userId = userIdMeta ? userIdMeta.content : null;

                if (!userId || !window.Echo) return;

                // Function to show toast alert
                window.showRealtimeToast = function(title, message, actionUrl, icon = 'fa-solid fa-bell', color = 'emerald') {
                    const container = document.getElementById('realtime-toast-container');
                    if (!container) return;

                    const toast = document.createElement('div');
                    toast.className = 'pointer-events-auto flex items-start gap-3 p-3.5 bg-white/95 backdrop-blur-md rounded-2xl border border-emerald-100 shadow-xl shadow-emerald-950/5 transform transition-all duration-300 translate-y-3 opacity-0';

                    const colorClass = color === 'rose' ? 'bg-rose-50 text-rose-600' : 
                                      color === 'blue' ? 'bg-blue-50 text-blue-600' :
                                      color === 'amber' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600';

                    toast.innerHTML = `
                        <div class="w-9 h-9 rounded-xl ${colorClass} flex items-center justify-center shrink-0 mt-0.5">
                            <i class="${icon} text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0 pr-1">
                            <div class="flex items-center justify-between gap-1">
                                <h6 class="text-xs font-extrabold text-slate-900 truncate">${escapeHtml(title)}</h6>
                                <span class="text-[10px] text-slate-400 font-semibold shrink-0">Just now</span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-0.5 line-clamp-2 leading-relaxed">${escapeHtml(message)}</p>
                            ${actionUrl ? `
                                <a href="${actionUrl}" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 hover:text-emerald-700 mt-2">
                                    <span>View details</span>
                                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                </a>
                            ` : ''}
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer shrink-0" onclick="this.closest('.pointer-events-auto').remove()">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    `;

                    container.appendChild(toast);

                    // Animate in
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-y-3', 'opacity-0');
                    });

                    // Auto dismiss after 6 seconds
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-x-3');
                        setTimeout(() => toast.remove(), 300);
                    }, 6000);
                };

                function escapeHtml(str) {
                    if (!str) return '';
                    return str.replace(/[&<>'"]/g, 
                        tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
                    );
                }

                // Listen for Real-Time In-App Notifications
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notif) => {
                        const badgeWrapper = document.getElementById('notif-badge-wrapper');
                        const badgeCount = document.getElementById('notif-badge-count');
                        const headerBadge = document.getElementById('notif-header-badge');
                        const headerCount = document.getElementById('notif-header-count');
                        const listContainer = document.getElementById('notif-list-container');
                        const emptyState = document.getElementById('notif-empty-state');

                        // Increment badge count
                        let currentCount = parseInt(headerCount?.textContent || '0', 10);
                        currentCount++;

                        if (badgeWrapper) badgeWrapper.classList.remove('hidden');
                        if (badgeCount) badgeCount.textContent = currentCount > 9 ? '9+' : currentCount;
                        if (headerBadge) headerBadge.classList.remove('hidden');
                        if (headerCount) headerCount.textContent = currentCount;

                        if (emptyState) emptyState.remove();

                        // Prepend new item to list
                        if (listContainer) {
                            const iconColor = notif.color === 'rose' ? 'bg-rose-50 text-rose-600' :
                                             notif.color === 'blue' ? 'bg-blue-50 text-blue-600' :
                                             notif.color === 'amber' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-[#059669]';

                            const item = document.createElement('div');
                            item.className = 'notif-item p-3 hover:bg-gray-50/80 transition-colors bg-emerald-50/30 border-l-3 border-emerald-500';
                            item.innerHTML = `
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl ${iconColor} flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="${escapeHtml(notif.icon || 'fa-solid fa-bell')} text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <h5 class="text-xs font-black text-[#111827] truncate">
                                                ${escapeHtml(notif.title || 'Notification')}
                                            </h5>
                                            <span class="text-[10px] text-gray-400 shrink-0 font-semibold">Just now</span>
                                        </div>
                                        <p class="text-[11px] text-gray-600 mt-0.5 line-clamp-2 leading-relaxed">
                                            ${escapeHtml(notif.message || '')}
                                        </p>
                                        <div class="flex items-center justify-between mt-2 pt-1">
                                            ${notif.action_url ? `
                                                <a href="${notif.action_url}" class="text-[11px] font-bold text-[#059669] hover:underline inline-flex items-center gap-1">
                                                    View details <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                                </a>
                                            ` : `<span class="text-[11px] text-gray-400">Mark as read</span>`}
                                        </div>
                                    </div>
                                </div>
                            `;
                            listContainer.prepend(item);
                        }

                        // Trigger toast notification
                        window.showRealtimeToast(
                            notif.title || 'New Notification',
                            notif.message || '',
                            notif.action_url,
                            notif.icon || 'fa-solid fa-bell',
                            notif.color || 'emerald'
                        );
                    });
            });
        </script>

        <!-- Role Action Shortcut -->
        @if ($role === 'student')
            <a href="{{ route('student.posts.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] shadow-xs transition-all">
                <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Find Internships</span>
            </a>
        @elseif ($role === 'company')
            <a href="{{ route('company.posts.index', ['create' => 1]) }}" 
               @click="if (window.location.pathname.endsWith('/company/posts')) { $event.preventDefault(); window.dispatchEvent(new CustomEvent('open-create-modal')); }"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#059669] text-white text-xs font-semibold hover:bg-[#047857] shadow-xs transition-all cursor-pointer">
                <i class="fa-solid fa-plus w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Post Job</span>
            </a>
        @endif

        <!-- User Profile Avatar & Dropdown Menu -->
        <div class="relative pl-1 sm:pl-2 border-l border-gray-200" x-data="{ userMenuOpen: false }">
            <button @click="userMenuOpen = !userMenuOpen" 
                    type="button" 
                    class="group flex items-center gap-2.5 p-1 sm:pr-2.5 rounded-2xl hover:bg-gray-100/80 transition-all cursor-pointer focus:outline-none"
                    title="{{ $displayName }} ({{ $user?->role_label }})">
                <div class="relative shrink-0">
                    @if($hasAvatar && $avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover ring-2 ring-emerald-500/20 shadow-xs group-hover:ring-emerald-500/40 transition-all">
                    @else
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#059669] text-white flex items-center justify-center font-bold text-xs shadow-xs group-hover:scale-105 transition-transform">
                            {{ $initials }}
                        </div>
                    @endif
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white absolute -bottom-0.5 -right-0.5" title="Online Active"></span>
                </div>
                <div class="hidden md:block text-left min-w-0 max-w-[130px] lg:max-w-[160px]">
                    <p class="text-xs font-bold text-[#111827] truncate leading-tight group-hover:text-emerald-700 transition-colors">{{ $displayName }}</p>
                    <p class="text-[10px] text-gray-500 font-medium truncate">{{ $user?->role_label ?? ucfirst($role) }}</p>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-gray-700 transition-transform duration-200 hidden sm:block" :class="userMenuOpen ? 'rotate-180' : ''"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userMenuOpen" 
                 x-cloak
                 @click.outside="userMenuOpen = false"
                 @keydown.escape.window="userMenuOpen = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                 class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                 style="display: none;">
                
                <!-- User Info Header -->
                <div class="p-4 bg-gradient-to-r from-emerald-50/60 to-slate-50/80 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            @if($hasAvatar && $avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="w-11 h-11 rounded-xl object-cover ring-2 ring-white shadow-xs">
                            @else
                                <div class="w-11 h-11 rounded-xl bg-[#059669] text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                    {{ $initials }}
                                </div>
                            @endif
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white absolute -bottom-0.5 -right-0.5"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-extrabold text-[#111827] truncate">{{ $displayName }}</h4>
                            <p class="text-[11px] text-gray-500 truncate">{{ $user?->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 text-[9px] font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                {{ $user?->role_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="p-2 space-y-0.5 text-xs">
                    @php
                        $dashboardRoute = match($role) {
                            'admin'   => route('admin.dashboard'),
                            'company' => route('company.dashboard'),
                            'student' => route('student.dashboard'),
                            default   => url('/'),
                        };
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                        <i class="fa-solid fa-house w-4 text-gray-400"></i>
                        <span>Dashboard</span>
                    </a>

                    @if($profileRoute)
                        <a href="{{ $profileRoute }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-user-gear w-4 text-gray-400"></i>
                            <span>Profile &amp; Settings</span>
                        </a>
                        <a href="{{ $profileRoute }}?tab=security" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-shield-halved w-4 text-gray-400"></i>
                            <span>Password &amp; Security</span>
                        </a>
                    @endif

                    @if($role === 'student')
                        <a href="{{ route('student.applications.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-file-circle-check w-4 text-gray-400"></i>
                            <span>My Applications</span>
                        </a>
                        <a href="{{ route('student.messages.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-comment-dots w-4 text-gray-400"></i>
                            <span>Direct Messages</span>
                        </a>
                    @elseif($role === 'company')
                        <a href="{{ route('company.applicants.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-users-viewfinder w-4 text-gray-400"></i>
                            <span>Applicant Pipeline</span>
                        </a>
                        <a href="{{ route('company.messages.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-comment-dots w-4 text-gray-400"></i>
                            <span>Candidate Chat</span>
                        </a>
                    @elseif($role === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-users-gear w-4 text-gray-400"></i>
                            <span>User Management</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:text-emerald-700 hover:bg-emerald-50 font-medium transition-colors">
                            <i class="fa-solid fa-chart-column w-4 text-gray-400"></i>
                            <span>Placement Reports</span>
                        </a>
                    @endif
                </div>

                <!-- Sign Out -->
                <div class="p-2 border-t border-gray-100 bg-gray-50/50">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors cursor-pointer text-left">
                            <i class="fa-solid fa-right-from-bracket w-4"></i>
                            <span>Sign out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
