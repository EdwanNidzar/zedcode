<div>
    {{-- HEADER --}}
    <div class="color-block color-block-coral" style="padding: 32px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 class="block-title" style="margin-bottom: 4px; font-size: 24px; color: var(--block-navy)">Kelola Role & Akses Terpadu</h2>
            <p class="block-desc" style="font-size: 14px; color: rgba(30,27,75,0.7)">Atur seluruh peran (Role) dan hak akses (Permission) pengguna di satu tempat.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 32px;">
        
        {{-- BAGIAN 1: ROLE MANAGEMENT --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Daftar Role</h3>
                <div style="display: flex; gap: 12px;">
                    <div style="position: relative; width: 250px;">
                        <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input wire:model.live.debounce.300ms="searchRole" type="text" placeholder="Cari role..." 
                            style="width: 100%; height: 36px; padding: 0 16px 0 36px; border-radius: var(--radius-sm); border: 1px solid var(--hairline); outline: none; font-size: 13px;">
                    </div>
                    <button wire:click="openRoleModal" class="btn-pill btn-pill-primary" style="background: var(--block-navy); color: var(--canvas); padding: 0 16px; height: 36px; font-size: 13px;">+ Tambah Role</button>
                </div>
            </div>

            @if(session('success_role'))
                <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 13px;">
                    {{ session('success_role') }}
                </div>
            @endif
            @if(session('error_role'))
                <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 13px;">
                    {{ session('error_role') }}
                </div>
            @endif

            <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-md); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: var(--surface-soft); border-bottom: 1px solid var(--hairline);">
                            <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Nama Role</th>
                            <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Permissions</th>
                            <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr style="border-bottom: 1px solid var(--hairline-soft);">
                                <td style="padding: 12px 16px;">
                                    <div style="font-weight: 600; font-size: 14px; color: var(--ink);">{{ $role->name }}</div>
                                    <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">{{ $role->users()->count() }} pengguna</div>
                                </td>
                                <td style="padding: 12px 16px; display: flex; flex-wrap: wrap; gap: 4px;">
                                    @if($role->name === 'Super Admin')
                                        <span style="background: var(--ink); color: var(--canvas); padding: 2px 8px; border-radius: var(--radius-pill); font-size: 10px; font-weight: 600; text-transform: uppercase;">All Access</span>
                                    @elseif($role->permissions->count() > 0)
                                        @foreach($role->permissions as $perm)
                                            <span style="background: var(--surface-soft); border: 1px solid var(--hairline); color: var(--muted); padding: 2px 8px; border-radius: var(--radius-pill); font-size: 11px;">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span style="color: var(--muted); font-size: 12px; font-style: italic;">Tidak ada permission</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <button wire:click="openRoleModal({{ $role->id }})" class="btn-pill btn-pill-secondary" style="padding: 4px 12px; font-size: 11px;">Edit</button>
                                        
                                        @can('delete role')
                                            @if($role->name !== 'Super Admin')
                                                <button wire:click="deleteRole({{ $role->id }})" wire:confirm="Yakin ingin menghapus role ini?" class="btn-pill" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 12px; font-size: 11px; cursor: pointer;">Hapus</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 32px; text-align: center; color: var(--muted);">Belum ada role.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 16px;">
                {{ $roles->links() }}
            </div>
        </div>

        {{-- BAGIAN 2: PERMISSION MANAGEMENT --}}
        <div style="border-top: 1px solid var(--hairline); padding-top: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Master Permissions</h3>
                <div style="display: flex; gap: 12px;">
                    <div style="position: relative; width: 250px;">
                        <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input wire:model.live.debounce.300ms="searchPermission" type="text" placeholder="Cari permission..." 
                            style="width: 100%; height: 36px; padding: 0 16px 0 36px; border-radius: var(--radius-sm); border: 1px solid var(--hairline); outline: none; font-size: 13px;">
                    </div>
                    <button wire:click="openPermissionModal" class="btn-pill btn-pill-secondary" style="background: white; color: var(--ink); padding: 0 16px; height: 36px; font-size: 13px;">+ Tambah Permission</button>
                </div>
            </div>

            @if(session('success_permission'))
                <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 13px;">
                    {{ session('success_permission') }}
                </div>
            @endif

            <div style="background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-md); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: var(--surface-soft); border-bottom: 1px solid var(--hairline);">
                            <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Nama Permission</th>
                            <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase;">Digunakan Oleh Role</th>
                            <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: var(--muted); text-transform: uppercase; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissionsList as $perm)
                            <tr style="border-bottom: 1px solid var(--hairline-soft);">
                                <td style="padding: 12px 16px; font-weight: 600; font-size: 13px; color: var(--ink);">
                                    {{ $perm->name }}
                                </td>
                                <td style="padding: 12px 16px; display: flex; flex-wrap: wrap; gap: 4px;">
                                    @forelse($perm->roles as $role)
                                        <span style="background: var(--surface-soft); border: 1px solid var(--hairline); color: var(--muted); padding: 2px 8px; border-radius: var(--radius-pill); font-size: 11px;">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span style="color: var(--muted); font-size: 12px; font-style: italic;">-</span>
                                    @endforelse
                                </td>
                                <td style="padding: 12px 16px; text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <button wire:click="openPermissionModal({{ $perm->id }})" class="btn-pill btn-pill-secondary" style="padding: 4px 12px; font-size: 11px;">Edit</button>
                                        
                                        @can('delete permission')
                                        <button wire:click="deletePermission({{ $perm->id }})" wire:confirm="Hapus permission permanen?" class="btn-pill" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 12px; font-size: 11px; cursor: pointer;">Hapus</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 32px; text-align: center; color: var(--muted);">Belum ada permission.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 16px;">
                {{ $permissionsList->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL ROLE --}}
    @if($isRoleModalOpen)
    <div style="position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center;">
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);" wire:click="closeRoleModal"></div>
        <div style="position: relative; background: var(--canvas); width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: var(--radius-lg); padding: 32px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 24px;">{{ $roleId ? 'Edit Role' : 'Buat Role Baru' }}</h3>

            <form wire:submit.prevent="saveRole">
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Nama Role</label>
                    <input type="text" wire:model="name" required placeholder="Contoh: Finance, Manager IT"
                        style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;"
                        @if($roleId && $name === 'Super Admin') readonly style="background: var(--surface-soft)" @endif>
                    @error('name') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                </div>

                @if(!($roleId && $name === 'Super Admin'))
                <div style="margin-bottom: 32px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 12px; display: block;">Pilih Permissions</label>

                    {{-- Add Permission on the fly --}}
                    <div style="display: flex; gap: 8px; margin-bottom: 16px; padding: 12px; background: var(--surface-soft); border-radius: var(--radius-md);">
                        <input type="text" wire:model="newPermissionName" placeholder="Tambah permission baru..."
                            style="flex: 1; height: 36px; padding: 0 12px; border: 1.5px solid var(--hairline); border-radius: var(--radius-sm); font-size: 13px; outline: none;">
                        <button type="button" wire:click="createPermissionOnTheFly" class="btn-pill" style="background: var(--ink); color: var(--canvas); padding: 0 16px; font-size: 13px;">Tambah</button>
                    </div>
                    @error('newPermissionName') <p style="color: var(--error); font-size: 12px; margin-top: -10px; margin-bottom: 10px;">{{ $message }}</p> @enderror
                    @if(session('permission_success')) <p style="color: var(--accent); font-size: 12px; margin-top: -10px; margin-bottom: 10px;">{{ session('permission_success') }}</p> @endif

                    {{-- Permission Checkboxes --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; max-height: 200px; overflow-y: auto; padding-right: 8px;">
                        @forelse($allPermissions as $perm)
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}" style="accent-color: var(--ink); width: 16px; height: 16px;">
                            {{ $perm->name }}
                        </label>
                        @empty
                        <div style="grid-column: span 2; font-size: 13px; color: var(--muted); text-align: center;">Belum ada permission.</div>
                        @endforelse
                    </div>
                </div>
                @else
                <div style="padding: 16px; background: #ede9fe; color: #5b21b6; border-radius: var(--radius-md); font-size: 13px; margin-bottom: 24px;">
                    Role Super Admin secara otomatis memiliki akses penuh.
                </div>
                @endif

                <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid var(--hairline);">
                    <button type="button" wire:click="closeRoleModal" class="btn-pill btn-pill-secondary">Batal</button>
                    <button type="submit" class="btn-pill btn-pill-primary">Simpan Role</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL PERMISSION --}}
    @if($isPermissionModalOpen)
    <div style="position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center;">
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);" wire:click="closePermissionModal"></div>
        <div style="position: relative; background: var(--canvas); width: 100%; max-width: 500px; border-radius: var(--radius-lg); padding: 32px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 24px;">{{ $permissionId ? 'Edit Permission' : 'Buat Permission Baru' }}</h3>

            <form wire:submit.prevent="savePermission">
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Nama Permission</label>
                    <input type="text" wire:model="permissionName" required placeholder="Contoh: approve leave"
                        style="width: 100%; height: 44px; padding: 0 16px; border: 1.5px solid var(--hairline); border-radius: var(--radius-md); outline: none;">
                    @error('permissionName') <p style="color: var(--error); font-size: 12px; margin-top: 6px;">{{ $message }}</p> @enderror
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid var(--hairline);">
                    <button type="button" wire:click="closePermissionModal" class="btn-pill btn-pill-secondary">Batal</button>
                    <button type="submit" class="btn-pill btn-pill-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
