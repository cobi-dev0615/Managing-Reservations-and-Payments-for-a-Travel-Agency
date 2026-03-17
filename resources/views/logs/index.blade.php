@extends('layouts.app')

@section('title', __('messages.logs'))
@section('page-title', __('messages.logs'))
@section('page-subtitle', __('messages.logs_subtitle'))

@section('content')

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab === 'emails' ? 'active' : '' }}" href="{{ route('logs.index', array_merge(request()->except('tab', 'activity_page'), ['tab' => 'emails'])) }}">
            <i class="bi bi-envelope me-1"></i> {{ __('messages.email_logs') }}
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab === 'activity' ? 'active' : '' }}" href="{{ route('logs.index', array_merge(request()->except('tab', 'email_page'), ['tab' => 'activity'])) }}">
            <i class="bi bi-clock-history me-1"></i> {{ __('messages.activity_logs') }}
        </a>
    </li>
</ul>

@if($tab === 'emails')
    {{-- Email Logs Filters --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('logs.index') }}" class="row g-3 align-items-end">
            <input type="hidden" name="tab" value="emails">
            <div class="col-md-2">
                <label for="date_from" class="form-label">{{ __('messages.from') }}</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">{{ __('messages.until') }}</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label for="trigger_type" class="form-label">{{ __('messages.type') }}</label>
                <select name="trigger_type" id="trigger_type" class="form-select">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="manual" {{ request('trigger_type') == 'manual' ? 'selected' : '' }}>{{ __('messages.manual_type') }}</option>
                    <option value="automatico" {{ request('trigger_type') == 'automatico' ? 'selected' : '' }}>{{ __('messages.automatic_type') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">{{ __('messages.search') }}</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('messages.client_name_search') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1">
                    <i class="bi bi-search"></i> {{ __('messages.filter') }}
                </button>
                <a href="{{ route('logs.index', ['tab' => 'emails']) }}" class="btn btn-outline-secondary flex-grow-1">
                    <i class="bi bi-x-lg"></i> {{ __('messages.clear') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Email Logs Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.client') }}</th>
                        <th>{{ __('messages.template') }}</th>
                        <th>{{ __('messages.subject') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.type') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emailLogs as $log)
                        <tr>
                            <td><span class="timestamp-muted">{{ $log->sent_at ? $log->sent_at->format('d/m/Y') : '-' }}</span></td>
                            <td class="fw-medium">{{ $log->client->name ?? 'N/A' }}</td>
                            <td>{{ $log->template->name ?? 'N/A' }}</td>
                            <td class="text-muted">{{ Str::limit($log->subject, 50) }}</td>
                            <td>
                                <span class="status-badge status-{{ $log->status === 'enviado' ? 'enviado' : 'falhou' }}">
                                    {{ $log->status === 'enviado' ? __('messages.sent') : __('messages.failed') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $log->trigger_type === 'manual' ? 'manual' : 'automatico' }}">
                                    {{ $log->trigger_type === 'manual' ? __('messages.manual_type') : __('messages.automatic_type') }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-action" data-bs-toggle="modal" data-bs-target="#emailModal{{ $log->id }}" title="{{ __('messages.view_email_body') }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal for email body --}}
                        <div class="modal fade" id="emailModal{{ $log->id }}" tabindex="-1" aria-labelledby="emailModalLabel{{ $log->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="emailModalLabel{{ $log->id }}">
                                            <i class="bi bi-envelope me-1"></i> {{ $log->subject }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3" style="font-size: 0.85rem;">
                                            <div class="detail-row">
                                                <div class="detail-label">{{ __('messages.to_label') }}</div>
                                                <div class="detail-value">{{ $log->client->email ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">{{ __('messages.subject') }}</div>
                                                <div class="detail-value">{{ $log->subject }}</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">{{ __('messages.template') }}</div>
                                                <div class="detail-value">{{ $log->template->name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">{{ __('messages.sent_at') }}</div>
                                                <div class="detail-value">{{ $log->sent_at ? $log->sent_at->format('d/m/Y') : '-' }}</div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div style="white-space: pre-wrap; font-family: 'Segoe UI', system-ui, sans-serif; line-height: 1.6; font-size: 0.88rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.5rem;">{{ $log->body }}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-envelope"></i>
                                    <p>{{ __('messages.no_email_logs') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.pagination', ['paginator' => $emailLogs])

@else
    {{-- Activity Logs Filters --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('logs.index') }}" class="row g-3 align-items-end">
            <input type="hidden" name="tab" value="activity">
            <div class="col-md-2">
                <label for="date_from" class="form-label">{{ __('messages.from') }}</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">{{ __('messages.until') }}</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label for="entity_type" class="form-label">{{ __('messages.entity') }}</label>
                <select name="entity_type" id="entity_type" class="form-select">
                    <option value="">{{ __('messages.all_entities') }}</option>
                    @foreach($entityTypes as $type)
                        <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">{{ __('messages.search') }}</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('messages.action_search') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1">
                    <i class="bi bi-search"></i> {{ __('messages.filter') }}
                </button>
                <a href="{{ route('logs.index', ['tab' => 'activity']) }}" class="btn btn-outline-secondary flex-grow-1">
                    <i class="bi bi-x-lg"></i> {{ __('messages.clear') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Activity Logs Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.datetime') }}</th>
                        <th>{{ __('messages.action') }}</th>
                        <th>{{ __('messages.entity') }}</th>
                        <th>{{ __('messages.id') }}</th>
                        <th>{{ __('messages.ip') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                        <tr>
                            <td><span class="timestamp-muted">{{ $log->created_at->format('d/m/Y H:i') }}</span></td>
                            <td>{{ $log->action }}</td>
                            <td><span class="count-badge">{{ $log->entity_type }}</span></td>
                            <td class="text-muted">{{ $log->entity_id ?? '-' }}</td>
                            <td><span class="timestamp-muted">{{ $log->ip_address ?? '-' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-clock-history"></i>
                                    <p>{{ __('messages.no_activity_logs') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.pagination', ['paginator' => $activityLogs])
@endif
@endsection
