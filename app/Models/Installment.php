<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    protected $fillable = [
        'booking_id', 'installment_number', 'amount', 'due_date', 'status',
        'payment_method', 'payment_link', 'paid_at', 'last_email_sent_at',
        'last_email_template_id', 'email_paused',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'last_email_sent_at' => 'datetime',
        'amount' => 'decimal:2',
        'email_paused' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function lastEmailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'last_email_template_id');
    }

    public function resolveStatus(): string
    {
        if ($this->status === 'pago') {
            return 'pago';
        }
        if ($this->payment_method === 'link' && empty($this->payment_link)) {
            return 'falta_link';
        }
        if ($this->due_date->isPast() && $this->status !== 'pago') {
            return 'atrasado';
        }
        return 'pendente';
    }
}
