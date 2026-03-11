@extends('layouts.app')

@section('title', 'Configuracoes')
@section('page-title', 'Configuracoes')

@section('breadcrumb')
<span class="current">Configuracoes</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-gear-fill text-primary me-2" style="font-size: 1.1rem;"></i>Configuracoes</h4>
        <p class="page-header-subtitle mb-0">Ajuste as configuracoes do sistema</p>
    </div>
</div>

<form method="POST" action="{{ route('settings.update') }}">
    @csrf
    @method('PUT')

    <div class="accordion" id="settingsAccordion">

        {{-- Mensagens de Pagamento --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPaymentMessages">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePaymentMessages" aria-expanded="true" aria-controls="collapsePaymentMessages">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i> Mensagens de Pagamento
                </button>
            </h2>
            <div id="collapsePaymentMessages" class="accordion-collapse collapse show" aria-labelledby="headingPaymentMessages" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="mb-3">
                        <label for="msg_link" class="form-label">Mensagem para Link de Pagamento</label>
                        <textarea name="msg_link" id="msg_link" rows="4" class="form-control">{{ old('msg_link', $allSettings['msg_link'] ?? '') }}</textarea>
                        <div class="form-text">Mensagem enviada quando o metodo de pagamento e Link.</div>
                    </div>

                    <div class="mb-3">
                        <label for="msg_pix" class="form-label">Mensagem para PIX</label>
                        <textarea name="msg_pix" id="msg_pix" rows="4" class="form-control">{{ old('msg_pix', $allSettings['msg_pix'] ?? '') }}</textarea>
                        <div class="form-text">Mensagem enviada quando o metodo de pagamento e PIX.</div>
                    </div>

                    <div class="mb-3">
                        <label for="msg_wise" class="form-label">Mensagem para Wise</label>
                        <textarea name="msg_wise" id="msg_wise" rows="4" class="form-control">{{ old('msg_wise', $allSettings['msg_wise'] ?? '') }}</textarea>
                        <div class="form-text">Mensagem enviada quando o metodo de pagamento e Wise.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Instrucoes PIX --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePix" aria-expanded="false" aria-controls="collapsePix">
                    <i class="bi bi-qr-code me-2 text-success"></i> Instrucoes PIX
                </button>
            </h2>
            <div id="collapsePix" class="accordion-collapse collapse" aria-labelledby="headingPix" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="mb-3">
                        <label for="pix_instructions" class="form-label">Instrucoes PIX</label>
                        <textarea name="pix_instructions" id="pix_instructions" rows="6" class="form-control">{{ old('pix_instructions', $allSettings['pix_instructions'] ?? '') }}</textarea>
                        <div class="form-text">Estas instrucoes serao incluidas nos e-mails usando o placeholder <code class="text-primary">{pix_instructions}</code>.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Automacao de E-mails --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAutomation">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAutomation" aria-expanded="false" aria-controls="collapseAutomation">
                    <i class="bi bi-robot me-2 text-info"></i> Automacao de E-mails
                </button>
            </h2>
            <div id="collapseAutomation" class="accordion-collapse collapse" aria-labelledby="headingAutomation" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <p class="text-muted small mb-3">Configure quais e-mails automaticos devem ser enviados com base na data de vencimento das parcelas.</p>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_7days_before" id="auto_7days_before" value="1"
                            {{ old('auto_7days_before', $allSettings['auto_7days_before'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_7days_before">7 dias antes do vencimento</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_3days_before" id="auto_3days_before" value="1"
                            {{ old('auto_3days_before', $allSettings['auto_3days_before'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_3days_before">3 dias antes do vencimento</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_due_date" id="auto_due_date" value="1"
                            {{ old('auto_due_date', $allSettings['auto_due_date'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_due_date">Dia do vencimento</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_1day_after" id="auto_1day_after" value="1"
                            {{ old('auto_1day_after', $allSettings['auto_1day_after'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_1day_after">1 dia apos o vencimento</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_7days_after" id="auto_7days_after" value="1"
                            {{ old('auto_7days_after', $allSettings['auto_7days_after'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_7days_after">7 dias apos o vencimento</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Configuracao SMTP --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSMTP">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSMTP" aria-expanded="false" aria-controls="collapseSMTP">
                    <i class="bi bi-envelope-at me-2 text-warning"></i> Configuracao SMTP
                </button>
            </h2>
            <div id="collapseSMTP" class="accordion-collapse collapse" aria-labelledby="headingSMTP" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="smtp_host" class="form-label">Host SMTP</label>
                            <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="{{ old('smtp_host', $allSettings['smtp_host'] ?? '') }}" placeholder="smtp.gmail.com">
                        </div>
                        <div class="col-md-4">
                            <label for="smtp_port" class="form-label">Porta</label>
                            <input type="text" name="smtp_port" id="smtp_port" class="form-control" value="{{ old('smtp_port', $allSettings['smtp_port'] ?? '') }}" placeholder="587">
                        </div>
                        <div class="col-md-6">
                            <label for="smtp_username" class="form-label">Usuario</label>
                            <input type="text" name="smtp_username" id="smtp_username" class="form-control" value="{{ old('smtp_username', $allSettings['smtp_username'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="smtp_password" class="form-label">Senha</label>
                            <input type="password" name="smtp_password" id="smtp_password" class="form-control" value="{{ old('smtp_password', $allSettings['smtp_password'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="smtp_encryption" class="form-label">Criptografia</label>
                            <select name="smtp_encryption" id="smtp_encryption" class="form-select">
                                <option value="tls" {{ old('smtp_encryption', $allSettings['smtp_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption', $allSettings['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="smtp_from_name" class="form-label">Nome do Remetente</label>
                            <input type="text" name="smtp_from_name" id="smtp_from_name" class="form-control" value="{{ old('smtp_from_name', $allSettings['smtp_from_name'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="smtp_from_email" class="form-label">E-mail do Remetente</label>
                            <input type="email" name="smtp_from_email" id="smtp_from_email" class="form-control" value="{{ old('smtp_from_email', $allSettings['smtp_from_email'] ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cron --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCron">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCron" aria-expanded="false" aria-controls="collapseCron">
                    <i class="bi bi-clock-history me-2 text-secondary"></i> Cron
                </button>
            </h2>
            <div id="collapseCron" class="accordion-collapse collapse" aria-labelledby="headingCron" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="alert alert-info mb-0" style="background: #eff6ff; color: #1e40af; border-left: 4px solid #3b82f6; border-radius: 0.5rem;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill mt-1"></i>
                            <div>
                                <strong>Agendamento Cron</strong>
                                <p class="mb-2 mt-2">O sistema de automacao de e-mails e executado diariamente via cron job. Configure o seguinte cron no servidor:</p>
                                <code class="d-block bg-dark text-light p-2 rounded" style="font-size: 0.82rem;">* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1</code>
                                <p class="mb-0 mt-2 small" style="opacity: 0.8;">O Laravel Scheduler executara as tarefas automaticas conforme configurado na secao de Automacao acima.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-lg"></i> Salvar Configuracoes
        </button>
    </div>
</form>
@endsection
