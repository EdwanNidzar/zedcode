<div>
@php
    $levelColors = [
        1 => ['bg' => '#fef9c3', 'text' => '#854d0e', 'border' => '#fde047'],
        2 => ['bg' => '#dbeafe', 'text' => '#1e40af', 'border' => '#93c5fd'],
        3 => ['bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
        4 => ['bg' => '#ffedd5', 'text' => '#9a3412', 'border' => '#fdba74'],
        5 => ['bg' => '#f3e8ff', 'text' => '#6b21a8', 'border' => '#c4b5fd'],
        6 => ['bg' => '#e0f2fe', 'text' => '#0c4a6e', 'border' => '#7dd3fc'],
    ];
    $color = $levelColors[$activeLevel] ?? $levelColors[2];
@endphp

<style>
    .chain-tabs { display: flex; gap: 4px; flex-wrap: wrap; padding: 16px 20px 0; border-bottom: 1px solid var(--hairline); background: var(--surface-soft); }
    .chain-tab {
        padding: 8px 16px; border-radius: 8px 8px 0 0; font-size: 13px; font-weight: 500;
        cursor: pointer; border: 1px solid transparent; border-bottom: none; transition: all 0.15s;
        background: transparent; color: var(--muted);
    }
    .chain-tab:hover { background: var(--canvas); color: var(--ink); }
    .chain-tab.active {
        background: var(--canvas); color: var(--ink); font-weight: 600;
        border-color: var(--hairline); border-bottom-color: var(--canvas);
        margin-bottom: -1px;
    }

    .chain-card { background: var(--canvas); border: 1px solid var(--hairline); border-radius: var(--radius-lg); overflow: hidden; }

    .chain-table { width: 100%; border-collapse: collapse; }
    .chain-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); padding: 12px 16px; text-align: left; background: var(--surface-soft); border-bottom: 1px solid var(--hairline); }
    .chain-table td { padding: 14px 16px; border-bottom: 1px solid var(--hairline-soft); vertical-align: middle; font-size: 14px; }
    .chain-table tr:last-child td { border-bottom: none; }
    .chain-table tr.inactive td { opacity: 0.45; }
    .chain-table tr:hover td { background: #fafafa; }

    .step-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; font-size: 12px; font-weight: 700; background: var(--ink); color: var(--canvas); }
    .step-badge.inactive { background: var(--hairline); color: var(--muted); }

    .role-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 50px; font-size: 13px; font-weight: 500; }

    .level-pill { display: inline-block; padding: 2px 8px; border-radius: 50px; font-size: 11px; font-weight: 700; font-family: monospace; }

    .toggle-btn { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; transition: all 0.15s; }
    .toggle-btn.on  { background: #dcfce7; color: #15803d; }
    .toggle-btn.off { background: var(--surface-soft); color: var(--muted); }
    .toggle-btn:hover { filter: brightness(0.95); }

    .move-btn { background: none; border: 1px solid var(--hairline); width: 26px; height: 26px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--muted); transition: all 0.15s; font-size: 11px; }
    .move-btn:hover { background: var(--ink); color: var(--canvas); border-color: var(--ink); }
    .move-btn:disabled { opacity: 0.3; cursor: not-allowed; }
    .move-btn:disabled:hover { background: none; color: var(--muted); border-color: var(--hairline); }

    .del-btn { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 12px; padding: 4px 8px; border-radius: 6px; transition: all 0.15s; }
    .del-btn:hover { background: #fee2e2; color: #dc2626; }

    .add-form { background: var(--surface-soft); border: 1px solid var(--hairline); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
    .add-form label { font-size: 12px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .add-form select, .add-form input[type="number"] {
        border: 1px solid var(--hairline); border-radius: var(--radius-sm); padding: 8px 12px;
        font-size: 13px; background: var(--canvas); color: var(--ink);
        outline: none; transition: border-color 0.15s;
    }
    .add-form select:focus, .add-form input:focus { border-color: var(--ink); }

    .preview-flow { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-top: 20px; padding: 16px; background: var(--surface-soft); border-radius: var(--radius-md); }
    .preview-node { padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; border: 1px solid var(--hairline); background: var(--canvas); }
    .preview-node.requester { background: var(--ink); color: var(--canvas); border-color: var(--ink); }
    .preview-node.approver { background: var(--canvas); color: var(--ink); }
    .preview-arrow { color: var(--muted); font-size: 16px; }

    .flash-success {
        display: flex; align-items: center; gap: 10px;
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-md);
        padding: 12px 16px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #15803d;
    }
    .warning-bar {
        display: flex; align-items: flex-start; gap: 10px;
        background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-md);
        padding: 12px 16px; margin-top: 20px; font-size: 13px; color: #92400e;
    }
</style>
    {{-- PAGE HEADER --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px;">
        <div>
            <h2 style="font-size:22px; font-weight:700; letter-spacing:-0.5px; margin-bottom:4px;">Rantai Approval Cuti</h2>
            <p style="font-size:14px; color:var(--muted);">Konfigurasi siapa yang menyetujui cuti berdasarkan jabatan pemohon. Hanya berlaku untuk pengajuan <strong>baru</strong>.</p>
        </div>
    </div>

    {{-- FLASH --}}
    @if (session()->has('success'))
        <div class="flash-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="chain-card">
        {{-- TABS --}}
        <div class="chain-tabs">
            @foreach ($levels as $lvl => $label)
                <button type="button" wire:click="setTab({{ $lvl }})"
                    class="chain-tab {{ $activeLevel === $lvl ? 'active' : '' }}">
                    {{ $label }}
                    @php
                        $cnt = \App\Models\ApprovalChainConfig::where('requester_level', $lvl)->where('is_active', true)->count();
                    @endphp
                    @if($cnt > 0)
                        <span style="margin-left:6px; background:var(--ink); color:var(--canvas); border-radius:50%; width:18px; height:18px; display:inline-flex; align-items:center; justify-content:center; font-size:10px; font-weight:700;">{{ $cnt }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- TAB BODY --}}
        <div style="padding: 24px;">

            {{-- Level Indicator + Actions --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:{{ $color['border'] }};"></span>
                    <span style="font-size:15px; font-weight:600; color:var(--ink);">{{ $levels[$activeLevel] }}</span>
                    <span style="font-size:12px; color:var(--muted);">— level {{ $activeLevel }}</span>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="button" wire:click="resetToDefault"
                        wire:confirm="Reset ke default? Semua konfigurasi custom untuk level ini akan dihapus."
                        style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:var(--radius-pill); border:1px solid var(--hairline); background:var(--canvas); font-size:13px; font-weight:500; cursor:pointer; color:var(--ink); transition:all 0.15s;"
                        onmouseover="this.style.borderColor='var(--ink)'" onmouseout="this.style.borderColor='var(--hairline)'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        Reset Default
                    </button>
                    <button type="button" wire:click="openAdd"
                        class="btn-pill btn-pill-primary" style="padding:7px 16px; font-size:13px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Step
                    </button>
                </div>
            </div>

            {{-- Add Step Form --}}
            @if ($isAdding)
                <div class="add-form">
                    <div>
                        <label>Role Approver</label>
                        <select wire:model="newRole" style="min-width:180px;">
                            <option value="">— Pilih Role —</option>
                            @foreach ($availableRoles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                        @error('newRole') <div style="font-size:11px; color:var(--error); margin-top:3px;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label>Urutan Step</label>
                        <input type="number" wire:model="newOrder" min="1" style="width:80px;">
                        @error('newOrder') <div style="font-size:11px; color:var(--error); margin-top:3px;">{{ $message }}</div> @enderror
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="button" wire:click="addStep" class="btn-pill btn-pill-primary" style="padding:8px 18px; font-size:13px;">Simpan</button>
                        <button type="button" wire:click="$set('isAdding', false)" class="btn-pill btn-pill-secondary" style="padding:8px 18px; font-size:13px;">Batal</button>
                    </div>
                </div>
            @endif

            {{-- Empty State --}}
            @if ($configs->isEmpty())
                <div style="text-align:center; padding:48px 24px; color:var(--muted);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px; display:block; opacity:0.3;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <p style="font-size:14px; font-weight:500; margin-bottom:4px;">Belum ada konfigurasi</p>
                    <p style="font-size:13px;">Tambah step baru atau klik <strong>Reset Default</strong> untuk mulai.</p>
                </div>

            {{-- Table --}}
            @else
                <table class="chain-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">Step</th>
                            <th>Role Approver</th>
                            <th style="width:90px; text-align:center;">Level</th>
                            <th style="width:110px; text-align:center;">Status</th>
                            <th style="width:100px; text-align:center;">Urutan</th>
                            <th style="width:70px; text-align:center;">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($configs as $i => $config)
                            <tr class="{{ $config->is_active ? '' : 'inactive' }}">
                                <td>
                                    <div class="step-badge {{ $config->is_active ? '' : 'inactive' }}">
                                        {{ $config->step_order }}
                                    </div>
                                </td>
                                <td>
                                    <span class="role-chip"
                                        style="background:{{ $color['bg'] }}; color:{{ $color['text'] }}; border:1px solid {{ $color['border'] }};">
                                        {{ $config->approver_role }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="level-pill"
                                        style="background:{{ $color['bg'] }}; color:{{ $color['text'] }};">
                                        Lv.{{ $config->approver_level }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <button type="button" wire:click="toggleActive({{ $config->id }})"
                                        class="toggle-btn {{ $config->is_active ? 'on' : 'off' }}">
                                        @if($config->is_active)
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            Aktif
                                        @else
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Nonaktif
                                        @endif
                                    </button>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:inline-flex; gap:4px;">
                                        <button type="button" wire:click="moveUp({{ $config->id }})"
                                            class="move-btn" @if($i === 0) disabled @endif title="Naikkan">▲</button>
                                        <button type="button" wire:click="moveDown({{ $config->id }})"
                                            class="move-btn" @if($i === $configs->count() - 1) disabled @endif title="Turunkan">▼</button>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <button type="button"
                                        wire:click="deleteStep({{ $config->id }})"
                                        wire:confirm="Hapus step '{{ $config->approver_role }}' dari rantai ini?"
                                        class="del-btn" title="Hapus">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Flow Preview --}}
                <div class="preview-flow">
                    <span style="font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px; margin-right:4px;">Preview:</span>
                    <span class="preview-node requester">{{ $levels[$activeLevel] }}</span>
                    @foreach ($configs->where('is_active', true) as $step)
                        <span class="preview-arrow">→</span>
                        <span class="preview-node approver"
                            style="border-color:{{ $color['border'] }}; color:{{ $color['text'] }}; background:{{ $color['bg'] }};">
                            {{ $step->approver_role }}
                        </span>
                    @endforeach
                    @if ($configs->where('is_active', true)->isEmpty())
                        <span style="font-size:12px; color:var(--error); font-style:italic;">⚠ Tidak ada step aktif</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Warning Bar --}}
    <div class="warning-bar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span>Perubahan hanya berlaku untuk <strong>pengajuan cuti baru</strong>. Pengajuan yang sedang berjalan tidak terpengaruh.</span>
    </div>
</div>
