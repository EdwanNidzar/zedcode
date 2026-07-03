<x-auth-layout
    title="Lupa Kata Sandi — ZED CORE"
    brandHeadline="Reset"
    brandSub="kata sandi Anda."
    brandDesc="Kami akan mengirimkan link reset kata sandi ke email perusahaan Anda.">

    <div class="form-header">
        <a href="{{ route('login') }}" class="form-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke login
        </a>
        <div class="form-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        </div>
        <h2 class="form-title">Lupa kata sandi?</h2>
        <p class="form-subtitle">Masukkan email Anda dan kami akan mengirimkan link untuk mereset kata sandi.</p>
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

    <form method="POST" action="{{ route('password.email') }}" id="forgot-form" novalidate>
        @csrf

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

        <button type="submit" class="btn-primary" id="btn-submit" style="margin-top:8px">
            <span class="btn-text">Kirim Link Reset</span>
            <div class="btn-spinner"></div>
        </button>
    </form>

    <div class="form-divider"><div class="form-divider-line"></div><span class="form-divider-text">Info</span><div class="form-divider-line"></div></div>

    <div class="info-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <p>Link reset berlaku selama <strong>60 menit</strong>. Periksa folder <strong>spam/junk</strong> jika email tidak masuk.</p>
    </div>

    <x-slot name="scripts">
    <script>
        document.getElementById('forgot-form').addEventListener('submit', () => {
            document.getElementById('btn-submit').classList.add('is-loading');
        });
    </script>
    </x-slot>

</x-auth-layout>
