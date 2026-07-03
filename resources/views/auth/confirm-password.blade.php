<x-auth-layout
    title="Konfirmasi Kata Sandi — ZED CORE"
    brandHeadline="Konfirmasi"
    brandSub="identitas Anda."
    brandDesc="Area ini memerlukan verifikasi tambahan untuk melindungi keamanan akun Anda.">

    <div class="form-header">
        <div class="form-icon-wrap" style="background:#fefce8">
            <svg viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h2 class="form-title">Konfirmasi kata sandi</h2>
        <p class="form-subtitle">Area yang Anda tuju memerlukan verifikasi. Masukkan kata sandi akun Anda untuk melanjutkan.</p>
    </div>

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" id="confirm-form" novalidate>
        @csrf

        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="form-input-wrap">
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <input id="password" type="password" name="password"
                    class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    placeholder="Masukkan kata sandi Anda" required autofocus autocomplete="current-password">
                <button type="button" class="password-toggle" id="toggle-pw" aria-label="Tampilkan kata sandi">
                    <svg id="eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eye-off" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary" id="btn-submit" style="margin-top:8px">
            <span class="btn-text">Konfirmasi & Lanjutkan</span>
            <div class="btn-spinner"></div>
        </button>
    </form>

    <div class="info-box" style="margin-top:20px">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <p>Ini adalah langkah keamanan tambahan. Sesi konfirmasi berlaku selama <strong>3 jam</strong>.</p>
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
        document.getElementById('confirm-form').addEventListener('submit', () => {
            document.getElementById('btn-submit').classList.add('is-loading');
        });
    </script>
    </x-slot>

</x-auth-layout>
