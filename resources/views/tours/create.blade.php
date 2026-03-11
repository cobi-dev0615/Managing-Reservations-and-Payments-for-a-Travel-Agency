@extends('layouts.app')

@section('title', 'Novo Tour')
@section('page-title', 'Novo Tour')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-1"></i> Novo Tour
            </div>
            <div class="card-body">
                <form action="{{ route('tours.store') }}" method="POST">
                    @csrf

                    <div class="section-heading">Informacoes Basicas</div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Classica Italia 2026" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label">Codigo <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="EUR-CLA-2026" required>
                            <div class="form-text">Ex: EUR-CLA-2026</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="section-heading">Configuracao</div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                <option value="grupo" {{ old('type') == 'grupo' ? 'selected' : '' }}>Grupo</option>
                                <option value="privado" {{ old('type') == 'privado' ? 'selected' : '' }}>Privado</option>
                                <option value="agencia" {{ old('type') == 'agencia' ? 'selected' : '' }}>Agencia</option>
                                <option value="influencer" {{ old('type') == 'influencer' ? 'selected' : '' }}>Influencer</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="default_currency" class="form-label">Moeda Padrao</label>
                            <select name="default_currency" id="default_currency" class="form-select @error('default_currency') is-invalid @enderror">
                                <option value="">Selecione...</option>
                                <option value="BRL" {{ old('default_currency') == 'BRL' ? 'selected' : '' }}>BRL - Real</option>
                                <option value="USD" {{ old('default_currency') == 'USD' ? 'selected' : '' }}>USD - Dolar</option>
                                <option value="EUR" {{ old('default_currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            </select>
                            @error('default_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_travelers" class="form-label">Max. Viajantes</label>
                            <input type="number" name="max_travelers" id="max_travelers" class="form-control @error('max_travelers') is-invalid @enderror" value="{{ old('max_travelers') }}" placeholder="Ex: 30" min="1">
                            @error('max_travelers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Observacoes</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Notas internas sobre o tour...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Salvar Tour
                        </button>
                        <a href="{{ route('tours.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
