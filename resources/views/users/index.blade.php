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

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Papel</th>
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
                        <td class="timestamp-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
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
                        <td colspan="5">
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
