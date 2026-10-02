<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kami | CAFFE KITA</title>
    <style>
        :root{--coffee:#38251d;--brown:#a66035;--cream:#fbf5ed;--muted:#866f60;--line:#eadbca}
        *{box-sizing:border-box}body{margin:0;background:var(--cream);color:var(--coffee);font-family:Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,sans-serif}
        a{color:inherit;text-decoration:none}.page{min-height:100vh;background:radial-gradient(circle at 90% 0,#f0d8ba 0,transparent 27%),var(--cream)}
        .nav{max-width:1180px;margin:auto;padding:22px 28px;display:flex;align-items:center;justify-content:space-between;gap:20px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:900;letter-spacing:.14em;font-size:14px}.brand-mark{width:38px;height:38px;border-radius:13px;background:var(--coffee);color:#f7d7a8;display:grid;place-items:center;font-size:20px}
        .nav-links{display:flex;align-items:center;gap:12px}.nav-link,.action{border:1px solid var(--line);border-radius:999px;padding:10px 16px;font-size:13px;font-weight:750;background:#fffaf5}
        .action{background:var(--brown);border-color:var(--brown);color:#fff}.intro{max-width:1180px;margin:auto;padding:54px 28px 24px;text-align:center}
        .eyebrow{color:var(--brown);font-size:11px;font-weight:900;letter-spacing:.2em}.intro h1{font:700 clamp(38px,6vw,64px)/1.05 Georgia,serif;margin:13px 0}.intro p{color:var(--muted);max-width:580px;margin:0 auto;line-height:1.7}
        .catalog{max-width:1180px;margin:auto;padding:24px 28px 70px}.toolbar{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:18px}
        .toolbar input{width:min(100%,340px);padding:12px 16px;border:1px solid var(--line);border-radius:999px;background:#fffaf5;color:var(--coffee);font:inherit;outline-color:var(--brown)}
        .filters{display:flex;gap:9px;overflow:auto;padding:4px 0 18px}.filter{flex:0 0 auto;border:1px solid var(--line);border-radius:999px;background:#fffaf5;color:var(--coffee);padding:9px 15px;font:inherit;font-size:12px;font-weight:700;cursor:pointer}.filter.active,.filter:hover{background:var(--coffee);border-color:var(--coffee);color:#fff}
        .count{font-size:13px;color:var(--muted)}.grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
        .card{overflow:hidden;background:#fffaf5;border:1px solid var(--line);border-radius:18px;box-shadow:0 12px 28px #38251d0b}
        .photo{height:190px;background:#f2e5d7;display:grid;place-items:center;overflow:hidden;position:relative}.photo img{width:100%;height:100%;object-fit:cover}.placeholder{font-size:48px}
        .photo-credit{position:absolute;right:8px;bottom:8px;padding:5px 8px;border-radius:999px;background:#38251dcc;color:#fffaf5;font-size:9px;line-height:1.2;text-decoration:none}.photo-credit:hover{text-decoration:underline}
        .body{padding:17px}.category{font-size:10px;color:var(--brown);font-weight:850;letter-spacing:.12em;text-transform:uppercase}
        .body h2{font:700 21px Georgia,serif;margin:8px 0}.description{min-height:42px;color:var(--muted);font-size:12px;line-height:1.65;margin:0 0 16px}
        .card-footer{display:flex;justify-content:space-between;align-items:center;gap:10px}.price{font-weight:850;color:#27745a}.availability{font-size:11px;color:var(--muted)}
        .empty{grid-column:1/-1;padding:50px 20px;text-align:center;border:1px dashed var(--line);border-radius:18px;color:var(--muted);background:#fffaf5}
        .empty strong{display:block;color:var(--coffee);font:700 24px Georgia,serif;margin-bottom:8px}
        @media(max-width:900px){.grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:650px){.nav{padding:16px 18px}.nav-links{gap:7px}.nav-link,.action{padding:9px 12px;font-size:11px}.intro{padding:38px 20px 18px}.catalog{padding:18px 18px 48px}.grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.photo{height:145px}.body{padding:13px}.body h2{font-size:18px}.toolbar{align-items:flex-start;flex-direction:column}.toolbar input{width:100%}}@media(max-width:380px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="page">
    <nav class="nav">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark">☕</span> CAFFE KITA</a>
        <div class="nav-links">
            <a class="nav-link" href="{{ route('home') }}">Beranda</a>
            @auth
                <a class="action" href="{{ auth()->user()->isCashier() ? route('transactions.index') : route('dashboard') }}">
                    {{ auth()->user()->isCashier() ? 'Buka Kasir' : 'Dashboard Admin' }}
                </a>
            @else
                <a class="action" href="{{ route('login') }}">Masuk</a>
            @endauth
        </div>
    </nav>

    <header class="intro">
        <span class="eyebrow">DIBUAT SEGAR UNTUK ANDA</span>
        <h1>Menu CAFFE KITA</h1>
        <p>Pilih teman terbaik untuk waktu santai Anda. Semua menu yang ditampilkan tersedia di CAFFE KITA.</p>
    </header>

    <main class="catalog">
        <div class="toolbar">
            <input id="menu-search" type="search" placeholder="Cari nama atau kategori menu..." aria-label="Cari menu">
            <span class="count" id="menu-count">{{ $products->count() }} menu tersedia</span>
        </div>

        @if ($products->isNotEmpty())
            <div class="filters" role="group" aria-label="Filter kategori menu">
                <button class="filter active" type="button" data-category="all">Semua</button>
                @foreach ($products->pluck('category')->filter()->unique()->sort() as $category)
                    <button class="filter" type="button" data-category="{{ strtolower($category) }}">{{ $category }}</button>
                @endforeach
            </div>
        @endif

        @php
            $imageCredits = [
                'images/cafe-menu/cappuccino.jpg' => [
                    'artist' => 'Aneth David (SLU)',
                    'source' => 'https://commons.wikimedia.org/wiki/File:A-cup-of-cappuccino-coffee-dar-es-salaam-cafe.jpg',
                    'license' => 'CC BY-SA 4.0',
                ],
                'images/cafe-menu/whipped-cream.jpg' => [
                    'artist' => 'jeffreyw',
                    'source' => 'https://commons.wikimedia.org/wiki/File:And_some_whipped_cream_(4315201282).jpg',
                    'license' => 'CC BY 2.0',
                ],
                'images/cafe-menu/caramel-macchiato.jpg' => [
                    'artist' => 'Mina Başer',
                    'source' => 'https://www.pexels.com/photo/iced-caramel-macchiato-on-marble-table-34969154/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/oat-milk.jpg' => [
                    'artist' => 'Polina Tankilevitch',
                    'source' => 'https://www.pexels.com/photo/milk-in-clear-glass-jar-4187717/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/espresso.jpg' => [
                    'artist' => 'MART PRODUCTION',
                    'source' => 'https://www.pexels.com/photo/glass-of-espresso-26626461/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/extra-espresso-shot.jpg' => [
                    'artist' => 'RDNE Stock project',
                    'source' => 'https://www.pexels.com/photo/close-up-shot-of-a-shot-glass-9052283/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/thai-tea.jpg' => [
                    'artist' => 'Nguyễn Tiến Thịnh',
                    'source' => 'https://www.pexels.com/photo/refreshing-iced-thai-milk-tea-on-wooden-table-33241812/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/strawberry-milkshake.jpg' => [
                    'artist' => 'Arief Setiawan',
                    'source' => 'https://www.pexels.com/photo/a-glass-of-strawberry-milkshake-with-a-piece-of-strawberry-on-top-10066814/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/taro-milk-tea.jpg' => [
                    'artist' => 'bams awey',
                    'source' => 'https://www.pexels.com/photo/taro-milk-tea-in-clear-glass-5335709/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/nasi-ayam-geprek.jpg' => [
                    'artist' => 'Supardisahabu',
                    'source' => 'https://commons.wikimedia.org/wiki/File:Ayam_Geprek_Uyat.jpg',
                    'license' => 'CC BY-SA 4.0',
                ],
                'images/cafe-menu/nasi-goreng.jpg' => [
                    'artist' => 'Monakha laden',
                    'source' => 'https://www.pexels.com/photo/delicious-indonesian-nasi-goreng-with-fried-egg-37171028/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/mie-goreng.jpg' => [
                    'artist' => 'Ega Morgan',
                    'source' => 'https://www.pexels.com/photo/delicious-indonesian-fried-noodles-with-satay-37179944/',
                    'license' => 'Pexels',
                ],
                'images/cafe-menu/sate-ayam.jpg' => [
                    'artist' => 'Man Fong Wong',
                    'source' => 'https://www.pexels.com/photo/delicious-indonesian-satay-with-peanut-sauce-37113562/',
                    'license' => 'Pexels',
                ],
            ];
        @endphp

        <div class="grid" id="menu-grid">
            @forelse ($products as $product)
                <article class="card" data-category="{{ strtolower($product->category ?? '') }}" data-search="{{ strtolower($product->name . ' ' . $product->category . ' ' . $product->description) }}">
                    <div class="photo">
                        @if ($product->image)
                            <img src="{{ str_starts_with($product->image, 'images/') ? asset($product->image) : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                            @if (isset($imageCredits[$product->image]))
                                <a class="photo-credit" href="{{ $imageCredits[$product->image]['source'] }}" target="_blank" rel="noopener noreferrer">
                                    Foto: {{ $imageCredits[$product->image]['artist'] }} · {{ $imageCredits[$product->image]['license'] }}
                                </a>
                            @endif
                        @else
                            <span class="placeholder" aria-hidden="true">{{ str_contains(strtolower($product->category ?? ''), 'snack') || str_contains(strtolower($product->category ?? ''), 'add-on') ? '🥐' : (str_contains(strtolower($product->category ?? ''), 'non-kopi') ? '🧋' : '☕') }}</span>
                        @endif
                    </div>
                    <div class="body">
                        <span class="category">{{ $product->category ?: 'Menu pilihan' }}</span>
                        <h2>{{ $product->name }}</h2>
                        <p class="description">{{ $product->description ?: 'Diracik dengan bahan pilihan, siap menemani hari Anda.' }}</p>
                        <div class="card-footer">
                            <span class="price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</span>
                            <span class="availability">{{ $product->stock > 0 ? 'Tersedia' : 'Stok habis' }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty" id="empty-state">
                    <strong>Menu sedang disiapkan</strong>
                    Silakan kembali lagi untuk melihat pilihan menu CAFFE KITA.
                </div>
            @endforelse
            @if ($products->isNotEmpty())
                <div class="empty" id="no-results" hidden>
                    <strong>Menu tidak ditemukan</strong>
                    Coba gunakan nama atau kategori yang berbeda.
                </div>
            @endif
        </div>
    </main>
</div>
<script>
    const searchInput = document.getElementById('menu-search');
    const menuCards = [...document.querySelectorAll('.card')];
    const noResults = document.getElementById('no-results');
    const menuCount = document.getElementById('menu-count');
    const filterButtons = [...document.querySelectorAll('.filter')];
    let selectedCategory = 'all';

    const updateMenu = () => {
        const query = searchInput.value.trim().toLocaleLowerCase('id');
        let visibleCards = 0;

        menuCards.forEach((card) => {
            const matchesCategory = selectedCategory === 'all' || card.dataset.category === selectedCategory;
            const matchesSearch = card.dataset.search.includes(query);
            const isVisible = matchesCategory && matchesSearch;
            card.hidden = !isVisible;
            visibleCards += Number(isVisible);
        });

        menuCount.textContent = `${visibleCards} menu tersedia`;

        if (noResults) {
            noResults.hidden = visibleCards > 0;
        }
    };

    searchInput.addEventListener('input', updateMenu);
    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            selectedCategory = button.dataset.category;
            filterButtons.forEach((filter) => filter.classList.toggle('active', filter === button));
            updateMenu();
        });
    });
</script>
</body>
</html>
