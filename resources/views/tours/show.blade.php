@extends('layouts.app')

@section('title', $tour->name)
@section('page-title', $tour->name)

@section('page-subtitle')
<code class="text-primary">{{ $tour->code }}</code>
<span class="status-badge status-{{ $tour->status }} ms-2">{{ ucfirst($tour->status) }}</span>
@endsection

@section('content')
<div class="page-header">
    <div></div>
    <div class="d-flex gap-2">
        @if(auth()->user()->canManage())
        <a href="{{ route('tours.edit', $tour) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i> {{ __('messages.edit') }}
        </a>
        @endif
        <a href="{{ route('tours.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Tour Details --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-1"></i> {{ __('messages.tour_details') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="detail-label">{{ __('messages.code') }}</div>
                            <div class="detail-value"><code>{{ $tour->code }}</code></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">{{ __('messages.name') }}</div>
                            <div class="detail-value fw-medium">{{ $tour->name }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">{{ __('messages.type') }}</div>
                            <div class="detail-value"><span class="count-badge">{{ ucfirst($tour->type) }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="detail-label">{{ __('messages.currency') }}</div>
                            <div class="detail-value">
                                @if($tour->default_currency)
                                    <span class="currency-badge currency-{{ $tour->default_currency }}">{{ $tour->default_currency }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">{{ __('messages.status') }}</div>
                            <div class="detail-value"><span class="status-badge status-{{ $tour->status }}">{{ ucfirst($tour->status) }}</span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">{{ __('messages.max_travelers') }}</div>
                            <div class="detail-value">{{ $tour->max_travelers ?? __('messages.no_limit') }}</div>
                        </div>
                    </div>
                </div>
                @if($tour->notes)
                    <hr class="my-3">
                    <div>
                        <div class="detail-label mb-1">{{ __('messages.notes') }}</div>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $tour->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Traveler Count --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-people me-1"></i> {{ __('messages.travelers') }}
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <div class="stat-card-value mb-1">{{ $tour->traveler_count }}</div>
                @if($tour->max_travelers)
                    <div class="text-muted mb-3" style="font-size: 0.82rem;">{{ __('messages.of') }} {{ $tour->max_travelers }} {{ __('messages.slots') }}</div>
                    @php
                        $pct = min(100, round(($tour->traveler_count / $tour->max_travelers) * 100));
                        if ($pct > 90) $barColor = '#ef4444';
                        elseif ($pct >= 75) $barColor = '#f59e0b';
                        else $barColor = '#22c55e';
                    @endphp
                    <div class="w-100">
                        <div class="capacity-bar" style="height: 10px;">
                            <div class="capacity-bar-fill" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                        </div>
                        <div class="text-muted mt-2" style="font-size: 0.75rem;">{{ $pct }}% {{ __('messages.occupied') }}</div>
                    </div>
                    @if($tour->traveler_count >= $tour->max_travelers)
                        <span class="badge bg-danger mt-2">{{ __('messages.full') }}</span>
                    @endif
                @else
                    <div class="text-muted" style="font-size: 0.82rem;">{{ __('messages.no_slot_limit') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tour Bookings --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-bookmark me-1"></i> {{ __('messages.tour_bookings') }}</span>
        <span class="count-badge">{{ $tour->bookings->count() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('messages.client') }}</th>
                    <th>{{ __('messages.start_date') }}</th>
                    <th>{{ __('messages.travelers') }}</th>
                    <th>{{ __('messages.currency') }}</th>
                    <th>{{ __('messages.total_value') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tour->bookings as $booking)
                    <tr>
                        <td class="fw-medium">{{ $booking->client->name ?? 'N/A' }}</td>
                        <td>{{ $booking->start_date ? $booking->start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $booking->num_travelers }}</td>
                        <td>
                            <span class="currency-badge currency-{{ $booking->currency }}">{{ $booking->currency }}</span>
                        </td>
                        <td class="fw-medium">{{ number_format($booking->total_value, 2, ',', '.') }}</td>
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
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-journal-bookmark"></i>
                                <p>{{ __('messages.no_bookings_tour') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
