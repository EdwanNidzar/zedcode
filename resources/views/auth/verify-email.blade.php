<x-auth-layout
    title="Verifikasi Email — ZED CORE"
    brandHeadline="Satu langkah"
    brandSub="lagi untuk masuk."
    brandDesc="Verifikasi email Anda untuk memastikan keamanan akun dan mengakses semua fitur sistem.">

    <div class="form-header">
        <div class="form-icon-wrap" style="background:#f0fdf4">
            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <h2 class="form-title">Verifikasi email Anda</h2>
        <p class="form-subtitle">Kami telah mengirimkan link verifikasi ke alamat email Anda. Silakan cek kotak masuk dan klik link tersebut.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-success" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <p>Link verifikasi baru telah dikirim ke email Anda.</p>
        </div>
    @endif

    {{-- Email indicator --}}
    <div class="alert-info" style="margin-bottom:24px">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <p>Link dikirim ke: <strong>{{ auth()->user()->email }}</strong></p>
    </div>

    {{-- Resend form --}}
    <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
        @csrf
        <button type="submit" class="btn-primary" id="btn-resend">
            <span class="btn-text">Kirim Ulang Email Verifikasi</span>
            <div class="btn-spinner"></div>
        </button>
    </form>

    <div class="form-divider"><div class="form-divider-line"></div><span class="form-divider-text">atau</span><div class="form-divider-line"></div></div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="width:100%;height:46px;background:transparent;border:1.5px solid var(--hairline);border-radius:var(--radius-pill);font-family:'Inter',sans-serif;font-size:14px;font-weight:500;color:var(--muted);cursor:pointer;transition:border-color .15s,color .15s"
            onmouseover="this.style.borderColor='var(--ink)';this.style.color='var(--ink)'"
            onmouseout="this.style.borderColor='var(--hairline)';this.style.color='var(--muted)'">
            Keluar dari Akun
        </button>
    </form>

    <div class="info-box" style="margin-top:20px">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <p>Tidak menemukan email? Periksa folder <strong>spam / junk</strong>. Link berlaku selama <strong>60 menit</strong>.</p>
    </div>

    <x-slot name="scripts">
    <script>
        document.getElementById('resend-form').addEventListener('submit', function() {
            const btn = document.getElementById('btn-resend');
            btn.classList.add('is-loading');
            btn.disabled = true;
        });
    </script>
    </x-slot>

</x-auth-layout>
