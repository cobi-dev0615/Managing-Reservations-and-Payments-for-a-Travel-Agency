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

        ActivityLog::log(__('messages.log_created_installment'), 'Installment', $installment->id, [
            'booking_id'         => $booking->id,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', __('messages.installment_added'));
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

        ActivityLog::log(__('messages.log_updated_installment'), 'Installment', $installment->id, [
            'booking_id'         => $installment->booking_id,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ]);

        return redirect()->route('bookings.show', $installment->booking_id)->with('success', __('messages.installment_updated'));
    }

    public function markPaid(Installment $installment)
    {
        $installment->update([
            'status'  => 'pago',
            'paid_at' => now(),
        ]);

        ActivityLog::log(__('messages.log_marked_paid'), 'Installment', $installment->id, [
            'booking_id'         => $installment->booking_id,
            'installment_number' => $installment->installment_number,
            'amount'             => $installment->amount,
        ]);

        return redirect()->back()->with('success', __('messages.installment_marked_paid'));
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

        ActivityLog::log(__('messages.log_deleted_installment'), 'Installment', null, $details);

        return redirect()->route('bookings.show', $bookingId)->with('success', __('messages.installment_deleted'));
    }

    public function toggleEmail(Installment $installment)
    {
        $installment->update([
            'email_paused' => !$installment->email_paused,
        ]);

        $action = $installment->email_paused ? __('messages.log_paused_emails') : __('messages.log_reactivated_emails');

        ActivityLog::log($action, 'Installment', $installment->id, [
            'booking_id'         => $installment->booking_id,
            'installment_number' => $installment->installment_number,
            'email_paused'       => $installment->email_paused,
        ]);

        $msg = $installment->email_paused
            ? __('messages.emails_paused')
            : __('messages.emails_reactivated');

        return redirect()->back()->with('success', $msg);
    }

    public function resendEmail(Installment $installment)
    {
        $installment->load(['booking.client', 'booking.tour']);

        // Warn if payment method is "link" but no link is set
        if ($installment->payment_method === 'link' && empty($installment->payment_link)) {
            return redirect()->back()->with('error', __('messages.missing_payment_link'));
        }

        $resolvedStatus = $installment->resolveStatus();

        // Choose template based on status
        $templateType = 'lembrete_pagamento';
        if ($resolvedStatus === 'atrasado') {
            $templateType = 'aviso_atraso';
        }

        $template = EmailTemplate::where('type', $templateType)->first();

        if (!$template) {
            return redirect()->back()->with('error', __('messages.template_not_found', ['type' => $templateType]));
        }

        $subject = PlaceholderHelper::replace($template->subject, $installment);
        $body = PlaceholderHelper::replace($template->body, $installment);

        // Send email via SMTP
        $client = $installment->booking->client;
        if (!$client || !$client->email) {
            return redirect()->back()->with('error', __('messages.client_no_email'));
        }

        $emailStatus = 'enviado';
        try {
            Mail::to($client->email)->send(new PaymentNotification($subject, $body));
        } catch (\Exception $e) {
            $emailStatus = 'falhou';
            return redirect()->back()->with('error', __('messages.email_send_failed', ['error' => $e->getMessage()]));
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

        ActivityLog::log(__('messages.log_resent_email'), 'Installment', $installment->id, [
            'booking_id'    => $installment->booking_id,
            'template_type' => $templateType,
        ]);

        return redirect()->back()->with('success', __('messages.email_resent'));
    }
}
