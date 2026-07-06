<div>
    <div class="color-block color-block-coral" style="padding: 32px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="block-title" style="margin-bottom: 4px; font-size: 24px; color: var(--block-navy)">Dashboard Approval</h2>
            <p class="block-desc" style="font-size: 14px; color: rgba(30,27,75,0.7)">Pusat persetujuan pengajuan cuti dan izin karyawan.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button wire:click="setTab('pending')" class="btn-pill" style="background: {{ $activeTab === 'pending' ? 'var(--ink)' : 'white' }}; color: {{ $activeTab === 'pending' ? 'white' : 'var(--ink)' }}; padding: 0 16px; height: 36px; border: 1px solid var(--hairline); font-size: 13px;">Antrean Saya</button>
            <button wire:click="setTab('history')" class="btn-pill" style="background: {{ $activeTab === 'history' ? 'var(--ink)' : 'white' }}; color: {{ $activeTab === 'history' ? 'white' : 'var(--ink)' }}; padding: 0 16px; height: 36px; border: 1px solid var(--hairline); font-size: 13px;">Semua Data / Riwayat</button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Modal Konfirmasi Tolak --}}
    @if($rejectingId)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; display: flex; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: var(--radius-lg); padding: 28px; width: 400px; box-shadow: var(--shadow-lg);">
            <h3 style="margin: 0 0 8px; font-size: 18px;">Tolak Pengajuan?</h3>
            <p style="color: var(--muted); font-size: 13px; margin-bottom: 16px;">Tindakan ini tidak bisa dibatalkan. Anda bisa menambahkan catatan alasan penolakan.</p>
            <textarea wire:model="rejectCatatan" placeholder="Catatan alasan (opsional)..." style="width: 100%; height: 80px; border: 1px solid var(--hairline); border-radius: var(--radius-md); padding: 10px; font-size: 13px; resize: none; box-sizing: border-box; margin-bottom: 16px;"></textarea>
            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <button wire:click="$set('rejectingId', null)" class="btn-pill" style="background: white; border: 1px solid var(--hairline); padding: 6px 16px; font-size: 13px;">Batal</button>
                <button wire:click="reject" class="btn-pill" style="background: var(--error); color: white; padding: 6px 16px; font-size: 13px;">Tolak Pengajuan</button>
            </div>
        </div>
    </div>
    @endif

    <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-lg); overflow: hidden;">
        
        @if($activeTab === 'pending')
            {{-- TAB: ANTREAN --}}
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface-soft); border-bottom: 1px solid var(--hairline);">
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Pemohon</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Detail Cuti</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Menunggu Aksi Dari</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase; text-align: right;">Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequests as $req)
                        <tr style="border-bottom: 1px solid var(--hairline-soft);">
                            <td style="padding: 16px 24px;">
                                <div style="font-weight: 700; font-size: 14px; color: var(--ink);">{{ $req->user->name }}</div>
                                <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">{{ $req->user->jabatan ?? 'Tanpa Jabatan' }} - {{ $req->user->divisi ?? 'Tanpa Divisi' }}</div>
                            </td>
                            <td style="padding: 16px 24px;">
                                <div style="font-size: 13px; font-weight: 600;">{{ $req->leave_type }} ({{ $req->jumlah_hari }} Hari)</div>
                                <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">{{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}</div>
                                <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">Alasan: {{ Str::limit($req->reason, 30) }}</div>
                                @if($req->attachment)
                                <div style="margin-top: 6px;">
                                    <a href="{{ Storage::url($req->attachment) }}" target="_blank" style="font-size: 11px; color: #2563eb; text-decoration: underline;">Lihat Lampiran</a>
                                </div>
                                @endif
                            </td>
                            <td style="padding: 16px 24px;">
                                {{-- Tampilkan step yang sedang menunggu --}}
                                @if($req->status_pengganti === 'pending')
                                    <span style="background: #fef08a; color: #854d0e; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;">Staff Pengganti</span>
                                @else
                                    @php $activeApproval = $req->approvals->firstWhere('status', 'pending') @endphp
                                    @if($activeApproval)
                                        <span style="background: #bfdbfe; color: #1e3a8a; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;">{{ $activeApproval->role_label }}</span>
                                        <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">{{ $activeApproval->approver->name }}</div>
                                    @endif
                                @endif
                            </td>
                            <td style="padding: 16px 24px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    {{-- Tombol untuk Pengganti --}}
                                    @if($req->status_pengganti === 'pending' && $req->pengganti_user_id === Auth::id())
                                        <button wire:click="rejectPengganti({{ $req->id }})" wire:confirm="Tolak sebagai pengganti?" class="btn-pill" style="background: white; color: var(--error); border: 1px solid var(--error); padding: 4px 12px; font-size: 11px;">Tolak</button>
                                        <button wire:click="approvePengganti({{ $req->id }})" class="btn-pill btn-pill-primary" style="padding: 4px 12px; font-size: 11px; background: #16a34a;">Setuju</button>
                                    @else
                                        {{-- Tombol untuk Approver di Chain --}}
                                        @php $myApproval = $req->approvals->firstWhere(fn($a) => $a->approver_id === Auth::id() && $a->status === 'pending') @endphp
                                        @if($myApproval)
                                            <button wire:click="confirmReject({{ $myApproval->id }})" class="btn-pill" style="background: white; color: var(--error); border: 1px solid var(--error); padding: 4px 12px; font-size: 11px;">Tolak</button>
                                            <button wire:click="approve({{ $myApproval->id }})" class="btn-pill btn-pill-primary" style="padding: 4px 12px; font-size: 11px; background: #16a34a;">Setuju</button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 48px; text-align: center; color: var(--muted);">Hore! Tidak ada antrean persetujuan untuk Anda saat ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 16px;">{{ $pendingRequests->links() }}</div>

        @else
            {{-- TAB: HISTORY --}}
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface-soft); border-bottom: 1px solid var(--hairline);">
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Tgl Pengajuan</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Pemohon</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Detail Cuti</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Rantai Approval</th>
                        <th style="padding: 16px 24px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Status Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyRequests as $req)
                        <tr style="border-bottom: 1px solid var(--hairline-soft);">
                            <td style="padding: 16px 24px; font-size: 13px;">{{ $req->created_at->format('d M Y') }}</td>
                            <td style="padding: 16px 24px;">
                                <div style="font-weight: 600; font-size: 14px; color: var(--ink);">{{ $req->user->name }}</div>
                                <div style="font-size: 11px; color: var(--muted);">{{ $req->user->jabatan }}</div>
                            </td>
                            <td style="padding: 16px 24px;">
                                <div style="font-size: 13px;">{{ $req->leave_type }} ({{ $req->jumlah_hari }} Hari)</div>
                                <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">{{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d M') }}</div>
                                @if($req->attachment)
                                <div style="margin-top: 6px;">
                                    <a href="{{ Storage::url($req->attachment) }}" target="_blank" style="font-size: 11px; color: #2563eb; text-decoration: underline;">Lihat Lampiran</a>
                                </div>
                                @endif
                            </td>
                            <td style="padding: 16px 24px;">
                                {{-- Rantai approval berjenjang --}}
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    @php
                                        $penggantiStatus = $req->status_pengganti;
                                        $colors = ['approved' => '#dcfce7', 'rejected' => '#fee2e2', 'pending' => '#f1f5f9'];
                                        $textColors = ['approved' => '#166534', 'rejected' => '#991b1b', 'pending' => '#475569'];
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 6px; font-size: 11px;">
                                        <span style="background: {{ $colors[$penggantiStatus] ?? '#f1f5f9' }}; color: {{ $textColors[$penggantiStatus] ?? '#475569' }}; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                            {{ $penggantiStatus === 'approved' ? '✓' : ($penggantiStatus === 'rejected' ? '✗' : '⋯') }}
                                        </span>
                                        <span>{{ $req->pengganti->name ?? '-' }} (Pengganti)</span>
                                    </div>
                                    @foreach($req->approvals as $approval)
                                    <div style="display: flex; align-items: center; gap: 6px; font-size: 11px;">
                                        <span style="background: {{ $colors[$approval->status] }}; color: {{ $textColors[$approval->status] }}; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                            {{ $approval->status === 'approved' ? '✓' : ($approval->status === 'rejected' ? '✗' : '⋯') }}
                                        </span>
                                        <span>{{ $approval->approver->name }} ({{ $approval->role_label }})</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td style="padding: 16px 24px;">
                                <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start;">
                                    @php $status = $req->overall_status @endphp
                                    @if($status === 'approved')
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;">✓ Disetujui Penuh</span>
                                        @if(Auth::id() === $req->user_id || Auth::user()->hasRoleIn(['HR / Manager', 'Super Admin', 'CEO', 'Direktur', 'Manager / GM']))
                                            <a href="{{ route('leave.print', $req->id) }}" target="_blank" class="btn-pill" style="border: 1px solid var(--hairline); padding: 4px 8px; font-size: 10px; background: white; color: var(--ink); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                Cetak Cuti
                                            </a>
                                        @endif
                                    @elseif($status === 'rejected')
                                        <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;">✗ Ditolak</span>
                                    @else
                                        <span style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;">⋯ Dalam Proses</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding: 48px; text-align: center; color: var(--muted);">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 16px;">{{ $historyRequests->links() }}</div>
        @endif
        
    </div>
</div>
