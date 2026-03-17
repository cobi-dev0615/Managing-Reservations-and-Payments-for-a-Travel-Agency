@extends('layouts.app')

@section('title', $client->name)
@section('page-title', $client->name)
@section('page-subtitle', $client->email)

@section('content')
<div class="page-header">
    <div></div>
    <div class="d-flex gap-2">
        @if(auth()->user()->canManage())
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i> {{ __('messages.edit') }}
        </a>
        @endif
        <a href="{{ route('clients.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>
</div>

{{-- Detalhes do Cliente --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-person me-1"></i> {{ __('messages.client_details') }}
    </div>
    <div class="card-body">
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.name') }}</div>
            <div class="detail-value fw-medium">{{ $client->name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.email') }}</div>
            <div class="detail-value"><a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a></div>
        </div>
        @if($client->notes)
            <div class="detail-row">
                <div class="detail-label">{{ __('messages.notes') }}</div>
                <div class="detail-value text-muted">{{ $client->notes }}</div>
            </div>
        @endif
    </div>
</div>

{{-- Reservas do Cliente --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-bookmark me-1"></i> {{ __('messages.client_bookings') }}</span>
        @if(auth()->user()->canManage())
        <a href="{{ route('bookings.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> {{ __('messages.new_booking') }}
        </a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('messages.tour') }}</th>
                    <th>{{ __('messages.start_date') }}</th>
                    <th>{{ __('messages.total_value') }}</th>
                    <th>{{ __('messages.currency') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
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
                                <p>{{ __('messages.no_bookings_client') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
