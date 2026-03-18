@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-100 text-gray-300 cursor-not-allowed bg-gray-50/50">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-700 hover:bg-gray-100 hover:border-gray-300 transition-all shadow-sm active:scale-95" aria-label="{{ __('Previous') }}">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-1 mx-2">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 flex items-center justify-center text-gray-400 font-light" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-900 text-white font-bold text-sm shadow-md shadow-gray-200">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-transparent text-gray-600 font-medium text-sm hover:bg-gray-50 hover:border-gray-100 transition-all hover:text-gray-900" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-700 hover:bg-gray-100 hover:border-gray-300 transition-all shadow-sm active:scale-95" aria-label="{{ __('Next') }}">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-100 text-gray-300 cursor-not-allowed bg-gray-50/50">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </span>
        @endif
    </nav>
@endif
