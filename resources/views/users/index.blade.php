@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Gerenciar Usuarios')
@section('page-subtitle', 'Controle de acesso e permissoes do sistema')

@section('content')
<div class="page-header">
    <div></div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo Usuario
    </a>
</div>

@php
    $pendingCount = $users->where('status', 'pending')->count();
@endphp

@if($pendingCount > 0)
<div class="alert alert-warning d-flex align-items-center mb-3" style="border-radius: 0.75rem; border: none; border-left: 4px solid #eab308; background: #fefce8; color: #854d0e; font-size: 0.88rem;">
    <i class="bi bi-clock-history me-2" style="font-size: 1.1rem;"></i>
    <span><strong>{{ $pendingCount }}</strong> usuario(s) aguardando aprovacao.</span>
</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Funcao</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="fw-medium">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 30px; height: 30px; background: linear-gradient(135deg, #3b82f6, #6366f1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.7rem; font-weight: 700; flex-shrink: 0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="count-badge" style="font-size: 0.6rem; background: #dbeafe; color: #1e40af;">voce</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @php
                                $roleColors = [
                                    'admin' => 'background: #fee2e2; color: #991b1b;',
                                    'manager' => 'background: #fef3c7; color: #92400e;',
                                    'viewer' => 'background: #f1f5f9; color: #475569;',
                                ];
                            @endphp
                            <span class="status-badge" style="{{ $roleColors[$user->role] ?? '' }}">
                                {{ $user->roleName() }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'approved' => 'background: #dcfce7; color: #166534;',
                                    'pending' => 'background: #fef3c7; color: #92400e;',
                                    'suspended' => 'background: #fee2e2; color: #991b1b;',
                                ];
                                $statusIcons = [
                                    'approved' => 'bi-check-circle-fill',
                                    'pending' => 'bi-clock-fill',
                                    'suspended' => 'bi-x-circle-fill',
                                ];
                            @endphp
                            <span class="status-badge" style="{{ $statusColors[$user->status] ?? '' }}">
                                <i class="bi {{ $statusIcons[$user->status] ?? '' }} me-1" style="font-size: 0.65rem;"></i>
                                {{ $user->statusName() }}
                            </span>
                        </td>
                        <td class="timestamp-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- Approve button for pending users --}}
                                @if($user->status === 'pending' && $user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.approve', $user) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="Aprovar">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Suspend/Reactivate button --}}
                                @if($user->id !== auth()->id() && !$user->isAdmin())
                                    @if($user->status === 'approved')
                                        <form method="POST" action="{{ route('users.suspend', $user) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning btn-action" title="Suspender" onclick="return confirm('Tem certeza que deseja suspender o usuario {{ $user->name }}?')">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        </form>
                                    @elseif($user->status === 'suspended')
                                        <form method="POST" action="{{ route('users.approve', $user) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="Reativar">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Excluir" onclick="confirmDelete('{{ route('users.destroy', $user) }}', 'Tem certeza que deseja excluir o usuario {{ $user->name }}?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>Nenhum usuario encontrado.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
