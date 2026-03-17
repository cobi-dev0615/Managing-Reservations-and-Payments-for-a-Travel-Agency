@extends('layouts.app')

@section('title', __('messages.profile'))
@section('page-title', __('messages.profile'))

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">

        {{-- Profile Info Card --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary"></i> {{ __('messages.profile_info') }}
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
                            <span class="timestamp-muted ms-2">{{ __('messages.member_since') }} {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> {{ __('messages.save_changes') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Change Password Card --}}
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-lock text-warning"></i> {{ __('messages.change_password') }}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">{{ __('messages.current_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="{{ __('messages.current_password_placeholder') }}" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('messages.new_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('messages.min_8_chars') }}" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_new_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('messages.repeat_new_password') }}" required>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key"></i> {{ __('messages.change_password') }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
