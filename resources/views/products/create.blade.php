<!DOCTYPE html>
<html>
<head>
<title>Tambah Produk</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
<div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8">
  <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Produk Baru</h1>
  <p class="text-sm text-gray-500 mb-6">Isi data Indomie Goreng disini le Grace!</p>

  @if ($errors->any())
    <div class="rounded-lg bg-red-50 p-3 text-sm text-red-700">
      <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif
  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div><label class="text-sm font-semibold">Nama Produk</label><input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Indomie Goreng" class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="text-sm font-semibold">Kode Produk</label><input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: KOP-001" class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></div>
      <div><label class="text-sm font-semibold">Barcode</label><input type="text" name="barcode" value="{{ old('barcode') }}" placeholder="Scan atau masukkan barcode" class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></div>
    </div>
    <div><label class="text-sm font-semibold">Kategori</label><input type="text" name="category" value="{{ old('category') }}" placeholder="Contoh: Minuman" class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></div>
    <div><label class="text-sm font-semibold">Deskripsi</label><textarea name="description" rows="3" placeholder="Deskripsi produk..." class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description') }}</textarea></div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="text-sm font-semibold">Harga</label><input type="number" name="price" required min="0" value="{{ old('price', 5000) }}" class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></div>
      <div><label class="text-sm font-semibold">Stok</label><input type="number" name="stock" required min="0" value="{{ old('stock', 20) }}" class="w-full mt-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></div>
    </div>
    <div><label class="text-sm font-semibold">Product Image</label><input type="file" name="image" class="w-full mt-1 px-4 py-2.5 border rounded-lg bg-gray-50"></div>
    <div class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="w-4 h-4 accent-blue-600"><label class="text-sm font-semibold">Tampilkan di Kasir</label></div>
    <div class="flex gap-3 pt-2">
      <a href="{{ route('products.index') }}" class="w-1/2 text-center bg-gray-200 hover:bg-gray-300 py-2.5 rounded-lg font-semibold">Batal</a>
      <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold shadow">Simpan Produk</button>
    </div>
  </form>
</div>
</body>
</html>