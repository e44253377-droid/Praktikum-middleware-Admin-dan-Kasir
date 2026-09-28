<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAFFE KITA | Kopi dan Cerita</title>
    <style>
        :root{--coffee:#38251d;--brown:#a66035;--cream:#fbf5ed;--muted:#866f60;--line:#eadbca}
        *{box-sizing:border-box}body{margin:0;background:var(--cream);color:var(--coffee);font-family:Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,sans-serif}
        .page{min-height:100vh;overflow:hidden;background:radial-gradient(circle at 85% 5%,#f0d8ba 0,transparent 27%),var(--cream)}
        .nav{max-width:1180px;margin:auto;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:20px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:900;letter-spacing:.14em;font-size:14px}.brand-mark{width:38px;height:38px;border-radius:13px;background:var(--coffee);color:#f7d7a8;display:grid;place-items:center;font-size:20px;box-shadow:0 8px 18px #38251d2b}
        .nav-links{display:flex;align-items:center;gap:24px;font-size:13px}.nav-links a{color:var(--coffee);text-decoration:none}.nav-links .login{border:1px solid var(--coffee);border-radius:999px;padding:10px 17px;font-weight:800}.nav-links .login:hover{background:var(--coffee);color:#fff}
        .hero{max-width:1180px;margin:0 auto;padding:74px 28px 92px;display:grid;grid-template-columns:1fr .9fr;align-items:center;gap:70px}
        .eyebrow{color:var(--brown);font-size:11px;font-weight:900;letter-spacing:.2em}.hero h1{font:700 clamp(42px,6vw,78px)/.98 Georgia,serif;letter-spacing:-.055em;margin:16px 0 22px;max-width:650px}.hero h1 em{color:var(--brown);font-style:normal}.hero p{color:var(--muted);font-size:16px;line-height:1.8;max-width:510px;margin:0 0 30px}.hero-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap}.button{display:inline-flex;align-items:center;gap:12px;text-decoration:none;border-radius:999px;padding:14px 21px;font-size:13px;font-weight:800}.button.primary{background:var(--brown);color:#fff;box-shadow:0 10px 22px #a6603538}.button.primary:hover{background:#894b2a}.button.secondary{border:1px solid var(--line);color:var(--coffee);background:#fffaf5}
        .visual{position:relative;min-height:430px}.coffee-card{position:absolute;inset:25px 0 0 25px;border-radius:34px;background:linear-gradient(145deg,#67412b,#251610);box-shadow:0 28px 55px #57331f33;overflow:hidden}.coffee-card:before{content:"";position:absolute;width:370px;height:370px;border:1px solid #e4ad7048;border-radius:50%;right:-125px;top:-95px;box-shadow:0 0 0 35px #e4ad7020,0 0 0 70px #e4ad7010}.coffee-card:after{content:"";position:absolute;width:210px;height:210px;border:1px solid #f1c7874d;border-radius:50%;left:-80px;bottom:-55px}
        .cup{position:absolute;z-index:2;left:50%;top:54%;transform:translate(-50%,-50%);width:205px;height:145px;border-radius:18px 18px 75px 75px;background:linear-gradient(140deg,#fff8ee,#d9c5ae);box-shadow:inset -14px -16px 25px #a8896a66,0 22px 25px #150b0766}.cup:before{content:"";position:absolute;width:165px;height:37px;left:20px;top:-15px;border-radius:50%;background:#4b2819;box-shadow:inset 0 8px 9px #a86b3d}.cup:after{content:"";position:absolute;width:56px;height:62px;right:-42px;top:26px;border:14px solid #e3cfb8;border-left:0;border-radius:0 45px 45px 0}.steam{position:absolute;z-index:3;left:50%;top:82px;width:8px;height:95px;border-left:2px solid #f7dfbf80;border-radius:50%;transform:rotate(12deg)}.steam.two{margin-left:32px;top:67px;transform:rotate(-13deg)}
        .visual-label{position:absolute;z-index:4;left:34px;bottom:32px;color:#f9e4c8}.visual-label strong{display:block;font:700 25px Georgia,serif}.visual-label span{font-size:11px;letter-spacing:.15em;color:#d5b898}
        .features{max-width:1180px;margin:auto;padding:0 28px 54px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px}.feature{background:#fffaf5;border:1px solid var(--line);border-radius:18px;padding:22px}.feature b{display:block;font:700 22px Georgia,serif;margin:10px 0 6px}.feature span{color:var(--muted);font-size:12px;line-height:1.6}
        @media(max-width:760px){.nav{padding:18px}.nav-links a:not(.login){display:none}.hero{padding:48px 22px 65px;grid-template-columns:1fr;gap:25px}.visual{min-height:320px}.coffee-card{inset:10px 0 0 10px}.features{grid-template-columns:1fr;padding:0 22px 35px}.hero h1{font-size:52px}}
    </style>
</head>
<body>
<div class="page">
    <nav class="nav">
        <div class="brand"><span class="brand-mark">☕</span> CAFFE KITA</div>
        <div class="nav-links">
            <a href="#cerita">Tentang Kami</a><a href="#menu">Menu</a>
            @auth
                <a class="login" href="{{ route('dashboard') }}">Buka Dashboard</a>
            @else
                <a class="login" href="{{ route('login') }}">Masuk</a>
            @endauth
        </div>
    </nav>
    <main class="hero">
        <section>
            <span class="eyebrow">KOPI &middot; MAKANAN &middot; CERITA</span>
            <h1>Secangkir hangat untuk <em>setiap cerita.</em></h1>
            <p>Selamat datang di CAFFE KITA. Tempat kecil dengan rasa yang besar, diracik segar untuk menemani pagi, istirahat, dan obrolan panjang Anda.</p>
            <div class="hero-actions">
                @auth
                    <a class="button primary" href="{{ route('dashboard') }}">Masuk ke aplikasi <span>→</span></a>
                @else
                    <a class="button primary" href="{{ route('login') }}">Mulai sekarang <span>→</span></a>
                @endauth
                <a class="button secondary" href="#menu">Lihat menu kami</a>
            </div>
        </section>
        <section class="visual" aria-label="Ilustrasi kopi CAFFE KITA">
            <div class="coffee-card"><div class="steam"></div><div class="steam two"></div><div class="cup"></div><div class="visual-label"><strong>made with care</strong><span>YOUR DAILY COFFEE RITUAL</span></div></div>
        </section>
    </main>
    <section class="features" id="menu">
        <article class="feature"><span>01 &middot; FRESHLY BREWED</span><b>Selalu segar</b><span>Setiap cangkir diracik setelah dipesan dengan biji kopi pilihan.</span></article>
        <article class="feature" id="cerita"><span>02 &middot; WARM PLACE</span><b>Ruang untuk cerita</b><span>Suasana nyaman untuk bekerja, bertemu, atau sekadar menikmati waktu.</span></article>
        <article class="feature"><span>03 &middot; GOOD SERVICE</span><b>Dilayani sepenuh hati</b><span>Karena kopi yang baik selalu hadir bersama pelayanan yang hangat.</span></article>
    </section>
</div>
</body>
</html>
