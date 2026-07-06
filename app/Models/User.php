<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'nik', 'divisi', 'jabatan', 'unit_kerja', 'phone', 'atasan_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function atasan()
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(User::class, 'atasan_id');
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function itemRequests()
    {
        return $this->hasMany(ItemRequest::class);
    }

    /**
     * Mendapatkan level jabatan berdasarkan role Spatie.
     * Mengambil level tertinggi dari semua role yang dimiliki user.
     */
    public function getJabatanLevelAttribute(): int
    {
        return $this->roles->max('level') ?? 0;
    }

    /**
     * Mendapatkan nama jabatan struktural (bukan Super Admin).
     */
    public function getPrimaryRoleLabelAttribute(): string
    {
        $structuralRoles = $this->roles->sortByDesc('level')->filter(fn($r) => $r->name !== 'Super Admin');
        return $structuralRoles->first()?->name ?? $this->roles->first()?->name ?? 'Employee';
    }
}
