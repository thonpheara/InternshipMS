<x-layout title="Manage Listings — Internship Management System">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Internship Listings</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Manage positions published to university students.</p>
            </div>
            <a href="{{ route('company.posts.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-colors flex items-center gap-1.5 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Create New Listing</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($posts->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Position Title</th>
                                <th class="py-3.5 px-4">Category</th>
                                <th class="py-3.5 px-4">Mode / Location</th>
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
                                    <td class="py-4 px-6 font-extrabold text-slate-900">
                                        <div class="text-sm">{{ $post->title }}</div>
                                        <span class="text-slate-600 font-normal">{{ $post->duration_weeks }} weeks • {{ $post->slots }} slot(s)</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-block px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-100">
                                            {{ $post->category ?? 'General IT' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <span class="font-bold uppercase text-[10px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-700">{{ ucfirst($post->type) }}</span>
                                        <span class="block text-slate-600 mt-0.5">{{ $post->location }}</span>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-emerald-600">
                                        {{ $post->stipend ? '$' . number_format($post->stipend, 0) . '/mo' : 'Unpaid/Standard' }}
                                    </td>
                                    <td class="py-4 px-4 font-extrabold text-slate-900">
                                        <a href="{{ route('company.applicants.index', ['post_id' => $post->id]) }}" class="text-emerald-600 hover:underline">
                                            {{ $post->applications_count }} Candidate(s)
                                        </a>
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$post->status" />
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($post->deadline)->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            <a href="{{ route('company.posts.edit', $post) }}" 
                                               class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                               title="Edit Post">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('company.posts.destroy', $post) }}" method="POST" class="inline-flex m-0" onsubmit="return confirm('Archive this internship post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                        title="Archive Post">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i data-lucide="folder-plus" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No internship listings created yet</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Create a listing to begin receiving applications from pre-vetted university students.</p>
                    <a href="{{ route('company.posts.create') }}" class="inline-block px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
                        Post Your First Opportunity
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-layout>
