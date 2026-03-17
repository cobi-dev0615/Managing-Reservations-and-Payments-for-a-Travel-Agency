@extends('layouts.app')

@section('title', __('messages.booking_number', ['id' => $booking->id]))
@section('page-title', __('messages.booking_number', ['id' => $booking->id]))

@section('page-subtitle')
{{ $booking->client->name ?? 'N/A' }} &middot; {{ $booking->tour_name }}
<span class="status-badge status-{{ $booking->status }} ms-2">{{ ucfirst($booking->status) }}</span>
@endsection

@section('content')
<div class="page-header">
    <div></div>
    <div class="d-flex gap-2">
        @if(auth()->user()->canManage())
        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i> {{ __('messages.edit') }}
        </a>
        @endif
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>
</div>

{{-- Booking Details --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-info-circle me-1"></i> {{ __('messages.booking_details') }}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.id') }}</div>
                    <div class="detail-value">#{{ $booking->id }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.client') }}</div>
                    <div class="detail-value">
                        @if($booking->client)
                            <a href="{{ route('clients.show', $booking->client) }}" class="text-decoration-none fw-medium">{{ $booking->client->name }}</a>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.tour') }}</div>
                    <div class="detail-value">
                        @if($booking->tour)
                            <a href="{{ route('tours.show', $booking->tour) }}" class="text-decoration-none fw-medium">{{ $booking->tour->name }}</a>
                        @else
                            {{ $booking->tour_manual ?? 'N/A' }}
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.start_date') }}</div>
                    <div class="detail-value">{{ $booking->start_date ? $booking->start_date->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.currency') }}</div>
                    <div class="detail-value"><span class="currency-badge currency-{{ $booking->currency }}">{{ $booking->currency }}</span></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.total_value') }}</div>
                    <div class="detail-value"><strong style="font-size: 1rem;">{{ number_format($booking->total_value, 2, ',', '.') }}</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.travelers') }}</div>
                    <div class="detail-value">{{ $booking->num_travelers }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">{{ __('messages.status') }}</div>
                    <div class="detail-value"><span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></div>
                </div>
            </div>
        </div>
        @if($booking->discount_notes)
            <hr class="my-3">
            <div>
                <div class="detail-label mb-1">{{ __('messages.discount_notes') }}</div>
                <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $booking->discount_notes }}</p>
            </div>
        @endif
        @if($booking->notes)
            <hr class="my-3">
            <div>
                <div class="detail-label mb-1">{{ __('messages.notes') }}</div>
                <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $booking->notes }}</p>
            </div>
        @endif
    </div>
</div>

{{-- Installments --}}
@php
    $totalInstallments = $booking->installments->sum('amount');
    $difference = $booking->total_value - $totalInstallments;
    $nextNumber = $booking->installments->max('installment_number') + 1;
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-credit-card me-1"></i> {{ __('messages.installments') }}</span>
        @if(auth()->user()->canManage())
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addInstallmentModal">
            <i class="bi bi-plus-lg"></i> {{ __('messages.add_installment') }}
        </button>
        @endif
    </div>

    {{-- Summary --}}
    <div class="card-body border-bottom" style="background: #fafbfc;">
        <div class="row text-center">
            <div class="col-md-4">
                <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">{{ __('messages.total_installments') }}</small>
                <strong class="d-block mt-1" style="font-size: 1.1rem;">{{ $booking->installments->count() }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">{{ __('messages.installments_sum') }}</small>
                <strong class="d-block mt-1" style="font-size: 1.1rem;">{{ number_format($totalInstallments, 2, ',', '.') }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">{{ __('messages.total_difference') }}</small>
                <strong class="d-block mt-1 {{ $difference != 0 ? 'text-danger' : 'text-success' }}" style="font-size: 1.1rem;">
                    {{ number_format($difference, 2, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>

    {{-- Installments Table --}}
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.value') }}</th>
                    <th>{{ __('messages.due_date') }}</th>
                    <th>{{ __('messages.method') }}</th>
                    <th>{{ __('messages.link') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.last_email') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($booking->installments as $installment)
                    @php
                        $resolvedStatus = $installment->resolveStatus();
                    @endphp
                    <tr>
                        <td class="fw-medium">{{ $installment->installment_number }}</td>
                        <td class="fw-medium">{{ number_format($installment->amount, 2, ',', '.') }}</td>
                        <td>{{ $installment->due_date->format('d/m/Y') }}</td>
                        <td><span class="count-badge">{{ strtoupper($installment->payment_method) }}</span></td>
                        <td>
                            @if($installment->payment_link)
                                <a href="{{ $installment->payment_link }}" target="_blank" class="btn btn-sm btn-outline-info btn-action">
                                    <i class="bi bi-link-45deg"></i> {{ __('messages.link') }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $resolvedStatus }}">{{ ucfirst(str_replace('_', ' ', $resolvedStatus)) }}</span>
                        </td>
                        <td>
                            @if($installment->last_email_sent_at)
                                <small class="timestamp-muted">{{ $installment->last_email_sent_at->format('d/m/Y H:i') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->canManage())
                            <div class="d-flex gap-1 flex-wrap">
                                @if($resolvedStatus !== 'pago')
                                    <form action="{{ route('installments.mark-paid', $installment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="{{ __('messages.mark_as_paid') }}">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn btn-sm btn-outline-secondary btn-action" title="{{ __('messages.edit') }}" onclick="openEditModal({{ $installment->id }}, {{ $installment->installment_number }}, '{{ $installment->amount }}', '{{ $installment->due_date->format('Y-m-d') }}', '{{ $installment->payment_method }}', '{{ $installment->payment_link ?? '' }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('installments.resend-email', $installment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info btn-action" title="{{ __('messages.resend_email') }}">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </form>

                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="{{ __('messages.delete') }}" onclick="confirmDelete('{{ route('installments.destroy', $installment) }}', '{{ __('messages.confirm_delete_installment') }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-credit-card"></i>
                                <p>{{ __('messages.no_installments') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add Installment Modal --}}
<div class="modal fade" id="addInstallmentModal" tabindex="-1" aria-labelledby="addInstallmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('installments.store', $booking) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addInstallmentModalLabel"><i class="bi bi-plus-circle me-1"></i> {{ __('messages.add_installment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_installment_number" class="form-label">{{ __('messages.installment_number') }}</label>
                        <input type="number" name="installment_number" id="add_installment_number" class="form-control" value="{{ $nextNumber }}" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="add_amount" class="form-label">{{ __('messages.value') }} <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="add_amount" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_due_date" class="form-label">{{ __('messages.due_date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" id="add_due_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_payment_method" class="form-label">{{ __('messages.payment_method') }} <span class="text-danger">*</span></label>
                        <select name="payment_method" id="add_payment_method" class="form-select" required onchange="toggleAddPaymentLink()">
                            <option value="pix">PIX</option>
                            <option value="link">Link</option>
                            <option value="wise">Wise</option>
                        </select>
                    </div>
                    <div class="mb-3" id="add_payment_link_group" style="display: none;">
                        <label for="add_payment_link" class="form-label">{{ __('messages.payment_link') }}</label>
                        <input type="text" name="payment_link" id="add_payment_link" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Installment Modal --}}
<div class="modal fade" id="editInstallmentModal" tabindex="-1" aria-labelledby="editInstallmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editInstallmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editInstallmentModalLabel"><i class="bi bi-pencil me-1"></i> {{ __('messages.edit_installment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_installment_number" class="form-label">{{ __('messages.installment_number') }}</label>
                        <input type="number" name="installment_number" id="edit_installment_number" class="form-control" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_amount" class="form-label">{{ __('messages.value') }} <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="edit_amount" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_due_date" class="form-label">{{ __('messages.due_date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" id="edit_due_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_payment_method" class="form-label">{{ __('messages.payment_method') }} <span class="text-danger">*</span></label>
                        <select name="payment_method" id="edit_payment_method" class="form-select" required onchange="toggleEditPaymentLink()">
                            <option value="pix">PIX</option>
                            <option value="link">Link</option>
                            <option value="wise">Wise</option>
                        </select>
                    </div>
                    <div class="mb-3" id="edit_payment_link_group" style="display: none;">
                        <label for="edit_payment_link" class="form-label">{{ __('messages.payment_link') }}</label>
                        <input type="text" name="payment_link" id="edit_payment_link" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAddPaymentLink() {
        const method = document.getElementById('add_payment_method').value;
        const group = document.getElementById('add_payment_link_group');
        group.style.display = method === 'link' ? 'block' : 'none';
        if (method !== 'link') {
            document.getElementById('add_payment_link').value = '';
        }
    }

    function toggleEditPaymentLink() {
        const method = document.getElementById('edit_payment_method').value;
        const group = document.getElementById('edit_payment_link_group');
        group.style.display = method === 'link' ? 'block' : 'none';
        if (method !== 'link') {
            document.getElementById('edit_payment_link').value = '';
        }
    }

    function openEditModal(id, number, amount, dueDate, method, link) {
        const form = document.getElementById('editInstallmentForm');
        form.action = '/installments/' + id;

        document.getElementById('edit_installment_number').value = number;
        document.getElementById('edit_amount').value = amount;
        document.getElementById('edit_due_date').value = dueDate;
        document.getElementById('edit_payment_method').value = method;
        document.getElementById('edit_payment_link').value = link;

        toggleEditPaymentLink();

        const modal = new bootstrap.Modal(document.getElementById('editInstallmentModal'));
        modal.show();
    }
</script>
@endpush
