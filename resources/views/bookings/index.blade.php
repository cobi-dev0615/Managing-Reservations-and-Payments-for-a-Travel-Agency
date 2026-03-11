@extends('layouts.app')

@section('title', 'Reservas')
@section('page-title', 'Reservas')
@section('page-subtitle', 'Gerencie todas as reservas de viagem')

@section('breadcrumb')
<span class="current">Reservas</span>
@endsection

@section('content')
<div class="page-header">
    <div></div>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nova Reserva
    </a>
</div>

{{-- Filtros --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('bookings.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">Todos</option>
                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluido</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="tour_id" class="form-label">Tour</label>
            <select name="tour_id" id="tour_id" class="form-select">
                <option value="">Todos</option>
                @foreach($tours as $tour)
                    <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>{{ $tour->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Cliente ou tour..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label for="per_page" class="form-label">Exibir</label>
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
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Tour</th>
                    <th>Data Inicio</th>
                    <th>Viajantes</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Acoes</th>
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
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary btn-action" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Excluir" onclick="confirmDelete('{{ route('bookings.destroy', $booking) }}', 'Tem certeza que deseja excluir esta reserva?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-journal-bookmark"></i>
                                <p>Nenhuma reserva encontrada.</p>
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
        Mostrando
        <strong>{{ $bookings->firstItem() ?? 0 }}</strong>
        a
        <strong>{{ $bookings->lastItem() ?? 0 }}</strong>
        de
        <strong>{{ $bookings->total() }}</strong>
        reserva(s)
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
