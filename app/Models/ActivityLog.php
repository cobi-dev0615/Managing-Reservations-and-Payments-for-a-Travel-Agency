<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['action', 'entity_type', 'entity_id', 'details', 'ip_address'];

    protected $casts = [
        'details' => 'array',
    ];

    public static function log(string $action, string $entityType, ?int $entityId = null, ?array $details = null): self
    {
        return self::create([
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }
}
