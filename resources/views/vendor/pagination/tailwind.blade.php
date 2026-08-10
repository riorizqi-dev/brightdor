@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        <div class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                      class="flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 text-ink-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-600 transition hover:border-rose-500/50 hover:text-rose-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="px-2 text-sm text-ink-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="flex h-9 w-9 items-center justify-center rounded-full bg-rose-600 text-sm font-bold text-white shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                               class="flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 bg-white text-sm text-ink-600 transition hover:border-rose-500/50 hover:text-rose-600">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-600 transition hover:border-rose-500/50 hover:text-rose-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                      class="flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 text-ink-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
