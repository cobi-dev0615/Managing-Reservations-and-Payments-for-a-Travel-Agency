<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'client_id', 'tour_id', 'tour_manual', 'start_date', 'currency',
        'total_value', 'discount_notes', 'num_travelers', 'status', 'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'total_value' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour ? $this->tour->name : ($this->tour_manual ?? 'N/A');
    }
}
