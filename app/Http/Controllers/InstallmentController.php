<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Installment;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\ActivityLog;
use App\Helpers\PlaceholderHelper;
use App\Mail\PaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InstallmentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'installment_number' => 'nullable|integer|min:1',
            'amount'             => 'required|numeric|min:0',
            'due_date'           => 'required|date',
            'payment_method'     => 'required|in:link,pix,wise',
            'payment_link'       => 'nullable|string|max:2048',
        ]);

        $validated['booking_id'] = $booking->id;
        $validated['status'] = 'pendente';

        $installment = Installment::create($validated);

        $installment->status = $installment->resolveStatus();
        $installment->save();

        ActivityLog::log('criou parcela', 'Installment', $installment->id, [
            'booking_id'         => $booking->id,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Parcela adicionada com sucesso.');
    }

    public function update(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'installment_number' => 'nullable|integer|min:1',
            'amount'             => 'required|numeric|min:0',
            'due_date'           => 'required|date',
            'payment_method'     => 'required|in:link,pix,wise',
            'payment_link'       => 'nullable|string|max:2048',
        ]);

        $installment->update($validated);

        $installment->status = $installment->resolveStatus();
        $installment->save();

        ActivityLog::log('atualizou parcela', 'Installment', $installment->id, [
            'booking_id'         => $installment->booking_id,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ]);

        return redirect()->route('bookings.show', $installment->booking_id)->with('success', 'Parcela atualizada com sucesso.');
    }

    public function markPaid(Installment $installment)
    {
        $installment->update([
            'status'  => 'pago',
            'paid_at' => now(),
        ]);

        ActivityLog::log('marcou como pago', 'Installment', $installment->id, [
            'booking_id'         => $installment->booking_id,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ]);

        return redirect()->back()->with('success', 'Parcela marcada como paga.');
    }

    public function destroy(Installment $installment)
    {
        $bookingId = $installment->booking_id;
        $details = [
            'booking_id'         => $bookingId,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ];

        $installment->delete();

        ActivityLog::log('excluiu parcela', 'Installment', null, $details);

        return redirect()->route('bookings.show', $bookingId)->with('success', 'Parcela excluída com sucesso.');
    }

    public function toggleEmail(Installment $installment)
    {
        $installment->update([
            'email_paused' => !$installment->email_paused,
        ]);

        $action = $installment->email_paused ? 'pausou e-mails' : 'reativou e-mails';

        ActivityLog::log($action, 'Installment', $installment->id, [
            'booking_id'         => $installment->booking_id,
            'installment_number' => $installment->installment_number,
            'email_paused'       => $installment->email_paused,
        ]);

        $msg = $installment->email_paused
            ? 'E-mails pausados para esta parcela.'
            : 'E-mails reativados para esta parcela.';

        return redirect()->back()->with('success', $msg);
    }

    public function resendEmail(Installment $installment)
    {
        $installment->load(['booking.client', 'booking.tour']);

        $resolvedStatus = $installment->resolveStatus();

        // Choose template based on status
        $templateType = 'lembrete_pagamento';
        if ($resolvedStatus === 'atrasado') {
            $templateType = 'aviso_atraso';
        }

        $template = EmailTemplate::where('type', $templateType)->first();

        if (!$template) {
            return redirect()->back()->with('error', 'Template de e-mail não encontrado para o tipo: ' . $templateType);
        }

        $subject = PlaceholderHelper::replace($template->subject, $installment);
        $body = PlaceholderHelper::replace($template->body, $installment);

        // Send email via SMTP
        $client = $installment->booking->client;
        if (!$client || !$client->email) {
            return redirect()->back()->with('error', 'Cliente sem e-mail cadastrado.');
        }

        $emailStatus = 'enviado';
        try {
            Mail::to($client->email)->send(new PaymentNotification($subject, $body));
        } catch (\Exception $e) {
            $emailStatus = 'falhou';
            return redirect()->back()->with('error', 'Falha ao enviar e-mail: ' . $e->getMessage());
        }

        // Create email log entry
        EmailLog::create([
            'installment_id' => $installment->id,
            'client_id'      => $installment->booking->client_id,
            'template_id'    => $template->id,
            'subject'        => $subject,
            'body'           => $body,
            'status'         => $emailStatus,
            'trigger_type'   => 'manual',
            'sent_at'        => now(),
        ]);

        // Update installment email tracking
        $installment->update([
            'last_email_sent_at'    => now(),
            'last_email_template_id' => $template->id,
        ]);

        ActivityLog::log('reenviou e-mail', 'Installment', $installment->id, [
            'booking_id'    => $installment->booking_id,
            'template_type' => $templateType,
        ]);

        return redirect()->back()->with('success', 'E-mail reenviado com sucesso.');
    }
}
