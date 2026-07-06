<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\LeaveRequest;
use App\Models\User;

/**
 * Notifikasi ke pemohon tentang status akhir pengajuannya (approved atau rejected).
 */
class LeaveStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected LeaveRequest $leaveRequest,
        protected string $status, // 'approved' atau 'rejected'
        protected ?User $actionBy = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $dateRange = \Carbon\Carbon::parse($this->leaveRequest->start_date)->format('d M Y')
            . ' – '
            . \Carbon\Carbon::parse($this->leaveRequest->end_date)->format('d M Y');

        if ($this->status === 'approved') {
            return [
                'leave_request_id' => $this->leaveRequest->id,
                'title'   => '🎉 Cuti Disetujui!',
                'message' => "Pengajuan cuti Anda ({$dateRange}) telah disetujui penuh.",
                'url'     => route('leave.approvals'),
                'type'    => 'approved',
            ];
        }

        return [
            'leave_request_id' => $this->leaveRequest->id,
            'title'   => '❌ Cuti Ditolak',
            'message' => "Maaf, pengajuan cuti Anda ({$dateRange}) ditolak oleh " . ($this->actionBy?->name ?? 'approver') . ".",
            'url'     => route('leave.approvals'),
            'type'    => 'rejected',
        ];
    }
}
