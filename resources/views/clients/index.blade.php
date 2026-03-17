@extends('layouts.app')

@section('title', __('messages.clients'))
@section('page-title', __('messages.clients'))
@section('page-subtitle', __('messages.manage_clients_subtitle'))

@section('content')
<div class="page-header">
    <div></div>
    @if(auth()->user()->canManage())
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> {{ __('messages.new_client') }}
    </a>
    @endif
</div>

{{-- Busca --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-end">
        <div class="col-md-8">
            <label for="search" class="form-label">{{ __('messages.search') }}</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="{{ __('messages.search_name_email') }}" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <label for="per_page" class="form-label">{{ __('messages.display') }}</label>
            <select name="per_page" id="per_page" class="form-select">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">
                <i class="bi bi-search"></i> {{ __('messages.search') }}
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
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.email') }}</th>
                    <th>{{ __('messages.bookings') }}</th>
                    <th>{{ __('messages.actions') }}</th>
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
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary btn-action" title="{{ __('messages.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->canManage())
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary btn-action" title="{{ __('messages.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="{{ __('messages.delete') }}" onclick="confirmDelete('{{ route('clients.destroy', $client) }}', '{{ __('messages.confirm_delete_client', ['name' => '']) }}' + '{{ $client->name }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>{{ __('messages.no_clients_found') }}</p>
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
