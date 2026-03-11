@extends('layouts.app')

@section('title', $client->name)
@section('page-title', $client->name)

@section('breadcrumb')
<a href="{{ route('clients.index') }}">Clientes</a> <span class="separator">/</span> <span class="current">{{ $client->name }}</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4>{{ $client->name }}</h4>
        <p class="page-header-subtitle mb-0">{{ $client->email }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('clients.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

{{-- Detalhes do Cliente --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-person me-1"></i> Detalhes do Cliente
    </div>
    <div class="card-body">
        <div class="detail-row">
            <div class="detail-label">Nome</div>
            <div class="detail-value fw-medium">{{ $client->name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">E-mail</div>
            <div class="detail-value"><a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a></div>
        </div>
        @if($client->notes)
            <div class="detail-row">
                <div class="detail-label">Observacoes</div>
                <div class="detail-value text-muted">{{ $client->notes }}</div>
            </div>
        @endif
    </div>
</div>

{{-- Reservas do Cliente --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-bookmark me-1"></i> Reservas do Cliente</span>
        <a href="{{ route('bookings.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Nova Reserva
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Data Inicio</th>
                    <th>Valor Total</th>
                    <th>Moeda</th>
                    <th>Status</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->bookings as $booking)
                    <tr>
                        <td class="fw-medium">{{ $booking->tour_name }}</td>
                        <td>{{ $booking->start_date ? $booking->start_date->format('d/m/Y') : '-' }}</td>
                        <td class="fw-medium">{{ number_format($booking->total_value, 2, ',', '.') }}</td>
                        <td>
                            <span class="currency-badge currency-{{ $booking->currency }}">{{ $booking->currency }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary btn-action">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-journal-bookmark"></i>
                                <p>Nenhuma reserva para este cliente.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
