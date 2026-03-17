@if ($paginator->hasPages())
<div class="rpms-pagination">
    <div class="rpms-pagination-info">
        <span>
            {{ __('messages.showing') }}
            <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
            {{ __('messages.to') }}
            <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
            {{ __('messages.of') }}
            <strong>{{ $paginator->total() }}</strong>
            {{ __('messages.results') }}
        </span>
    </div>
    <nav>
        <ul class="rpms-pagination-nav">
            {{-- First Page --}}
            @if ($paginator->onFirstPage())
                <li class="rpms-page-item disabled">
                    <span class="rpms-page-link"><i class="bi bi-chevron-double-left"></i></span>
                </li>
            @else
                <li class="rpms-page-item">
                    <a class="rpms-page-link" href="{{ $paginator->url(1) }}" title="{{ __('messages.first_page') }}">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
            @endif

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <li class="rpms-page-item disabled">
                    <span class="rpms-page-link"><i class="bi bi-chevron-left"></i></span>
                </li>
            @else
                <li class="rpms-page-item">
                    <a class="rpms-page-link" href="{{ $paginator->previousPageUrl() }}" title="{{ __('messages.previous') }}">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $window = 2;
                $start = max(1, $currentPage - $window);
                $end = min($lastPage, $currentPage + $window);

                if ($currentPage <= $window + 1) {
                    $end = min($lastPage, ($window * 2) + 1);
                }
                if ($currentPage >= $lastPage - $window) {
                    $start = max(1, $lastPage - ($window * 2));
                }
            @endphp

            @if ($start > 1)
                <li class="rpms-page-item">
                    <a class="rpms-page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="rpms-page-item disabled">
                        <span class="rpms-page-link rpms-page-dots">&hellip;</span>
                    </li>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <li class="rpms-page-item active">
                        <span class="rpms-page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="rpms-page-item">
                        <a class="rpms-page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <li class="rpms-page-item disabled">
                        <span class="rpms-page-link rpms-page-dots">&hellip;</span>
                    </li>
                @endif
                <li class="rpms-page-item">
                    <a class="rpms-page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li class="rpms-page-item">
                    <a class="rpms-page-link" href="{{ $paginator->nextPageUrl() }}" title="{{ __('messages.next') }}">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="rpms-page-item disabled">
                    <span class="rpms-page-link"><i class="bi bi-chevron-right"></i></span>
                </li>
            @endif

            {{-- Last Page --}}
            @if ($paginator->currentPage() === $paginator->lastPage())
                <li class="rpms-page-item disabled">
                    <span class="rpms-page-link"><i class="bi bi-chevron-double-right"></i></span>
                </li>
            @else
                <li class="rpms-page-item">
                    <a class="rpms-page-link" href="{{ $paginator->url($paginator->lastPage()) }}" title="{{ __('messages.last_page') }}">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endif
