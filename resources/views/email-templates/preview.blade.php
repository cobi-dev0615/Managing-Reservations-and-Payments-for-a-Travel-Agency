@extends('layouts.app')

@section('title', __('messages.preview') . ' - ' . $emailTemplate->name)
@section('page-title', __('messages.template_preview'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ __('messages.preview') }}: {{ $emailTemplate->name }}</h4>
    </div>
    <a href="{{ route('email-templates.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-info text-dark">{{ \App\Models\EmailTemplate::$types[$emailTemplate->type] ?? $emailTemplate->type }}</span>
                <strong class="ms-2">{{ $emailTemplate->name }}</strong>
            </div>
            <small class="text-muted">{{ __('messages.sample_data') }}</small>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label text-muted small">{{ __('messages.subject_label') }}</label>
            <div class="p-2 bg-light rounded">
                <strong>{{ $subject }}</strong>
            </div>
        </div>

        <div>
            <label class="form-label text-muted small">{{ __('messages.email_body_label') }}</label>
            <div class="p-3 bg-light rounded" style="white-space: pre-wrap; font-family: 'Segoe UI', system-ui, sans-serif; line-height: 1.6;">{{ $body }}</div>
        </div>
    </div>
</div>
@endsection
