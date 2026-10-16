<x-layout>
    <div class="flex flex-col h-[calc(100vh-6rem)] sm:h-[calc(100vh-7rem)] lg:h-[calc(100vh-8rem)]">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 shrink-0">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Direct Candidate Messaging</h1>
                <p class="text-xs text-slate-600 mt-1">Communicate directly with applicants, schedule interviews, and clarify requirements.</p>
            </div>
            <a href="{{ route('company.applicants.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition-colors shrink-0">
                <i class="fa-solid fa-users w-4 h-4"></i>
                <span>Applicant Pipeline</span>
            </a>
        </div>

        @if($conversations->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-12 text-center max-w-lg mx-auto my-auto">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-comment w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No active conversations</h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    You can start a conversation directly with candidates by clicking the "Message Candidate" button in your Applicant Pipeline.
                </p>
                <div class="mt-6">
                    <a href="{{ route('company.applicants.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all">
                        <i class="fa-solid fa-users w-4 h-4"></i>
                        <span>Go to Applicant Pipeline</span>
                    </a>
                </div>
            </div>
        @else
            <!-- 2-Column Chat Workspace -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 flex-1 min-h-0">
                
                <!-- Left Column: Conversations List -->
                <div class="lg:col-span-4 border-r border-slate-200/80 flex flex-col h-full min-h-0 bg-slate-50/50 overflow-hidden">
                    <div class="p-4 border-b border-slate-200/80 bg-white shrink-0">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Candidates</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[11px] border border-emerald-100">
                                {{ $conversations->count() }} active
                            </span>
                        </div>
                    </div>

                    <div id="conversations-list" class="flex-1 min-h-0 overflow-y-auto divide-y divide-slate-100">
                        @foreach($conversations as $convo)
                            @php
                                $isActive = $activeConversation && $activeConversation->id === $convo->id;
                                $unreadCount = $convo->unreadCountFor(Auth::user());
                                $studentUser = $convo->studentProfile->user;
                                $studentName = $studentUser?->name ?? 'Candidate';
                                $studentAvatar = $studentUser?->avatar_url;
                                $studentHasAvatar = $studentUser?->has_avatar;
                                $studentInitials = $studentUser?->initials ?? 'ST';
                            @endphp
                            <a href="{{ route('company.messages.index', ['conversation_id' => $convo->id]) }}" 
                               data-convo-id="{{ $convo->id }}"
                               class="convo-item block p-4 transition-colors {{ $isActive ? 'bg-emerald-50/80 border-l-4 border-emerald-600' : 'hover:bg-slate-100/70' }}">
                                <div class="flex items-start gap-3">
                                    @if($studentHasAvatar && $studentAvatar)
                                        <img src="{{ $studentAvatar }}" alt="{{ $studentName }}" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-slate-200 shadow-xs">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                            {{ $studentInitials }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-xs font-bold text-slate-900 truncate">{{ $studentName }}</h4>
                                            <span class="convo-time text-[10px] text-slate-600 shrink-0">
                                                {{ $convo->last_message_at ? $convo->last_message_at->diffForHumans(null, true, true) : '' }}
                                            </span>
                                        </div>
                                        @if($convo->application && $convo->application->internshipPost)
                                            <span class="inline-block text-[10px] font-semibold text-emerald-700 bg-emerald-100/60 px-1.5 py-0.5 rounded mb-1 truncate max-w-full">
                                                {{ $convo->application->internshipPost->title }}
                                            </span>
                                        @endif
                                        <p class="convo-preview text-xs text-slate-600 truncate">
                                            {{ $convo->latestMessage?->body ?? 'Start this conversation...' }}
                                        </p>
                                    </div>
                                    <span class="convo-unread w-5 h-5 rounded-full bg-emerald-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0 {{ $unreadCount > 0 ? '' : 'hidden' }}">
                                        {{ $unreadCount }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column: Active Chat Stream -->
                <div class="lg:col-span-8 flex flex-col h-full min-h-0 bg-white overflow-hidden">
                    @if($activeConversation)
                        @php
                            $candidate = $activeConversation->studentProfile->user;
                            $candidateAvatar = $candidate?->avatar_url;
                            $candidateHasAvatar = $candidate?->has_avatar;
                            $candidateInitials = $candidate?->initials ?? 'ST';
                        @endphp
                        <!-- Chat Header -->
                        <div class="p-4 border-b border-slate-200/80 flex items-center justify-between bg-white shrink-0">
                            <div class="flex items-center gap-3">
                                @if($candidateHasAvatar && $candidateAvatar)
                                    <img src="{{ $candidateAvatar }}" alt="{{ $candidate->name ?? 'Candidate' }}" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-slate-200 shadow-xs">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ $candidateInitials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-extrabold text-slate-900">{{ $candidate->name ?? 'Candidate' }}</h3>
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Real-time</span>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-600">
                                        <span>{{ $activeConversation->studentProfile->major ?? 'Student' }} ({{ $activeConversation->studentProfile->cohort_year ?? 'Candidate' }})</span>
                                        @if($activeConversation->application && $activeConversation->application->internshipPost)
                                            <span>•</span>
                                            <span class="font-medium text-emerald-600">{{ $activeConversation->application->internshipPost->title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($activeConversation->application)
                                <a href="{{ route('company.applicants.index', ['post_id' => $activeConversation->application->internship_post_id]) }}" class="text-[11px] font-bold text-emerald-600 hover:underline">
                                    Review Application
                                </a>
                            @endif
                        </div>

                        <!-- Chat Messages History -->
                        <div id="message-container" data-active-convo-id="{{ $activeConversation->id }}" class="flex-1 min-h-0 p-6 overflow-y-auto space-y-4 bg-slate-50/40">
                            @forelse($messages as $msg)
                                @php
                                    $isMe = $msg->sender_id === Auth::id();
                                @endphp
                                <div class="message-bubble flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[75%] space-y-1">
                                        <div class="flex items-center gap-2 {{ $isMe ? 'justify-end' : 'justify-start' }} text-[10px] text-slate-600">
                                            <span class="font-semibold">{{ $isMe ? 'You (Host Recruiter)' : $msg->sender->name }}</span>
                                            <span>{{ $msg->created_at->format('h:i A') }}</span>
                                            @if($isMe)
                                                <span>•</span>
                                                <span class="{{ $msg->is_read ? 'text-emerald-600 font-bold' : 'text-slate-600' }}">
                                                    {{ $msg->is_read ? 'Read' : 'Sent' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="p-3.5 rounded-2xl text-xs leading-relaxed {{ $isMe ? 'bg-emerald-600 text-white rounded-br-xs shadow-sm shadow-emerald-600/10' : 'bg-white border border-slate-200/90 text-slate-800 rounded-bl-xs shadow-xs' }}">
                                            {{ $msg->body }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="no-messages-placeholder" class="text-center py-16 text-slate-600 text-xs">
                                    <i class="fa-solid fa-comment-dots w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p>No messages yet in this candidate conversation.</p>
                                    <p class="text-[11px] text-slate-600 mt-1">Send a message below to schedule an interview or ask questions.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Chat Input Box -->
                        <div class="p-4 border-t border-slate-200/80 bg-white shrink-0">
                            <form id="chat-form" action="{{ route('company.messages.store', $activeConversation) }}" method="POST" class="flex items-center gap-2.5">
                                @csrf
                                <div class="flex-1">
                                    <input type="text" 
                                           id="chat-input"
                                           name="body" 
                                           required 
                                           autocomplete="off"
                                           placeholder="Type your message to {{ $candidate->name ?? 'the candidate' }}..."
                                           class="w-full h-12 px-4.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition-all text-slate-900 placeholder:text-slate-400">
                                </div>
                                <button type="submit" id="chat-submit-btn" class="h-12 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 shrink-0 hover:translate-y-[-1px] cursor-pointer">
                                    <span>Send</span>
                                    <i class="fa-solid fa-paper-plane w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center p-8 text-center text-slate-600 text-xs">
                            Select a candidate conversation on the left to start messaging.
                        </div>
                    @endif
                </div>

            </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('message-container');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatBtn = document.getElementById('chat-submit-btn');
            const activeConvoId = container?.dataset.activeConvoId;
            const userIdMeta = document.querySelector('meta[name="user-id"]');
            const currentUserId = userIdMeta ? parseInt(userIdMeta.content, 10) : null;

            // Auto scroll chat to bottom
            const scrollToBottom = () => {
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            };
            scrollToBottom();

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.innerText = text;
                return div.innerHTML;
            }

            function appendBubble({ isMe, senderName, time, body, isRead }) {
                if (!container) return;
                const placeholder = document.getElementById('no-messages-placeholder');
                if (placeholder) placeholder.remove();

                const bubble = document.createElement('div');
                bubble.className = `message-bubble flex ${isMe ? 'justify-end' : 'justify-start'} animate-fade-in`;
                bubble.innerHTML = `
                    <div class="max-w-[75%] space-y-1">
                        <div class="flex items-center gap-2 ${isMe ? 'justify-end' : 'justify-start'} text-[10px] text-slate-600">
                            <span class="font-semibold">${escapeHtml(senderName)}</span>
                            <span>${escapeHtml(time)}</span>
                            ${isMe ? `
                                <span>•</span>
                                <span class="${isRead ? 'text-emerald-600 font-bold' : 'text-slate-600'}">
                                    ${isRead ? 'Read' : 'Sent'}
                                </span>
                            ` : ''}
                        </div>
                        <div class="p-3.5 rounded-2xl text-xs leading-relaxed ${isMe ? 'bg-emerald-600 text-white rounded-br-xs shadow-sm shadow-emerald-600/10' : 'bg-white border border-slate-200/90 text-slate-800 rounded-bl-xs shadow-xs'}">
                            ${escapeHtml(body)}
                        </div>
                    </div>
                `;
                container.appendChild(bubble);
                scrollToBottom();
            }

            function updateConvoSidebar(convoId, messageBody, timeText, incrementUnread = false) {
                const convoItem = document.querySelector(`.convo-item[data-convo-id="${convoId}"]`);
                if (convoItem) {
                    const preview = convoItem.querySelector('.convo-preview');
                    const time = convoItem.querySelector('.convo-time');
                    const unreadBadge = convoItem.querySelector('.convo-unread');

                    if (preview) preview.textContent = messageBody;
                    if (time) time.textContent = timeText;

                    if (incrementUnread && unreadBadge) {
                        let count = parseInt(unreadBadge.textContent.trim() || '0', 10);
                        count++;
                        unreadBadge.textContent = count;
                        unreadBadge.classList.remove('hidden');
                    }

                    // Move to top of conversations list
                    const list = document.getElementById('conversations-list');
                    if (list && list.firstElementChild !== convoItem) {
                        list.prepend(convoItem);
                    }
                }
            }

            // AJAX instant submit
            if (chatForm && chatInput) {
                chatForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const text = chatInput.value.trim();
                    if (!text) return;

                    chatInput.value = '';
                    chatInput.focus();

                    // Pre-render local bubble
                    const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    appendBubble({
                        isMe: true,
                        senderName: 'You (Host Recruiter)',
                        time: nowTime,
                        body: text,
                        isRead: false
                    });

                    // Update sidebar immediately
                    if (activeConvoId) {
                        updateConvoSidebar(activeConvoId, text, 'Just now', false);
                    }

                    try {
                        const socketId = window.Echo?.socketId();
                        const headers = {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        };
                        if (socketId) headers['X-Socket-ID'] = socketId;

                        await window.axios.post(chatForm.action, { body: text }, { headers });
                    } catch (err) {
                        console.error('Failed to transmit message:', err);
                    }
                });
            }

            // Real-Time WebSockets with Laravel Echo
            if (window.Echo) {
                // 1. Listen for messages on the active conversation channel
                if (activeConvoId) {
                    window.Echo.private(`conversation.${activeConvoId}`)
                        .listen('.message.sent', (e) => {
                            // Only append if not sent by me (to avoid duplication if socket ID was missing)
                            if (e.sender_id !== currentUserId) {
                                appendBubble({
                                    isMe: false,
                                    senderName: e.sender_name,
                                    time: e.created_at_time,
                                    body: e.body,
                                    isRead: false
                                });
                                updateConvoSidebar(e.conversation_id, e.body, 'Just now', false);
                            }
                        });
                }

                // 2. Listen for incoming messages across all candidate conversations
                if (currentUserId) {
                    window.Echo.private(`App.Models.User.${currentUserId}`)
                        .listen('.message.sent', (e) => {
                            if (String(e.conversation_id) !== String(activeConvoId)) {
                                updateConvoSidebar(e.conversation_id, e.body, 'Just now', true);
                            }
                        });
                }
            }
        });
    </script>
</x-layout>
