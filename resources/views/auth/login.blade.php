<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - CAFFE KITA</title>
    <style>
        :root{font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;color:#172033}
        *{box-sizing:border-box}body{margin:0;min-height:100vh;background:#f4f7f8}
        .login-page{min-height:100vh;display:grid;grid-template-columns:minmax(0,1.08fr) minmax(420px,.92fr)}
        .brand-panel{position:relative;display:flex;align-items:center;padding:clamp(35px,7vw,100px);overflow:hidden;background:linear-gradient(145deg,#0d3b3d,#116d60 58%,#1a9274);color:#fff}
        .brand-panel:before,.brand-panel:after{content:"";position:absolute;border-radius:50%;border:1px solid #ffffff25}
        .brand-panel:before{width:480px;height:480px;right:-160px;top:-120px}.brand-panel:after{width:310px;height:310px;left:-170px;bottom:-120px}
        .brand-content{position:relative;z-index:1;max-width:530px}.brand-mark{width:58px;height:58px;display:grid;place-items:center;background:#ffffff18;border:1px solid #ffffff3d;border-radius:18px;font-size:27px;margin-bottom:28px}
        .brand-content h1{font-size:clamp(38px,5vw,70px);line-height:1.02;letter-spacing:-.05em;margin:0 0 17px;font-weight:900}.brand-content p{max-width:410px;color:#d9f5eb;font-size:16px;line-height:1.7;margin:0}.brand-tag{display:inline-flex;margin-top:30px;padding:9px 13px;border-radius:30px;background:#ffffff16;color:#d9f5eb;font-size:12px;font-weight:700;letter-spacing:.04em}
        .form-panel{display:flex;align-items:center;justify-content:center;padding:30px;background:#fff}.login-box{width:min(100%,390px)}.mini-brand{display:flex;align-items:center;gap:10px;color:#16836c;font-size:13px;font-weight:900;letter-spacing:.08em;margin-bottom:35px}.mini-logo{width:38px;height:38px;display:grid;place-items:center;border-radius:12px;background:#e6f7f1;font-size:19px}.login-box h2{font-size:30px;letter-spacing:-.04em;margin:0;color:#172033}.login-box .subtitle{color:#7a899a;font-size:14px;margin:9px 0 28px}.field{margin-bottom:18px}.field label{display:block;color:#3c4b60;font-size:12px;font-weight:800;margin-bottom:8px}        .field input{width:100%;height:48px;border:1px solid #dce5eb;border-radius:10px;padding:0 14px;color:#172033;font-size:14px;outline:0;transition:.15s;background:#fbfcfd}.field input:focus{border-color:#16836c;box-shadow:0 0 0 4px #16836c18;background:#fff}.password-wrap{position:relative}.password-wrap input{padding-right:48px}.toggle-password{position:absolute;right:7px;top:7px;width:34px;height:34px;border:0;border-radius:8px;background:transparent;color:#7b8b9b;display:grid;place-items:center;cursor:pointer}.toggle-password:hover{background:#eaf6f2;color:#16836c}.toggle-password svg{width:19px;height:19px}.field-error{color:#d13f4d;font-size:11px;margin-top:6px}.login-button{width:100%;height:49px;border:0;border-radius:10px;background:#16836c;color:#fff;font-size:14px;font-weight:800;cursor:pointer;box-shadow:0 8px 16px #16836c2b;transition:.15s}.login-button:hover{background:#116d5a;transform:translateY(-1px)}.login-footer{text-align:center;color:#8996a5;font-size:13px;margin-top:24px}.login-footer a{color:#16836c;font-weight:800;text-decoration:none}.login-footer a:hover{text-decoration:underline}.demo-note{margin-top:30px;border:1px solid #e4eee9;background:#f6fbf9;padding:12px 14px;border-radius:10px;color:#668075;font-size:11px;line-height:1.6}.demo-note strong{display:block;color:#16836c;margin-bottom:3px}
        @media(max-width:800px){.login-page{grid-template-columns:1fr}.brand-panel{min-height:280px;padding:36px 28px;align-items:flex-end}.brand-content h1{font-size:42px}.brand-content p{font-size:14px}.brand-mark{margin-bottom:18px}.form-panel{min-height:calc(100vh - 280px);padding:35px 24px}}
    </style>
</head>
<body>
<main class="login-page">
    <section class="brand-panel">
        <div class="brand-content">
            <div class="brand-mark">☕</div>
            <h1>CAFFE<br>KITA</h1>
            <p>Kelola produk dan transaksi dengan lebih cepat, rapi, dan nyaman dalam satu sistem.</p>
            <span class="brand-tag">POINT OF SALE &middot; MANAGEMENT</span>
        </div>
    </section>
    <section class="form-panel">
        <div class="login-box">
            <div class="mini-brand"><span class="mini-logo">☕</span> CAFFE KITA</div>
            <h2>Selamat datang kembali</h2>
            <p class="subtitle">Masuk untuk melanjutkan ke sistem toko.</p>
            @if ($errors->any())
                <div class="field-error" style="margin-bottom:18px">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label for="email">EMAIL</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@contoh.com" required autofocus autocomplete="username">
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="password">PASSWORD</label>
                    <div class="password-wrap">
                        <input id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                        <button class="toggle-password" type="button" id="toggle-password" aria-label="Tampilkan password" title="Tampilkan password">
                            <svg id="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18 18 0 0 1-3.1 4.5"/><path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10.8 10.8 0 0 0 3.2-.5"/></svg>
                        </button>
                    </div>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <button class="login-button" type="submit">Masuk ke Sistem</button>
            </form>
            <div class="login-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></div>
            <div class="demo-note"><strong>Akses sistem</strong>Gunakan akun admin untuk mengelola produk atau akun kasir untuk melakukan transaksi.</div>
        </div>
    </section>
</main>
<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    togglePassword.addEventListener('click', () => {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        eyeOpen.style.display = isHidden ? 'none' : 'block';
        eyeClosed.style.display = isHidden ? 'block' : 'none';
        togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        togglePassword.setAttribute('title', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
    });
</script>
</body>
</html>
