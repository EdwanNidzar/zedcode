<x-app-layout title="Kelola Pengguna">

    <div class="color-block color-block-navy" style="padding: 32px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="block-title" style="margin-bottom: 4px; font-size: 24px;">Daftar Pengguna</h2>
            <p class="block-desc" style="font-size: 14px;">Kelola peran dan hak akses pengguna dalam sistem.</p>
        </div>
        {{-- Optional Add Button: <a href="#" class="btn-pill btn-pill-primary" style="background: var(--block-lime); color: var(--ink);">+ Tambah User</a> --}}
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

    <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-lg); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--surface-soft); border-bottom: 1px solid var(--hairline);">
                    <th style="padding: 16px 24px; font-weight: 600; font-size: 13px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama & Email</th>
                    <th style="padding: 16px 24px; font-weight: 600; font-size: 13px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Role / Hak Akses</th>
                    <th style="padding: 16px 24px; font-weight: 600; font-size: 13px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Tgl Bergabung</th>
                    <th style="padding: 16px 24px; font-weight: 600; font-size: 13px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid var(--hairline-soft);">
                        <td style="padding: 16px 24px;">
                            <div style="font-weight: 600; font-size: 15px; color: var(--ink);">{{ $user->name }}</div>
                            <div style="font-size: 13px; color: var(--muted); margin-top: 2px;">{{ $user->email }}</div>
                        </td>
                        <td style="padding: 16px 24px;">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    @php
                                        $bg = '#f3f4f6'; $color = '#4b5563';
                                        if($role->name === 'Super Admin') { $bg = '#ede9fe'; $color = '#7c3aed'; }
                                        elseif($role->name === 'HR / Manager') { $bg = '#ffedd5'; $color = '#ea580c'; }
                                    @endphp
                                    <span style="background: {{ $bg }}; color: {{ $color }}; padding: 4px 10px; border-radius: var(--radius-pill); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span style="color: var(--muted); font-size: 13px;">Belum ada role</span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px; font-size: 14px; color: var(--ink);">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td style="padding: 16px 24px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-pill btn-pill-secondary" style="padding: 6px 14px; font-size: 12px;">Edit Role</a>
                                
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-pill" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 14px; font-size: 12px; cursor: pointer;">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: var(--muted);">Belum ada pengguna terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 24px;">
        {{ $users->links() }}
    </div>

</x-app-layout>
