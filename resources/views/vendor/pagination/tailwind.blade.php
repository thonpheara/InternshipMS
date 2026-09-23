@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        
        <!-- Mobile Navigation Buttons -->
        <div class="flex justify-between flex-1 sm:hidden gap-2 items-center">
            @if ($paginator->hasPages())
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200/80 cursor-not-allowed rounded-xl select-none">
                        <i class="fa-solid fa-chevron-left mr-1.5 text-[10px]"></i>
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition-all">
                        <i class="fa-solid fa-chevron-left mr-1.5 text-[10px]"></i>
                        {!! __('pagination.previous') !!}
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition-all">
                        {!! __('pagination.next') !!}
                        <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
                    </a>
                @else
                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200/80 cursor-not-allowed rounded-xl select-none">
                        {!! __('pagination.next') !!}
                        <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
                    </span>
                @endif
            @else
                <span class="text-xs text-slate-500 font-medium">Page 1 of 1</span>
            @endif
        </div>

        <!-- Desktop Navigation & Count -->
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-xs text-slate-500">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-slate-800">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-bold text-slate-800">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-bold text-slate-800">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1 rounded-2xl bg-slate-50/80 p-1 border border-slate-200/70">
                    
                    @if ($paginator->hasPages())
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-300 cursor-not-allowed select-none">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                               class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-600 bg-white border border-slate-200/70 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition-all cursor-pointer"
                               aria-label="{{ __('pagination.previous') }}"
                               title="Previous Page">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true" class="w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-400 select-none">
                                    {{ $element }}
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page" 
                                              class="w-8 h-8 flex items-center justify-center rounded-xl bg-emerald-600 text-white font-black text-xs shadow-sm shadow-emerald-600/30 border border-emerald-600 select-none">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" 
                                           class="w-8 h-8 flex items-center justify-center rounded-xl bg-white text-slate-700 font-bold text-xs border border-slate-200/70 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition-all cursor-pointer"
                                           aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
                               class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-600 bg-white border border-slate-200/70 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition-all cursor-pointer"
                               aria-label="{{ __('pagination.next') }}"
                               title="Next Page">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-300 cursor-not-allowed select-none">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </span>
                        @endif
                    @else
                        {{-- Single Page (Total <= PerPage) --}}
                        <span aria-current="page" 
                              class="w-8 h-8 flex items-center justify-center rounded-xl bg-emerald-600 text-white font-black text-xs shadow-sm shadow-emerald-600/30 border border-emerald-600 select-none">
                            1
                        </span>
                    @endif

                </span>
            </div>
        </div>

    </nav>
@endif
