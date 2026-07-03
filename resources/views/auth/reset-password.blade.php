<x-auth-layout
    title="Reset Kata Sandi — ZED CORE"
    brandHeadline="Buat kata sandi"
    brandSub="baru yang kuat."
    brandDesc="Pilih kata sandi yang aman dan belum pernah Anda gunakan sebelumnya.">

    <div class="form-header">
        <div class="form-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h2 class="form-title">Reset kata sandi</h2>
        <p class="form-subtitle">Masukkan kata sandi baru untuk akun <strong>{{ $request->email }}</strong></p>
    </div>

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" id="reset-form" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ $request->email }}">

        {{-- New Password --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi Baru</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <input id="password" type="password" name="password"
                    class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    placeholder="Min. 8 karakter" required autofocus autocomplete="new-password">
                <button type="button" class="password-toggle" id="toggle-pw" aria-label="Tampilkan kata sandi">
                    <svg id="eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eye-off" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <div class="password-strength">
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
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="form-input" placeholder="Ulangi kata sandi baru" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-primary" id="btn-submit" style="margin-top:8px">
            <span class="btn-text">Simpan Kata Sandi Baru</span>
            <div class="btn-spinner"></div>
        </button>
    </form>

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
        const levels = ['', 'Lemah', 'Cukup', 'Bagus', 'Kuat'];
        const cls = ['', 'weak', 'fair', 'good', 'strong'];

        pw.addEventListener('input', () => {
            const v = pw.value;
            let score = 0;
            if (v.length >= 8) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;
            bars.forEach((bar, i) => {
                bar.className = 'strength-bar';
                if (i < score) bar.classList.add(cls[score]);
            });
            label.textContent = v ? levels[score] : '';
        });

        document.getElementById('reset-form').addEventListener('submit', () => {
            document.getElementById('btn-submit').classList.add('is-loading');
        });
    </script>
    </x-slot>

</x-auth-layout>
