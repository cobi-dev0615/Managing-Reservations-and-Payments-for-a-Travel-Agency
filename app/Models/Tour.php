<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'default_currency', 'notes', 'status', 'max_travelers',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getTravelerCountAttribute(): int
    {
        return $this->bookings()->where('status', '!=', 'cancelado')->sum('num_travelers');
    }
}
