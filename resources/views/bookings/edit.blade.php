@extends('layouts.app')

@section('title', __('messages.edit_booking') . ' #' . $booking->id)
@section('page-title', __('messages.edit_booking'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-1"></i> {{ __('messages.edit_booking') }} #{{ $booking->id }}
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="section-heading">{{ __('messages.client') }}</div>

                    <div class="mb-3">
                        <label for="client_id" class="form-label">{{ __('messages.client') }} <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">{{ __('messages.select_client') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $booking->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-heading">{{ __('messages.tour') }}</div>

                    <div class="card mb-3" style="background: #fafbfc;">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tour_type" id="tour_type_catalogo" value="catalogo" {{ old('tour_type', $booking->tour_id ? 'catalogo' : 'manual') == 'catalogo' ? 'checked' : '' }} onchange="toggleTourType()">
                                    <label class="form-check-label fw-medium" for="tour_type_catalogo">{{ __('messages.catalog') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tour_type" id="tour_type_manual" value="manual" {{ old('tour_type', $booking->tour_id ? 'catalogo' : 'manual') == 'manual' ? 'checked' : '' }} onchange="toggleTourType()">
                                    <label class="form-check-label fw-medium" for="tour_type_manual">{{ __('messages.manual') }}</label>
                                </div>
                            </div>

                            <div id="tour_catalogo_section">
                                <label for="tour_id" class="form-label">{{ __('messages.catalog_tour') }}</label>
                                <select name="tour_id" id="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">{{ __('messages.select_tour') }}</option>
                                    @foreach($tours as $tour)
                                        <option value="{{ $tour->id }}" {{ old('tour_id', $booking->tour_id) == $tour->id ? 'selected' : '' }}>{{ $tour->name }} ({{ $tour->code }})</option>
                                    @endforeach
                                </select>
                                @error('tour_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="tour_manual_section" style="display: none;">
                                <label for="tour_manual" class="form-label">{{ __('messages.manual_tour_name') }}</label>
                                <input type="text" name="tour_manual" id="tour_manual" class="form-control @error('tour_manual') is-invalid @enderror" value="{{ old('tour_manual', $booking->tour_manual) }}" placeholder="{{ __('messages.manual_tour_placeholder') }}">
                                @error('tour_manual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="section-heading">{{ __('messages.booking_details') }}</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="start_date" class="form-label">{{ __('messages.start_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $booking->start_date ? $booking->start_date->format('Y-m-d') : '') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="currency" class="form-label">{{ __('messages.currency') }} <span class="text-danger">*</span></label>
                            <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="BRL" {{ old('currency', $booking->currency) == 'BRL' ? 'selected' : '' }}>{{ __('messages.brl_real') }}</option>
                                <option value="USD" {{ old('currency', $booking->currency) == 'USD' ? 'selected' : '' }}>{{ __('messages.usd_dollar') }}</option>
                                <option value="EUR" {{ old('currency', $booking->currency) == 'EUR' ? 'selected' : '' }}>{{ __('messages.eur_euro') }}</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="total_value" class="form-label">{{ __('messages.total_value') }} <span class="text-danger">*</span></label>
                            <input type="number" name="total_value" id="total_value" class="form-control @error('total_value') is-invalid @enderror" value="{{ old('total_value', $booking->total_value) }}" step="0.01" min="0" required>
                            @error('total_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="discount_notes" class="form-label">{{ __('messages.discount_notes') }}</label>
                        <textarea name="discount_notes" id="discount_notes" class="form-control @error('discount_notes') is-invalid @enderror" rows="2">{{ old('discount_notes', $booking->discount_notes) }}</textarea>
                        @error('discount_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="num_travelers" class="form-label">{{ __('messages.num_travelers') }} <span class="text-danger">*</span></label>
                            <input type="number" name="num_travelers" id="num_travelers" class="form-control @error('num_travelers') is-invalid @enderror" value="{{ old('num_travelers', $booking->num_travelers) }}" min="1" required>
                            @error('num_travelers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">{{ __('messages.status') }}</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pendente" {{ old('status', $booking->status) == 'pendente' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
                                <option value="confirmado" {{ old('status', $booking->status) == 'confirmado' ? 'selected' : '' }}>{{ __('messages.status_confirmed') }}</option>
                                <option value="cancelado" {{ old('status', $booking->status) == 'cancelado' ? 'selected' : '' }}>{{ __('messages.status_cancelled') }}</option>
                                <option value="concluido" {{ old('status', $booking->status) == 'concluido' ? 'selected' : '' }}>{{ __('messages.status_completed') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> {{ __('messages.save_changes') }}
                        </button>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
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
