<x-auth-layout
    title="Daftar Akun — ZED CORE"
    brandHeadline="Bergabung dengan"
    brandSub="tim Zedcore."
    brandDesc="Buat akun Anda untuk mulai mengelola cuti, permintaan barang, dan dokumen perusahaan.">

    <div class="form-header">
        <a href="{{ route('login') }}" class="form-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke login
        </a>
        <div class="form-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
        </div>
        <h2 class="form-title">Buat akun baru</h2>
        <p class="form-subtitle">Isi data di bawah untuk mendaftar ke sistem</p>
    </div>

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                    placeholder="Nama Anda" required autofocus autocomplete="name">
            </div>
            @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    placeholder="nama@perusahaan.com" required autocomplete="email">
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
                    placeholder="Min. 8 karakter" required autocomplete="new-password">
                <button type="button" class="password-toggle" id="toggle-pw" aria-label="Tampilkan kata sandi">
                    <svg id="eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eye-off" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <div class="password-strength" id="strength-bars">
                <div class="strength-bar" id="s1"></div>
                <div class="strength-bar" id="s2"></div>
                <div class="strength-bar" id="s3"></div>
                <div class="strength-bar" id="s4"></div>
            </div>
            <p class="strength-label" id="strength-label"></p>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="form-input" placeholder="Ulangi kata sandi" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-primary" id="btn-submit" style="margin-top:8px">
            <span class="btn-text">Buat Akun</span>
            <div class="btn-spinner"></div>
        </button>
    </form>

    <p class="form-footer-text">
        Sudah punya akun? <a href="{{ route('login') }}" class="link-primary">Masuk di sini</a>
    </p>

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

        // Password strength meter
        const bars = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
        const label = document.getElementById('strength-label');
        const levels = [
            { name: '', cls: '' },
            { name: 'Lemah', cls: 'weak' },
            { name: 'Cukup', cls: 'fair' },
            { name: 'Bagus', cls: 'good' },
            { name: 'Kuat', cls: 'strong' },
        ];

        pw.addEventListener('input', () => {
            const v = pw.value;
            let score = 0;
            if (v.length >= 8) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            bars.forEach((bar, i) => {
                bar.className = 'strength-bar';
                if (i < score) bar.classList.add(levels[score].cls);
            });
            label.textContent = v ? levels[score].name : '';
        });

        // Loading state
        document.getElementById('register-form').addEventListener('submit', () => {
            document.getElementById('btn-submit').classList.add('is-loading');
        });
    </script>
    </x-slot>

</x-auth-layout>
