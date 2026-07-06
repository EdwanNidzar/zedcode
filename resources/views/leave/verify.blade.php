<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen Cuti - ZED CORE</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 400px; width: 100%; text-align: center; border-top: 4px solid #22c55e; }
        .icon { width: 64px; height: 64px; color: #22c55e; margin: 0 auto 15px; }
        h1 { margin: 0 0 10px; font-size: 20px; color: #0f172a; }
        p { color: #475569; font-size: 14px; margin-bottom: 20px; }
        .details { background: #f1f5f9; padding: 15px; border-radius: 8px; text-align: left; font-size: 13px; line-height: 1.6; }
        .details strong { color: #0f172a; display: block; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="card">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        
        <h1>Dokumen Terverifikasi Sah</h1>
        <p>QR Code ini berasal dari sistem resmi ZED CORE dan terbukti validitasnya.</p>
        
        @php
            $signerName = '-';
            if ($role === 'pemohon') $signerName = $req->user->name;
            if ($role === 'spv') $signerName = $req->user->atasan->name ?? 'Atasan';
            if ($role === 'manager') $signerName = \App\Models\User::role('Super Admin')->first()->name ?? 'Manager';
            if ($role === 'hrd') $signerName = \App\Models\User::role('HR / Manager')->first()->name ?? 'HRD';
            if ($role === 'direktur') $signerName = \App\Models\User::role('Super Admin')->first()->name ?? 'Direktur';
        @endphp

        <div class="details">
            <strong>Ditandatangani secara digital oleh:</strong>
            <span style="font-size: 16px; color: #2563eb; font-weight: bold; display: block; margin-top: 4px;">{{ $signerName }}</span>
            <span style="display: block; font-size: 12px; color: #64748b;">(Sebagai {{ strtoupper($role) }})</span>
            
            <hr style="border: 0; border-top: 1px solid #cbd5e1; margin: 15px 0;">
            
            <strong>Dokumen Referensi:</strong>
            Surat Permohonan Cuti - {{ $req->user->name }}<br>
            Tanggal: {{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
        </div>
    </div>
</body>
</html>
