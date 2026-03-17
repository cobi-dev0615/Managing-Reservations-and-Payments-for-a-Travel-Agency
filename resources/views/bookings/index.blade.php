@extends('layouts.app')

@section('title', __('messages.bookings'))
@section('page-title', __('messages.bookings'))
@section('page-subtitle', __('messages.manage_bookings_subtitle'))

@section('content')
<div class="page-header">
    <div></div>
    @if(auth()->user()->canManage())
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> {{ __('messages.new_booking') }}
    </a>
    @endif
</div>

{{-- Filtros --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('bookings.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="status" class="form-label">{{ __('messages.status') }}</label>
            <select name="status" id="status" class="form-select">
                <option value="">{{ __('messages.all') }}</option>
                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
                <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>{{ __('messages.status_confirmed') }}</option>
                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>{{ __('messages.status_cancelled') }}</option>
                <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>{{ __('messages.status_completed') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="tour_id" class="form-label">{{ __('messages.tour') }}</label>
            <select name="tour_id" id="tour_id" class="form-select">
                <option value="">{{ __('messages.all') }}</option>
                @foreach($tours as $tour)
                    <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>{{ $tour->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="search" class="form-label">{{ __('messages.search') }}</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('messages.client_or_tour') }}" value="{{ request('search') }}">
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
            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
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
                    <th>{{ __('messages.id') }}</th>
                    <th>{{ __('messages.client') }}</th>
                    <th>{{ __('messages.tour') }}</th>
                    <th>{{ __('messages.start_date') }}</th>
                    <th>{{ __('messages.travelers') }}</th>
                    <th>{{ __('messages.total_value') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td class="text-muted">#{{ $booking->id }}</td>
                        <td class="fw-medium">{{ $booking->client->name ?? 'N/A' }}</td>
                        <td>{{ $booking->tour_name }}</td>
                        <td>{{ $booking->start_date ? $booking->start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $booking->num_travelers }}</td>
                        <td>
                            <span class="currency-badge currency-{{ $booking->currency }} me-1">{{ $booking->currency }}</span>
                            <span class="fw-medium">{{ number_format($booking->total_value, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary btn-action" title="{{ __('messages.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->canManage())
                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary btn-action" title="{{ __('messages.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="{{ __('messages.delete') }}" onclick="confirmDelete('{{ route('bookings.destroy', $booking) }}', '{{ __('messages.confirm_delete_booking') }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-journal-bookmark"></i>
                                <p>{{ __('messages.no_bookings_found') }}</p>
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
        <strong>{{ $bookings->firstItem() ?? 0 }}</strong>
        {{ __('messages.to') }}
        <strong>{{ $bookings->lastItem() ?? 0 }}</strong>
        {{ __('messages.of') }}
        <strong>{{ $bookings->total() }}</strong>
        {{ __('messages.bookings_count') }}
    </div>
    @if($bookings->hasPages())
        <nav>
            <ul class="rpms-pagination-nav">
                @if ($bookings->onFirstPage())
                    <li class="rpms-page-item disabled"><span class="rpms-page-link"><i class="bi bi-chevron-left"></i></span></li>
                @else
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $bookings->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
                @endif

                @php
                    $currentPage = $bookings->currentPage();
                    $lastPage = $bookings->lastPage();
                    $window = 2;
                    $start = max(1, $currentPage - $window);
                    $end = min($lastPage, $currentPage + $window);
                    if ($currentPage <= $window + 1) $end = min($lastPage, ($window * 2) + 1);
                    if ($currentPage >= $lastPage - $window) $start = max(1, $lastPage - ($window * 2));
                @endphp

                @if ($start > 1)
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $bookings->url(1) }}">1</a></li>
                    @if ($start > 2)
                        <li class="rpms-page-item disabled"><span class="rpms-page-link rpms-page-dots">&hellip;</span></li>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <li class="rpms-page-item {{ $page == $currentPage ? 'active' : '' }}">
                        @if ($page == $currentPage)
                            <span class="rpms-page-link">{{ $page }}</span>
                        @else
                            <a class="rpms-page-link" href="{{ $bookings->url($page) }}">{{ $page }}</a>
                        @endif
                    </li>
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <li class="rpms-page-item disabled"><span class="rpms-page-link rpms-page-dots">&hellip;</span></li>
                    @endif
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $bookings->url($lastPage) }}">{{ $lastPage }}</a></li>
                @endif

                @if ($bookings->hasMorePages())
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $bookings->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
                @else
                    <li class="rpms-page-item disabled"><span class="rpms-page-link"><i class="bi bi-chevron-right"></i></span></li>
                @endif
            </ul>
        </nav>
    @endif
</div>
@endsection
