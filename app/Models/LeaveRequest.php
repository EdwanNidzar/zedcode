<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id', 'start_date', 'end_date', 'leave_type', 'reason',
        'keterangan_lainnya', 'tanggal_cuti_sebelumnya', 'jumlah_hari', 'tanggal_kembali',
        'no_telp_darurat', 'pengganti_user_id',
        'status_pengganti', // Tetap ada untuk step pertama (pengganti)
        'attachment'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengganti(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengganti_user_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->orderBy('order');
    }

    /**
     * Status keseluruhan pengajuan berdasarkan rantai approver.
     */
    public function getOverallStatusAttribute(): string
    {
        // Cek pengganti dulu
        if ($this->status_pengganti === 'rejected') return 'rejected';
        if ($this->status_pengganti === 'pending') return 'pending';

        $approvals = $this->approvals;
        if ($approvals->isEmpty()) return 'pending';

        if ($approvals->contains('status', 'rejected')) return 'rejected';
        if ($approvals->every(fn($a) => $a->status === 'approved')) return 'approved';

        return 'in_progress';
    }

    /**
     * Approver yang saat ini giliran menyetujui (status pending dengan order terendah).
     */
    public function getActiveApprovalAttribute(): ?LeaveApproval
    {
        return $this->approvals->firstWhere('status', 'pending');
    }
}

