@extends('layouts.app')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.dashboard'))
@section('page-subtitle', __('messages.dashboard_viewer_subtitle'))

@section('content')
{{-- Booking Stats --}}
<div class="section-heading">{{ __('messages.booking_summary') }}</div>
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card stat-card">
            <div class="card-body text-center py-3">
                <div class="stat-card-label">{{ __('messages.total_bookings') }}</div>
                <div class="fs-4 fw-bold mt-1">{{ $bookingStats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card stat-card">
            <div class="card-body text-center py-3">
                <div class="stat-card-label">{{ __('messages.pending') }}</div>
                <div class="fs-4 fw-bold text-warning mt-1">{{ $bookingStats['pendente'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card stat-card">
            <div class="card-body text-center py-3">
                <div class="stat-card-label">{{ __('messages.confirmed') }}</div>
                <div class="fs-4 fw-bold text-success mt-1">{{ $bookingStats['confirmado'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card stat-card">
            <div class="card-body text-center py-3">
                <div class="stat-card-label">{{ __('messages.completed') }}</div>
                <div class="fs-4 fw-bold text-primary mt-1">{{ $bookingStats['concluido'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    {{-- Payment Status --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-pie-chart-fill text-primary me-1"></i> {{ __('messages.payment_status') }}
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="paymentStatusChart" height="260"></canvas>
            </div>
        </div>
    </div>
    {{-- Tours by Type --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-compass-fill text-warning me-1"></i> {{ __('messages.tours_by_type') }}
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="tourTypeChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Bookings per Month --}}
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-graph-up text-info me-1"></i> {{ __('messages.bookings_per_month') }}
            </div>
            <div class="card-body">
                <canvas id="bookingsChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = isDark ? 'rgba(59,130,246,0.08)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const tooltipBg = isDark ? 'rgba(10,18,45,0.92)' : 'rgba(15,23,42,0.9)';

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = textColor;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.pointStyleWidth = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = tooltipBg;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.titleFont = { weight: '600', size: 13 };

    // --- Payment Status (Doughnut) ---
    const statusLabels = @json(array_keys($paymentStatusChart));
    const statusValues = @json(array_values($paymentStatusChart));
    const statusColors = ['#f59e0b', '#22c55e', '#ef4444', '#6366f1'];

    new Chart(document.getElementById('paymentStatusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: statusColors.map(c => c + 'cc'),
                borderColor: statusColors,
                borderWidth: 2,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        },
                    },
                },
            },
        },
    });

    // --- Tours by Type (Doughnut) ---
    const tourLabels = @json(array_keys($tourTypeChart));
    const tourValues = @json(array_values($tourTypeChart));
    const tourColors = ['#3b82f6', '#8b5cf6', '#f59e0b', '#ec4899'];

    new Chart(document.getElementById('tourTypeChart'), {
        type: 'doughnut',
        data: {
            labels: tourLabels,
            datasets: [{
                data: tourValues,
                backgroundColor: tourColors.map(c => c + 'cc'),
                borderColor: tourColors,
                borderWidth: 2,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        },
                    },
                },
            },
        },
    });

    // --- Bookings per Month (Line) ---
    const bookingsLabels = @json($bookingsLabels);
    const bookingsData = @json($bookingsData);

    new Chart(document.getElementById('bookingsChart'), {
        type: 'line',
        data: {
            labels: bookingsLabels,
            datasets: [{
                label: '{{ __('messages.chart_bookings') }}',
                data: bookingsData,
                borderColor: '#3b82f6',
                backgroundColor: isDark ? 'rgba(59,130,246,0.1)' : 'rgba(59,130,246,0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: isDark ? '#0f172a' : '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                x: { grid: { display: false }, ticks: { color: textColor } },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: textColor, stepSize: 1 },
                },
            },
            plugins: { legend: { display: false } },
        },
    });
});
</script>
@endpush
