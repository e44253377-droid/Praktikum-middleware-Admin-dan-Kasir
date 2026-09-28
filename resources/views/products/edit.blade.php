<!DOCTYPE html>
<html lang="id">
<head>
<title>Edit Produk - CAFFE KITA</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
<div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8">
  <h1 class="text-2xl font-bold text-gray-800 mb-1">Edit Produk</h1>
  <p class="text-sm text-gray-500 mb-6">Perubahan stok dan harga langsung digunakan oleh kasir.</p>
  @if ($errors->any())
    <div class="rounded-lg bg-red-50 p-3 mb-4 text-sm text-red-700">
      <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif
  <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf @method('PUT')
    <div><label class="text-sm font-semibold">Nama Produk</label><input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full mt-1 px-4 py-2.5 border rounded-lg"></div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="text-sm font-semibold">Kode Produk</label><input type="text" name="code" value="{{ old('code', $product->code) }}" class="w-full mt-1 px-4 py-2.5 border rounded-lg"></div>
      <div><label class="text-sm font-semibold">Barcode</label><input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="w-full mt-1 px-4 py-2.5 border rounded-lg"></div>
    </div>
    <div><label class="text-sm font-semibold">Kategori</label><input type="text" name="category" value="{{ old('category', $product->category) }}" class="w-full mt-1 px-4 py-2.5 border rounded-lg"></div>
    <div><label class="text-sm font-semibold">Deskripsi</label><textarea name="description" rows="3" class="w-full mt-1 px-4 py-2.5 border rounded-lg">{{ old('description', $product->description) }}</textarea></div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="text-sm font-semibold">Harga</label><input type="number" name="price" min="0" value="{{ old('price', $product->price) }}" required class="w-full mt-1 px-4 py-2.5 border rounded-lg"></div>
      <div><label class="text-sm font-semibold">Stok</label><input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required class="w-full mt-1 px-4 py-2.5 border rounded-lg"></div>
    </div>
    <div><label class="text-sm font-semibold">Gambar Produk</label><input type="file" name="image" accept="image/*" class="w-full mt-1 px-4 py-2.5 border rounded-lg bg-gray-50"></div>
    <div class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="w-4 h-4 accent-blue-600"><label class="text-sm font-semibold">Tampilkan di Kasir</label></div>
    <div class="flex gap-3 pt-2">
      <a href="{{ route('products.index') }}" class="w-1/2 text-center bg-gray-200 hover:bg-gray-300 py-2.5 rounded-lg font-semibold">Batal</a>
      <button type="submit" class="w-1/2 bg-amber-600 hover:bg-amber-700 text-white py-2.5 rounded-lg font-semibold">Simpan Perubahan</button>
    </div>
  </form>
</div>
</body>
</html>
