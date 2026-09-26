<x-layout>
    @php
        $autoEditPost = null;
        if (request('edit')) {
            $autoEditPost = $posts->firstWhere('id', request('edit')) ?? \App\Models\InternshipPost::find(request('edit'));
        }
        $failedEditPostId = old('editing_post_id');
        $initialEditOpen = ($errors->any() && $failedEditPostId) || ($autoEditPost !== null);
        $initialCreateOpen = ($errors->any() && !$failedEditPostId) || request('create');

        $initialEditPost = [
            'id' => '',
            'title' => '',
            'category' => 'General IT',
            'location' => '',
            'duration_weeks' => 12,
            'slots' => 1,
            'stipend' => '',
            'deadline' => '',
            'status' => 'pending_approval',
            'description' => '',
            'responsibilities' => '',
            'requirements' => '',
            'updateUrl' => '',
        ];

        if ($failedEditPostId && $errors->any()) {
            $initialEditPost = [
                'id' => $failedEditPostId,
                'title' => old('title', ''),
                'category' => old('category', 'General IT'),
                'location' => old('location', ''),
                'duration_weeks' => old('duration_weeks', 12),
                'slots' => old('slots', 1),
                'stipend' => old('stipend', ''),
                'deadline' => old('deadline', ''),
                'status' => old('status', 'pending_approval'),
                'description' => old('description', ''),
                'responsibilities' => old('responsibilities', ''),
                'requirements' => old('requirements', ''),
                'updateUrl' => route('company.posts.update', $failedEditPostId),
            ];
        } elseif ($autoEditPost) {
            $initialEditPost = [
                'id' => $autoEditPost->id,
                'title' => $autoEditPost->title,
                'category' => $autoEditPost->category ?? 'General IT',
                'location' => $autoEditPost->location,
                'duration_weeks' => $autoEditPost->duration_weeks,
                'slots' => $autoEditPost->slots,
                'stipend' => $autoEditPost->stipend ? (string)(float)$autoEditPost->stipend : '',
                'deadline' => \Carbon\Carbon::parse($autoEditPost->deadline)->format('Y-m-d'),
                'status' => $autoEditPost->status,
                'description' => $autoEditPost->description,
                'responsibilities' => $autoEditPost->responsibilities ?? '',
                'requirements' => $autoEditPost->requirements ?? '',
                'updateUrl' => route('company.posts.update', $autoEditPost),
            ];
        }
    @endphp

    <div class="flex flex-col h-[calc(100vh-6rem)] sm:h-[calc(100vh-7rem)] lg:h-[calc(100vh-8rem)]"
         x-data="listingsManager({{ $initialCreateOpen ? 'true' : 'false' }}, {{ $initialEditOpen ? 'true' : 'false' }}, @js($initialEditPost))"
         @open-create-modal.window="createModalOpen = true">

        <div class="flex items-center justify-between pb-4 shrink-0">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Internship Listings</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Manage positions published to university students.</p>
            </div>
            <button type="button" 
                    @click="createModalOpen = true" 
                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 active:scale-95 transition-all flex items-center gap-1.5 shadow-sm shadow-emerald-600/20 cursor-pointer shrink-0">
                <i class="fa-solid fa-plus w-4 h-4"></i>
                <span>Create New Listing</span>
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between flex-1 min-h-0">
            @if ($posts->isNotEmpty())
                <div class="overflow-auto flex-1 min-h-0">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 z-10 bg-slate-50/95 backdrop-blur-xs">
                            <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Position Title</th>
                                <th class="py-3.5 px-4">Category</th>
                                <th class="py-3.5 px-4">Location</th>
                                <th class="py-3.5 px-4">Stipend</th>
                                <th class="py-3.5 px-4">Applicants</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Deadline</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2.5 px-6 font-extrabold text-slate-900">
                                        <div class="text-sm">{{ $post->title }}</div>
                                        <span class="text-slate-600 font-normal text-xs">{{ $post->duration_weeks }} weeks • {{ $post->slots }} slot(s)</span>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <span class="inline-block px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200/80">
                                            {{ $post->category ?? 'General IT' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 text-slate-700 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                            <span>{{ $post->location }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-4 font-bold text-emerald-600">
                                        {{ $post->stipend ? '$' . number_format($post->stipend, 0) . '/mo' : 'Unpaid/Standard' }}
                                    </td>
                                    <td class="py-2.5 px-4 font-extrabold text-slate-900">
                                        <a href="{{ route('company.applicants.index', ['post_id' => $post->id]) }}" class="text-emerald-600 hover:underline">
                                            {{ $post->applications_count }} Candidate(s)
                                        </a>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <x-status-badge :status="$post->status" />
                                    </td>
                                    <td class="py-2.5 px-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($post->deadline)->format('M d, Y') }}
                                    </td>
                                    <td class="py-2.5 px-6 text-right">
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            <button type="button" 
                                                    @click="openEditModal({
                                                        id: {{ $post->id }},
                                                        title: @js($post->title),
                                                        category: @js($post->category ?? 'General IT'),
                                                        location: @js($post->location),
                                                        duration_weeks: {{ $post->duration_weeks }},
                                                        slots: {{ $post->slots }},
                                                        stipend: @js($post->stipend ? (string)(float)$post->stipend : ''),
                                                        deadline: @js(\Carbon\Carbon::parse($post->deadline)->format('Y-m-d')),
                                                        status: @js($post->status),
                                                        description: @js($post->description),
                                                        responsibilities: @js($post->responsibilities ?? ''),
                                                        requirements: @js($post->requirements ?? ''),
                                                        updateUrl: @js(route('company.posts.update', $post))
                                                    })"
                                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Edit Post">
                                                <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                            </button>
                                            <form action="{{ route('company.posts.destroy', $post) }}" method="POST" class="inline-flex m-0" onsubmit="return confirm('Archive this internship post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                        title="Archive Post">
                                                    <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-white shrink-0">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-16 text-center text-slate-600 space-y-3">
                    <i class="fa-solid fa-folder-plus w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No internship listings created yet</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Create a listing to begin receiving applications from pre-vetted university students.</p>
                    <button type="button" 
                            @click="createModalOpen = true" 
                            class="inline-block px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 active:scale-95 transition-all shadow-sm shadow-emerald-600/20 cursor-pointer">
                        Post Your First Opportunity
                    </button>
                </div>
            @endif
        </div>

        {{-- Post Internship (Create) Modal --}}
        @include('company.posts.modal-create')

        {{-- Edit Internship Modal --}}
        @include('company.posts.modal-edit')

    </div>

    <script>
        (function() {
            const categories = [
                { icon: '🖥️', label: 'IT Support & Helpdesk' },
                { icon: '🌐', label: 'Web Development' },
                { icon: '📱', label: 'Mobile Development' },
                { icon: '☕', label: 'Java / Spring Boot' },
                { icon: '⚙️', label: 'Backend Engineering' },
                { icon: '🎨', label: 'Frontend Engineering' },
                { icon: '🔗', label: 'Full-Stack Development' },
                { icon: '📊', label: 'Data Science & Analytics' },
                { icon: '🔒', label: 'Cybersecurity' },
                { icon: '☁️', label: 'DevOps & Cloud' },
                { icon: '✏️', label: 'UI/UX Design' },
                { icon: '🔌', label: 'Network Engineering' },
                { icon: '🗄️', label: 'Database Administration' },
                { icon: '🧪', label: 'Quality Assurance' },
                { icon: '📋', label: 'Business Analysis' },
                { icon: '💡', label: 'General IT' },
            ];

            const statuses = [
                { value: 'approved',         icon: '✅', label: 'Approved & Published' },
                { value: 'pending_approval', icon: '⏳', label: 'Pending Approval' },
                { value: 'closed',           icon: '🔒', label: 'Closed' },
                { value: 'draft',            icon: '📝', label: 'Draft' },
            ];

            function registerListingsManager() {
                if (window.Alpine && !Alpine.data('listingsManager')) {
                    Alpine.data('listingsManager', (initialCreateOpen, initialEditOpen, initialEditPost) => ({
                        createModalOpen: initialCreateOpen,
                        editModalOpen: initialEditOpen,
                        editPost: initialEditPost || {},
                        categoryDropdownOpen: false,
                        categorySearch: '',
                        editCategory: (initialEditPost && initialEditPost.category) ? initialEditPost.category : 'General IT',
                        editCategoryIcon: '💡',
                        statusDropdownOpen: false,
                        editStatus: (initialEditPost && initialEditPost.status) ? initialEditPost.status : 'pending_approval',
                        editStatusIcon: '⏳',
                        editStatusLabel: 'Pending Approval',
                        categories: categories,
                        statuses: statuses,

                        init() {
                            const matchedCat = this.categories.find(c => c.label === this.editCategory);
                            if (matchedCat) this.editCategoryIcon = matchedCat.icon;

                            const matchedStat = this.statuses.find(s => s.value === this.editStatus);
                            if (matchedStat) {
                                this.editStatusIcon = matchedStat.icon;
                                this.editStatusLabel = matchedStat.label;
                            }
                        },

                        get filteredEditCategories() {
                            if (!this.categorySearch) return this.categories;
                            return this.categories.filter(c => c.label.toLowerCase().includes(this.categorySearch.toLowerCase()));
                        },

                        selectEditCategory(cat) {
                            this.editCategory = cat.label;
                            this.editCategoryIcon = cat.icon;
                            this.editPost.category = cat.label;
                            this.categoryDropdownOpen = false;
                            this.categorySearch = '';
                        },

                        selectEditStatus(s) {
                            this.editStatus = s.value;
                            this.editStatusIcon = s.icon;
                            this.editStatusLabel = s.label;
                            this.editPost.status = s.value;
                            this.statusDropdownOpen = false;
                        },

                        openEditModal(post) {
                            this.editPost = { ...post };
                            this.editCategory = post.category || 'General IT';
                            const matchedCat = this.categories.find(c => c.label === this.editCategory);
                            this.editCategoryIcon = matchedCat ? matchedCat.icon : '💡';
                            this.categorySearch = '';
                            this.categoryDropdownOpen = false;

                            this.editStatus = post.status || 'pending_approval';
                            const matchedStat = this.statuses.find(s => s.value === this.editStatus);
                            this.editStatusIcon = matchedStat ? matchedStat.icon : '⏳';
                            this.editStatusLabel = matchedStat ? matchedStat.label : 'Pending Approval';
                            this.statusDropdownOpen = false;

                            this.editModalOpen = true;
                        }
                    }));
                }
            }

            if (window.Alpine) {
                registerListingsManager();
            } else {
                document.addEventListener('alpine:init', registerListingsManager);
            }
        })();
    </script>
</x-layout>
