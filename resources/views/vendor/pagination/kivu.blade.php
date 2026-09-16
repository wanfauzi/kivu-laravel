@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-xs text-kivu-text-muted">
            Halaman <span class="font-semibold text-kivu-text">{{ $paginator->currentPage() }}</span>
            @if (method_exists($paginator, 'lastPage'))
                dari {{ $paginator->lastPage() }}
            @endif
        </p>

        <ul class="flex items-center gap-1">
            {{-- Previous --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-9 items-center justify-center rounded-kivu-sm border border-kivu-border px-3 text-sm text-kivu-text-muted opacity-50">
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="kivu-focus inline-flex h-9 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm font-medium text-kivu-text transition hover:bg-kivu-surface-muted">
                        Sebelumnya
                    </a>
                @endif
            </li>

            {{-- Page numbers --}}
            @if (method_exists($paginator, 'getUrlRange'))
                @php
                    $start = max(1, $paginator->currentPage() - 2);
                    $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
                @endphp

                @foreach ($paginator->getUrlRange($start, $end) as $page => $url)
                    <li>
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="inline-flex h-9 min-w-9 items-center justify-center rounded-kivu-sm bg-kivu-primary px-3 text-sm font-semibold text-white">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="kivu-focus inline-flex h-9 min-w-9 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm font-medium text-kivu-text transition hover:bg-kivu-surface-muted">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endforeach
            @endif

            {{-- Next --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="kivu-focus inline-flex h-9 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm font-medium text-kivu-text transition hover:bg-kivu-surface-muted">
                        Berikutnya
                    </a>
                @else
                    <span class="inline-flex h-9 items-center justify-center rounded-kivu-sm border border-kivu-border px-3 text-sm text-kivu-text-muted opacity-50">
                        Berikutnya
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
