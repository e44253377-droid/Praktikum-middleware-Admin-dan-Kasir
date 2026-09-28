<!DOCTYPE html>
<html>
<head>
<title>Menu Kita - Banyak Pilihan</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>.filter-active{background:#7f1d1d !important; color:white !important; border-color:#7f1d1d !important}</style>
</head>
<body class="bg-[#FFF8F0]">

<div class="bg-white shadow-sm p-4 sticky top-0 z-10 flex justify-between items-center px-8">
  <h1 class="font-black text-2xl text-amber-900">CAFFE KITA 🍽️</h1>
  <div class="flex items-center gap-3">
    <a href="{{ route('dashboard') }}" class="border border-amber-900 text-amber-900 px-4 py-2 rounded-full text-sm font-bold hover:bg-amber-50 transition">← Dashboard Admin</a>
    <a href="{{ route('products.create') }}" class="bg-amber-900 text-white px-4 py-2 rounded-full text-sm font-bold">+ Tambah Menu</a>
  </div>
</div>

<div class="max-w-6xl mx-auto p-6">
  <h2 class="text-3xl font-black text-amber-900">Pilih Racikan Favoritmu Hari Ini!</h2>
  <p class="text-amber-800/60 text-sm mt-1">Ada {{ $products->count() }} pilihan minuman & makanan premium</p>

  <!-- TOMBOL FILTER YANG BISA DI TEKAN -->
  <div class="flex gap-3 mt-6 overflow-x-auto pb-2" id="filterContainer">
    <button onclick="filterMenu('semua', this)" class="filter-btn filter-active bg-white border border-amber-200 px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap">✨ Semua</button>
    <button onclick="filterMenu('minuman', this)" class="filter-btn bg-white border border-amber-200 px-5 py-2 rounded-full text-sm whitespace-nowrap">🥤 Minuman</button>
    <button onclick="filterMenu('makanan', this)" class="filter-btn bg-white border border-amber-200 px-5 py-2 rounded-full text-sm whitespace-nowrap">🍱 Makanan</button>
    <button onclick="filterMenu('populer', this)" class="filter-btn bg-white border border-amber-200 px-5 py-2 rounded-full text-sm whitespace-nowrap">🔥 Populer</button>
    <button onclick="filterMenu('baru', this)" class="filter-btn bg-white border border-amber-200 px-5 py-2 rounded-full text-sm whitespace-nowrap">🆕 Baru</button>
    <button onclick="filterMenu('diskon', this)" class="filter-btn bg-white border border-amber-200 px-5 py-2 rounded-full text-sm whitespace-nowrap">💸 Diskon</button>
  </div>

  <!-- GRID MENU -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mt-6" id="menuGrid">
    @foreach($products as $product)
    <div class="product-card bg-white rounded-[20px] p-3 shadow-sm border border-amber-100 hover:shadow-lg transition" 
         data-category="{{ strtolower($product->category) }}" 
         data-name="{{ strtolower($product->name) }}">
      <div class="bg-amber-50 rounded-[16px] h-36 flex items-center justify-center overflow-hidden relative">
        @if($product->image)
          <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
        @else
          <span class="text-4xl">🍹</span>
        @endif
        <span class="absolute top-2 left-2 {{ $product->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $product->is_active ? 'Aktif di Kasir' : 'Nonaktif' }}</span>
        <span class="absolute top-2 right-2 bg-white/90 text-[10px] px-2 py-0.5 rounded-full">Stok {{ $product->stock }}</span>
      </div>
      <div class="mt-3 px-1">
        <h3 class="font-bold text-amber-950 text-sm">{{ $product->name }}</h3>
        <p class="text-[11px] text-amber-800/50 mt-1 capitalize">{{ $product->category ?: 'Tanpa kategori' }} · {{ $product->pos_code }}</p>
        <div class="flex justify-between items-center mt-3">
          <p class="font-black text-green-600 text-sm">Rp {{ number_format($product->price,0,',','.') }}</p>
          <div class="flex gap-1">
            <a href="{{ route('products.edit',$product->id) }}" class="bg-amber-100 text-amber-900 w-7 h-7 flex items-center justify-center rounded-full text-xs">✏️</a>
            <form action="{{ route('products.destroy',$product->id) }}" method="POST">@csrf @method('DELETE')<button class="bg-red-100 text-red-600 w-7 h-7 flex items-center justify-center rounded-full text-xs">🗑️</button></form>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<script>
function filterMenu(kategori, btn) {
  // 1. Ganti warna tombol yang aktif
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('filter-active'));
  btn.classList.add('filter-active');

  // 2. Filter card nya
  document.querySelectorAll('.product-card').forEach(card => {
    let cat = card.dataset.category;
    let name = card.dataset.name;
    
    if(kategori === 'semua') {
      card.style.display = 'block';
    } else if(kategori === 'minuman' && cat.includes('minuman')) {
      card.style.display = 'block';
    } else if(kategori === 'makanan' && cat.includes('makanan')) {
      card.style.display = 'block';
    } else if(kategori === 'populer' && (name.includes('boba') || name.includes('geprek'))) {
      // anggap boba & geprek yang populer
      card.style.display = 'block';
    } else if(kategori === 'baru' && name.includes('bakso')) {
      // anggap bakso yang baru
      card.style.display = 'block';
    } else if(kategori === 'diskon') {
      card.style.display = 'block'; // nanti bisa kasih diskon
    } else if(kategori === cat) {
      card.style.display = 'block';
    } else {
      if(['populer','baru','diskon'].includes(kategori)) {
         // untuk demo, kalau populer/baru/diskon tetep show semua dulu biar gak kosong
         // ubah logika ini sesuai selera
      }
      if(kategori === 'minuman' || kategori === 'makanan') {
         card.style.display = 'none';
      }
    }
    
    // LOGIKA SIMPEL YANG PASTI JALAN:
    if(kategori === 'minuman') {
      card.style.display = cat.includes('minuman') ? 'block' : 'none';
    }
    if(kategori === 'makanan') {
      card.style.display = cat.includes('makanan') ? 'block' : 'none';
    }
    if(kategori === 'populer') {
      card.style.display = (name.includes('boba') || name.includes('geprek')) ? 'block' : 'none';
    }
    if(kategori === 'baru') {
      card.style.display = name.includes('bakso') ? 'block' : 'none';
    }
  });
}
</script>

</body>
</html>