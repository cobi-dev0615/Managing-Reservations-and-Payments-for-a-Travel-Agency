@extends('layouts.app')

@section('title', __('messages.edit_email_template'))
@section('page-title', __('messages.edit_email_template'))

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-1"></i> {{ __('messages.edit_email_template') }}: {{ $emailTemplate->name }}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('email-templates.update', $emailTemplate) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __('messages.type') }} <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">{{ __('messages.select_type') }}</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $emailTemplate->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $emailTemplate->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">{{ __('messages.subject') }} <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $emailTemplate->subject) }}">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="body" class="form-label">{{ __('messages.email_body') }} <span class="text-danger">*</span></label>
                        <textarea name="body" id="body" rows="12" class="form-control @error('body') is-invalid @enderror" style="font-family: 'Courier New', Courier, monospace; font-size: 0.85rem; line-height: 1.6;">{{ old('body', $emailTemplate->body) }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> {{ __('messages.save_changes') }}
                        </button>
                        <a href="{{ route('email-templates.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="position: sticky; top: 76px;">
            <div class="card-header">
                <i class="bi bi-info-circle me-1"></i> {{ __('messages.available_placeholders') }}
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">{{ __('messages.placeholders_help') }}</p>
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        @foreach($placeholders as $placeholder => $description)
                            <tr>
                                <td><code class="text-primary" style="font-size: 0.78rem;">{{ $placeholder }}</code></td>
                                <td class="text-muted" style="font-size: 0.75rem;">{{ $description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
