<div>

    {{-- ═══════════════════════════════════════════════════════════
         INLINE STYLES — Handbook Reader
    ═══════════════════════════════════════════════════════════ --}}
    <style>
        /* ── Layout utama 3 kolom ─────────────────────────────── */
        .hb-reader {
            display: grid;
            grid-template-columns: 260px 1fr 220px;
            gap: 0;
            min-height: calc(100vh - 72px);
            align-items: start;
            transition: grid-template-columns 0.3s;
        }

        .hb-reader.no-toc {
            grid-template-columns: 260px 1fr;
        }

        /* ── Sidebar kiri (Navigasi Kategori) ──────────────────── */
        .hb-sidebar {
            position: sticky;
            top: 72px;
            height: calc(100vh - 72px);
            overflow-y: auto;
            background: var(--canvas);
            border-right: 1px solid var(--hairline);
            padding: 24px 0;
            scrollbar-width: thin;
        }

        .hb-sidebar-header {
            padding: 0 16px 16px;
            border-bottom: 1px solid var(--hairline-soft);
            margin-bottom: 8px;
        }

        .hb-sidebar-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 10px;
        }

        /* Search */
        .hb-search-wrap {
            position: relative;
        }

        .hb-search-input {
            width: 100%;
            padding: 8px 12px 8px 34px;
            border: 1px solid var(--hairline);
            border-radius: var(--radius-pill);
            font-size: 13px;
            background: var(--surface-soft);
            color: var(--ink);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .hb-search-input:focus {
            border-color: var(--ink);
            background: var(--canvas);
            box-shadow: 0 0 0 3px rgba(10,10,10,0.06);
        }

        .hb-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }

        /* Search Dropdown */
        .hb-search-results {
            position: absolute;
            top: calc(100% + 6px);
            left: 0; right: 0;
            background: var(--canvas);
            border: 1px solid var(--hairline);
            border-radius: var(--radius-lg);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            z-index: 50;
            overflow: hidden;
        }

        .hb-search-result-item {
            display: block;
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--hairline-soft);
            transition: background 0.1s;
        }

        .hb-search-result-item:last-child { border-bottom: none; }
        .hb-search-result-item:hover { background: var(--surface-soft); }

        .hb-search-result-title { font-size: 13px; font-weight: 600; color: var(--ink); }
        .hb-search-result-cat   { font-size: 11px; color: var(--muted); margin-top: 2px; }

        /* Nav Kategori & Artikel */
        .hb-nav-section { margin-bottom: 4px; }

        .hb-nav-cat-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.2px;
        }

        .hb-nav-cat-label .cat-icon { font-size: 14px; }

        .hb-nav-article {
            display: block;
            width: 100%;
            padding: 7px 16px 7px 32px;
            font-size: 13px;
            color: var(--muted);
            text-align: left;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: color 0.15s, background 0.15s;
            border-radius: 0;
            line-height: 1.4;
        }

        .hb-nav-article:hover { background: var(--surface-soft); color: var(--ink); }

        .hb-nav-article.active {
            background: var(--block-lime);
            color: var(--ink);
            font-weight: 600;
        }

        /* ── Konten Tengah ──────────────────────────────────────── */
        .hb-content {
            padding: 32px 40px;
            min-height: calc(100vh - 72px);
        }

        /* Breadcrumb */
        .hb-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .hb-breadcrumb-sep { color: var(--hairline); }
        .hb-breadcrumb-current { color: var(--ink); }

        /* Article Hero Block */
        .hb-article-hero {
            background: var(--block-lime);
            border-radius: var(--radius-lg);
            padding: 36px 40px;
            margin-bottom: 32px;
        }

        .hb-article-hero-eyebrow {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(10,10,10,0.5);
            margin-bottom: 8px;
        }

        .hb-article-hero-title {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1.1;
            color: var(--ink);
            margin-bottom: 12px;
        }

        .hb-article-meta {
            font-size: 12px;
            color: rgba(10,10,10,0.55);
        }

        /* Article Body */
        .hb-article-body {
            font-size: 15px;
            line-height: 1.75;
            color: var(--ink);
            max-width: 680px;
        }

        .hb-article-body h2 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 36px 0 12px;
            padding-top: 12px;
            border-top: 1px solid var(--hairline-soft);
            scroll-margin-top: 96px;
        }

        .hb-article-body h3 {
            font-size: 17px;
            font-weight: 600;
            letter-spacing: -0.3px;
            margin: 24px 0 10px;
            scroll-margin-top: 96px;
        }

        .hb-article-body p { margin-bottom: 16px; }

        .hb-article-body ol,
        .hb-article-body ul {
            padding-left: 20px;
            margin-bottom: 16px;
        }

        .hb-article-body li { margin-bottom: 6px; }

        .hb-article-body strong { font-weight: 700; }

        .hb-article-body em { font-style: italic; }

        .hb-article-body code {
            background: var(--surface-soft);
            border: 1px solid var(--hairline);
            border-radius: 4px;
            padding: 1px 6px;
            font-size: 13px;
            font-family: 'JetBrains Mono', monospace;
        }

        .hb-article-body pre {
            background: var(--ink);
            color: var(--block-lime);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            overflow-x: auto;
            margin-bottom: 16px;
            font-size: 13px;
            line-height: 1.6;
        }

        .hb-article-body blockquote {
            border-left: 3px solid var(--block-lime);
            background: var(--surface-soft);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            padding: 12px 16px;
            margin: 0 0 16px;
            font-size: 14px;
        }

        /* Callout boxes */
        .hb-callout {
            border-radius: var(--radius-md);
            padding: 14px 16px;
            margin-bottom: 16px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 14px;
        }

        .hb-callout-tip     { background: var(--block-lime); }
        .hb-callout-warn    { background: var(--block-coral); }
        .hb-callout-info    { background: var(--block-lilac); }
        .hb-callout-icon    { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

        /* Prev / Next Navigation */
        .hb-nav-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid var(--hairline);
        }

        .hb-nav-footer-btn {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 16px 20px;
            background: var(--canvas);
            border: 1px solid var(--hairline);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s;
            text-align: left;
        }

        .hb-nav-footer-btn:hover {
            border-color: var(--ink);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .hb-nav-footer-btn.next { text-align: right; }

        .hb-nav-footer-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted);
        }

        .hb-nav-footer-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        /* Empty / Welcome State */
        .hb-welcome {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            gap: 16px;
        }

        .hb-welcome-icon {
            width: 64px; height: 64px;
            background: var(--block-lime);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
        }

        .hb-welcome-title { font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
        .hb-welcome-desc  { font-size: 15px; color: var(--muted); max-width: 400px; line-height: 1.6; }

        /* ── TOC Kolom Kanan ───────────────────────────────────── */
        .hb-toc {
            position: sticky;
            top: 88px;
            padding: 24px 16px 24px 24px;
            border-left: 1px solid var(--hairline-soft);
        }

        .hb-toc-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .hb-toc-list { list-style: none; padding: 0; margin: 0; }

        .hb-toc-item {
            margin-bottom: 2px;
        }

        .hb-toc-link {
            display: block;
            font-size: 12px;
            color: var(--muted);
            padding: 4px 8px;
            border-radius: 6px;
            border-left: 2px solid transparent;
            text-decoration: none;
            transition: color 0.15s, border-color 0.15s, background 0.15s;
            line-height: 1.4;
        }

        .hb-toc-link:hover { color: var(--ink); background: var(--surface-soft); }

        .hb-toc-link.active {
            color: var(--ink);
            font-weight: 600;
            border-left-color: var(--ink);
            background: var(--surface-soft);
        }

        .hb-toc-link.level-h3 { padding-left: 20px; font-size: 11px; }

        /* ── Responsive ─────────────────────────────────────────── */
        @media (max-width: 1100px) {
            .hb-reader { grid-template-columns: 240px 1fr; }
            .hb-toc    { display: none; }
        }

        /* Mobile Sidebar Overlay */
        .hb-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 90;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .hb-sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Mobile Toggle Button */
        .hb-mobile-toggle {
            display: none;
            align-items: center;
            gap: 8px;
            background: var(--canvas);
            border: 1px solid var(--hairline);
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            cursor: pointer;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .hb-reader   { grid-template-columns: 1fr !important; }
            
            /* Sidebar becomes offcanvas drawer */
            .hb-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                width: 280px;
                z-index: 100;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 4px 0 24px rgba(0,0,0,0.1);
            }

            .hb-sidebar.open {
                transform: translateX(0);
            }

            .hb-mobile-toggle { display: inline-flex; }

            .hb-content  { padding: 20px 16px; min-height: auto; }
            .hb-article-hero { padding: 24px 20px; border-radius: var(--radius-md); }
            .hb-article-hero-title { font-size: 26px; }
            .hb-nav-footer { grid-template-columns: 1fr; margin-top: 32px; }
            .hb-welcome { min-height: 50vh; }
            .hb-welcome-title { font-size: 20px; }
        }
    </style>

    <div class="hb-reader" wire:key="handbook-reader">

        {{-- Mobile Overlay --}}
        <div class="hb-sidebar-overlay" id="hb-overlay" onclick="toggleMobileSidebar()"></div>

        {{-- ══════════════════════════════════════════════════
             KOLOM KIRI: Sidebar Navigasi
        ══════════════════════════════════════════════════ --}}
        <aside class="hb-sidebar">

            {{-- Header + Search --}}
            <div class="hb-sidebar-header">
                <div class="hb-sidebar-title">Handbook Zedpos</div>

                <div class="hb-search-wrap">
                    <svg class="hb-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="text"
                        class="hb-search-input"
                        placeholder="Cari artikel..."
                        wire:model.live.debounce.300ms="search"
                        autocomplete="off"
                    >

                    {{-- Search Dropdown --}}
                    @if(strlen($search) >= 2)
                        <div class="hb-search-results">
                            @forelse($searchResults as $result)
                                <button
                                    class="hb-search-result-item"
                                    wire:click="selectArticle({{ $result->id }})"
                                >
                                    <div class="hb-search-result-title">{{ $result->title }}</div>
                                    <div class="hb-search-result-cat">{{ $result->category->name }}</div>
                                </button>
                            @empty
                                <div style="padding: 12px 14px; font-size: 12px; color: var(--muted);">Tidak ada hasil ditemukan.</div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>

            {{-- Navigasi Kategori & Artikel --}}
            @foreach($categories as $category)
                <div class="hb-nav-section">
                    <div class="hb-nav-cat-label">
                        @if($category->icon)
                            <span class="cat-icon">{{ $category->icon }}</span>
                        @endif
                        {{ $category->name }}
                    </div>

                    @foreach($category->publishedArticles as $art)
                        <button
                            class="hb-nav-article {{ $article?->id === $art->id ? 'active' : '' }}"
                            wire:click="selectArticle({{ $art->id }})"
                        >
                            {{ $art->title }}
                        </button>
                    @endforeach
                </div>
            @endforeach

        </aside>

        {{-- ══════════════════════════════════════════════════
             KOLOM TENGAH: Konten Artikel
        ══════════════════════════════════════════════════ --}}
        <main class="hb-content" id="hb-main-content">

            {{-- Mobile Toggle Button --}}
            <button class="hb-mobile-toggle" onclick="toggleMobileSidebar()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                Daftar Topik
            </button>

            @if($article)

                {{-- Breadcrumb --}}
                <nav class="hb-breadcrumb" aria-label="Breadcrumb">
                    <span>Handbook</span>
                    <span class="hb-breadcrumb-sep">›</span>
                    <span>{{ $article->category->name }}</span>
                    <span class="hb-breadcrumb-sep">›</span>
                    <span class="hb-breadcrumb-current">{{ $article->title }}</span>
                </nav>

                {{-- Hero Block --}}
                <div class="hb-article-hero">
                    <div class="hb-article-hero-eyebrow">{{ $article->category->name }}</div>
                    <h1 class="hb-article-hero-title">{{ $article->title }}</h1>
                    <div class="hb-article-meta">
                        Ditulis oleh {{ $article->author->name }} · Diperbarui {{ $article->updated_at->diffForHumans() }}
                    </div>
                </div>

                {{-- Body Konten --}}
                <div class="hb-article-body" id="hb-article-body">
                    {!! $article->content !!}
                </div>

                {{-- Prev / Next --}}
                <div class="hb-nav-footer">
                    <div>
                        @if($prevArticle)
                            <button class="hb-nav-footer-btn" wire:click="selectArticle({{ $prevArticle->id }})">
                                <span class="hb-nav-footer-label">← Sebelumnya</span>
                                <span class="hb-nav-footer-title">{{ $prevArticle->title }}</span>
                            </button>
                        @endif
                    </div>
                    <div>
                        @if($nextArticle)
                            <button class="hb-nav-footer-btn next" wire:click="selectArticle({{ $nextArticle->id }})">
                                <span class="hb-nav-footer-label">Selanjutnya →</span>
                                <span class="hb-nav-footer-title">{{ $nextArticle->title }}</span>
                            </button>
                        @endif
                    </div>
                </div>

            @else

                {{-- Welcome State --}}
                <div class="hb-welcome">
                    <div class="hb-welcome-icon">📖</div>
                    <h2 class="hb-welcome-title">Selamat Datang di Handbook Zedpos</h2>
                    <p class="hb-welcome-desc">Pilih topik dari sidebar kiri untuk mulai membaca panduan penggunaan Zedpos. Atau gunakan kotak pencarian di atas untuk menemukan artikel spesifik.</p>

                    {{-- Quick Links --}}
                    <div style="display:flex; flex-wrap:wrap; gap:8px; justify-content:center; margin-top:8px;">
                        @foreach($categories as $cat)
                            @if($cat->publishedArticles->isNotEmpty())
                                <button
                                    class="btn-pill btn-pill-secondary"
                                    wire:click="selectArticle({{ $cat->publishedArticles->first()->id }})"
                                    style="font-size:13px;"
                                >
                                    @if($cat->icon) {{ $cat->icon }} @endif
                                    {{ $cat->name }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

            @endif

        </main>

    </div>

    {{-- ══════════════════════════════════════════════════════════
         JAVASCRIPT — Scroll Spy, TOC Builder, & Mobile Sidebar
    ══════════════════════════════════════════════════════════ --}}
    <script>
        // Mobile Sidebar Toggle
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.hb-sidebar');
            const overlay = document.getElementById('hb-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                if (sidebar.classList.contains('open')) {
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden'; // Prevent background scroll
                } else {
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        }

        // Close sidebar when clicking a link on mobile
        document.addEventListener('livewire:navigating', () => {
             const sidebar = document.querySelector('.hb-sidebar');
             const overlay = document.getElementById('hb-overlay');
             if (sidebar && sidebar.classList.contains('open')) {
                 sidebar.classList.remove('open');
                 if(overlay) overlay.classList.remove('show');
                 document.body.style.overflow = '';
             }
        });

        // Build TOC dari heading di dalam artikel
        function buildTOC() {
            const body    = document.getElementById('hb-article-body');
            const tocList = document.getElementById('hb-toc-list');
            const reader  = document.querySelector('.hb-reader');
            const tocPanel = document.getElementById('hb-toc-panel');

            if (!body || !tocList) {
                if (reader) reader.classList.add('no-toc');
                if (tocPanel) tocPanel.style.display = 'none';
                return;
            }

            tocList.innerHTML = '';

            const headings = body.querySelectorAll('h2, h3');

            if (headings.length === 0) {
                if (reader) reader.classList.add('no-toc');
                if (tocPanel) tocPanel.style.display = 'none';
                return;
            }
            
            // Tampilkan kembali jika ada heading
            if (reader) reader.classList.remove('no-toc');
            if (tocPanel) tocPanel.style.display = 'block';

            headings.forEach((heading, i) => {
                // Beri ID jika belum ada
                if (!heading.id) {
                    heading.id = 'hb-heading-' + i;
                }

                const li   = document.createElement('li');
                li.className = 'hb-toc-item';

                const a    = document.createElement('a');
                a.href     = '#' + heading.id;
                a.className = 'hb-toc-link' + (heading.tagName === 'H3' ? ' level-h3' : '');
                a.textContent = heading.textContent;

                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });

                li.appendChild(a);
                tocList.appendChild(li);
            });

            // ScrollSpy
            setupScrollSpy();
        }

        function setupScrollSpy() {
            const links    = document.querySelectorAll('.hb-toc-link');
            const headings = Array.from(document.querySelectorAll('#hb-article-body h2, #hb-article-body h3'));

            if (!headings.length) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        links.forEach(link => link.classList.remove('active'));
                        const active = document.querySelector(`.hb-toc-link[href="#${entry.target.id}"]`);
                        active?.classList.add('active');
                    }
                });
            }, {
                rootMargin: '-88px 0px -60% 0px',
                threshold: 0
            });

            headings.forEach(h => observer.observe(h));
        }

        // Jalankan saat load pertama
        document.addEventListener('DOMContentLoaded', buildTOC);

        // Jalankan ulang setiap kali Livewire update artikel
        document.addEventListener('livewire:navigated', buildTOC);

        // Livewire event saat artikel berganti
        Livewire.on('article-changed', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(buildTOC, 150);
            
            // Auto close mobile sidebar
            const sidebar = document.querySelector('.hb-sidebar');
            const overlay = document.getElementById('hb-overlay');
            if (sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                if(overlay) overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        // Fallback: setelah setiap Livewire update
        document.addEventListener('livewire:update', () => {
            setTimeout(buildTOC, 200);
        });
    </script>

</div>
