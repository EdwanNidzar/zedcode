<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class LeaveRequestForm extends Component
{
    use WithFileUploads;

    // User Info (Readonly)
    public $user;

    // Form fields
    public $leave_type = 'Cuti';
    public $reason = '';
    public $keterangan_lainnya = '';
    public $tanggal_cuti_sebelumnya;
    
    public $start_date;
    public $end_date;
    public $jumlah_hari = 0;
    public $tanggal_kembali;
    
    public $no_telp_darurat;
    public $pengganti_user_id;
    public $attachment;

    public function mount()
    {
        $this->user = Auth::user();
        $this->no_telp_darurat = $this->user->phone;
    }

    // Hitung jumlah hari otomatis (hari kerja sederhana)
    public function updated($propertyName)
    {
        if ($propertyName === 'start_date' || $propertyName === 'end_date') {
            if ($this->start_date && $this->end_date) {
                $start = \Carbon\Carbon::parse($this->start_date);
                $end = \Carbon\Carbon::parse($this->end_date);
                if ($start <= $end) {
                    $this->jumlah_hari = $start->diffInDaysFiltered(function(\Carbon\Carbon $date) {
                        return !$date->isWeekend();
                    }, $end) + 1; // Termasuk hari terakhir
                    
                    // Set tanggal kembali (1 hari setelah end_date, jika weekend skip)
                    $kembali = $end->copy()->addDay();
                    while ($kembali->isWeekend()) {
                        $kembali->addDay();
                    }
                    $this->tanggal_kembali = $kembali->format('Y-m-d');
                } else {
                    $this->jumlah_hari = 0;
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            'leave_type' => 'required|in:Cuti,Izin Sakit Lainnya,Lainnya',
            'keterangan_lainnya' => 'required_if:leave_type,Lainnya|nullable|string',
            'reason' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jumlah_hari' => 'required|integer|min:1',
            'tanggal_kembali' => 'required|date',
            'no_telp_darurat' => 'required|string',
            'pengganti_user_id' => 'required|exists:users,id',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        if (!$this->user->atasan_id && !($this->user->jabatan_level > 5)) {
            session()->flash('error', 'Anda belum mengatur Atasan Langsung di profil. Lengkapi profil terlebih dahulu.');
            return;
        }

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('leave-attachments', 'public');
        }

        $leaveRequest = LeaveRequest::create([
            'user_id' => $this->user->id,
            'leave_type' => $this->leave_type,
            'keterangan_lainnya' => $this->leave_type === 'Lainnya' ? $this->keterangan_lainnya : null,
            'reason' => $this->reason,
            'tanggal_cuti_sebelumnya' => $this->tanggal_cuti_sebelumnya,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'jumlah_hari' => $this->jumlah_hari,
            'tanggal_kembali' => $this->tanggal_kembali,
            'no_telp_darurat' => $this->no_telp_darurat,
            'pengganti_user_id' => $this->pengganti_user_id,
            'attachment' => $attachmentPath,
        ]);

        // Generate Rantai Approver Secara Dinamis
        $pengganti = \App\Models\User::find($this->pengganti_user_id);
        app(\App\Services\ApprovalChainService::class)->createApprovalChain($leaveRequest, $pengganti);

        // Kirim notifikasi ke pengganti
        $pengganti->notify(new \App\Notifications\LeaveApprovalRequestNotification($leaveRequest,
            (object)['role_label' => 'Staff Pengganti', 'approver' => $pengganti]
        ));

        // Reset form except read-only
        $this->reset(['leave_type', 'reason', 'keterangan_lainnya', 'tanggal_cuti_sebelumnya', 'start_date', 'end_date', 'jumlah_hari', 'tanggal_kembali', 'no_telp_darurat', 'pengganti_user_id', 'attachment']);
        session()->flash('success', 'Pengajuan cuti berhasil dikirim! Menunggu konfirmasi dari Staff Pengganti.');
    }

    public function render()
    {
        $penggantiList = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        
        // Hitung cuti untuk HR/View (Cek tabel leave_balances)
        $leaveBalance = LeaveBalance::where('user_id', Auth::id())
                                    ->where('leave_type', 'Cuti Tahunan')
                                    ->where('year', date('Y'))
                                    ->first();
        $hakCuti = $leaveBalance ? $leaveBalance->balance : 12; // default 12

        $sudahDiambil = LeaveRequest::where('user_id', Auth::id())
            ->where('leave_type', 'Cuti')
            ->whereYear('start_date', date('Y'))
            ->get()
            ->filter(fn($r) => $r->overall_status === 'approved') // Filter via computed attribute
            ->sum('jumlah_hari');
            
        $sisaCuti = $hakCuti - $sudahDiambil;

        return view('livewire.leave.leave-request-form', compact('penggantiList', 'hakCuti', 'sudahDiambil', 'sisaCuti'))
            ->layout('components.app-layout');
    }
}
