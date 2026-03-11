@extends('layouts.app')

@section('title', 'Editar Tour')
@section('page-title', 'Editar Tour')

@section('breadcrumb')
<a href="{{ route('tours.index') }}">Tours</a> <span class="separator">/</span> <a href="{{ route('tours.show', $tour) }}">{{ $tour->name }}</a> <span class="separator">/</span> <span class="current">Editar</span>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-1"></i> Editar Tour: {{ $tour->name }}
            </div>
            <div class="card-body">
                <form action="{{ route('tours.update', $tour) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="section-heading">Informacoes Basicas</div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $tour->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label">Codigo <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $tour->code) }}" required>
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
                                <option value="grupo" {{ old('type', $tour->type) == 'grupo' ? 'selected' : '' }}>Grupo</option>
                                <option value="privado" {{ old('type', $tour->type) == 'privado' ? 'selected' : '' }}>Privado</option>
                                <option value="agencia" {{ old('type', $tour->type) == 'agencia' ? 'selected' : '' }}>Agencia</option>
                                <option value="influencer" {{ old('type', $tour->type) == 'influencer' ? 'selected' : '' }}>Influencer</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="default_currency" class="form-label">Moeda Padrao</label>
                            <select name="default_currency" id="default_currency" class="form-select @error('default_currency') is-invalid @enderror">
                                <option value="">Selecione...</option>
                                <option value="BRL" {{ old('default_currency', $tour->default_currency) == 'BRL' ? 'selected' : '' }}>BRL - Real</option>
                                <option value="USD" {{ old('default_currency', $tour->default_currency) == 'USD' ? 'selected' : '' }}>USD - Dolar</option>
                                <option value="EUR" {{ old('default_currency', $tour->default_currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            </select>
                            @error('default_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_travelers" class="form-label">Max. Viajantes</label>
                            <input type="number" name="max_travelers" id="max_travelers" class="form-control @error('max_travelers') is-invalid @enderror" value="{{ old('max_travelers', $tour->max_travelers) }}" min="1">
                            @error('max_travelers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="ativo" {{ old('status', $tour->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('status', $tour->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Observacoes</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $tour->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Salvar Alteracoes
                        </button>
                        <a href="{{ route('tours.show', $tour) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
