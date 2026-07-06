<div>
    <div class="color-block color-block-lime" style="padding: 32px; margin-bottom: 24px;">
        <h2 class="block-title" style="margin-bottom: 4px; font-size: 24px; color: var(--block-navy)">Formulir Permohonan Cuti / Izin</h2>
        <p class="block-desc" style="font-size: 14px; color: rgba(30,27,75,0.7)">Harap isi formulir ini dengan lengkap. Permohonan akan diteruskan ke atasan untuk disetujui.</p>
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

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
        
        {{-- KIRI: FORMULIR --}}
        <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-lg); padding: 32px;">
            <form wire:submit.prevent="save">
                
                {{-- DATA PEMOHON --}}
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--hairline); padding-bottom: 8px;">1. Data Pemohon</h3>
                    <p style="font-size: 13px; color: var(--muted); margin-bottom: 16px;">Saya yang bertanda tangan di bawah ini:</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="font-size: 12px; color: var(--muted); display: block;">NIK</label>
                            <div style="font-size: 14px; font-weight: 600;">{{ $user->nik ?? '-' }}</div>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: var(--muted); display: block;">NAMA</label>
                            <div style="font-size: 14px; font-weight: 600;">{{ $user->name }}</div>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: var(--muted); display: block;">DIVISI</label>
                            <div style="font-size: 14px; font-weight: 600;">{{ $user->divisi ?? '-' }}</div>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: var(--muted); display: block;">JABATAN</label>
                            <div style="font-size: 14px; font-weight: 600;">{{ $user->jabatan ?? '-' }}</div>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: var(--muted); display: block;">UNIT KERJA</label>
                            <div style="font-size: 14px; font-weight: 600;">{{ $user->unit_kerja ?? '-' }}</div>
                        </div>
                    </div>
                    @if(!$user->nik || !$user->atasan_id)
                        <div style="margin-top: 12px; padding: 8px; background: #fffbeb; color: #b45309; font-size: 12px; border-radius: var(--radius-sm);">
                            ⚠️ Data profil belum lengkap. <a href="{{ route('profile') }}" style="color: #b45309; text-decoration: underline;">Lengkapi Profil</a>.
                        </div>
                    @endif
                </div>

                {{-- JENIS PENGAJUAN --}}
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--hairline); padding-bottom: 8px;">2. Mengajukan Permohonan</h3>
                    
                    <div style="display: flex; gap: 24px; margin-bottom: 16px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                            <input type="radio" wire:model.live="leave_type" value="Cuti" style="accent-color: var(--ink); width: 16px; height: 16px;"> Cuti
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                            <input type="radio" wire:model.live="leave_type" value="Izin Sakit Lainnya" style="accent-color: var(--ink); width: 16px; height: 16px;"> Izin Sakit Lainnya (Dengan Keterangan Dokter)
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                            <input type="radio" wire:model.live="leave_type" value="Lainnya" style="accent-color: var(--ink); width: 16px; height: 16px;"> Lainnya
                        </label>
                    </div>

                    @if($leave_type === 'Lainnya')
                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Keterangan Lainnya</label>
                            <input type="text" wire:model="keterangan_lainnya" placeholder="Sebutkan..." style="width: 100%; height: 40px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none;">
                            @error('keterangan_lainnya') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Keterangan / Alasan</label>
                        <textarea wire:model="reason" rows="3" placeholder="Alasan pengajuan..." style="width: 100%; padding: 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none; font-family: inherit; resize: vertical;"></textarea>
                        @error('reason') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- WAKTU CUTI --}}
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--hairline); padding-bottom: 8px;">3. Periode Pengajuan</h3>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Tanggal Pengajuan Cuti Sebelumnya <span style="font-weight: normal; color: var(--muted);">(Kosongkan jika belum pernah)</span></label>
                        <input type="date" wire:model="tanggal_cuti_sebelumnya" style="width: 100%; max-width: 200px; height: 40px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Dari Tanggal</label>
                            <input type="date" wire:model.live="start_date" style="width: 100%; height: 40px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none;">
                            @error('start_date') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Sampai Tanggal</label>
                            <input type="date" wire:model.live="end_date" style="width: 100%; height: 40px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none;">
                            @error('end_date') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Jumlah Hari Kerja</label>
                            <div style="height: 40px; display: flex; align-items: center; padding: 0 12px; background: var(--surface-soft); border-radius: var(--radius-sm); font-weight: 700;">
                                {{ $jumlah_hari }} Hari
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Tanggal Kembali Bekerja</label>
                            <div style="height: 40px; display: flex; align-items: center; padding: 0 12px; background: var(--surface-soft); border-radius: var(--radius-sm); font-weight: 700;">
                                {{ $tanggal_kembali ? \Carbon\Carbon::parse($tanggal_kembali)->format('d M Y') : '-' }}
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">No. Telp Saat Tidak Masuk</label>
                            <input type="text" wire:model="no_telp_darurat" style="width: 100%; height: 40px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none;">
                            @error('no_telp_darurat') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- PENGGANTI --}}
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--hairline); padding-bottom: 8px;">4. Pengganti Selama Tidak Masuk</h3>
                    
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Pilih Staff Pengganti</label>
                    <select wire:model="pengganti_user_id" style="width: 100%; height: 40px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); outline: none; background: white; margin-bottom: 8px;">
                        <option value="">-- Pilih Staff --</option>
                        @foreach($penggantiList as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->divisi ?? 'Tanpa Divisi' }})</option>
                        @endforeach
                    </select>
                    @error('pengganti_user_id') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                    <p style="font-size: 12px; color: var(--muted);">Catatan: Pengganti akan menerima notifikasi dan harus menyetujui di dalam sistem sebelum diajukan ke atasan.</p>
                </div>

                <!-- Lampiran -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Lampiran Bukti (Opsional)</label>
                    <p style="font-size: 11px; color: var(--muted); margin-bottom: 8px;">Wajib untuk Izin Sakit (Surat Dokter) atau Cuti Luar Kota (Tiket). Max 2MB, format PDF/JPG/PNG.</p>
                    <input type="file" wire:model="attachment" accept=".pdf,image/*" style="width: 100%; font-size: 13px;">
                    @error('attachment') <span style="color: var(--error); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    <div wire:loading wire:target="attachment" style="font-size: 12px; color: var(--muted); margin-top: 4px;">Mengunggah lampiran...</div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-pill btn-pill-primary">Kirim Pengajuan Cuti</button>
                </div>

            </form>
        </div>

        {{-- KANAN: PANEL INFO HR & STATUS --}}
        <div>
            <div class="color-block color-block-pink" style="padding: 24px; border-radius: var(--radius-lg); margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 8px;">Keterangan dari HR (Sistem)</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px;">
                    <span style="font-weight: 600;">Hak Cuti Saat Ini:</span>
                    <span>{{ $hakCuti }} Hari</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px;">
                    <span style="font-weight: 600;">Telah Diambil:</span>
                    <span>{{ $sudahDiambil }} Hari</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px;">
                    <span style="font-weight: 600;">Akan Diambil (Form ini):</span>
                    <span>{{ $jumlah_hari }} Hari</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: 700; color: var(--ink); border-top: 1px solid rgba(0,0,0,0.1); padding-top: 12px;">
                    <span>Sisa Hak Cuti (Estimasi):</span>
                    <span style="color: {{ ($sisaCuti - $jumlah_hari) < 0 ? 'var(--error)' : 'var(--ink)' }}">{{ $sisaCuti - $jumlah_hari }} Hari</span>
                </div>
                
                <p style="font-size: 11px; margin-top: 16px; color: rgba(0,0,0,0.6); line-height: 1.5;">Data hak cuti di atas dihitung otomatis oleh sistem dan akan dicek kembali oleh HRD saat proses approval.</p>
            </div>

            <div style="background: #fff; border: 1px solid var(--hairline); border-radius: var(--radius-lg); padding: 24px;">
                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 16px;">Alur Persetujuan</h3>
                <ul style="padding-left: 16px; margin: 0; font-size: 13px; line-height: 1.6; color: var(--muted);">
                    <li><strong>Pemohon:</strong> Ttd Otomatis saat Submit</li>
                    <li><strong>Staff Pengganti:</strong> Konfirmasi ketersediaan</li>
                    <li><strong>Atasan Langsung:</strong> Review & Setuju</li>
                    <li><strong>HRD:</strong> Validasi sisa hak cuti</li>
                    <li><strong>Manager / GM:</strong> Persetujuan Final</li>
                </ul>
            </div>
        </div>

    </div>
</div>
