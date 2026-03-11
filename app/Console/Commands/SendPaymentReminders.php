<?php

namespace App\Console\Commands;

use App\Helpers\PlaceholderHelper;
use App\Mail\PaymentNotification;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Installment;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    protected $signature = 'email:send-reminders';
    protected $description = 'Send payment reminder emails based on installment due dates';

    // Trigger points: days relative to due_date (negative = before, positive = after)
    private array $triggers = [
        -7 => ['setting' => 'auto_7days_before', 'template_type' => 'lembrete_pagamento', 'label' => '7 dias antes'],
        -3 => ['setting' => 'auto_3days_before', 'template_type' => 'lembrete_pagamento', 'label' => '3 dias antes'],
         0 => ['setting' => 'auto_due_date',     'template_type' => 'lembrete_pagamento', 'label' => 'dia do vencimento'],
         1 => ['setting' => 'auto_1day_after',    'template_type' => 'aviso_atraso',       'label' => '1 dia após'],
         7 => ['setting' => 'auto_7days_after',   'template_type' => 'aviso_atraso',       'label' => '7 dias após'],
    ];

    public function handle(): int
    {
        $today = Carbon::today();
        $sent = 0;
        $skipped = 0;

        $this->info("Executando verificação de e-mails automáticos - {$today->format('d/m/Y')}");

        foreach ($this->triggers as $daysOffset => $config) {
            // Check if this trigger is enabled in settings
            $enabled = Setting::get($config['setting'], '1');
            if (!$enabled || $enabled === '0') {
                $this->line("  [{$config['label']}] Desabilitado - pulando");
                continue;
            }

            // Find the template
            $template = EmailTemplate::where('type', $config['template_type'])->first();
            if (!$template) {
                $this->warn("  [{$config['label']}] Template '{$config['template_type']}' não encontrado - pulando");
                continue;
            }

            // Calculate target due date
            $targetDate = $today->copy()->addDays(-$daysOffset);

            // Find matching installments
            $installments = Installment::where('status', '!=', 'pago')
                ->whereDate('due_date', $targetDate)
                ->with('booking.client', 'booking.tour')
                ->get();

            $this->line("  [{$config['label']}] Vencimento {$targetDate->format('d/m/Y')}: {$installments->count()} parcela(s)");

            foreach ($installments as $installment) {
                // Skip if email is paused for this installment
                if ($installment->email_paused) {
                    $this->line("    Parcela #{$installment->id} - e-mails pausados, pulando");
                    $skipped++;
                    continue;
                }

                // No-repeat rule: don't send same template type if it was the last one sent
                if ($installment->last_email_template_id !== null && (int)$installment->last_email_template_id === (int)$template->id) {
                    $this->line("    Parcela #{$installment->id} - mesmo template já enviado, pulando");
                    $skipped++;
                    continue;
                }

                // Skip if no client email
                $client = $installment->booking->client;
                if (!$client || !$client->email) {
                    $this->line("    Parcela #{$installment->id} - cliente sem e-mail, pulando");
                    $skipped++;
                    continue;
                }

                // Replace placeholders
                $subject = PlaceholderHelper::replace($template->subject, $installment);
                $body = PlaceholderHelper::replace($template->body, $installment);

                // Send email via SMTP
                $emailStatus = 'enviado';
                try {
                    Mail::to($client->email)->send(new PaymentNotification($subject, $body));
                } catch (\Exception $e) {
                    $emailStatus = 'falhou';
                    $this->error("    Parcela #{$installment->id} -> FALHOU: {$e->getMessage()}");
                }

                // Log the email
                EmailLog::create([
                    'installment_id' => $installment->id,
                    'client_id' => $client->id,
                    'template_id' => $template->id,
                    'subject' => $subject,
                    'body' => $body,
                    'status' => $emailStatus,
                    'trigger_type' => 'automatico',
                    'sent_at' => now(),
                ]);

                // Update installment tracking
                $installment->update([
                    'last_email_sent_at' => now(),
                    'last_email_template_id' => $template->id,
                ]);

                $this->info("    Parcela #{$installment->id} -> {$client->email} ({$config['label']}) [{$emailStatus}]");
                $sent++;
            }
        }

        $this->newLine();
        $this->info("Concluído: {$sent} e-mail(s) enviado(s), {$skipped} pulado(s)");

        return Command::SUCCESS;
    }
}
