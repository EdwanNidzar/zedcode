<div>
    <div class="color-block color-block-pink" style="padding: 32px; margin-bottom: 24px;">
        <h2 class="block-title" style="margin-bottom: 4px; font-size: 24px;">Profil Karyawan</h2>
        <p class="block-desc" style="font-size: 14px; color: rgba(30,27,75,0.7)">Lengkapi data diri Anda. Data ini akan otomatis masuk ke setiap form pengajuan.</p>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-lg); padding: 32px;">
        <form wire:submit.prevent="save">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                
                {{-- Kiri --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">NIK (Nomor Induk Karyawan)</label>
                        <input type="text" wire:model="nik" placeholder="Masukkan NIK Anda" style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;">
                        @error('nik') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Jabatan</label>
                        <input type="text" wire:model="jabatan" placeholder="Contoh: Staff IT, Marketing Manager" style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Divisi</label>
                        <input type="text" wire:model="divisi" placeholder="Contoh: IT, Marketing, Finance" style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;">
                    </div>
                </div>

                {{-- Kanan --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Unit Kerja</label>
                        <input type="text" wire:model="unit_kerja" placeholder="Contoh: Kantor Pusat, Cabang X" style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Nomor Telepon (Aktif)</label>
                        <input type="text" wire:model="phone" placeholder="Contoh: 08123456789" style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Atasan Langsung</label>
                        <select wire:model="atasan_id" style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none; background: white;">
                            <option value="">-- Pilih Atasan Langsung --</option>
                            @foreach($atasans as $atasan)
                                <option value="{{ $atasan->id }}">{{ $atasan->name }} ({{ $atasan->email }})</option>
                            @endforeach
                        </select>
                        <p style="font-size: 11px; color: var(--muted); margin-top: 6px;">Pilih orang yang akan menyetujui pengajuan cuti Anda pertama kali.</p>
                    </div>
                </div>
                
            </div>
            
            <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--hairline); text-align: right;">
                <button type="submit" class="btn-pill btn-pill-primary">Simpan Profil</button>
            </div>
        </form>
    </div>
</div>
