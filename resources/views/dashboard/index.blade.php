@extends('layouts.app')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.operational_dashboard'))
@section('page-subtitle', __('messages.dashboard_subtitle'))

@section('content')
{{-- Payment Status Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl col-md-4 col-6">
        <a href="{{ route('payments.index', ['status' => 'pendente', 'date_to' => now()->addDays(7)->format('Y-m-d')]) }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <div class="stat-card-label">{{ __('messages.due_soon') }}</div>
                            <div class="stat-card-value text-warning">{{ $statusCounts['vence_breve'] }}</div>
                        </div>
                    </div>
                    <div class="text-muted mt-2" style="font-size: 0.7rem;">{{ __('messages.next_7_days') }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl col-md-4 col-6">
        <a href="{{ route('payments.index', ['date_from' => now()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon" style="background: #dbeafe; color: #2563eb;">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div>
                            <div class="stat-card-label">{{ __('messages.due_today') }}</div>
                            <div class="stat-card-value text-primary">{{ $statusCounts['vence_hoje'] }}</div>
                        </div>
                    </div>
                    <div class="text-muted mt-2" style="font-size: 0.7rem;">{{ now()->format('d/m/Y') }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl col-md-4 col-6">
        <a href="{{ route('payments.index', ['status' => 'atrasado']) }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon" style="background: #fee2e2; color: #dc2626;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <div class="stat-card-label">{{ __('messages.overdue') }}</div>
                            <div class="stat-card-value text-danger">{{ $statusCounts['atrasado'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl col-md-4 col-6">
        <a href="{{ route('payments.index', ['status' => 'pago']) }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon" style="background: #d1fae5; color: #059669;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div class="stat-card-label">{{ __('messages.paid') }}</div>
                            <div class="stat-card-value text-success">{{ $statusCounts['pago'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl col-md-4 col-6">
        <a href="{{ route('payments.index', ['status' => 'falta_link']) }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon" style="background: #e0e7ff; color: #4f46e5;">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                        <div>
                            <div class="stat-card-label">{{ __('messages.missing_link') }}</div>
                            <div class="stat-card-value" style="color: #4f46e5;">{{ $statusCounts['falta_link'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Currency Totals --}}
@if($currencyTotals->count() > 0)
<div class="section-heading">{{ __('messages.pending_values_currency') }}</div>
<div class="row g-3 mb-4">
    @foreach($currencyTotals as $currency => $data)
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <span class="currency-badge currency-{{ $currency }}" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">{{ $currency }}</span>
                <div>
                    <div class="fw-bold" style="font-size: 1.15rem; letter-spacing: -0.02em;">{{ number_format($data->total, 2, ',', '.') }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $data->count }} {{ __('messages.pending_installments') }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Booking Stats --}}
<div class="section-heading">{{ __('messages.booking_summary') }}</div>
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index') }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">{{ __('messages.total_bookings') }}</div>
                    <div class="fs-4 fw-bold mt-1">{{ $bookingStats['total'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index', ['status' => 'pendente']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">{{ __('messages.pending') }}</div>
                    <div class="fs-4 fw-bold text-warning mt-1">{{ $bookingStats['pendente'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index', ['status' => 'confirmado']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">{{ __('messages.confirmed') }}</div>
                    <div class="fs-4 fw-bold text-success mt-1">{{ $bookingStats['confirmado'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index', ['status' => 'concluido']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">{{ __('messages.completed') }}</div>
                    <div class="fs-4 fw-bold text-primary mt-1">{{ $bookingStats['concluido'] }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-fill text-success me-1"></i> {{ __('messages.monthly_revenue') }}
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="260"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-pie-chart-fill text-primary me-1"></i> {{ __('messages.payment_status') }}
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="paymentStatusChart" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-graph-up text-info me-1"></i> {{ __('messages.bookings_per_month') }}
            </div>
            <div class="card-body">
                <canvas id="bookingsChart" height="260"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-compass-fill text-warning me-1"></i> {{ __('messages.tours_by_type') }}
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="tourTypeChart" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock text-primary me-1"></i> {{ __('messages.upcoming_due') }}</span>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
            </div>
            <div class="card-body p-0">
                @if($upcomingInstallments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.due_date') }}</th>
                                <th>{{ __('messages.client') }}</th>
                                <th>{{ __('messages.tour') }}</th>
                                <th>{{ __('messages.value') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingInstallments as $inst)
                            <tr>
                                <td>
                                    @if($inst->due_date->isToday())
                                        <span class="badge bg-primary">{{ __('messages.today') }}</span>
                                    @else
                                        {{ $inst->due_date->format('d/m') }}
                                    @endif
                                </td>
                                <td>
                                    @if($inst->booking->client)
                                        <a href="{{ route('clients.show', $inst->booking->client) }}" class="text-decoration-none fw-medium">{{ $inst->booking->client->name }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-muted">{{ $inst->booking->tour_name }}</td>
                                <td>
                                    <span class="currency-badge currency-{{ $inst->booking->currency }}">{{ $inst->booking->currency }}</span>
                                    <span class="fw-medium">{{ number_format($inst->amount, 2, ',', '.') }}</span>
                                </td>
                                <td><span class="status-badge status-{{ $inst->resolveStatus() }}">{{ ucfirst(str_replace('_', ' ', $inst->resolveStatus())) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-calendar-check"></i>
                    <p>{{ __('messages.no_due_7_days') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle text-danger me-1"></i> {{ __('messages.overdue_installments') }}</span>
                <a href="{{ route('payments.index', ['status' => 'atrasado']) }}" class="btn btn-sm btn-outline-danger">{{ __('messages.view_all') }}</a>
            </div>
            <div class="card-body p-0">
                @if($overdueInstallments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.due_date') }}</th>
                                <th>{{ __('messages.client') }}</th>
                                <th>{{ __('messages.tour') }}</th>
                                <th>{{ __('messages.value') }}</th>
                                <th>{{ __('messages.days') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueInstallments as $inst)
                            <tr>
                                <td class="text-danger fw-medium">{{ $inst->due_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($inst->booking->client)
                                        <a href="{{ route('clients.show', $inst->booking->client) }}" class="text-decoration-none fw-medium">{{ $inst->booking->client->name }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-muted">{{ $inst->booking->tour_name }}</td>
                                <td>
                                    <span class="currency-badge currency-{{ $inst->booking->currency }}">{{ $inst->booking->currency }}</span>
                                    <span class="fw-medium">{{ number_format($inst->amount, 2, ',', '.') }}</span>
                                </td>
                                <td>
                                    @php $overdueDays = (int) $inst->due_date->diffInDays(now()); @endphp
                                    <span class="badge bg-danger" style="font-size: 0.68rem;">{{ $overdueDays == 0 ? __('messages.less_than_1_day') : $overdueDays . 'd' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-check-circle"></i>
                    <p>{{ __('messages.no_overdue') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-activity text-muted me-1"></i> {{ __('messages.recent_activity') }}</span>
        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('messages.view_logs') }}</a>
    </div>
    <div class="card-body p-0">
        @if($recentActivity->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.datetime') }}</th>
                        <th>{{ __('messages.action') }}</th>
                        <th>{{ __('messages.entity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $log)
                    <tr>
                        <td><span class="timestamp-muted">{{ $log->created_at->format('d/m H:i') }}</span></td>
                        <td>{{ $log->action }}</td>
                        <td>
                            <span class="badge bg-secondary" style="font-size: 0.68rem;">{{ $log->entity_type }}</span>
                            @if($log->entity_type === 'Tour' && $log->entity_id)
                                <a href="{{ route('tours.show', $log->entity_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @elseif($log->entity_type === 'Client' && $log->entity_id)
                                <a href="{{ route('clients.show', $log->entity_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @elseif($log->entity_type === 'Booking' && $log->entity_id)
                                <a href="{{ route('bookings.show', $log->entity_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @elseif($log->entity_type === 'Installment' && $log->booking_id)
                                <a href="{{ route('bookings.show', $log->booking_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @else
                                #{{ $log->entity_id }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-clock-history"></i>
            <p>{{ __('messages.no_activity') }}</p>
        </div>
        @endif
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

    // --- 1) Monthly Revenue (Bar) ---
    const revenueLabels = @json($revenueLabels);
    const revenueDatasets = [];
    const currencyStyles = {
        BRL: { bg: 'rgba(34,197,94,0.7)', border: '#22c55e', hoverBg: 'rgba(34,197,94,0.9)' },
        USD: { bg: 'rgba(59,130,246,0.7)', border: '#3b82f6', hoverBg: 'rgba(59,130,246,0.9)' },
        EUR: { bg: 'rgba(245,158,11,0.7)', border: '#f59e0b', hoverBg: 'rgba(245,158,11,0.9)' },
    };
    @foreach($revenueData as $currency => $values)
        revenueDatasets.push({
            label: '{{ $currency }}',
            data: @json($values),
            backgroundColor: currencyStyles['{{ $currency }}'].bg,
            borderColor: currencyStyles['{{ $currency }}'].border,
            hoverBackgroundColor: currencyStyles['{{ $currency }}'].hoverBg,
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        });
    @endforeach

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: { labels: revenueLabels, datasets: revenueDatasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                x: { grid: { display: false }, ticks: { color: textColor } },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: textColor, callback: v => v.toLocaleString('pt-BR') },
                },
            },
            plugins: {
                legend: { display: revenueDatasets.length > 1 },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 }),
                    },
                },
            },
        },
    });

    // --- 2) Payment Status (Doughnut) ---
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

    // --- 3) Bookings per Month (Line) ---
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

    // --- 4) Tours by Type (Doughnut) ---
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
});
</script>
@endpush
