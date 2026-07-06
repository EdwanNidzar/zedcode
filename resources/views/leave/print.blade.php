<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Cuti - {{ $req->user->name }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Main Container Border */
        .form-box {
            border: 3px solid #000;
            padding: 15px;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 20px;
        }
        .header-table td {
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            padding: 5px;
        }
        .logo-cell {
            width: 15%;
            font-size: 40px;
            line-height: 1;
            font-family: monospace;
        }
        .title-cell {
            font-size: 16px;
        }
        .code-cell {
            width: 20%;
            font-size: 12px;
        }

        /* Info Layout */
        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }
        .label {
            width: 200px;
            font-weight: bold;
        }
        .colon {
            width: 20px;
            text-align: center;
        }
        .value {
            flex: 1;
        }
        .value-box {
            border: 1px solid #000;
            display: inline-block;
            padding: 4px 10px;
            min-width: 80px;
            text-align: center;
            margin: 0 5px;
        }
        
        .divider {
            border-bottom: 1px solid #000;
            margin: 15px 0;
        }

        /* Signatures & Bottom Section */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .signature-table th, .signature-table td {
            border: 2px solid #000;
            padding: 5px;
        }
        .signature-table th {
            font-weight: bold;
        }
        .sign-box {
            height: 90px;
            vertical-align: middle;
        }
        .sign-td {
            padding: 5px !important;
        }
        .qr-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
        
        /* Layout Bawah (Direktur & Checkbox) */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .director-table {
            width: 25%;
            border-collapse: collapse;
            text-align: center;
            border: 2px solid #000;
        }
        .director-table th, .director-table td {
            border: 2px solid #000;
            padding: 5px;
        }
        
        .checkbox-container {
            display: flex;
            gap: 30px;
            margin-right: 15%;
            margin-bottom: 20px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            font-size: 12px;
        }
        .checkbox-box {
            width: 24px;
            height: 24px;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Fasilitas Checkmarks */
        .checkbox {
            width: 14px;
            height: 14px;
            border: 2px solid #000;
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }
        .check-svg { width: 16px; height: 16px; color: black; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        .btn-print {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-family: sans-serif;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print" style="text-align: right;">
            <button onclick="window.print()" class="btn-print">Cetak Formulir</button>
        </div>

        <div class="form-box">
            <table class="header-table">
                <tr>
                    <td class="logo-cell" rowspan="2">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 6L8 8.5V13.5L12 16L16 13.5V8.5L12 6Z" fill="black"/>
                        </svg>
                    </td>
                    <td>SYIHAB JAYA UTAMA</td>
                    <td class="code-cell" rowspan="2">F-ZED-HRD-004</td>
                </tr>
                <tr>
                    <td class="title-cell">FORMULIR PERMOHONAN CUTI</td>
                </tr>
            </table>

            <div class="info-row">
                <div class="label">NIK KARYAWAN</div><div class="colon">:</div>
                <div class="value">{{ $req->user->nik ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="label">NAMA KARYAWAN</div><div class="colon">:</div>
                <div class="value">{{ $req->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="label">JOIN DATE</div><div class="colon">:</div>
                <div class="value">-</div>
            </div>
            <div class="info-row">
                <div class="label">JENIS CUTI</div><div class="colon">:</div>
                <div class="value">{{ strtoupper($req->leave_type) }}</div>
            </div>
            
            <div class="info-row" style="margin-top: 10px;">
                <div class="label">PERIODE CUTI</div><div class="colon">:</div>
                <div class="value">
                    <span class="value-box" style="width: 100px;">{{ \Carbon\Carbon::parse($req->start_date)->format('d-M-y') }}</span>
                    <span style="margin: 0 10px;">SAMPAI</span>
                    <span class="value-box" style="width: 100px;">{{ \Carbon\Carbon::parse($req->end_date)->format('d-M-y') }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="label">JENIS CUTI YANG DIAMBIL</div><div class="colon">:</div>
                <div class="value">
                    <span class="value-box" style="width: 100px;">{{ strtoupper($req->leave_type) }}</span>
                    <span style="margin: 0 10px; display: inline-block; width: 45px;">KUOTA</span>
                    <span class="value-box" style="width: 100px;">{{ $req->user->leaveBalances->firstWhere('leave_type', 'Cuti Tahunan')->balance ?? '-' }} HARI</span>
                </div>
            </div>
            <div class="info-row">
                <div class="label">TANGGAL CUTI YANG DIAMBIL</div><div class="colon">:</div>
                <div class="value">
                    <span class="value-box" style="width: 100px;">{{ \Carbon\Carbon::parse($req->start_date)->format('d-M-y') }}</span>
                    <span style="margin: 0 10px;">SAMPAI</span>
                    <span class="value-box" style="width: 100px;">{{ \Carbon\Carbon::parse($req->end_date)->format('d-M-y') }}</span>
                </div>
            </div>
            <div class="info-row" style="margin-top: 5px;">
                <div class="label"></div><div class="colon"></div>
                <div class="value">
                    <span class="value-box" style="width: 100px;">TOTAL {{ $req->jumlah_hari }} HARI</span>
                    <span style="margin: 0 10px; display: inline-block; width: 45px;">SISA</span>
                    @php
                        $balance = $req->user->leaveBalances->firstWhere('leave_type', 'Cuti Tahunan')->balance ?? 0;
                        $sisa = $balance - $req->jumlah_hari;
                    @endphp
                    <span class="value-box" style="width: 100px;">{{ $sisa }} HARI</span>
                </div>
            </div>
            
            <div class="info-row" style="margin-top: 10px;">
                <div class="label">KEMBALI BEKERJA</div><div class="colon">:</div>
                <div class="value">{{ \Carbon\Carbon::parse($req->tanggal_kembali)->format('l, d F Y') }}</div>
            </div>

            <div class="divider"></div>

            <div class="info-row">
                <div class="label">DELEGASI</div><div class="colon">:</div>
                <div class="value" style="display: flex; align-items: center;">
                    <span style="width: 250px;">{{ $req->pengganti->name ?? '-' }}</span>
                    <span style="margin-right: 10px;">PARAF</span>
                    <span class="value-box" style="width: 100px; height: 20px; line-height: 20px; text-align: center;">
                        @if($req->status_pengganti === 'approved') 
                            <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> 
                        @endif
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="label">JOB PENDING</div><div class="colon">:</div>
                <div class="value">TERLAMPIR</div>
            </div>
            <div class="info-row">
                <div class="label">CATATAN</div><div class="colon">:</div>
                <div class="value">-</div>
            </div>

            <div class="divider"></div>

            <div class="info-row">
                <div class="label">FASILITAS CUTI</div><div class="colon">:</div>
                <div class="value">
                    ADA/TIDAK ADA &nbsp;&nbsp;&nbsp;
                    <div style="display: inline-flex; gap: 40px; vertical-align: top;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div><span class="checkbox"></span> TRAVEL</div>
                            <div><span class="checkbox"></span> KERETA</div>
                            <div><span class="checkbox"></span> BBM</div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div><span class="checkbox"></span> KAPAL</div>
                            <div><span class="checkbox"></span> PESAWAT</div>
                            <div><span class="checkbox"></span> PESAWAT & TRAVEL</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info-row" style="margin-top: 15px;">
                <div class="label">TUJUAN CUTI</div><div class="colon">:</div>
                <div class="value">{{ strtoupper($req->reason) }}</div>
            </div>

            <div class="divider"></div>
            
            <div style="margin-bottom: 10px; font-weight: bold;">
                BANJARBARU, {{ strtoupper(\Carbon\Carbon::parse($req->created_at)->translatedFormat('j F Y')) }}
            </div>

            @php
                $approvals = $req->approvals->keyBy('role_label');
                $spvApproval = $approvals->get('SPV');
                $hrApproval = $approvals->get('HR / Manager');
                $mgrApproval = $approvals->get('Manager / GM');
                $dirApproval = $approvals->get('Direktur') ?? $approvals->get('CEO');

                $spvName = $spvApproval ? $spvApproval->approver->name : ($req->user->atasan->name ?? '-');
                $hrName = $hrApproval ? $hrApproval->approver->name : (\App\Models\User::role('HR / Manager')->first()->name ?? 'HRD');
                $mgrName = $mgrApproval ? $mgrApproval->approver->name : (\App\Models\User::role('Manager / GM')->first()->name ?? 'MANAGER/GM');
                $dirName = $dirApproval ? $dirApproval->approver->name : (\App\Models\User::role('Direktur')->first()->name ?? 'DIREKTUR');
            @endphp

            @php
                $columnsCount = 1 + ($spvApproval ? 1 : 0) + ($mgrApproval ? 1 : 0) + ($hrApproval ? 1 : 0);
                $colWidth = (100 / $columnsCount) . '%';
            @endphp

            <table class="signature-table">
                <tr>
                    <th style="width: {{ $colWidth }};">DIBUAT OLEH,</th>
                    @if($spvApproval) <th style="width: {{ $colWidth }};">DIKETAHUI OLEH,</th> @endif
                    @if($mgrApproval) <th style="width: {{ $colWidth }};">DIKETAHUI OLEH,</th> @endif
                    @if($hrApproval) <th style="width: {{ $colWidth }};">DIKETAHUI OLEH,</th> @endif
                </tr>
                <tr>
                    <td class="sign-box sign-td">
                        <div class="qr-container" style="flex-direction: column; gap: 4px;">
                            <img src="{{ (new \chillerlan\QRCode\QRCode)->render(route('leave.verify', ['id' => $req->id, 'role' => 'pemohon'])) }}" width="70" height="70" />
                            <a href="{{ route('leave.verify', ['id' => $req->id, 'role' => 'pemohon']) }}" target="_blank" style="font-size: 8px; color: blue; text-decoration: underline;">Link Verifikasi</a>
                        </div>
                    </td>
                    @if($spvApproval)
                    <td class="sign-box sign-td">
                        @if($spvApproval->status === 'approved')
                            <div class="qr-container" style="flex-direction: column; gap: 4px;">
                                <img src="{{ (new \chillerlan\QRCode\QRCode)->render(route('leave.verify', ['id' => $req->id, 'role' => 'spv'])) }}" width="70" height="70" />
                                <a href="{{ route('leave.verify', ['id' => $req->id, 'role' => 'spv']) }}" target="_blank" style="font-size: 8px; color: blue; text-decoration: underline;">Link Verifikasi</a>
                            </div>
                        @endif
                    </td>
                    @endif
                    @if($mgrApproval)
                    <td class="sign-box sign-td">
                        @if($mgrApproval->status === 'approved')
                            <div class="qr-container" style="flex-direction: column; gap: 4px;">
                                <img src="{{ (new \chillerlan\QRCode\QRCode)->render(route('leave.verify', ['id' => $req->id, 'role' => 'manager'])) }}" width="70" height="70" />
                                <a href="{{ route('leave.verify', ['id' => $req->id, 'role' => 'manager']) }}" target="_blank" style="font-size: 8px; color: blue; text-decoration: underline;">Link Verifikasi</a>
                            </div>
                        @endif
                    </td>
                    @endif
                    @if($hrApproval)
                    <td class="sign-box sign-td">
                        @if($hrApproval->status === 'approved')
                            <div class="qr-container" style="flex-direction: column; gap: 4px;">
                                <img src="{{ (new \chillerlan\QRCode\QRCode)->render(route('leave.verify', ['id' => $req->id, 'role' => 'hrd'])) }}" width="70" height="70" />
                                <a href="{{ route('leave.verify', ['id' => $req->id, 'role' => 'hrd']) }}" target="_blank" style="font-size: 8px; color: blue; text-decoration: underline;">Link Verifikasi</a>
                            </div>
                        @endif
                    </td>
                    @endif
                </tr>
                <tr>
                    <td>USER YANG MENGAJUKAN<br/>{{ strtoupper($req->user->name) }}</td>
                    @if($spvApproval) <td>SPV<br/>{{ strtoupper($spvName) }}</td> @endif
                    @if($mgrApproval) <td>MANAGER/GM<br/>{{ strtoupper($mgrName) }}</td> @endif
                    @if($hrApproval) <td>HRD<br/>{{ strtoupper($hrName) }}</td> @endif
                </tr>
            </table>

            <div class="bottom-section">
                <table class="director-table">
                    <tr>
                        <th>DISETUJUI OLEH,</th>
                    </tr>
                    <tr>
                        <td class="sign-box sign-td">
                            @if($dirApproval && $dirApproval->status === 'approved')
                                <div class="qr-container" style="flex-direction: column; gap: 4px;">
                                    <img src="{{ (new \chillerlan\QRCode\QRCode)->render(route('leave.verify', ['id' => $req->id, 'role' => 'direktur'])) }}" width="70" height="70" />
                                    <a href="{{ route('leave.verify', ['id' => $req->id, 'role' => 'direktur']) }}" target="_blank" style="font-size: 8px; color: blue; text-decoration: underline;">Link Verifikasi</a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>DIREKTUR<br/>{{ strtoupper($dirName) }}</td>
                    </tr>
                </table>

                <div class="checkbox-container">
                    <div class="checkbox-item">
                        <div class="checkbox-box">
                            @if($req->overall_status === 'approved') 
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> 
                            @endif
                        </div>
                        <span>DISETUJUI</span>
                    </div>
                    
                    <div class="checkbox-item">
                        <div class="checkbox-box">
                            @if($req->overall_status === 'rejected') 
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg> 
                            @endif
                        </div>
                        <span>DITOLAK</span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 10px; color: red; font-weight: bold; font-size: 11px;">
                CATATAN:<br>
                - Form cuti wajib diselesaikan (lengkap tandatangan & diserahkan ke HRD)
            </div>

        </div>
    </div>
</body>
</html>