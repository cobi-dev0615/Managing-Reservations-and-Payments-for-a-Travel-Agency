@extends('layouts.app')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')

@section('breadcrumb')
<span class="current">Meu Perfil</span>
@endsection

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">

        {{-- Profile Info Card --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary"></i> Informacoes do Perfil
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6, #6366f1); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.4rem; font-weight: 700; flex-shrink: 0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                        <span class="text-muted" style="font-size: 0.82rem;">{{ $user->email }}</span>
                        <div class="mt-1">
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
                            <span class="timestamp-muted ms-2">Membro desde {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Salvar Alteracoes
                    </button>
                </form>
            </div>
        </div>

        {{-- Change Password Card --}}
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-lock text-warning"></i> Alterar Senha
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Digite sua senha atual" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimo 8 caracteres" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repita a nova senha" required>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key"></i> Alterar Senha
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
