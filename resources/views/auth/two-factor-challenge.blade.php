<x-auth-layout
    title="Autentikasi Dua Faktor — ZED CORE"
    brandHeadline="Verifikasi"
    brandSub="dua langkah."
    brandDesc="Akun Anda dilindungi dengan autentikasi dua faktor untuk keamanan yang lebih tinggi.">

    <div class="form-header">
        <div class="form-icon-wrap" style="background:#f5f3ff">
            <svg viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
        </div>
        <h2 class="form-title" id="page-title">Kode Autentikasi</h2>
        <p class="form-subtitle" id="page-desc">Masukkan kode 6 digit dari aplikasi authenticator Anda (Google Authenticator, Authy, dll).</p>
    </div>

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- TOTP Form --}}
    <div id="totp-section">
        <form method="POST" action="{{ route('two-factor.login') }}" id="totp-form" novalidate>
            @csrf

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label" style="text-align:center;display:block;margin-bottom:14px">Kode OTP</label>
                <div class="form-input-wrap">
                    <input type="text" name="code" id="totp-code"
                        class="form-input form-input-no-icon"
                        style="text-align:center;font-size:24px;font-weight:700;letter-spacing:8px;height:56px"
                        placeholder="000000"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        autocomplete="one-time-code"
                        autofocus
                        required>
                </div>
                @error('code') <p class="field-error" style="text-align:center">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary" id="btn-totp">
                <span class="btn-text">Verifikasi Kode</span>
                <div class="btn-spinner"></div>
            </button>
        </form>
    </div>

    {{-- Recovery Code Form --}}
    <div id="recovery-section" style="display:none">
        <div class="form-header" style="margin-bottom:20px">
            <h2 class="form-title" style="font-size:20px">Gunakan Kode Pemulihan</h2>
            <p class="form-subtitle">Masukkan salah satu kode pemulihan yang Anda simpan saat mengaktifkan 2FA.</p>
        </div>

        <form method="POST" action="{{ route('two-factor.login') }}" id="recovery-form" novalidate>
            @csrf
            <div class="form-group">
                <label for="recovery_code" class="form-label">Kode Pemulihan</label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                    <input id="recovery_code" type="text" name="recovery_code"
                        class="form-input" placeholder="xxxx-xxxx-xxxx"
                        autocomplete="one-time-code" required>
                </div>
                @error('recovery_code') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary" id="btn-recovery" style="margin-top:8px">
                <span class="btn-text">Gunakan Kode Pemulihan</span>
                <div class="btn-spinner"></div>
            </button>
        </form>
    </div>

    {{-- Toggle between TOTP and Recovery --}}
    <div class="form-divider" style="margin-top:24px"><div class="form-divider-line"></div><span class="form-divider-text">atau</span><div class="form-divider-line"></div></div>

    <div style="text-align:center">
        <button type="button" class="toggle-link" id="toggle-mode">
            Tidak bisa mengakses aplikasi? Gunakan kode pemulihan
        </button>
    </div>

    <div class="info-box" style="margin-top:20px">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <p>Kode OTP baru dibuat setiap <strong>30 detik</strong>. Kode pemulihan hanya bisa digunakan <strong>sekali</strong>.</p>
    </div>

    <x-slot name="scripts">
    <script>
        let isRecovery = false;
        const totpSection = document.getElementById('totp-section');
        const recoverySection = document.getElementById('recovery-section');
        const toggleBtn = document.getElementById('toggle-mode');
        const pageTitle = document.getElementById('page-title');
        const pageDesc = document.getElementById('page-desc');

        toggleBtn.addEventListener('click', () => {
            isRecovery = !isRecovery;
            totpSection.style.display = isRecovery ? 'none' : 'block';
            recoverySection.style.display = isRecovery ? 'block' : 'none';
            toggleBtn.textContent = isRecovery
                ? 'Kembali gunakan kode dari aplikasi authenticator'
                : 'Tidak bisa mengakses aplikasi? Gunakan kode pemulihan';
            pageTitle.textContent = isRecovery ? 'Kode Pemulihan' : 'Kode Autentikasi';
            pageDesc.textContent = isRecovery
                ? 'Gunakan salah satu kode pemulihan yang Anda simpan.'
                : 'Masukkan kode 6 digit dari aplikasi authenticator Anda.';
        });

        // Auto-format & submit TOTP
        const totpInput = document.getElementById('totp-code');
        totpInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
            if (e.target.value.length === 6) {
                document.getElementById('btn-totp').classList.add('is-loading');
                document.getElementById('totp-form').submit();
            }
        });

        // Loading states
        document.getElementById('totp-form').addEventListener('submit', () => {
            document.getElementById('btn-totp').classList.add('is-loading');
        });
        document.getElementById('recovery-form').addEventListener('submit', () => {
            document.getElementById('btn-recovery').classList.add('is-loading');
        });
    </script>
    </x-slot>

</x-auth-layout>
