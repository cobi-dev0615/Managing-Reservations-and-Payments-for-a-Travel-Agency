@extends('layouts.app')

@section('title', 'Nova Reserva')
@section('page-title', 'Nova Reserva')

@section('breadcrumb')
<a href="{{ route('bookings.index') }}">Reservas</a> <span class="separator">/</span> <span class="current">Nova Reserva</span>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-1"></i> Nova Reserva
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf

                    <div class="section-heading">Cliente</div>

                    <div class="mb-3">
                        <label for="client_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">Selecione um cliente...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-heading">Tour</div>

                    <div class="card mb-3" style="background: #fafbfc;">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tour_type" id="tour_type_catalogo" value="catalogo" {{ old('tour_type', 'catalogo') == 'catalogo' ? 'checked' : '' }} onchange="toggleTourType()">
                                    <label class="form-check-label fw-medium" for="tour_type_catalogo">Catalogo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tour_type" id="tour_type_manual" value="manual" {{ old('tour_type') == 'manual' ? 'checked' : '' }} onchange="toggleTourType()">
                                    <label class="form-check-label fw-medium" for="tour_type_manual">Manual</label>
                                </div>
                            </div>

                            <div id="tour_catalogo_section">
                                <label for="tour_id" class="form-label">Tour do Catalogo</label>
                                <select name="tour_id" id="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">Selecione um tour...</option>
                                    @foreach($tours as $tour)
                                        <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>{{ $tour->name }} ({{ $tour->code }})</option>
                                    @endforeach
                                </select>
                                @error('tour_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="tour_manual_section" style="display: none;">
                                <label for="tour_manual" class="form-label">Nome do Tour (Manual)</label>
                                <input type="text" name="tour_manual" id="tour_manual" class="form-control @error('tour_manual') is-invalid @enderror" value="{{ old('tour_manual') }}" placeholder="Digite o nome do tour...">
                                @error('tour_manual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="section-heading">Detalhes da Reserva</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="start_date" class="form-label">Data Inicio <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="currency" class="form-label">Moeda <span class="text-danger">*</span></label>
                            <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                <option value="BRL" {{ old('currency') == 'BRL' ? 'selected' : '' }}>BRL - Real</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - Dolar</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="total_value" class="form-label">Valor Total <span class="text-danger">*</span></label>
                            <input type="number" name="total_value" id="total_value" class="form-control @error('total_value') is-invalid @enderror" value="{{ old('total_value') }}" step="0.01" min="0" required>
                            @error('total_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="discount_notes" class="form-label">Notas de Desconto</label>
                        <textarea name="discount_notes" id="discount_notes" class="form-control @error('discount_notes') is-invalid @enderror" rows="2" placeholder="Informacoes sobre descontos aplicados...">{{ old('discount_notes') }}</textarea>
                        @error('discount_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="num_travelers" class="form-label">Numero de Viajantes <span class="text-danger">*</span></label>
                            <input type="number" name="num_travelers" id="num_travelers" class="form-control @error('num_travelers') is-invalid @enderror" value="{{ old('num_travelers', 1) }}" min="1" required>
                            @error('num_travelers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pendente" {{ old('status', 'pendente') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="confirmado" {{ old('status') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                <option value="concluido" {{ old('status') == 'concluido' ? 'selected' : '' }}>Concluido</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Observacoes</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Notas internas sobre a reserva...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-heading">Parcelas Automaticas</div>

                    <div class="card mb-4" style="background: #fafbfc;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="num_installments" class="form-label">Numero de Parcelas</label>
                                    <input type="number" name="num_installments" id="num_installments" class="form-control" value="{{ old('num_installments', 0) }}" min="0">
                                    <div class="form-text">Deixe 0 para nao gerar parcelas automaticamente.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="installment_payment_method" class="form-label">Metodo de Pagamento</label>
                                    <select name="installment_payment_method" id="installment_payment_method" class="form-select">
                                        <option value="pix" {{ old('installment_payment_method', 'pix') == 'pix' ? 'selected' : '' }}>PIX</option>
                                        <option value="link" {{ old('installment_payment_method') == 'link' ? 'selected' : '' }}>Link</option>
                                        <option value="wise" {{ old('installment_payment_method') == 'wise' ? 'selected' : '' }}>Wise</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Salvar Reserva
                        </button>
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleTourType() {
        const catalogo = document.getElementById('tour_type_catalogo').checked;
        const catalogoSection = document.getElementById('tour_catalogo_section');
        const manualSection = document.getElementById('tour_manual_section');
        const tourIdSelect = document.getElementById('tour_id');
        const tourManualInput = document.getElementById('tour_manual');

        if (catalogo) {
            catalogoSection.style.display = 'block';
            manualSection.style.display = 'none';
            tourManualInput.value = '';
        } else {
            catalogoSection.style.display = 'none';
            manualSection.style.display = 'block';
            tourIdSelect.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleTourType();
    });
</script>
@endpush
