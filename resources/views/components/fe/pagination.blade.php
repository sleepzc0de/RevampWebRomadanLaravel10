@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-10 flex items-center justify-center gap-1.5">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="flex h-10 w-10 items-center justify-center rounded-full text-slate-300 dark:text-slate-700" aria-hidden="true">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
               class="flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-brand-50 hover:text-brand-700 dark:text-slate-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="flex h-10 w-10 items-center justify-center text-sm text-slate-400 dark:text-slate-600" aria-disabled="true">&hellip;</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-700 text-sm font-semibold text-white shadow-[var(--shadow-soft)] dark:bg-brand-600">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                           class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-medium text-slate-600 transition hover:bg-brand-50 hover:text-brand-700 dark:text-slate-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
               class="flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-brand-50 hover:text-brand-700 dark:text-slate-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="flex h-10 w-10 items-center justify-center rounded-full text-slate-300 dark:text-slate-700" aria-hidden="true">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif
