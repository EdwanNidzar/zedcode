<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ApprovalChainConfig extends Model
{
    protected $fillable = [
        'requester_level',
        'approver_role',
        'approver_level',
        'step_order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: hanya config yang aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter berdasarkan level pemohon.
     */
    public function scopeForLevel(Builder $query, int $level): Builder
    {
        return $query->where('requester_level', $level);
    }

    /**
     * Relasi ke user yang membuat/mengubah config ini.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
