@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-2">
        {{-- Mobile Controls --}}
        <div class="flex items-center justify-between w-full sm:hidden gap-3">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-on-surface-variant/50 bg-surface-container-low border border-outline-variant/30 rounded-xl cursor-not-allowed">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-primary bg-surface-container-lowest hover:bg-surface-container border border-outline-variant/40 rounded-xl transition-colors shadow-2xs">
                    &larr; Sebelumnya
                </a>
            @endif

            <span class="text-xs font-medium text-on-surface-variant">
                Hal <span class="font-bold text-primary">{{ $paginator->currentPage() }}</span> / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-primary bg-surface-container-lowest hover:bg-surface-container border border-outline-variant/40 rounded-xl transition-colors shadow-2xs">
                    Berikutnya &rarr;
                </a>
            @else
                <span class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-on-surface-variant/50 bg-surface-container-low border border-outline-variant/30 rounded-xl cursor-not-allowed">
                    Berikutnya &rarr;
                </span>
            @endif
        </div>

        {{-- Desktop Summary Text --}}
        <div class="hidden sm:block">
            <p class="text-xs text-on-surface-variant leading-5">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-bold text-primary">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-primary">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span class="font-bold text-primary">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>

        {{-- Desktop Page Numbers --}}
        <div class="hidden sm:flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Sebelumnya" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant/20 bg-surface-container-low text-on-surface-variant/40 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container text-primary transition-colors shadow-2xs">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-on-surface-variant">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold rounded-xl bg-primary text-white shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium rounded-xl border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container text-on-surface hover:text-primary transition-colors shadow-2xs" aria-label="Halaman {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container text-primary transition-colors shadow-2xs">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <span aria-disabled="true" aria-label="Berikutnya" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant/20 bg-surface-container-low text-on-surface-variant/40 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif
