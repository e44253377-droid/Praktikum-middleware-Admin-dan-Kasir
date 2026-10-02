<x-app-layout>
    <x-slot name="header">
        <div class="admin-heading">
            <div>
                <span class="admin-eyebrow">ADMINISTRATION</span>
                <h2>Dashboard Admin</h2>
                <p>Kelola operasional CAFFE KITA dengan lebih mudah.</p>
            </div>
            <div class="admin-date">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
    </x-slot>

    <div class="admin-dashboard">
        <section class="admin-welcome">
            <div class="admin-welcome-user">
                @if (auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Foto {{ auth()->user()->name }}" class="admin-profile-photo">
                @else
                    <div class="admin-profile-photo admin-profile-fallback">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div>
                <span class="admin-eyebrow">SELAMAT DATANG KEMBALI</span>
                <h1>Halo, {{ auth()->user()->name }} 👋</h1>
                <p>Berikut ringkasan aktivitas toko hari ini.</p>
                </div>
            </div>
            @can('manage-products')
                <a href="{{ route('products.index') }}" class="admin-button">Kelola Produk <span>→</span></a>
            @endcan
        </section>

        <section class="admin-stat-grid">
            <article class="admin-stat-card">
                <div class="stat-icon stat-icon-green">Rp</div>
                <div><span>Pendapatan Hari Ini</span><strong>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</strong><small>Transaksi hari ini</small></div>
            </article>
            <article class="admin-stat-card">
                <div class="stat-icon stat-icon-blue">↗</div>
                <div><span>Transaksi Hari Ini</span><strong>{{ number_format($todayTransactionCount) }}</strong><small>Transaksi berhasil</small></div>
            </article>
            <article class="admin-stat-card">
                <div class="stat-icon stat-icon-purple">▦</div>
                <div><span>Produk Aktif</span><strong>{{ number_format($productCount) }}</strong><small>Produk tersedia di katalog</small></div>
            </article>
            <article class="admin-stat-card">
                <div class="stat-icon stat-icon-orange">!</div>
                <div><span>Stok Menipis</span><strong>{{ number_format($lowStockCount) }}</strong><small>Perlu segera diperiksa</small></div>
            </article>
        </section>

        <section class="admin-content-grid">
            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div><h3>Transaksi Terbaru</h3><p>Aktivitas penjualan terakhir</p></div>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Nomor Transaksi</th><th>Kasir</th><th>Metode</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->transaction_number ?: $transaction->kode_transaksi }}</strong><small>{{ $transaction->created_at->format('d/m/Y H:i') }}</small></td>
                                    <td>{{ $transaction->cashier?->name ?? '-' }}</td>
                                    <td>{{ $transaction->payment_method ?: $transaction->metode_bayar }}</td>
                                    <td class="admin-total">Rp {{ number_format($transaction->grand_total ?: $transaction->total_harga, 0, ',', '.') }}</td>
                                    <td><span class="admin-status">{{ ucfirst($transaction->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="admin-empty">Belum ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="admin-panel stock-panel">
                <div class="admin-panel-heading">
                    <div><h3>Stok Menipis</h3><p>Produk dengan stok maksimal 5</p></div>
                    @can('manage-products')
                        <a href="{{ route('products.index') }}">Lihat semua <span>→</span></a>
                    @endcan
                </div>
                <div class="stock-list">
                    @forelse($lowStockProducts as $product)
                        <div class="stock-item">
                            <div class="stock-product">
                                <div class="stock-avatar">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                                <div><strong>{{ $product->name }}</strong><small>{{ $product->pos_code }}</small></div>
                            </div>
                            <span class="{{ $product->stock <= 0 ? 'stock-empty' : 'stock-warning' }}">{{ $product->stock <= 0 ? 'Habis' : $product->stock . ' tersisa' }}</span>
                        </div>
                    @empty
                        <div class="admin-empty">Semua stok masih aman.</div>
                    @endforelse
                </div>
            </article>
        </section>
    </div>

    <style>
        .admin-heading{display:flex;justify-content:space-between;align-items:center;gap:20px}.admin-heading h2{font-size:22px;font-weight:800;color:#172033;margin:3px 0}.admin-heading p{font-size:13px;color:#718096;margin:0}.admin-eyebrow{font-size:10px;letter-spacing:.16em;color:#16836c;font-weight:800}.admin-date{font-size:13px;color:#718096}.admin-dashboard{min-height:calc(100vh - 145px);background:#f3f6f9;padding:28px max(24px,calc((100% - 1280px)/2)) 48px}.admin-welcome{display:flex;justify-content:space-between;align-items:center;gap:20px;background:linear-gradient(110deg,#173b4c,#16836c);color:#fff;border-radius:18px;padding:26px 30px;margin-bottom:20px;box-shadow:0 12px 28px #16836c2b}.admin-welcome-user{display:flex;align-items:center;gap:16px}.admin-profile-photo{width:64px;height:64px;border-radius:18px;object-fit:cover;border:3px solid #ffffff80;box-shadow:0 5px 12px #062e2c4d}.admin-profile-fallback{display:flex;align-items:center;justify-content:center;background:#ffffff26;color:#fff;font-size:25px;font-weight:900}.admin-welcome h1{font-size:24px;font-weight:800;margin:5px 0}.admin-welcome p{color:#d9f4eb;font-size:13px;margin:0}.admin-button{display:inline-flex;align-items:center;gap:18px;background:#fff;color:#176e5b;padding:11px 15px;border-radius:9px;font-size:12px;font-weight:800;white-space:nowrap}.admin-button span,.admin-panel-heading a span{font-size:17px}.admin-stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:15px;margin-bottom:20px}.admin-stat-card,.admin-panel{background:#fff;border:1px solid #e4eaf0;border-radius:15px;box-shadow:0 7px 20px #233b530b}.admin-stat-card{display:flex;align-items:center;gap:13px;padding:18px}.stat-icon{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:13px}.stat-icon-green{background:#e5f7f0;color:#16836c}.stat-icon-blue{background:#eaf2ff;color:#4674c7}.stat-icon-purple{background:#f0ebff;color:#7652c9}.stat-icon-orange{background:#fff1e3;color:#db792a}.admin-stat-card span,.admin-stat-card small{display:block;color:#8090a2;font-size:11px}.admin-stat-card strong{display:block;color:#172033;font-size:20px;margin:4px 0}.admin-stat-card small{font-size:10px}.admin-content-grid{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(300px,.8fr);gap:18px}.admin-panel{padding:20px}.admin-panel-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:15px}.admin-panel-heading h3{margin:0;color:#172033;font-size:16px;font-weight:800}.admin-panel-heading p{margin:4px 0 0;color:#8a98a8;font-size:11px}.admin-panel-heading a{color:#16836c;font-size:11px;font-weight:800;white-space:nowrap}.admin-table-wrap{overflow:auto}.admin-table{width:100%;border-collapse:collapse;min-width:600px}.admin-table th{text-align:left;color:#9aa7b4;text-transform:uppercase;font-size:10px;letter-spacing:.05em;padding:9px 7px;border-bottom:1px solid #edf1f4}.admin-table td{padding:12px 7px;border-bottom:1px solid #f0f3f5;color:#526174;font-size:12px}.admin-table td strong{display:block;color:#26374b;font-size:12px}.admin-table td small{display:block;color:#a0adba;font-size:10px;margin-top:3px}.admin-total{font-weight:800;color:#16836c!important}.admin-status{background:#e7f7ee;color:#16834e;border-radius:15px;padding:5px 8px;font-size:10px;font-weight:800}.admin-empty{text-align:center!important;color:#9aa8b5!important;padding:30px!important}.stock-list{display:grid;gap:7px}.stock-item{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f0f3f5;gap:8px}.stock-item:last-child{border-bottom:0}.stock-product{display:flex;align-items:center;gap:9px;min-width:0}.stock-avatar{width:31px;height:31px;background:#eaf6f2;color:#16836c;border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:12px}.stock-product strong,.stock-product small{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px}.stock-product strong{font-size:12px;color:#26374b}.stock-product small{font-size:10px;color:#9aa8b5;margin-top:2px}.stock-warning,.stock-empty{font-size:10px;font-weight:800;border-radius:12px;padding:5px 8px;background:#fff3e6;color:#d87826;white-space:nowrap}.stock-empty{background:#fff0f0;color:#d13f4d}@media(max-width:1000px){.admin-stat-grid{grid-template-columns:repeat(2,1fr)}.admin-content-grid{grid-template-columns:1fr}}@media(max-width:600px){.admin-heading{display:block}.admin-date{margin-top:8px}.admin-dashboard{padding:18px 14px 35px}.admin-welcome{display:block;padding:22px}.admin-welcome-user{align-items:flex-start}.admin-button{margin-top:18px}.admin-stat-grid{grid-template-columns:1fr}.admin-panel{padding:15px}}
    </style>
</x-app-layout>
