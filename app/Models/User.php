<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_users';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'nama_lengkap',
        'email',
        'telepon',
        'role',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // Relationships
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    // Role Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    // Accessors
    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'danger',
            'staff' => 'primary',
            default => 'secondary',
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'staff' => 'Staff',
            default => '-',
        };
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nama_lengkap ?: $this->name;
    }

    // Backward-compat: banyak view pakai ->id sebelum refactor PK
    public function getIdAttribute(): mixed
    {
        return $this->id_users;
    }
}
