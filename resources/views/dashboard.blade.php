<x-app-layout title="Dashboard">
    
    {{-- WELCOME BLOCK --}}
    <div class="color-block color-block-lime">
        <h2 class="block-title">Halo, {{ auth()->user()->name }}!</h2>
        <p class="block-desc">Selamat datang di sistem manajemen internal ZED CORE. Dari sini Anda dapat mengajukan cuti, merequest perlengkapan kerja, dan mengakses dokumen penting perusahaan.</p>
        
        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <a href="#" class="btn-pill btn-pill-primary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Buat Pengajuan Cuti
            </a>
            <a href="#" class="btn-pill btn-pill-secondary">
                Request Barang
            </a>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Sisa Cuti Tahunan</div>
            <div class="stat-value">12 <span style="font-size:16px;font-weight:500;color:var(--muted)">hari</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pengajuan Aktif</div>
            <div class="stat-value">1 <span style="font-size:16px;font-weight:500;color:var(--muted)">menunggu</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Dokumen Baru</div>
            <div class="stat-value">3 <span style="font-size:16px;font-weight:500;color:var(--muted)">file</span></div>
        </div>
    </div>

    {{-- ADDITIONAL CONTENT --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        
        {{-- Aktivitas --}}
        <div>
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;letter-spacing:-0.5px">Aktivitas Terakhir</h3>
            <div style="background:var(--canvas);border:1px solid var(--hairline);border-radius:var(--radius-lg);padding:24px;">
                <p style="color:var(--muted);font-size:14px;text-align:center;padding:24px 0;">Belum ada aktivitas yang ditampilkan.</p>
            </div>
        </div>

        {{-- Pengumuman --}}
        <div>
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;letter-spacing:-0.5px">Pengumuman HR</h3>
            <div style="background:var(--block-lilac);border-radius:var(--radius-lg);padding:24px;">
                <h4 style="font-weight:700;margin-bottom:8px">Pembaruan Handbook Karyawan</h4>
                <p style="font-size:14px;line-height:1.5;margin-bottom:16px;color:rgba(10,10,10,0.75)">
                    Kebijakan WFH dan pengajuan reimburse terbaru telah ditambahkan ke Handbook v2.1.
                </p>
                <a href="#" style="font-size:13px;font-weight:600;color:var(--ink);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    Baca selengkapnya
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>

    </div>

</x-app-layout>
