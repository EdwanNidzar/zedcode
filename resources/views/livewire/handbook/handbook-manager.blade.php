<div>

    {{-- Quill.js CDN --}}
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js" defer></script>

    <style>
        /* ── Layout Manager ───────────────────────────────────── */
        .hbm-wrap {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* ── Page Hero ─────────────────────────────────────────── */
        .hbm-hero {
            background: var(--block-navy);
            color: var(--canvas);
            border-radius: var(--radius-lg);
            padding: 36px 40px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .hbm-hero-eyebrow {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--block-lime);
            margin-bottom: 6px;
        }

        .hbm-hero-title {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.8px;
        }

        .hbm-hero-desc {
            font-size: 14px;
            line-height: 1.5;
            color: rgba(255,255,255,0.65);
            margin-top: 6px;
            max-width: 480px;
        }

        .hbm-hero-actions { display: flex; gap: 10px; flex-shrink: 0; }

        /* ── Flash Message ─────────────────────────────────────── */
        .hbm-flash {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hbm-flash-success { background: var(--block-lime); color: var(--ink); }
        .hbm-flash-error   { background: #fee2e2; color: #991b1b; }

        /* ── Tab Bar ───────────────────────────────────────────── */
        .hbm-tabs {
            display: flex;
            gap: 4px;
            background: var(--surface-soft);
            padding: 4px;
            border-radius: var(--radius-pill);
            margin-bottom: 24px;
            width: fit-content;
        }

        .hbm-tab {
            padding: 8px 20px;
            border-radius: var(--radius-pill);
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            color: var(--muted);
            background: transparent;
        }

        .hbm-tab.active {
            background: var(--ink);
            color: var(--canvas);
        }

        /* ── Split Layout (List + Editor) ─────────────────────── */
        .hbm-split {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 24px;
            align-items: start;
        }

        /* ── List Panel ────────────────────────────────────────── */
        .hbm-panel {
            background: var(--canvas);
            border: 1px solid var(--hairline);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .hbm-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--hairline);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .hbm-panel-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
        }

        /* Search Input */
        .hbm-search {
            padding: 7px 12px 7px 32px;
            border: 1px solid var(--hairline);
            border-radius: var(--radius-pill);
            font-size: 12px;
            width: 160px;
            background: var(--surface-soft);
            color: var(--ink);
            outline: none;
            transition: border-color 0.15s, width 0.2s;
        }

        .hbm-search:focus { border-color: var(--ink); width: 200px; }

        .hbm-search-wrap { position: relative; }
        .hbm-search-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--muted); }

        /* Article/Category Row */
        .hbm-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--hairline-soft);
            transition: background 0.1s;
        }

        .hbm-row:last-child { border-bottom: none; }
        .hbm-row:hover { background: var(--surface-soft); }

        .hbm-row-main { flex: 1; min-width: 0; }

        .hbm-row-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hbm-row-meta { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .hbm-row-actions {
            display: flex;
            gap: 6px;
            flex-shrink: 0;
        }

        /* Status Badge */
        .hbm-badge {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: var(--radius-pill);
            cursor: pointer;
            border: none;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .hbm-badge-published { background: var(--block-lime); color: var(--ink); }
        .hbm-badge-draft     { background: var(--surface-soft); color: var(--muted); border: 1px solid var(--hairline); }

        /* Icon Buttons */
        .hbm-icon-btn {
            width: 30px; height: 30px;
            border-radius: 50%;
            border: 1px solid var(--hairline);
            background: var(--canvas);
            color: var(--muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .hbm-icon-btn:hover { background: var(--ink); color: var(--canvas); border-color: var(--ink); }
        .hbm-icon-btn svg   { width: 13px; height: 13px; }

        .hbm-icon-btn-danger:hover { background: #dc2626; border-color: #dc2626; }

        /* Empty State */
        .hbm-empty {
            padding: 40px 20px;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        /* ── Editor Panel ──────────────────────────────────────── */
        .hbm-editor-panel {
            background: var(--canvas);
            border: 1px solid var(--hairline);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .hbm-editor-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--hairline);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hbm-editor-title { font-size: 14px; font-weight: 700; }

        .hbm-editor-body { padding: 24px; }

        /* Form Fields */
        .hbm-form-group { margin-bottom: 18px; }

        .hbm-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 18px;
        }

        .hbm-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .hbm-input,
        .hbm-select {
            width: 100%;
            padding: 9px 14px;
            border: 1px solid var(--hairline);
            border-radius: var(--radius-md);
            font-size: 13px;
            color: var(--ink);
            background: var(--canvas);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            font-family: inherit;
        }

        .hbm-input:focus,
        .hbm-select:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(10,10,10,0.06);
        }

        .hbm-input.error { border-color: #dc2626; }

        .hbm-error-msg { font-size: 11px; color: #dc2626; margin-top: 4px; }

        /* Quill Editor */
        .hbm-quill-wrap {
            border: 1px solid var(--hairline);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: box-shadow 0.15s;
        }

        .hbm-quill-wrap:focus-within {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(10,10,10,0.06);
        }

        #quill-editor { min-height: 280px; font-size: 14px; font-family: inherit; }

        /* Override Quill styles agar konsisten */
        .ql-toolbar { border: none !important; border-bottom: 1px solid var(--hairline) !important; background: var(--surface-soft); }
        .ql-container { border: none !important; }
        .ql-editor { font-family: 'Inter', system-ui, sans-serif; font-size: 14px; line-height: 1.7; }

        /* Status Toggle (Pills) */
        .hbm-status-toggle {
            display: flex;
            gap: 4px;
            background: var(--surface-soft);
            padding: 4px;
            border-radius: var(--radius-pill);
            width: fit-content;
        }

        .hbm-status-opt {
            padding: 6px 16px;
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            color: var(--muted);
            background: transparent;
        }

        .hbm-status-opt.active-draft      { background: var(--ink); color: var(--canvas); }
        .hbm-status-opt.active-published  { background: var(--block-lime); color: var(--ink); }

        /* Form Actions */
        .hbm-form-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid var(--hairline-soft);
            margin-top: 24px;
        }

        /* Category Icon Preview */
        .hbm-icon-preview {
            width: 40px; height: 40px;
            background: var(--block-lime);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hbm-split { grid-template-columns: 1fr; }
        }
    </style>

    <div class="hbm-wrap" wire:key="handbook-manager">

        {{-- Hero --}}
        <div class="hbm-hero">
            <div>
                <div class="hbm-hero-eyebrow">Admin Panel</div>
                <h1 class="hbm-hero-title">Kelola Handbook Zedpos</h1>
                <p class="hbm-hero-desc">Buat, edit, dan atur artikel panduan penggunaan Zedpos. Artikel yang dipublikasikan akan langsung terlihat oleh semua pengguna.</p>
            </div>
            <div class="hbm-hero-actions">
                <a href="{{ route('handbook.index') }}" class="btn-pill btn-pill-secondary" style="font-size:13px; color: var(--canvas); border-color: rgba(255,255,255,0.2);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Preview Handbook
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="hbm-flash hbm-flash-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="hbm-flash hbm-flash-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Tab Bar --}}
        <div class="hbm-tabs">
            <button
                class="hbm-tab {{ $activeTab === 'articles' ? 'active' : '' }}"
                wire:click="switchTab('articles')"
            >
                📄 Artikel
                <span style="font-size:10px; opacity:0.6; margin-left:4px;">({{ $articles->count() }})</span>
            </button>
            <button
                class="hbm-tab {{ $activeTab === 'categories' ? 'active' : '' }}"
                wire:click="switchTab('categories')"
            >
                📁 Kategori
                <span style="font-size:10px; opacity:0.6; margin-left:4px;">({{ $categories->count() }})</span>
            </button>
        </div>

        {{-- ══════════════════════ ARTIKEL TAB ══════════════════════ --}}
        @if($activeTab === 'articles')
        <div class="hbm-split">

            {{-- LIST --}}
            <div class="hbm-panel">
                <div class="hbm-panel-header">
                    <span class="hbm-panel-title">Semua Artikel</span>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <div class="hbm-search-wrap">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="hbm-search" placeholder="Cari artikel..." wire:model.live.debounce.300ms="search">
                        </div>
                        <button class="btn-pill btn-pill-primary" style="font-size:12px; padding:7px 16px;" wire:click="createArticle">
                            + Buat Artikel
                        </button>
                    </div>
                </div>

                @forelse($articles as $art)
                    <div class="hbm-row">
                        <div class="hbm-row-main">
                            <div class="hbm-row-title">{{ $art->title }}</div>
                            <div class="hbm-row-meta">{{ $art->category->name }} · #{{ $art->sort_order }}</div>
                        </div>

                        <button
                            class="hbm-badge {{ $art->status === 'published' ? 'hbm-badge-published' : 'hbm-badge-draft' }}"
                            wire:click="toggleArticleStatus({{ $art->id }})"
                            title="Klik untuk toggle status"
                        >
                            {{ $art->status === 'published' ? 'Published' : 'Draft' }}
                        </button>

                        <div class="hbm-row-actions">
                            <button class="hbm-icon-btn" wire:click="editArticle({{ $art->id }})" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="hbm-icon-btn hbm-icon-btn-danger" wire:click="deleteArticle({{ $art->id }})" wire:confirm="Yakin ingin menghapus artikel '{{ $art->title }}'?" title="Hapus">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="hbm-empty">
                        Belum ada artikel. Klik <strong>"+ Buat Artikel"</strong> untuk memulai.
                    </div>
                @endforelse
            </div>

            {{-- EDITOR --}}
            @if($editorMode !== 'list')
            <div class="hbm-editor-panel" wire:key="article-editor-{{ $editingArticleId ?? 'new' }}">
                <div class="hbm-editor-header">
                    <span class="hbm-editor-title">
                        {{ $editingArticleId ? '✏️ Edit Artikel' : '✨ Buat Artikel Baru' }}
                    </span>
                    <button class="hbm-icon-btn" wire:click="cancelArticleEdit" title="Batal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="hbm-editor-body">
                    {{-- Judul --}}
                    <div class="hbm-form-group">
                        <label class="hbm-label">Judul Artikel <span style="color:#dc2626">*</span></label>
                        <input
                            type="text"
                            class="hbm-input {{ $errors->has('articleTitle') ? 'error' : '' }}"
                            placeholder="Contoh: Cara Melakukan Penjualan"
                            wire:model="articleTitle"
                            id="article-title-input"
                        >
                        @error('articleTitle')
                            <div class="hbm-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori + Status --}}
                    <div class="hbm-form-row">
                        <div>
                            <label class="hbm-label">Kategori <span style="color:#dc2626">*</span></label>
                            <select
                                class="hbm-select {{ $errors->has('articleCategoryId') ? 'error' : '' }}"
                                wire:model="articleCategoryId"
                            >
                                <option value="">— Pilih Kategori —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('articleCategoryId')
                                <div class="hbm-error-msg">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="hbm-label">Urutan (Sort Order)</label>
                            <input
                                type="number"
                                class="hbm-input"
                                wire:model="articleSortOrder"
                                min="0"
                            >
                        </div>
                    </div>

                    {{-- Status Toggle --}}
                    <div class="hbm-form-group">
                        <label class="hbm-label">Status Publikasi</label>
                        <div class="hbm-status-toggle">
                            <button
                                type="button"
                                class="hbm-status-opt {{ $articleStatus === 'draft' ? 'active-draft' : '' }}"
                                wire:click="$set('articleStatus', 'draft')"
                            >Draft</button>
                            <button
                                type="button"
                                class="hbm-status-opt {{ $articleStatus === 'published' ? 'active-published' : '' }}"
                                wire:click="$set('articleStatus', 'published')"
                            >Published</button>
                        </div>
                    </div>

                    {{-- Rich Text Editor (Quill.js) --}}
                    <div class="hbm-form-group">
                        <label class="hbm-label">Konten Artikel</label>
                        <div class="hbm-quill-wrap" wire:ignore>
                            <div id="quill-editor"></div>
                        </div>
                        {{-- Hidden input untuk Livewire sync --}}
                        <input type="hidden" wire:model="articleContent" id="article-content-hidden">
                        @error('articleContent')
                            <div class="hbm-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="hbm-form-actions">
                        <button
                            class="btn-pill btn-pill-primary"
                            style="font-size:13px;"
                            wire:click="saveArticle"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                        >
                            <span wire:loading.remove wire:target="saveArticle">
                                {{ $editingArticleId ? 'Simpan Perubahan' : 'Buat Artikel' }}
                            </span>
                            <span wire:loading wire:target="saveArticle">Menyimpan...</span>
                        </button>
                        <button class="btn-pill btn-pill-secondary" style="font-size:13px;" wire:click="cancelArticleEdit">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
            @else
            {{-- Placeholder ketika list mode --}}
            <div class="hbm-panel" style="display:flex; align-items:center; justify-content:center; min-height:300px;">
                <div style="text-align:center; color:var(--muted);">
                    <div style="font-size:32px; margin-bottom:12px;">📝</div>
                    <div style="font-size:13px; font-weight:600;">Pilih artikel untuk diedit</div>
                    <div style="font-size:12px; margin-top:4px;">atau klik "+ Buat Artikel" untuk artikel baru</div>
                </div>
            </div>
            @endif

        </div>
        @endif

        {{-- ══════════════════════ KATEGORI TAB ══════════════════════ --}}
        @if($activeTab === 'categories')
        <div class="hbm-split">

            {{-- LIST --}}
            <div class="hbm-panel">
                <div class="hbm-panel-header">
                    <span class="hbm-panel-title">Semua Kategori</span>
                    <button class="btn-pill btn-pill-primary" style="font-size:12px; padding:7px 16px;" wire:click="createCategory">
                        + Buat Kategori
                    </button>
                </div>

                @forelse($categories as $cat)
                    <div class="hbm-row">
                        <div style="width:36px; height:36px; background:var(--block-lime); border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
                            {{ $cat->icon ?: '📁' }}
                        </div>
                        <div class="hbm-row-main">
                            <div class="hbm-row-title">{{ $cat->name }}</div>
                            <div class="hbm-row-meta">{{ $cat->articles_count }} artikel · Urutan #{{ $cat->sort_order }}</div>
                        </div>
                        <div class="hbm-row-actions">
                            <button class="hbm-icon-btn" wire:click="editCategory({{ $cat->id }})" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="hbm-icon-btn hbm-icon-btn-danger" wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Yakin ingin menghapus kategori '{{ $cat->name }}'? Artikel di dalamnya tidak akan ikut terhapus." title="Hapus">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="hbm-empty">
                        Belum ada kategori. Buat kategori dulu sebelum menambah artikel.
                    </div>
                @endforelse
            </div>

            {{-- EDITOR --}}
            @if($editorMode !== 'list')
            <div class="hbm-editor-panel" wire:key="cat-editor-{{ $editingCategoryId ?? 'new' }}">
                <div class="hbm-editor-header">
                    <span class="hbm-editor-title">
                        {{ $editingCategoryId ? '✏️ Edit Kategori' : '✨ Buat Kategori Baru' }}
                    </span>
                    <button class="hbm-icon-btn" wire:click="cancelCategoryEdit" title="Batal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="hbm-editor-body">
                    {{-- Preview Icon --}}
                    <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px;">
                        <div class="hbm-icon-preview">{{ $categoryIcon ?: '📁' }}</div>
                        <div>
                            <div style="font-size:15px; font-weight:700; letter-spacing:-0.3px;">{{ $categoryName ?: 'Nama Kategori' }}</div>
                            <div style="font-size:11px; color:var(--muted); margin-top:2px;">Preview tampilan di sidebar</div>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="hbm-form-group">
                        <label class="hbm-label">Nama Kategori <span style="color:#dc2626">*</span></label>
                        <input
                            type="text"
                            class="hbm-input {{ $errors->has('categoryName') ? 'error' : '' }}"
                            placeholder="Contoh: Memulai Zedpos"
                            wire:model.live="categoryName"
                        >
                        @error('categoryName')
                            <div class="hbm-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Icon + Urutan --}}
                    <div class="hbm-form-row">
                        <div>
                            <label class="hbm-label">Ikon (Emoji)</label>
                            <input
                                type="text"
                                class="hbm-input"
                                placeholder="Contoh: 🚀"
                                wire:model.live="categoryIcon"
                                maxlength="5"
                            >
                            <div style="font-size:11px; color:var(--muted); margin-top:4px;">Gunakan 1 emoji sebagai ikon kategori</div>
                        </div>
                        <div>
                            <label class="hbm-label">Urutan (Sort Order)</label>
                            <input
                                type="number"
                                class="hbm-input"
                                wire:model="categorySortOrder"
                                min="0"
                            >
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="hbm-form-actions">
                        <button
                            class="btn-pill btn-pill-primary"
                            style="font-size:13px;"
                            wire:click="saveCategory"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="saveCategory">
                                {{ $editingCategoryId ? 'Simpan Perubahan' : 'Buat Kategori' }}
                            </span>
                            <span wire:loading wire:target="saveCategory">Menyimpan...</span>
                        </button>
                        <button class="btn-pill btn-pill-secondary" style="font-size:13px;" wire:click="cancelCategoryEdit">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="hbm-panel" style="display:flex; align-items:center; justify-content:center; min-height:300px;">
                <div style="text-align:center; color:var(--muted);">
                    <div style="font-size:32px; margin-bottom:12px;">📁</div>
                    <div style="font-size:13px; font-weight:600;">Pilih kategori untuk diedit</div>
                    <div style="font-size:12px; margin-top:4px;">atau klik "+ Buat Kategori" untuk kategori baru</div>
                </div>
            </div>
            @endif

        </div>
        @endif

    </div>

    {{-- ══════════════════════════════════════════════════════════
         JAVASCRIPT — Quill.js Integration
    ══════════════════════════════════════════════════════════ --}}
    <x-slot name="scripts">
    <script>
        let quillInstance = null;

        function initQuill(content = '') {
            // Tunggu Quill library dan editor DOM tersedia
            const waitForQuill = setInterval(() => {
                const editorEl = document.getElementById('quill-editor');
                if (typeof Quill === 'undefined' || !editorEl) return;

                clearInterval(waitForQuill);

                // Hancurkan instance lama jika ada
                if (quillInstance) {
                    quillInstance = null;
                    editorEl.innerHTML = '';
                }

                quillInstance = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Tulis konten artikel di sini... Gunakan toolbar di atas untuk memformat teks, menambah gambar, membuat list, dll.',
                    modules: {
                        toolbar: [
                            [{ 'header': [2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['blockquote', 'code-block'],
                            ['link', 'image'],
                            [{ 'color': [] }, { 'background': [] }],
                            ['clean']
                        ]
                    }
                });

                // Set konten awal
                if (content) {
                    quillInstance.root.innerHTML = content;
                }

                // Sync ke Livewire saat ada perubahan
                quillInstance.on('text-change', () => {
                    const html    = quillInstance.root.innerHTML;
                    const hidden  = document.getElementById('article-content-hidden');
                    if (hidden) {
                        hidden.value = html;
                        hidden.dispatchEvent(new Event('input'));
                    }

                    // Update Livewire model langsung
                    @this.set('articleContent', html);
                });
            }, 100);
        }

        // Livewire event dari HandbookManager::createArticle dan editArticle
        Livewire.on('init-quill', ({ content }) => {
            setTimeout(() => initQuill(content || ''), 100);
        });

        // Init ulang setelah Livewire re-render
        document.addEventListener('livewire:update', () => {
            const editorEl = document.getElementById('quill-editor');
            if (editorEl && !quillInstance) {
                // Editor sudah ada di DOM tapi Quill belum di-init
                const hidden = document.getElementById('article-content-hidden');
                initQuill(hidden?.value || '');
            }
        });
    </script>
    </x-slot>

</div>
