<x-auth-layout
    title="Login — ZED CORE"
    brandHeadline="Selamat"
    brandSub="datang kembali."
    brandDesc="Masuk ke sistem ZED CORE untuk mengelola cuti, permintaan barang, dan dokumen perusahaan.">

    <div class="form-header">
        <h2 class="form-title">Masuk ke akun</h2>
        <p class="form-subtitle">Gunakan email & kata sandi perusahaan Anda</p>
    </div>

    @if (session('status'))
        <div class="alert-success" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <p>{{ session('status') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="login-form" novalidate>
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    placeholder="nama@perusahaan.com" required autofocus autocomplete="email">
            </div>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <input id="password" type="password" name="password"
                    class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="password-toggle" id="toggle-pw" aria-label="Tampilkan kata sandi">
                    <svg id="eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eye-off" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Options --}}
        <div class="form-options">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat saya
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-muted">Lupa kata sandi?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary" id="btn-submit">
            <span class="btn-text">Masuk ke Sistem</span>
            <div class="btn-spinner"></div>
        </button>
    </form>

    <div class="form-divider"><div class="form-divider-line"></div><span class="form-divider-text">Akses Terbatas</span><div class="form-divider-line"></div></div>

    <div class="info-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <p>Sistem hanya untuk karyawan <strong>Zedcore</strong>. Masalah akses? Hubungi <strong>HR / IT</strong>.</p>
    </div>

    <x-slot name="scripts">
    <script>
        // Password toggle
        const pw = document.getElementById('password');
        document.getElementById('toggle-pw').addEventListener('click', () => {
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            document.getElementById('eye-on').style.display = show ? 'none' : 'block';
            document.getElementById('eye-off').style.display = show ? 'block' : 'none';
        });
        // Loading state
        document.getElementById('login-form').addEventListener('submit', () => {
            document.getElementById('btn-submit').classList.add('is-loading');
        });
    </script>
    </x-slot>

</x-auth-layout>
