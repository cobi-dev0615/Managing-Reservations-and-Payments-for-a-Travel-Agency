<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_VIEWER = 'viewer';

    const ROLES = [
        self::ROLE_ADMIN => 'Administrador',
        self::ROLE_MANAGER => 'Gerente',
        self::ROLE_VIEWER => 'Visualizador',
    ];

    public static function translatedRoles(): array
    {
        return [
            self::ROLE_ADMIN => __('messages.role_admin'),
            self::ROLE_MANAGER => __('messages.role_manager'),
            self::ROLE_VIEWER => __('messages.role_viewer'),
        ];
    }

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SUSPENDED = 'suspended';

    const STATUSES = [
        self::STATUS_PENDING => 'Pendente',
        self::STATUS_APPROVED => 'Aprovado',
        self::STATUS_SUSPENDED => 'Suspenso',
    ];

    public static function translatedStatuses(): array
    {
        return [
            self::STATUS_PENDING => __('messages.status_pending_label'),
            self::STATUS_APPROVED => __('messages.status_approved'),
            self::STATUS_SUSPENDED => __('messages.status_suspended'),
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        // Admin accounts are always approved
        static::saving(function (User $user) {
            if ($user->role === self::ROLE_ADMIN) {
                $user->status = self::STATUS_APPROVED;
            }
        });
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isViewer(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        return in_array($this->role, $roles);
    }

    public function canManage(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }

    public function roleName(): string
    {
        return self::translatedRoles()[$this->role] ?? $this->role;
    }

    public function statusName(): string
    {
        return self::translatedStatuses()[$this->status] ?? $this->status;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }
}
