<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Sistem manajemen internal perusahaan.' }}">
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
            --block-lime: #d9f99d;
            --accent: #16a34a;
            --muted: #737373;
            --error: #dc2626;
            --error-bg: #fef2f2;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 20px;
            --radius-pill: 50px;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--ink);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ============ LAYOUT ============ */
        .auth-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ============ LEFT BRAND PANEL ============ */
        .brand-panel {
            position: relative;
            background: var(--ink);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -120px; right: -80px;
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(217,249,157,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -60px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(217,249,157,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            position: relative;
            z-index: 1;
        }

        .brand-logo-mark {
            width: 40px; height: 40px;
            background: var(--block-lime);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-logo-mark svg { width: 22px; height: 22px; }

        .brand-logo-text {
            font-size: 18px;
            font-weight: 700;
            color: var(--canvas);
            letter-spacing: -0.5px;
        }

        .brand-logo-text span { color: var(--block-lime); }

        .brand-content { position: relative; z-index: 1; }

        .brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(217, 249, 157, 0.12);
            border: 1px solid rgba(217, 249, 157, 0.2);
            color: var(--block-lime);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: var(--radius-pill);
            margin-bottom: 28px;
        }

        .brand-tag-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--block-lime);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        .brand-headline {
            font-size: 46px;
            font-weight: 700;
            line-height: 1.05;
            letter-spacing: -2px;
            color: var(--canvas);
            margin-bottom: 16px;
        }

        .brand-headline span { color: var(--block-lime); display: block; }

        .brand-desc {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255,255,255,0.5);
            max-width: 320px;
        }

        .brand-footer {
            position: relative; z-index: 1;
            font-size: 12px;
            color: rgba(255,255,255,0.25);
            letter-spacing: 0.5px;
        }

        /* ============ RIGHT FORM PANEL ============ */
        .form-panel {
            background: var(--canvas);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ============ SHARED FORM ELEMENTS ============ */
        .form-header { margin-bottom: 32px; }

        .form-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            margin-bottom: 24px;
            transition: color 0.15s;
        }
        .form-back:hover { color: var(--ink); }
        .form-back svg { width: 14px; height: 14px; }

        .form-icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: var(--surface-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .form-icon-wrap svg { width: 24px; height: 24px; }

        .form-title {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.8px;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .form-subtitle { font-size: 14px; color: var(--muted); line-height: 1.5; }
        .form-subtitle strong { color: var(--ink); font-weight: 600; }

        .form-group { margin-bottom: 16px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 7px;
        }

        .form-input-wrap { position: relative; }

        .form-input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: #a3a3a3;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 46px;
            padding: 0 16px 0 42px;
            background: var(--canvas);
            border: 1.5px solid var(--hairline);
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: var(--ink);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-input:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(10,10,10,0.07);
        }

        .form-input::placeholder { color: #c4c4c4; }
        .form-input.is-error { border-color: var(--error); }

        .form-input-no-icon { padding-left: 14px; }

        .password-toggle {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer;
            color: #a3a3a3;
            padding: 4px;
            display: flex; align-items: center; justify-content: center;
            transition: color 0.15s;
        }
        .password-toggle:hover { color: var(--ink); }
        .password-toggle svg { width: 16px; height: 16px; }

        .field-error { font-size: 12px; color: var(--error); margin-top: 5px; font-weight: 500; }

        /* Alerts */
        .alert-error {
            background: var(--error-bg);
            border: 1px solid rgba(220,38,38,0.15);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .alert-error svg { width: 16px; height: 16px; color: var(--error); flex-shrink: 0; margin-top: 1px; }
        .alert-error p { font-size: 13px; color: var(--error); font-weight: 500; line-height: 1.4; }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid rgba(22,163,74,0.15);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .alert-success svg { width: 16px; height: 16px; color: var(--accent); flex-shrink: 0; margin-top: 1px; }
        .alert-success p { font-size: 13px; color: var(--accent); font-weight: 500; line-height: 1.4; }

        .alert-info {
            background: #eff6ff;
            border: 1px solid rgba(59,130,246,0.15);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .alert-info svg { width: 16px; height: 16px; color: #3b82f6; flex-shrink: 0; margin-top: 1px; }
        .alert-info p { font-size: 13px; color: #1d4ed8; line-height: 1.5; }

        /* Buttons */
        .btn-primary {
            width: 100%;
            height: 48px;
            background: var(--ink);
            color: var(--canvas);
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.2px;
            border: none;
            border-radius: var(--radius-pill);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: opacity 0.15s, transform 0.1s;
        }
        .btn-primary:hover { opacity: 0.88; }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .btn-spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .is-loading .btn-text { display: none; }
        .is-loading .btn-spinner { display: block; }

        /* Options row */
        .form-options {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }

        .checkbox-label {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer; font-size: 13px; color: var(--muted);
            user-select: none;
        }
        .checkbox-label input[type="checkbox"] {
            width: 16px; height: 16px;
            border: 1.5px solid var(--hairline);
            border-radius: 4px; cursor: pointer;
            accent-color: var(--ink);
        }

        .link-muted { font-size: 13px; font-weight: 500; color: var(--muted); text-decoration: none; transition: color 0.15s; }
        .link-muted:hover { color: var(--ink); }

        .link-primary { font-size: 13px; font-weight: 600; color: var(--ink); text-decoration: none; transition: opacity 0.15s; }
        .link-primary:hover { opacity: 0.65; }

        /* Dividers & helpers */
        .form-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 22px 0;
        }
        .form-divider-line { flex: 1; height: 1px; background: var(--hairline); }
        .form-divider-text { font-size: 12px; color: #c4c4c4; font-weight: 500; white-space: nowrap; }

        .form-footer-text {
            text-align: center;
            font-size: 13px;
            color: var(--muted);
            margin-top: 24px;
        }

        .info-box {
            background: var(--surface-soft);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .info-box svg { width: 15px; height: 15px; color: var(--muted); flex-shrink: 0; margin-top: 1px; }
        .info-box p { font-size: 12.5px; color: var(--muted); line-height: 1.5; }
        .info-box strong { color: var(--ink); font-weight: 600; }

        /* OTP / Code input */
        .otp-group {
            display: flex; gap: 10px;
            margin-bottom: 24px;
        }
        .otp-input {
            flex: 1;
            height: 56px;
            background: var(--canvas);
            border: 1.5px solid var(--hairline);
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--ink);
            text-align: center;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            letter-spacing: 2px;
        }
        .otp-input:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(10,10,10,0.07);
        }

        /* 2FA recovery toggle */
        .toggle-link {
            font-size: 13px; font-weight: 500; color: var(--muted);
            cursor: pointer; text-decoration: underline; text-underline-offset: 2px;
            background: none; border: none; padding: 0;
            transition: color 0.15s;
        }
        .toggle-link:hover { color: var(--ink); }

        /* Password strength indicator */
        .password-strength {
            margin-top: 6px;
            display: flex; gap: 4px;
        }
        .strength-bar {
            flex: 1; height: 3px;
            border-radius: 3px;
            background: var(--hairline);
            transition: background 0.3s;
        }
        .strength-bar.weak { background: var(--error); }
        .strength-bar.fair { background: #f59e0b; }
        .strength-bar.good { background: #3b82f6; }
        .strength-bar.strong { background: var(--accent); }
        .strength-label { font-size: 11px; color: var(--muted); margin-top: 4px; }

        /* Responsive */
        @media (max-width: 900px) {
            .auth-wrapper { grid-template-columns: 1fr; }
            .brand-panel { padding: 28px 24px; min-height: 200px; }
            .brand-headline { font-size: 30px; }
            .brand-desc { max-width: 100%; font-size: 14px; }
            .brand-footer { display: none; }
            .form-panel { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    {{-- LEFT — Brand Panel --}}
    <div class="brand-panel">
        <a href="{{ route('login') }}" class="brand-logo">
            <div class="brand-logo-mark">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L4 7V17L12 22L20 17V7L12 2Z" fill="#0a0a0a" stroke="#0a0a0a" stroke-width="1.5"/>
                    <path d="M12 6L8 8.5V13.5L12 16L16 13.5V8.5L12 6Z" fill="#d9f99d"/>
                </svg>
            </div>
            <span class="brand-logo-text">ZED <span>CORE</span></span>
        </a>

        <div class="brand-content">
            <div class="brand-tag">
                <span class="brand-tag-dot"></span>
                Internal System
            </div>
            <h1 class="brand-headline">{{ $brandHeadline ?? 'Kelola tim' }}<span>{{ $brandSub ?? 'lebih cerdas.' }}</span></h1>
            <p class="brand-desc">{{ $brandDesc ?? 'Satu platform terpadu untuk pengajuan cuti, permintaan barang, dan manajemen SDM perusahaan Anda.' }}</p>
        </div>

        <p class="brand-footer">{{ date('Y') }} ZED CORE. All rights reserved.</p>
    </div>

    {{-- RIGHT — Form Panel --}}
    <div class="form-panel">
        <div class="form-container">
            {{ $slot }}
        </div>
    </div>

</div>

{{ $scripts ?? '' }}

</body>
</html>
