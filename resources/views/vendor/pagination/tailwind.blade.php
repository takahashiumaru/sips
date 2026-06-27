@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="pagination-premium">
        <div class="flex flex-col gap-3 sm:hidden">
            <div class="flex items-center justify-between gap-3">
                @if ($paginator->onFirstPage())
                    <span class="pagination-mobile-btn pagination-disabled" aria-disabled="true">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-mobile-btn">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>Sebelumnya</span>
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-mobile-btn justify-end">
                        <span>Berikutnya</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <span class="pagination-mobile-btn pagination-disabled justify-end" aria-disabled="true">
                        <span>Berikutnya</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                @endif
            </div>

            <p class="text-center text-[11px] font-bold text-slate-400">
                Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
                <span class="text-slate-300">/</span>
                {{ number_format($paginator->total(), 0, ',', '.') }} data
            </p>
        </div>

        <div class="hidden sm:flex sm:flex-col sm:gap-3 lg:flex-row lg:items-center lg:justify-between">
            <p class="text-xs font-semibold text-slate-500">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-black text-slate-800">{{ number_format($paginator->firstItem(), 0, ',', '.') }}</span>
                    -
                    <span class="font-black text-slate-800">{{ number_format($paginator->lastItem(), 0, ',', '.') }}</span>
                @else
                    <span class="font-black text-slate-800">0</span>
                @endif
                dari
                <span class="font-black text-slate-800">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
                data
            </p>

            <div class="flex flex-wrap items-center gap-1.5">
                @if ($paginator->onFirstPage())
                    <span class="pagination-icon-btn pagination-disabled" aria-disabled="true" aria-label="Halaman sebelumnya">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-icon-btn" aria-label="Halaman sebelumnya">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-page pagination-ellipsis" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page pagination-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-page" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-icon-btn" aria-label="Halaman berikutnya">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <span class="pagination-icon-btn pagination-disabled" aria-disabled="true" aria-label="Halaman berikutnya">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
