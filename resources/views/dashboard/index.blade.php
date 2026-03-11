@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Welcome Header --}}
<div class="page-header mb-4">
    <div>
        <h4>Dashboard Operacional</h4>
        <p class="page-header-subtitle mb-0">Visao geral do sistema de reservas e pagamentos</p>
    </div>
</div>

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
                            <div class="stat-card-label">Vence em Breve</div>
                            <div class="stat-card-value text-warning">{{ $statusCounts['vence_breve'] }}</div>
                        </div>
                    </div>
                    <div class="text-muted mt-2" style="font-size: 0.7rem;">Proximos 7 dias</div>
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
                            <div class="stat-card-label">Vence Hoje</div>
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
                            <div class="stat-card-label">Atrasado</div>
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
                            <div class="stat-card-label">Pago</div>
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
                            <div class="stat-card-label">Falta Link</div>
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
<div class="section-heading">Valores Pendentes por Moeda</div>
<div class="row g-3 mb-4">
    @foreach($currencyTotals as $currency => $data)
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <span class="currency-badge currency-{{ $currency }}" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">{{ $currency }}</span>
                <div>
                    <div class="fw-bold" style="font-size: 1.15rem; letter-spacing: -0.02em;">{{ number_format($data->total, 2, ',', '.') }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $data->count }} parcela(s) pendente(s)</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Booking Stats --}}
<div class="section-heading">Resumo de Reservas</div>
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index') }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">Total Reservas</div>
                    <div class="fs-4 fw-bold mt-1">{{ $bookingStats['total'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index', ['status' => 'pendente']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">Pendentes</div>
                    <div class="fs-4 fw-bold text-warning mt-1">{{ $bookingStats['pendente'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index', ['status' => 'confirmado']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">Confirmadas</div>
                    <div class="fs-4 fw-bold text-success mt-1">{{ $bookingStats['confirmado'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('bookings.index', ['status' => 'concluido']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body text-center py-3">
                    <div class="stat-card-label">Concluidas</div>
                    <div class="fs-4 fw-bold text-primary mt-1">{{ $bookingStats['concluido'] }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Upcoming Installments --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock text-primary me-1"></i> Proximos Vencimentos</span>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                @if($upcomingInstallments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Vencimento</th>
                                <th>Cliente</th>
                                <th>Tour</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingInstallments as $inst)
                            <tr>
                                <td>
                                    @if($inst->due_date->isToday())
                                        <span class="badge bg-primary">HOJE</span>
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
                    <p>Nenhum vencimento nos proximos 7 dias</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Overdue Installments --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle text-danger me-1"></i> Parcelas em Atraso</span>
                <a href="{{ route('payments.index', ['status' => 'atrasado']) }}" class="btn btn-sm btn-outline-danger">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                @if($overdueInstallments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Vencimento</th>
                                <th>Cliente</th>
                                <th>Tour</th>
                                <th>Valor</th>
                                <th>Dias</th>
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
                                    @php $overdueDays = $inst->due_date->diffInDays(now()); @endphp
                                    <span class="badge bg-danger" style="font-size: 0.68rem;">{{ $overdueDays == 0 ? '< 1 dia' : $overdueDays . 'd' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-check-circle"></i>
                    <p>Nenhuma parcela em atraso</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-activity text-muted me-1"></i> Atividade Recente</span>
        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary">Ver Logs</a>
    </div>
    <div class="card-body p-0">
        @if($recentActivity->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Acao</th>
                        <th>Entidade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $log)
                    <tr>
                        <td><span class="timestamp-muted">{{ $log->created_at->format('d/m H:i') }}</span></td>
                        <td>{{ $log->action }}</td>
                        <td>
                            <span class="badge bg-secondary" style="font-size: 0.68rem;">{{ $log->entity_type }}</span>
                            @if($log->entity_type === 'Tour')
                                <a href="{{ route('tours.show', $log->entity_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @elseif($log->entity_type === 'Client')
                                <a href="{{ route('clients.show', $log->entity_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @elseif($log->entity_type === 'Booking')
                                <a href="{{ route('bookings.show', $log->entity_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                            @elseif($log->entity_type === 'Installment')
                                @if($log->booking_id)
                                    <a href="{{ route('bookings.show', $log->booking_id) }}" class="text-decoration-none">#{{ $log->entity_id }}</a>
                                @else
                                    #{{ $log->entity_id }}
                                @endif
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
            <p>Nenhuma atividade registrada</p>
        </div>
        @endif
    </div>
</div>
@endsection
