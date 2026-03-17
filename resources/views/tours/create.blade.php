@extends('layouts.app')

@section('title', __('messages.new_tour'))
@section('page-title', __('messages.new_tour'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-1"></i> {{ __('messages.new_tour') }}
            </div>
            <div class="card-body">
                <form action="{{ route('tours.store') }}" method="POST">
                    @csrf

                    <div class="section-heading">{{ __('messages.basic_info') }}</div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('messages.tour_name_placeholder') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label">{{ __('messages.code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="{{ __('messages.code_placeholder') }}" required>
                            <div class="form-text">{{ __('messages.code_example') }}</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="section-heading">{{ __('messages.configuration') }}</div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label">{{ __('messages.type') }} <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="grupo" {{ old('type') == 'grupo' ? 'selected' : '' }}>{{ __('messages.group') }}</option>
                                <option value="privado" {{ old('type') == 'privado' ? 'selected' : '' }}>{{ __('messages.private') }}</option>
                                <option value="agencia" {{ old('type') == 'agencia' ? 'selected' : '' }}>{{ __('messages.agency') }}</option>
                                <option value="influencer" {{ old('type') == 'influencer' ? 'selected' : '' }}>{{ __('messages.influencer') }}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="default_currency" class="form-label">{{ __('messages.default_currency') }}</label>
                            <select name="default_currency" id="default_currency" class="form-select @error('default_currency') is-invalid @enderror">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="BRL" {{ old('default_currency') == 'BRL' ? 'selected' : '' }}>{{ __('messages.brl_real') }}</option>
                                <option value="USD" {{ old('default_currency') == 'USD' ? 'selected' : '' }}>{{ __('messages.usd_dollar') }}</option>
                                <option value="EUR" {{ old('default_currency') == 'EUR' ? 'selected' : '' }}>{{ __('messages.eur_euro') }}</option>
                            </select>
                            @error('default_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_travelers" class="form-label">{{ __('messages.max_travelers') }}</label>
                            <input type="number" name="max_travelers" id="max_travelers" class="form-control @error('max_travelers') is-invalid @enderror" value="{{ old('max_travelers') }}" placeholder="{{ __('messages.max_travelers_placeholder') }}" min="1">
                            @error('max_travelers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('messages.status') }}</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="{{ __('messages.notes_placeholder') }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> {{ __('messages.save_tour') }}
                        </button>
                        <a href="{{ route('tours.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
