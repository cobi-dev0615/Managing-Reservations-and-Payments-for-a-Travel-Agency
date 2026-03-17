@extends('layouts.app')

@section('title', __('messages.tours'))
@section('page-title', __('messages.tours'))
@section('page-subtitle', __('messages.manage_tours_subtitle'))

@section('content')
<div class="page-header">
    <div></div>
    @if(auth()->user()->canManage())
    <a href="{{ route('tours.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> {{ __('messages.new_tour') }}
    </a>
    @endif
</div>

{{-- Filtros --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('tours.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="type" class="form-label">{{ __('messages.type') }}</label>
            <select name="type" id="type" class="form-select">
                <option value="">{{ __('messages.all_types') }}</option>
                <option value="grupo" {{ request('type') == 'grupo' ? 'selected' : '' }}>{{ __('messages.group') }}</option>
                <option value="privado" {{ request('type') == 'privado' ? 'selected' : '' }}>{{ __('messages.private') }}</option>
                <option value="agencia" {{ request('type') == 'agencia' ? 'selected' : '' }}>{{ __('messages.agency') }}</option>
                <option value="influencer" {{ request('type') == 'influencer' ? 'selected' : '' }}>{{ __('messages.influencer') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">{{ __('messages.status') }}</label>
            <select name="status" id="status" class="form-select">
                <option value="">{{ __('messages.all') }}</option>
                <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="search" class="form-label">{{ __('messages.search') }}</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('messages.name_or_code') }}" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label for="per_page" class="form-label">{{ __('messages.display') }}</label>
            <select name="per_page" id="per_page" class="form-select">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 d-flex gap-2 align-items-end">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ route('tours.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

{{-- Tabela --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('messages.code') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.type') }}</th>
                    <th>{{ __('messages.currency') }}</th>
                    <th>{{ __('messages.travelers') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tours as $tour)
                    <tr>
                        <td><code class="text-primary" style="font-size: 0.8rem;">{{ $tour->code }}</code></td>
                        <td class="fw-medium">{{ $tour->name }}</td>
                        <td>
                            <span class="count-badge">{{ ucfirst($tour->type) }}</span>
                        </td>
                        <td>
                            @if($tour->default_currency)
                                <span class="currency-badge currency-{{ $tour->default_currency }}">{{ $tour->default_currency }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-medium">
                                    {{ $tour->traveler_count }}
                                    @if($tour->max_travelers)
                                        <span class="text-muted">/ {{ $tour->max_travelers }}</span>
                                    @endif
                                </span>
                                @if($tour->max_travelers)
                                    @php
                                        $pct = min(100, round(($tour->traveler_count / $tour->max_travelers) * 100));
                                        if ($pct > 90) $barColor = '#ef4444';
                                        elseif ($pct >= 75) $barColor = '#f59e0b';
                                        else $barColor = '#22c55e';
                                    @endphp
                                    <div class="capacity-bar flex-grow-1" style="min-width: 50px;">
                                        <div class="capacity-bar-fill" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $tour->status }}">{{ ucfirst($tour->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tours.show', $tour) }}" class="btn btn-sm btn-outline-primary btn-action" title="{{ __('messages.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->canManage())
                                <a href="{{ route('tours.edit', $tour) }}" class="btn btn-sm btn-outline-secondary btn-action" title="{{ __('messages.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('tours.toggle-status', $tour) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-action" title="{{ $tour->status === 'ativo' ? __('messages.deactivate') : __('messages.activate') }}" data-confirm="{{ __('messages.change_status_confirm') }}" data-confirm-title="{{ __('messages.change_status') }}">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-map"></i>
                                <p>{{ __('messages.no_tours_found') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Results count & pagination --}}
<div class="rpms-pagination">
    <div class="rpms-pagination-info">
        {{ __('messages.showing') }}
        <strong>{{ $tours->firstItem() ?? 0 }}</strong>
        {{ __('messages.to') }}
        <strong>{{ $tours->lastItem() ?? 0 }}</strong>
        {{ __('messages.of') }}
        <strong>{{ $tours->total() }}</strong>
        {{ __('messages.tours_count') }}
    </div>
    @if($tours->hasPages())
        <nav>
            <ul class="rpms-pagination-nav">
                @if ($tours->onFirstPage())
                    <li class="rpms-page-item disabled"><span class="rpms-page-link"><i class="bi bi-chevron-left"></i></span></li>
                @else
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $tours->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
                @endif

                @php
                    $currentPage = $tours->currentPage();
                    $lastPage = $tours->lastPage();
                    $window = 2;
                    $start = max(1, $currentPage - $window);
                    $end = min($lastPage, $currentPage + $window);
                    if ($currentPage <= $window + 1) $end = min($lastPage, ($window * 2) + 1);
                    if ($currentPage >= $lastPage - $window) $start = max(1, $lastPage - ($window * 2));
                @endphp

                @if ($start > 1)
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $tours->url(1) }}">1</a></li>
                    @if ($start > 2)
                        <li class="rpms-page-item disabled"><span class="rpms-page-link rpms-page-dots">&hellip;</span></li>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <li class="rpms-page-item {{ $page == $currentPage ? 'active' : '' }}">
                        @if ($page == $currentPage)
                            <span class="rpms-page-link">{{ $page }}</span>
                        @else
                            <a class="rpms-page-link" href="{{ $tours->url($page) }}">{{ $page }}</a>
                        @endif
                    </li>
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <li class="rpms-page-item disabled"><span class="rpms-page-link rpms-page-dots">&hellip;</span></li>
                    @endif
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $tours->url($lastPage) }}">{{ $lastPage }}</a></li>
                @endif

                @if ($tours->hasMorePages())
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $tours->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
                @else
                    <li class="rpms-page-item disabled"><span class="rpms-page-link"><i class="bi bi-chevron-right"></i></span></li>
                @endif
            </ul>
        </nav>
    @endif
</div>
@endsection
