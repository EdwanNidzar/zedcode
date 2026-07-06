<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\LeaveRequest;

/**
 * Notifikasi ke approver berikutnya bahwa ada pengajuan yang perlu disetujui.
 */
class LeaveApprovalRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected LeaveRequest $leaveRequest,
        protected object $approval // Menerima LeaveApproval model maupun stdClass
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $pemohon = $this->leaveRequest->user;
        $dateRange = \Carbon\Carbon::parse($this->leaveRequest->start_date)->format('d M Y')
            . ' – '
            . \Carbon\Carbon::parse($this->leaveRequest->end_date)->format('d M Y');

        return [
            'leave_request_id' => $this->leaveRequest->id,
            'title'   => '📋 Persetujuan Cuti Menunggu',
            'message' => ($pemohon?->name ?? 'Seseorang') . " mengajukan cuti ({$dateRange}). Giliran Anda sebagai " . ($this->approval->role_label ?? 'Approver') . " untuk menyetujui.",
            'url'     => route('leave.approvals'),
            'type'    => 'request',
        ];
    }
}
