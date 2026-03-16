@extends('layouts.app')

@section('title', 'Cockpit de Pagamentos')
@section('page-title', 'Cockpit de Pagamentos')
@section('page-subtitle', 'Visao geral de todas as parcelas e pagamentos')

@section('content')

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="stat-card-label">Pendente</div>
                        <div class="stat-card-value text-warning" style="font-size: 1.5rem;">{{ $stats['counts']['pendente'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: #d1fae5; color: #059669;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-card-label">Pago</div>
                        <div class="stat-card-value text-success" style="font-size: 1.5rem;">{{ $stats['counts']['pago'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: #fee2e2; color: #dc2626;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-card-label">Atrasado</div>
                        <div class="stat-card-value text-danger" style="font-size: 1.5rem;">{{ $stats['counts']['atrasado'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: #e0e7ff; color: #4f46e5;">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div>
                        <div class="stat-card-label">Falta Link</div>
                        <div class="stat-card-value" style="font-size: 1.5rem; color: #4f46e5;">{{ $stats['counts']['falta_link'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Currency totals --}}
    @foreach($stats['totals_by_currency'] as $currency => $total)
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="currency-badge currency-{{ $currency }}" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">{{ $currency }}</span>
                    <div>
                        <div class="fw-bold" style="font-size: 1.1rem; letter-spacing: -0.02em;">{{ number_format($total, 2, ',', '.') }}</div>
                        <div class="text-muted" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.03em;">Pendente</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Filtros --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('payments.index') }}" class="row g-3 align-items-end">
        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">Todos</option>
                <option value="pendente" {{ ($filters['status'] ?? '') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="pago" {{ ($filters['status'] ?? '') == 'pago' ? 'selected' : '' }}>Pago</option>
                <option value="atrasado" {{ ($filters['status'] ?? '') == 'atrasado' ? 'selected' : '' }}>Atrasado</option>
                <option value="falta_link" {{ ($filters['status'] ?? '') == 'falta_link' ? 'selected' : '' }}>Falta Link</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="payment_method" class="form-label">Metodo</label>
            <select name="payment_method" id="payment_method" class="form-select">
                <option value="">Todos</option>
                <option value="link" {{ ($filters['payment_method'] ?? '') == 'link' ? 'selected' : '' }}>Link</option>
                <option value="pix" {{ ($filters['payment_method'] ?? '') == 'pix' ? 'selected' : '' }}>PIX</option>
                <option value="wise" {{ ($filters['payment_method'] ?? '') == 'wise' ? 'selected' : '' }}>Wise</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="tour_id" class="form-label">Tour</label>
            <select name="tour_id" id="tour_id" class="form-select">
                <option value="">Todos</option>
                @foreach($tours as $tour)
                    <option value="{{ $tour->id }}" {{ ($filters['tour_id'] ?? '') == $tour->id ? 'selected' : '' }}>{{ $tour->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="date_from" class="form-label">De</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label for="date_to" class="form-label">Ate</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Cliente ou tour..." value="{{ $filters['search'] ?? '' }}">
        </div>
        <div class="col-md-12 d-flex gap-2 align-items-end">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-search"></i> Filtrar
            </button>
            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i> Limpar
            </a>
        </div>
    </form>
</div>

{{-- Tabela de Parcelas --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Vencimento</th>
                    <th>Cliente</th>
                    <th>Tour</th>
                    <th>Parcela</th>
                    <th>Valor</th>
                    <th>Moeda</th>
                    <th>Metodo</th>
                    <th>Link</th>
                    <th>Ultimo E-mail</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($installments as $installment)
                    @php
                        $rowClass = '';
                        if ($installment->status === 'atrasado') $rowClass = 'table-danger';
                        elseif ($installment->status === 'falta_link') $rowClass = 'table-warning';
                        elseif ($installment->status === 'pago') $rowClass = 'table-success';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>
                            <span class="status-badge status-{{ $installment->status }}">
                                {{ $installment->status === 'falta_link' ? 'Falta Link' : ucfirst($installment->status) }}
                            </span>
                        </td>
                        <td>{{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '-' }}</td>
                        <td class="fw-medium">{{ $installment->booking->client->name ?? 'N/A' }}</td>
                        <td>{{ $installment->booking->tour_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $installment->installment_number }}</td>
                        <td class="fw-medium">{{ number_format($installment->amount, 2, ',', '.') }}</td>
                        <td>
                            <span class="currency-badge currency-{{ $installment->booking->currency ?? 'BRL' }}">
                                {{ $installment->booking->currency ?? 'BRL' }}
                            </span>
                        </td>
                        <td><span class="count-badge">{{ strtoupper($installment->payment_method) }}</span></td>
                        <td>
                            @if($installment->payment_link)
                                <a href="{{ $installment->payment_link }}" target="_blank" class="btn btn-sm btn-outline-primary btn-action" title="Abrir link">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                            @elseif($installment->payment_method === 'link')
                                <span class="text-warning small"><i class="bi bi-exclamation-triangle"></i></span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($installment->last_email_sent_at)
                                <span class="timestamp-muted" title="{{ $installment->last_email_sent_at->format('d/m/Y H:i') }}">
                                    {{ $installment->last_email_sent_at->format('d/m') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($installment->status !== 'pago')
                                    <form action="{{ route('installments.mark-paid', $installment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="Marcar como Pago" data-confirm="Marcar parcela como paga?" data-confirm-title="Confirmar Pagamento">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('bookings.show', $installment->booking_id) }}#parcelas" class="btn btn-sm btn-outline-secondary btn-action" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($installment->status !== 'pago')
                                    <form action="{{ route('installments.resend-email', $installment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-info btn-action" title="Reenviar E-mail" data-confirm="Reenviar e-mail para esta parcela?" data-confirm-title="Reenviar E-mail">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('installments.toggle-email', $installment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $installment->email_paused ? 'btn-warning' : 'btn-outline-warning' }} btn-action" title="{{ $installment->email_paused ? 'Reativar E-mails' : 'Pausar E-mails' }}" data-confirm="{{ $installment->email_paused ? 'Reativar e-mails automaticos?' : 'Pausar e-mails automaticos?' }}" data-confirm-title="E-mails Automaticos">
                                            <i class="bi {{ $installment->email_paused ? 'bi-play-fill' : 'bi-pause-fill' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="bi bi-credit-card"></i>
                                <p>Nenhuma parcela encontrada.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Results count, per-page selector & pagination --}}
<div class="rpms-pagination">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="rpms-pagination-info">
            Mostrando
            <strong>{{ $installments->firstItem() ?? 0 }}</strong>
            a
            <strong>{{ $installments->lastItem() ?? 0 }}</strong>
            de
            <strong>{{ $installments->total() }}</strong>
            parcela(s)
        </div>
        <form method="GET" action="{{ route('payments.index') }}" class="d-flex align-items-center gap-2">
            @foreach(request()->except(['per_page', 'page']) as $key => $value)
                @if($value)<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endif
            @endforeach
            <select name="per_page" class="form-select form-select-sm" style="width: 5rem; font-size: 0.78rem; padding-right: 2rem;" onchange="this.form.submit()">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @if($installments->hasPages())
        <nav>
            <ul class="rpms-pagination-nav">
                @if ($installments->onFirstPage())
                    <li class="rpms-page-item disabled"><span class="rpms-page-link"><i class="bi bi-chevron-left"></i></span></li>
                @else
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $installments->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
                @endif

                @php
                    $currentPage = $installments->currentPage();
                    $lastPage = $installments->lastPage();
                    $window = 2;
                    $start = max(1, $currentPage - $window);
                    $end = min($lastPage, $currentPage + $window);
                    if ($currentPage <= $window + 1) $end = min($lastPage, ($window * 2) + 1);
                    if ($currentPage >= $lastPage - $window) $start = max(1, $lastPage - ($window * 2));
                @endphp

                @if ($start > 1)
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $installments->url(1) }}">1</a></li>
                    @if ($start > 2)
                        <li class="rpms-page-item disabled"><span class="rpms-page-link rpms-page-dots">&hellip;</span></li>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <li class="rpms-page-item {{ $page == $currentPage ? 'active' : '' }}">
                        @if ($page == $currentPage)
                            <span class="rpms-page-link">{{ $page }}</span>
                        @else
                            <a class="rpms-page-link" href="{{ $installments->url($page) }}">{{ $page }}</a>
                        @endif
                    </li>
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <li class="rpms-page-item disabled"><span class="rpms-page-link rpms-page-dots">&hellip;</span></li>
                    @endif
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $installments->url($lastPage) }}">{{ $lastPage }}</a></li>
                @endif

                @if ($installments->hasMorePages())
                    <li class="rpms-page-item"><a class="rpms-page-link" href="{{ $installments->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
                @else
                    <li class="rpms-page-item disabled"><span class="rpms-page-link"><i class="bi bi-chevron-right"></i></span></li>
                @endif
            </ul>
        </nav>
    @endif
</div>
@endsection
