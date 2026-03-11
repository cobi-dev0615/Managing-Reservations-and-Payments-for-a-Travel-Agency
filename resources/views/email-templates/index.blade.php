@extends('layouts.app')

@section('title', 'Templates de E-mail')
@section('page-title', 'Templates de E-mail')
@section('page-subtitle', 'Configure os modelos de e-mail enviados aos clientes')

@section('breadcrumb')
<span class="current">Templates de E-mail</span>
@endsection

@section('content')
<div class="page-header">
    <div></div>
    <a href="{{ route('email-templates.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo Template
    </a>
</div>

@forelse($types as $typeKey => $typeLabel)
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-envelope-paper text-muted"></i>
            <strong>{{ $typeLabel }}</strong>
            <span class="count-badge ms-auto">
                {{ isset($templates[$typeKey]) ? $templates[$typeKey]->count() : 0 }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="table-layout: fixed;">
                <colgroup>
                    <col style="width: 22%;">
                    <col style="width: 42%;">
                    <col style="width: 20%;">
                    <col style="width: 16%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Assunto</th>
                        <th>Tipo</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($templates[$typeKey]) && $templates[$typeKey]->count())
                        @foreach($templates[$typeKey] as $template)
                            <tr>
                                <td class="fw-medium">{{ $template->name }}</td>
                                <td class="text-muted" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $template->subject }}</td>
                                <td>
                                    <span class="count-badge">{{ $typeLabel }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('email-templates.edit', $template) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('email-templates.preview', $template) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-action" title="Preview">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Excluir" onclick="confirmDelete('{{ route('email-templates.destroy', $template) }}', 'Tem certeza que deseja excluir este template?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">
                                <div class="empty-state" style="padding: 2rem;">
                                    <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                                    <p>Nenhum template para este tipo.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-envelope-paper"></i>
                <p>Nenhum tipo de template configurado.</p>
            </div>
        </div>
    </div>
@endforelse
@endsection
