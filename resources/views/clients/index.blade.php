@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('page-subtitle', 'Gerencie sua base de clientes')

@section('content')
<div class="page-header">
    <div></div>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo Cliente
    </a>
</div>

{{-- Busca --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-end">
        <div class="col-md-10">
            <label for="search" class="form-label">Buscar</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Buscar por nome ou e-mail..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">
                <i class="bi bi-search"></i> Buscar
            </button>
        </div>
    </form>
</div>

{{-- Tabela --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Reservas</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="fw-medium">{{ $client->name }}</td>
                        <td class="text-muted">{{ $client->email }}</td>
                        <td>
                            <span class="count-badge">{{ $client->bookings_count }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary btn-action" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Excluir" onclick="confirmDelete('{{ route('clients.destroy', $client) }}', 'Tem certeza que deseja excluir o cliente {{ $client->name }}?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>Nenhum cliente encontrado.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('partials.pagination', ['paginator' => $clients])
@endsection
