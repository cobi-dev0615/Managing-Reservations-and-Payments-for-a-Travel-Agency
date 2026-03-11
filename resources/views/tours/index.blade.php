@extends('layouts.app')

@section('title', 'Tours')
@section('page-title', 'Tours')

@section('breadcrumb')
<span class="current">Tours</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-map-fill text-primary me-2" style="font-size: 1.1rem;"></i>Tours</h4>
        <p class="page-header-subtitle mb-0">Gerencie todos os tours e pacotes de viagem</p>
    </div>
    <a href="{{ route('tours.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo Tour
    </a>
</div>

{{-- Filtros --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('tours.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="type" class="form-label">Tipo</label>
            <select name="type" id="type" class="form-select">
                <option value="">Todos os tipos</option>
                <option value="grupo" {{ request('type') == 'grupo' ? 'selected' : '' }}>Grupo</option>
                <option value="privado" {{ request('type') == 'privado' ? 'selected' : '' }}>Privado</option>
                <option value="agencia" {{ request('type') == 'agencia' ? 'selected' : '' }}>Agencia</option>
                <option value="influencer" {{ request('type') == 'influencer' ? 'selected' : '' }}>Influencer</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">Todos</option>
                <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Nome ou codigo..." value="{{ request('search') }}">
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
                    <th>Codigo</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Moeda</th>
                    <th>Viajantes</th>
                    <th>Status</th>
                    <th>Acoes</th>
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
                                <a href="{{ route('tours.show', $tour) }}" class="btn btn-sm btn-outline-primary btn-action" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tours.edit', $tour) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('tours.toggle-status', $tour) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-action" title="{{ $tour->status === 'ativo' ? 'Desativar' : 'Ativar' }}" onclick="return confirm('Deseja alterar o status deste tour?')">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-map"></i>
                                <p>Nenhum tour encontrado.</p>
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
        <strong>{{ $tours->firstItem() ?? 0 }}</strong>
        a
        <strong>{{ $tours->lastItem() ?? 0 }}</strong>
        de
        <strong>{{ $tours->total() }}</strong>
        tour(s)
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
