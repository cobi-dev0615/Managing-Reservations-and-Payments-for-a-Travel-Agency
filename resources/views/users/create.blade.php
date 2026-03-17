@extends('layouts.app')

@section('title', __('messages.new_user'))
@section('page-title', __('messages.new_user'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus me-1"></i> {{ __('messages.new_user') }}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('messages.full_name_placeholder') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('messages.user_email_placeholder') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('messages.user_role') }} <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                            @foreach(\App\Models\User::translatedRoles() as $key => $label)
                                <option value="{{ $key }}" {{ old('role', 'viewer') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {!! __('messages.role_admin_desc') !!} | {!! __('messages.role_manager_short') !!} | {!! __('messages.role_viewer_short') !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('messages.password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('messages.min_8_chars') }}" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('messages.repeat_password') }}" required>
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> {{ __('messages.create_user') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
