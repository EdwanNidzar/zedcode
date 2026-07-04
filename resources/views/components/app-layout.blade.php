<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — ZED CORE</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink: #0a0a0a;
            --canvas: #ffffff;
            --surface-soft: #f5f5f4;
            --hairline: #e5e5e5;
            --hairline-soft: #f0f0f0;
            --block-lime: #d9f99d;
            --block-lilac: #ddd6fe;
            --block-navy: #1e1b4b;
            --block-coral: #ffedd5;
            --accent: #16a34a;
            --muted: #737373;
            --error: #dc2626;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 24px;
            --radius-pill: 50px;
            --spacing-md: 16px;
            --spacing-lg: 24px;
            --spacing-xl: 32px;
            --spacing-xxl: 48px;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--surface-soft);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* =========================================
           LAYOUT GRID
        ========================================= */
        .app-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        /* =========================================
           SIDEBAR
        ========================================= */
        .sidebar {
            background: var(--canvas);
            border-right: 1px solid var(--hairline);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 40;
        }

        .sidebar-header {
            padding: 24px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--hairline-soft);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-mark {
            width: 32px; height: 32px;
            background: var(--ink);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo-mark svg { width: 18px; height: 18px; color: var(--block-lime); }

        .brand-logo-text {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.5px;
        }

        .sidebar-nav {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin: 16px 0 8px 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            text-decoration: none;
            color: var(--muted);
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius-md);
            transition: all 0.15s ease;
        }

        .nav-item svg { width: 18px; height: 18px; transition: color 0.15s; }

        .nav-item:hover {
            background: var(--surface-soft);
            color: var(--ink);
        }

        .nav-item.active {
            background: var(--ink);
            color: var(--canvas);
        }

        .nav-item.active svg { color: var(--block-lime); }

        /* =========================================
           MAIN WRAPPER & TOPBAR
        ========================================= */
        .main-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 72px;
            background: var(--canvas);
            border-bottom: 1px solid var(--hairline);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--spacing-xl);
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .topbar-left {
            display: flex;
            align-items: center;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.4px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* User Profile Area */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 16px 6px 6px;
            background: var(--surface-soft);
            border-radius: var(--radius-pill);
            border: 1px solid var(--hairline);
        }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--block-lilac);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: var(--ink);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
        }

        /* Role Badge */
        .role-badge {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
        }

        /* Role Colors - using Spatie roles if available */
        .role-badge.super-admin { color: #7c3aed; }
        .role-badge.hr { color: #ea580c; }
        
        .logout-btn {
            background: transparent;
            border: 1px solid var(--hairline);
            color: var(--ink);
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .logout-btn:hover {
            background: var(--ink);
            color: var(--canvas);
            border-color: var(--ink);
        }

        .logout-btn svg { width: 16px; height: 16px; }

        /* =========================================
           CONTENT AREA
        ========================================= */
        .content {
            flex: 1;
            padding: var(--spacing-xl);
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
        }

        /* =========================================
           COMMON COMPONENTS
        ========================================= */
        /* Color Block Sections (Figma Style) */
        .color-block {
            border-radius: var(--radius-lg);
            padding: var(--spacing-xxl);
            margin-bottom: var(--spacing-xl);
            position: relative;
            overflow: hidden;
        }

        .color-block-lime { background: var(--block-lime); color: var(--ink); }
        .color-block-lilac { background: var(--block-lilac); color: var(--ink); }
        .color-block-navy { background: var(--block-navy); color: var(--canvas); }
        .color-block-coral { background: var(--block-coral); color: var(--ink); }
        .color-block-white { background: var(--canvas); color: var(--ink); border: 1px solid var(--hairline); }

        .block-title {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -1px;
            margin-bottom: 12px;
        }

        .block-desc {
            font-size: 16px;
            line-height: 1.5;
            max-width: 600px;
            opacity: 0.8;
        }

        /* Pills (Buttons) */
        .btn-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s;
            border: none;
        }

        .btn-pill-primary {
            background: var(--ink);
            color: var(--canvas);
        }
        .btn-pill-primary:hover { opacity: 0.9; transform: scale(0.98); }

        .btn-pill-secondary {
            background: var(--canvas);
            color: var(--ink);
            border: 1px solid var(--hairline);
        }
        .btn-pill-secondary:hover { border-color: var(--ink); }

        /* Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
        }

        .stat-card {
            background: var(--canvas);
            border: 1px solid var(--hairline);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -1.5px;
        }

        @media (max-width: 1024px) {
            .app-layout { grid-template-columns: 1fr; }
            .sidebar { display: none; /* Needs mobile menu implementation */ }
        }
    </style>
    @livewireStyles
</head>
<body>

    <div class="app-layout">
        
        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="brand-logo">
                    <div class="brand-logo-mark">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6L8 8.5V13.5L12 16L16 13.5V8.5L12 6Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="brand-logo-text">ZED CORE</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-label">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>

                <div class="nav-label">Pengajuan</div>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Cuti Karyawan
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
                    Permintaan Barang
                </a>

                <div class="nav-label">Perusahaan</div>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Handbook
                </a>

                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR / Manager'))
                <div class="nav-label">Admin Panel</div>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 00-3-3.87"></path><path d="M16 3.13a4 4 0 010 7.75"></path></svg>
                    Kelola Pengguna
                </a>
                <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Kelola Role & Akses
                </a>
                @endif
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="main-wrapper">
            
            {{-- TOPBAR --}}
            <header class="topbar">
                <div class="topbar-left">
                    <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="topbar-right">
                    <div class="user-profile">
                        @php
                            $name = auth()->user()->name;
                            $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            
                            $roleName = 'Employee'; // Default fallback
                            $roleClass = '';
                            if (auth()->user()->roles->count() > 0) {
                                $roleName = auth()->user()->roles->first()->name;
                                if ($roleName === 'Super Admin') $roleClass = 'super-admin';
                                elseif ($roleName === 'HR / Manager') $roleClass = 'hr';
                            }
                        @endphp
                        <div class="user-avatar">{{ strtoupper($initials) }}</div>
                        <div class="user-info">
                            <span class="user-name">{{ $name }}</span>
                            <span class="role-badge {{ $roleClass }}">{{ $roleName }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn" title="Keluar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        </button>
                    </form>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="content">
                {{ $slot }}
            </main>

        </div>
    </div>

    @livewireScripts
    {{ $scripts ?? '' }}
</body>
</html>
