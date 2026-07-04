<x-app-layout title="Edit Pengguna">

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('admin.users.index') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--canvas); border: 1px solid var(--hairline); color: var(--ink); text-decoration: none;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
        <h2 style="font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">Edit Pengguna & Role</h2>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        
        {{-- Form Panel --}}
        <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-lg); padding: 32px;">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; outline: none;">
                    @error('name') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="email" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; outline: none;">
                    @error('email') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 32px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 12px;">Pilih Hak Akses (Role)</label>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($roles as $role)
                        <label style="display: flex; align-items: flex-start; gap: 12px; padding: 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); cursor: pointer; transition: all 0.15s;"
                               onmouseover="this.style.borderColor='var(--ink)'" onmouseout="this.style.borderColor='var(--hairline)'">
                            <input type="checkbox" name="roles[]" value="{{ $role }}" {{ in_array($role, $userRole) ? 'checked' : '' }}
                                style="margin-top: 2px; accent-color: var(--ink); width: 16px; height: 16px;">
                            <div>
                                <div style="font-weight: 600; font-size: 15px; color: var(--ink);">{{ $role }}</div>
                                <div style="font-size: 13px; color: var(--muted); margin-top: 4px;">
                                    @if($role === 'Super Admin')
                                        Akses penuh ke seluruh sistem termasuk kelola pengguna.
                                    @elseif($role === 'HR / Manager')
                                        Dapat menyetujui/menolak pengajuan cuti dan barang.
                                    @else
                                        Akses standar untuk karyawan mengajukan permintaan.
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('roles') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-pill btn-pill-primary" style="flex: 1; padding: 12px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- Info Panel --}}
        <div>
            <div class="color-block color-block-lilac" style="padding: 32px;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">Info Akun</h3>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; color: rgba(10,10,10,0.8); line-height: 1.8;">
                    <li><strong>Status:</strong> Aktif</li>
                    <li><strong>Terdaftar pada:</strong> {{ $user->created_at->format('d M Y H:i') }}</li>
                    <li><strong>Terakhir Diperbarui:</strong> {{ $user->updated_at->format('d M Y H:i') }}</li>
                </ul>
            </div>
            
            <div style="background: #fffbfa; border: 1px solid #ffdfd6; border-radius: var(--radius-lg); padding: 24px; margin-top: 24px;">
                <h4 style="color: #c2410c; font-weight: 700; margin-bottom: 8px; font-size: 15px;">Area Berbahaya</h4>
                <p style="font-size: 13px; color: #9a3412; margin-bottom: 16px;">Menghapus akun bersifat permanen dan tidak dapat dibatalkan.</p>
                @if(auth()->id() !== $user->id)
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Hapus permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer;">Hapus Pengguna</button>
                </form>
                @else
                <p style="font-size: 13px; font-weight: 600; color: #9a3412;">Anda tidak dapat menghapus akun Anda sendiri.</p>
                @endif
            </div>
        </div>

    </div>

</x-app-layout>
