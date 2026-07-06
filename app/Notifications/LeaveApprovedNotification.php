<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LeaveRequest;

class LeaveApprovedNotification extends Notification
{
    use Queueable;

    protected $leaveRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Menyimpan notifikasi di database untuk icon lonceng
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'title' => 'Pengajuan Cuti Disetujui!',
            'message' => 'Pengajuan cuti Anda untuk tanggal ' . \Carbon\Carbon::parse($this->leaveRequest->start_date)->format('d M Y') . ' telah disetujui penuh oleh Manager/Direktur.',
            'url' => route('leave.approvals') . '?tab=history'
        ];
    }
}
