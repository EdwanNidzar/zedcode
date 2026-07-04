<div>
    <div class="color-block color-block-navy" style="padding: 32px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="block-title" style="margin-bottom: 4px; font-size: 24px;">Daftar Pengguna</h2>
            <p class="block-desc" style="font-size: 14px;">Kelola peran dan hak akses pengguna dalam sistem.</p>
        </div>
        <div style="position: relative; width: 300px;">
            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." 
                style="width: 100%; height: 44px; padding: 0 16px 0 40px; border-radius: var(--radius-md); border: none; outline: none; font-family: inherit; font-size: 14px; color: var(--ink);">
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
                        <td style="padding: 16px 24px; display: flex; flex-wrap: wrap; gap: 6px;">
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
                                    <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Yakin ingin menghapus pengguna ini?" class="btn-pill" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 14px; font-size: 12px; cursor: pointer;">Hapus</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: var(--muted);">Belum ada pengguna terdaftar atau tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 24px;">
        {{ $users->links() }}
    </div>
</div>
