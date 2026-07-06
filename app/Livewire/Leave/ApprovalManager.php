<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\LeaveRequest;
use App\Models\LeaveApproval;
use App\Services\ApprovalChainService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ApprovalManager extends Component
{
    use WithPagination;

    public $activeTab = 'pending';
    public $rejectCatatan = '';
    public $rejectingId = null;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function approve($approvalId)
    {
        $approval = LeaveApproval::with('leaveRequest.user', 'leaveRequest.approvals')->findOrFail($approvalId);
        $user = Auth::user();

        // Validasi: hanya approver yang bersangkutan yang boleh
        if ((int) $approval->approver_id !== (int) $user->id) {
            session()->flash('error', 'Anda tidak berhak menyetujui item ini.');
            return;
        }

        if ($approval->status !== 'pending') {
            session()->flash('error', 'Item ini sudah diproses sebelumnya.');
            return;
        }

        app(ApprovalChainService::class)->processApproval($approval, true);
        session()->flash('success', 'Pengajuan berhasil disetujui.');
    }

    public function confirmReject($approvalId)
    {
        $this->rejectingId = $approvalId;
    }

    public function reject()
    {
        if (!$this->rejectingId) return;

        $approval = LeaveApproval::with('leaveRequest.user', 'leaveRequest.approvals')->findOrFail($this->rejectingId);
        $user = Auth::user();

        if ((int) $approval->approver_id !== (int) $user->id) {
            session()->flash('error', 'Anda tidak berhak menolak item ini.');
            $this->rejectingId = null;
            return;
        }

        app(ApprovalChainService::class)->processApproval($approval, false, $this->rejectCatatan ?: null);
        $this->rejectingId = null;
        $this->rejectCatatan = '';
        session()->flash('success', 'Pengajuan berhasil ditolak.');
    }

    public function approvePengganti($requestId)
    {
        $request = LeaveRequest::findOrFail($requestId);
        if ((int) $request->pengganti_user_id !== (int) Auth::id()) {
            session()->flash('error', 'Anda bukan pengganti yang ditunjuk.');
            return;
        }
        if ($request->status_pengganti !== 'pending') {
            session()->flash('error', 'Sudah diproses.');
            return;
        }

        $request->update(['status_pengganti' => 'approved']);

        // Kirim notifikasi ke approver pertama dalam chain
        $firstApproval = $request->approvals()->where('status', 'pending')->orderBy('order')->first();
        if ($firstApproval) {
            $firstApproval->approver->notify(new \App\Notifications\LeaveApprovalRequestNotification($request, $firstApproval));
        }

        session()->flash('success', 'Anda telah menyetujui sebagai pengganti.');
    }

    public function rejectPengganti($requestId)
    {
        $request = LeaveRequest::findOrFail($requestId);
        if ((int) $request->pengganti_user_id !== (int) Auth::id()) return;

        $request->update(['status_pengganti' => 'rejected']);
        // Tolak semua approval chain
        $request->approvals()->update(['status' => 'rejected']);
        $request->user->notify(new \App\Notifications\LeaveStatusNotification($request, 'rejected', Auth::user()));
        session()->flash('success', 'Pengajuan ditolak.');
    }

    public function getPendingRequestsProperty()
    {
        $user = Auth::user();

        return LeaveRequest::with(['user', 'pengganti', 'approvals.approver'])
            ->where(function ($q) use ($user) {
                // Sebagai Pengganti
                $q->orWhere(function ($sub) use ($user) {
                    $sub->where('pengganti_user_id', $user->id)
                        ->where('status_pengganti', 'pending');
                });

                // Sebagai approver di chain (hanya yang giliran sekarang)
                $q->orWhereHas('approvals', function ($sub) use ($user) {
                    $sub->where('approver_id', $user->id)
                        ->where('status', 'pending')
                        ->where('order', function ($orderSub) {
                            // Hanya order paling rendah (giliran pertama yang masih pending)
                            $orderSub->selectRaw('MIN(`order`)')
                                ->from('leave_approvals as la2')
                                ->whereColumn('la2.leave_request_id', 'leave_approvals.leave_request_id')
                                ->where('la2.status', 'pending');
                        });
                })->where('status_pengganti', 'approved'); // Pengganti harus approve dulu
            })
            ->latest()
            ->paginate(10, ['*'], 'pendingPage');
    }

    public function getHistoryRequestsProperty()
    {
        $user = Auth::user();
        $query = LeaveRequest::with(['user', 'pengganti', 'approvals.approver']);

        if (!$user->hasRole(['Super Admin', 'HR / Manager', 'CEO', 'Direktur', 'Manager / GM'])) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('pengganti_user_id', $user->id)
                  ->orWhereHas('approvals', fn($a) => $a->where('approver_id', $user->id));
            });
        }

        return $query->latest()->paginate(10, ['*'], 'historyPage');
    }

    public function render()
    {
        return view('livewire.leave.approval-manager', [
            'pendingRequests' => $this->pendingRequests,
            'historyRequests' => $this->historyRequests,
        ])->layout('components.app-layout');
    }
}
