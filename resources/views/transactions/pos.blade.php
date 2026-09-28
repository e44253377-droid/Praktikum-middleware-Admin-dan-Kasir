<x-app-layout>
    <div class="pos-shell">
        <header class="pos-header">
            <div>
                <div class="cafe-brand"><span class="cafe-mark">☕</span><span>CAFFE KITA</span></div>
                <span class="eyebrow">POINT OF SALE &middot; DAILY BREW</span>
                <h1>Racik pesanan terbaik hari ini.</h1>
                <p>Jl. Contoh Raya No. 10 &middot; Kopi hangat, suasana nyaman.</p>
            </div>
            <div class="pos-header-actions">
                <a href="{{ route('profile.edit') }}" class="cashier-profile" title="Pengaturan akun">
                    @if (auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Foto {{ auth()->user()->name }}">
                    @else
                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                    <span class="cashier-profile-name">{{ auth()->user()->name }}</span>
                </a>
                <a href="{{ route('dashboard') }}" class="back-dashboard">← Kembali</a>
                <div class="transaction-meta">
                    <strong>{{ $transactionNumber }}</strong>
                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                    <span>Kasir bertugas</span>
                </div>
            </div>
        </header>

        <div id="pos-alert" class="pos-alert" hidden></div>
        <main class="pos-grid">
            <section class="pos-left">
                <article class="pos-card search-card">
                    <div class="card-title"><h2>Tambah Barang</h2><span>F2</span></div>
                    <div class="search-wrap">
                        <input id="product-search" type="search" placeholder="Cari nama, kode, atau scan barcode..." autocomplete="off">
                        <span>⌕</span>
                    </div>
                    <div id="search-results" class="search-results"></div>
                    <div id="product-catalog" class="catalog-grid"></div>
                </article>

                <article class="pos-card">
                    <div class="card-title">
                        <h2>Keranjang Belanja <b id="cart-count">0</b></h2>
                        <button type="button" class="text-button" id="clear-cart">Kosongkan</button>
                    </div>
                    <div class="table-scroll">
                        <table class="cart-table">
                            <thead><tr><th>No</th><th>Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
                            <tbody id="cart-body"><tr><td colspan="6" class="empty-cart">Belum ada barang. Cari atau scan barang untuk memulai.</td></tr></tbody>
                        </table>
                    </div>
                </article>

                <article class="pos-card customer-card">
                    <div class="card-title"><h2>Pelanggan</h2></div>
                    <div class="customer-fields">
                        <select id="customer-type">
                            <option>Umum</option><option>Member</option><option>Member VIP</option>
                        </select>
                        <input id="member-number" type="text" placeholder="Nomor member / nomor HP">
                    </div>
                </article>
            </section>

            <aside class="pos-right">
                <article class="pos-card summary-card">
                    <div class="card-title"><h2>Ringkasan Pembayaran</h2></div>
                    <div class="summary-row"><span>Total Item</span><strong id="total-items">0</strong></div>
                    <div class="summary-row"><span>Subtotal</span><strong id="subtotal">Rp 0</strong></div>
                    <label class="input-row"><span>Diskon (%)</span><input id="discount-percent" type="number" min="0" step="0.01" value="0"></label>
                    <label class="input-row"><span>Diskon (Rp)</span><input id="discount-nominal" type="number" min="0" step="1" value="0"></label>
                    <label class="input-row"><span>Pajak / PPN</span><input id="tax" type="number" min="0" step="1" value="0"></label>
                    <label class="input-row"><span>Biaya Lain</span><input id="other-fee" type="number" min="0" step="1" value="0"></label>
                    <div class="grand-total"><span>TOTAL AKHIR</span><strong id="grand-total">Rp 0</strong></div>
                </article>

                <article class="pos-card payment-card">
                    <div class="card-title"><h2>Pembayaran</h2><span>F4</span></div>
                    <label class="field-label">Metode Pembayaran
                        <select id="payment-method">
                            <option>Tunai</option><option>QRIS</option><option>Debit</option><option>Kredit</option><option>E-Wallet</option><option>Transfer</option>
                        </select>
                    </label>
                    <label class="field-label">Uang Dibayar
                        <input id="paid-amount" type="number" min="0" step="1" placeholder="0">
                    </label>
                    <div class="quick-cash">
                        <button type="button" data-cash="40000">Rp 40.000</button>
                        <button type="button" data-cash="50000">Rp 50.000</button>
                        <button type="button" data-cash="100000">Rp 100.000</button>
                        <button type="button" id="exact-cash">Uang Pas</button>
                    </div>
                    <div id="change-box" class="change-box neutral"><span>Masukkan pembayaran</span><strong>Rp 0</strong></div>
                </article>

                <div class="action-grid">
                    <button type="button" class="action secondary" id="hold-transaction">Tahan</button>
                    <button type="button" class="action danger" id="cancel-transaction">Batal</button>
                    <button type="button" class="action primary" id="pay-transaction">BAYAR &amp; CETAK</button>
                </div>
                <button type="button" class="held-toggle" id="show-held">Lihat transaksi ditahan ({{ $heldTransactions->count() }})</button>
                <div id="held-list" class="held-list" hidden></div>
            </aside>
        </main>
    </div>

    <style>
        .pos-shell{min-height:calc(100vh - 64px);background:#f3f6f9;padding:24px;color:#172033}.pos-header{max-width:1440px;margin:0 auto 18px;display:flex;justify-content:space-between;align-items:center;gap:20px}.pos-header-actions{display:flex;align-items:center;gap:18px}.cashier-profile{display:flex;align-items:center;gap:8px;color:#172033;text-decoration:none;font-size:12px;font-weight:800}.cashier-profile img,.cashier-profile>span:first-child{width:34px;height:34px;border-radius:50%;object-fit:cover}.cashier-profile>span:first-child{display:flex;align-items:center;justify-content:center;background:#dff4ed;color:#16836c;font-size:13px}.cashier-profile-name{max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.cashier-profile:hover .cashier-profile-name{color:#16836c}.back-dashboard{border:1px solid #16836c;color:#16836c;background:#fff;border-radius:8px;padding:9px 12px;font-size:12px;font-weight:800;white-space:nowrap;transition:.15s}.back-dashboard:hover{background:#eaf7f3}.eyebrow{font-size:11px;letter-spacing:.16em;color:#16836c;font-weight:800}.pos-header h1{font-size:26px;font-weight:900;margin:3px 0;color:#12243c}.pos-header p{font-size:13px;color:#718096}.transaction-meta{text-align:right;display:grid;gap:3px;color:#718096;font-size:12px}.transaction-meta strong{font-size:18px;color:#16836c}.pos-grid{max-width:1440px;margin:auto;display:grid;grid-template-columns:minmax(0,1.65fr) minmax(320px,.75fr);gap:18px}.pos-left,.pos-right{display:grid;gap:18px;align-content:start}.pos-card{background:#fff;border:1px solid #e4eaf0;border-radius:16px;padding:18px;box-shadow:0 8px 24px #233b5310}.card-title{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}.card-title h2{font-size:16px;font-weight:800;margin:0}.card-title h2 b{background:#e8f5f1;color:#16836c;border-radius:20px;padding:2px 8px;font-size:11px;margin-left:5px}.card-title>span{background:#f0f4f8;color:#65758b;border-radius:6px;padding:3px 7px;font-size:11px;font-weight:800}.search-wrap{position:relative}.search-wrap input,.customer-fields input,.customer-fields select,.field-label input,.field-label select,.input-row input{width:100%;border:1px solid #dbe4ec;border-radius:9px;padding:10px 12px;font-size:13px;background:#fbfcfd}.search-wrap input{padding-right:38px}.search-wrap span{position:absolute;right:13px;top:8px;font-size:20px;color:#16836c}.search-results{display:grid;gap:7px;margin-top:10px;max-height:175px;overflow:auto}.result-item{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border:1px solid #e7edf2;border-radius:9px;cursor:pointer;transition:.15s}.result-item:hover{border-color:#16836c;background:#f0faf7}.result-item strong{font-size:13px}.result-item small{display:block;color:#8a98a8;font-size:11px;margin-top:2px}.result-price{color:#16836c;font-weight:800;font-size:13px}.search-image{width:38px;height:38px;object-fit:cover;border-radius:6px;margin-right:8px;flex:0 0 auto}.search-placeholder{width:38px;height:38px;display:flex;align-items:center;justify-content:center;background:#eaf6f2;border-radius:6px;margin-right:8px;flex:0 0 auto}.table-scroll{overflow:auto}.cart-table{width:100%;border-collapse:collapse;min-width:630px}.cart-table th{text-align:left;font-size:11px;text-transform:uppercase;color:#8b99a8;letter-spacing:.04em;padding:8px;border-bottom:1px solid #e8edf2}.cart-table td{padding:12px 8px;border-bottom:1px solid #f0f3f5;font-size:13px}.cart-table td:nth-child(1){color:#9ba8b5;width:28px}.product-name{font-weight:700}.product-code{font-size:11px;color:#91a0ad}.qty-control{display:flex;align-items:center;gap:5px}.qty-control button{border:0;background:#eaf6f2;color:#16836c;width:24px;height:24px;border-radius:6px;font-weight:800;cursor:pointer}.qty-control input{width:42px;text-align:center;border:1px solid #dbe4ec;border-radius:5px;padding:4px}.remove-item{border:0;background:#fff0f0;color:#d94d57;border-radius:6px;width:27px;height:27px;cursor:pointer}.empty-cart{text-align:center;color:#9aa8b5;padding:34px!important}.text-button{background:transparent;border:0;color:#d94d57;font-size:12px;cursor:pointer}.customer-fields{display:grid;grid-template-columns:1fr 1fr;gap:10px}.summary-row,.input-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;color:#64758a;font-size:13px}.summary-row strong{color:#24354d}.input-row input{width:145px;text-align:right;padding:7px}.grand-total{border-top:2px solid #e9eff3;margin-top:10px;padding-top:14px;display:flex;justify-content:space-between;align-items:center;color:#16836c;font-size:13px;font-weight:800}.grand-total strong{font-size:23px}.field-label{display:grid;gap:6px;font-size:12px;color:#63748a;font-weight:700;margin-bottom:11px}.field-label input{font-size:18px;font-weight:800;color:#172033}.quick-cash{display:flex;flex-wrap:wrap;gap:6px;margin:4px 0 13px}.quick-cash button{border:1px solid #cfe7df;background:#f5fbf9;color:#16836c;border-radius:7px;padding:7px 9px;font-size:11px;cursor:pointer}.change-box{border-radius:10px;padding:12px;display:flex;justify-content:space-between;align-items:center;font-size:12px}.change-box strong{font-size:18px}.change-box.neutral{background:#f2f5f7;color:#738297}.change-box.success{background:#e8f7ef;color:#16834e}.change-box.error{background:#fff0f0;color:#d13f4d}.action-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px}.action{border:0;border-radius:9px;padding:12px;font-weight:800;cursor:pointer;transition:.15s}.action:hover{transform:translateY(-1px)}.action.primary{grid-column:1/-1;background:#16836c;color:#fff;box-shadow:0 5px 12px #16836c33}.action.secondary{background:#e9f2f8;color:#3d6681}.action.danger{background:#fff0f0;color:#d13f4d}.held-toggle{background:none;border:0;color:#16836c;font-size:12px;cursor:pointer;text-align:right}.held-list{display:grid;gap:7px}.held-item{display:flex;justify-content:space-between;align-items:center;border:1px solid #e5edf0;border-radius:8px;padding:9px;font-size:12px}.held-item button{border:0;background:#e8f7f2;color:#16836c;border-radius:5px;padding:5px 8px;cursor:pointer}.pos-alert{max-width:1440px;margin:0 auto 12px;padding:11px 14px;border-radius:8px;background:#fff0f0;color:#b73543;font-size:13px}.pos-alert.ok{background:#e9f8f0;color:#16754d}@media(max-width:900px){.pos-grid{grid-template-columns:1fr}.pos-header{align-items:flex-start}.pos-header-actions{align-items:flex-end;flex-direction:column-reverse;gap:8px}.transaction-meta{font-size:11px}.pos-shell{padding:14px}}@media(max-width:550px){.pos-header{display:block}.pos-header-actions{align-items:flex-start;margin-top:10px}.transaction-meta{text-align:left;margin-top:0}.customer-fields{grid-template-columns:1fr}}
        .catalog-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:10px;margin-top:10px}.catalog-item{border:1px solid #eadfd2;background:#fffaf4;border-radius:13px;padding:10px;text-align:left;cursor:pointer;transition:.2s;box-shadow:0 5px 15px #4b2d1b0d}.catalog-item:hover{border-color:#b8794c;background:#fff5e9;transform:translateY(-2px);box-shadow:0 9px 20px #4b2d1b1c}.catalog-item:disabled{cursor:not-allowed;opacity:.65}.catalog-image{width:100%;height:105px;object-fit:cover;border-radius:9px;background:#f0dfc8;margin-bottom:8px}.catalog-placeholder{display:flex;align-items:center;justify-content:center;font-size:28px}.catalog-item strong,.catalog-item small{display:block}.catalog-item strong{font-size:12px;color:#35251d}.catalog-item small{color:#9a8170;font-size:10px;margin-top:3px}.catalog-item span{display:block;color:#a55f35;font-size:12px;font-weight:800;margin-top:7px}
        .pos-shell{background:#f7f1e9;color:#35251d;padding:28px}.pos-header{max-width:1440px;margin-bottom:22px}.cafe-brand{display:flex;align-items:center;gap:8px;color:#a55f35;font-size:12px;letter-spacing:.16em;font-weight:900;margin-bottom:7px}.cafe-mark{display:inline-flex;align-items:center;justify-content:center;width:25px;height:25px;border-radius:50%;background:#35251d;color:#f7d7a8;font-size:14px;box-shadow:0 4px 10px #35251d2b}.eyebrow{color:#b8794c;font-size:10px}.pos-header h1{font-family:Georgia,serif;color:#35251d;font-size:30px;letter-spacing:-.02em}.pos-header p{color:#907968}.pos-card{border:1px solid #eadfd2;border-radius:18px;box-shadow:0 10px 28px #4b2d1b0d}.card-title h2{color:#35251d}.card-title h2 b{background:#f4e2cf;color:#a55f35}.card-title>span{background:#f4eadf;color:#8e725e}.search-wrap input,.customer-fields input,.customer-fields select,.field-label input,.field-label select,.input-row input{border-color:#eadfd2;background:#fffdf9;color:#35251d}.search-wrap input:focus,.customer-fields input:focus,.customer-fields select:focus,.field-label input:focus,.field-label select:focus,.input-row input:focus{border-color:#b8794c;box-shadow:0 0 0 3px #b8794c1c;outline:0}.search-wrap span{color:#a55f35}.search-results .result-item:hover{border-color:#b8794c;background:#fff5e9}.result-price{color:#a55f35}.search-placeholder{background:#f4e2cf}.back-dashboard{border-color:#b8794c;color:#8e4f2b;background:#fffaf4}.back-dashboard:hover{background:#f9ead9}.cashier-profile{color:#5d4638}.cashier-profile>span:first-child{background:#f4e2cf;color:#8e4f2b}.cashier-profile:hover .cashier-profile-name{color:#a55f35}.transaction-meta strong{color:#a55f35}.cart-table th{color:#a58d7d;border-color:#eee3d8}.cart-table td{border-color:#f2e9e0}.qty-control button{background:#f4e2cf;color:#8e4f2b}.remove-item{background:#fff0ed}.customer-fields select,.field-label select{cursor:pointer}.grand-total{border-color:#eadfd2;color:#a55f35}.quick-cash button{border-color:#e6cdb6;background:#fff8ef;color:#9b572f}.change-box.neutral{background:#f3e9df;color:#907968}.action.primary{background:#a55f35;box-shadow:0 6px 14px #a55f3533}.action.primary:hover{background:#8e4f2b}.action.secondary{background:#eee4da;color:#6c5140}.held-toggle{color:#a55f35}
        @media(max-width:900px){.pos-shell{padding:18px}.pos-header h1{font-size:25px}}
        @media(max-width:550px){.pos-shell{padding:12px}.cafe-brand{font-size:11px}.pos-header h1{font-size:23px}.cashier-profile-name{display:none}}
    </style>
    <script>
        const products = @json($posProducts);
        const endpoints = {store: @json(route('transactions.store')), hold: @json(route('transactions.hold')), held: @json(route('transactions.held'))};
        let cart = [];
        const money = value => 'Rp ' + Math.max(0, Number(value) || 0).toLocaleString('id-ID');
        const el = id => document.getElementById(id);
        const showAlert = (message, ok = false) => { const box = el('pos-alert'); box.textContent = message; box.hidden = false; box.className = 'pos-alert' + (ok ? ' ok' : ''); setTimeout(() => box.hidden = true, 4000); };
        const totals = () => {
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
            const percent = subtotal * (Number(el('discount-percent').value) || 0) / 100;
            const nominal = Number(el('discount-nominal').value) || 0;
            const tax = Number(el('tax').value) || 0, fee = Number(el('other-fee').value) || 0;
            const total = Math.max(0, subtotal - percent - nominal + tax + fee);
            return {subtotal, discount: percent + nominal, tax, fee, total};
        };
        function renderCart() {
            const body = el('cart-body');
            el('cart-count').textContent = cart.length;
            el('total-items').textContent = cart.reduce((sum, item) => sum + item.qty, 0);
            if (!cart.length) { body.innerHTML = '<tr><td colspan="6" class="empty-cart">Belum ada barang. Cari atau scan barang untuk memulai.</td></tr>'; }
            else body.innerHTML = cart.map((item, i) => `<tr><td>${i + 1}</td><td><div class="product-name">${item.name}</div><div class="product-code">${item.code}</div></td><td>${money(item.price)}</td><td><div class="qty-control"><button data-action="minus" data-id="${item.id}">−</button><input data-action="qty" data-id="${item.id}" type="number" min="1" value="${item.qty}"><button data-action="plus" data-id="${item.id}">+</button></div></td><td><strong>${money(item.price * item.qty)}</strong></td><td><button class="remove-item" data-action="remove" data-id="${item.id}">×</button></td></tr>`).join('');
            const calc = totals(); el('subtotal').textContent = money(calc.subtotal); el('grand-total').textContent = money(calc.total); updateChange();
        }
        function addProduct(product) { if (!product || product.stock < 1) return showAlert('Produk sedang habis.'); const found = cart.find(item => item.id === product.id); if (found) found.qty = Math.min(found.qty + 1, product.stock); else cart.push({...product, qty: 1}); renderCart(); el('product-search').value = ''; el('search-results').innerHTML = ''; }
        function updateChange() { const total = totals().total, paid = Number(el('paid-amount').value) || 0, box = el('change-box'); if (!paid) { box.className = 'change-box neutral'; box.innerHTML = '<span>Masukkan pembayaran</span><strong>Rp 0</strong>'; } else if (paid < total) { box.className = 'change-box error'; box.innerHTML = `<span>UANG KURANG</span><strong>${money(total - paid)}</strong>`; } else { box.className = 'change-box success'; box.innerHTML = `<span>KEMBALIAN</span><strong>${money(paid - total)}</strong>`; } }
        function productImage(product) {
            return product.image
                ? `<img class="catalog-image" src="${product.image}" alt="${product.name}">`
                : '<div class="catalog-image catalog-placeholder">☕</div>';
        }
        function renderProductCatalog() {
            el('product-catalog').innerHTML = products.map(p => `<button type="button" class="catalog-item" data-product="${p.id}" ${p.stock < 1 ? 'disabled' : ''}>${productImage(p)}<strong>${p.name}</strong><small>${p.code} · Stok ${p.stock}</small><span>${p.stock < 1 ? 'Habis' : money(p.price)}</span></button>`).join('');
        }
        el('product-search').addEventListener('input', event => { const query = event.target.value.toLowerCase().trim(); const results = query ? products.filter(p => [p.name, p.code, p.barcode].some(value => String(value || '').toLowerCase().includes(query))).slice(0, 8) : []; el('product-catalog').hidden = Boolean(query); el('search-results').innerHTML = results.map(p => `<div class="result-item" data-product="${p.id}">${p.image ? `<img class="search-image" src="${p.image}" alt="${p.name}">` : '<span class="search-placeholder">☕</span>'}<div><strong>${p.name}</strong><small>${p.code}${p.barcode ? ' · ' + p.barcode : ''} · Stok ${p.stock}</small></div><span class="result-price">${money(p.price)}</span></div>`).join(''); if (query && !results.length) el('search-results').innerHTML = '<small style="padding:8px;color:#9aa8b5">Barang tidak ditemukan.</small>'; });
        el('search-results').addEventListener('click', event => { const item = event.target.closest('[data-product]'); if (item) addProduct(products.find(p => p.id == item.dataset.product)); });
        el('product-catalog').addEventListener('click', event => { const item = event.target.closest('[data-product]'); if (item) addProduct(products.find(p => p.id == item.dataset.product)); });
        el('cart-body').addEventListener('click', event => { const target = event.target.closest('[data-action]'); if (!target) return; const item = cart.find(i => i.id == target.dataset.id); if (!item) return; if (target.dataset.action === 'plus') item.qty = Math.min(item.qty + 1, item.stock); if (target.dataset.action === 'minus') item.qty = Math.max(1, item.qty - 1); if (target.dataset.action === 'remove') cart = cart.filter(i => i.id != target.dataset.id); renderCart(); });
        el('cart-body').addEventListener('change', event => { if (event.target.dataset.action === 'qty') { const item = cart.find(i => i.id == event.target.dataset.id); item.qty = Math.max(1, Math.min(item.stock, Number(event.target.value) || 1)); renderCart(); } });
        ['discount-percent','discount-nominal','tax','other-fee','paid-amount'].forEach(id => el(id).addEventListener('input', () => { renderCart(); }));
        document.querySelectorAll('[data-cash]').forEach(button => button.addEventListener('click', () => { el('paid-amount').value = button.dataset.cash; updateChange(); }));
        el('exact-cash').addEventListener('click', () => { el('paid-amount').value = totals().total; updateChange(); });
        el('clear-cart').addEventListener('click', () => { cart = []; renderCart(); });
        el('cancel-transaction').addEventListener('click', () => { if (cart.length && !confirm('Batalkan transaksi ini?')) return; cart = []; ['paid-amount','member-number'].forEach(id => el(id).value = ''); renderCart(); });
        async function send(url, options) { const response = await fetch(url, {headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, ...options}); const data = await response.json(); if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Terjadi kesalahan.'); return data; }
        el('hold-transaction').addEventListener('click', async () => { if (!cart.length) return showAlert('Keranjang masih kosong.'); try { await send(endpoints.hold, {method:'POST', body: JSON.stringify({label: 'Transaksi ' + new Date().toLocaleTimeString('id-ID'), payload: {cart, customerType: el('customer-type').value, memberNumber: el('member-number').value, discountPercent: el('discount-percent').value, discountNominal: el('discount-nominal').value, tax: el('tax').value, fee: el('other-fee').value}})}); showAlert('Transaksi berhasil ditahan.', true); el('cancel-transaction').click(); } catch (error) { showAlert(error.message); } });
        el('show-held').addEventListener('click', async () => { const list = el('held-list'); list.hidden = !list.hidden; if (list.hidden) return; try { const held = await (await fetch(endpoints.held)).json(); list.innerHTML = held.length ? held.map(row => `<div class="held-item"><span>${row.label}<small style="display:block;color:#9aa8b5">${new Date(row.created_at).toLocaleString('id-ID')}</small></span><button data-resume="${row.id}">Lanjutkan</button></div>`).join('') : '<small>Belum ada transaksi ditahan.</small>'; } catch (error) { showAlert('Daftar transaksi ditahan gagal dimuat.'); } });
        el('held-list').addEventListener('click', async event => { if (!event.target.dataset.resume) return; const response = await send('/kasir/held/' + event.target.dataset.resume + '/resume', {method:'POST'}); const p = response.payload; cart = p.cart || []; el('customer-type').value = p.customerType || 'Umum'; el('member-number').value = p.memberNumber || ''; el('discount-percent').value = p.discountPercent || 0; el('discount-nominal').value = p.discountNominal || 0; el('tax').value = p.tax || 0; el('other-fee').value = p.fee || 0; renderCart(); el('held-list').hidden = true; });
        el('pay-transaction').addEventListener('click', async () => { const calc = totals(), paid = Number(el('paid-amount').value) || 0; if (!cart.length) return showAlert('Tidak bisa membayar: keranjang kosong.'); if (paid < calc.total) return showAlert('Uang dibayar masih kurang.'); try { const data = await send(endpoints.store, {method:'POST', body: JSON.stringify({items: cart.map(i => ({product_id:i.id, qty:i.qty})), subtotal:calc.subtotal, discount:calc.discount, tax:calc.tax, other_fee:calc.fee, grand_total:calc.total, paid_amount:paid, payment_method:el('payment-method').value, customer_type:el('customer-type').value, member_number:el('member-number').value})}); window.open(data.receipt_url, '_blank'); cart = []; el('paid-amount').value = ''; renderCart(); showAlert('Transaksi berhasil disimpan.', true); } catch (error) { showAlert(error.message); } });
        document.addEventListener('keydown', event => { if (event.key === 'F2') { event.preventDefault(); el('product-search').focus(); } if (event.key === 'F4') { event.preventDefault(); el('paid-amount').focus(); } if (event.key === 'Escape') el('cancel-transaction').click(); });
        renderProductCatalog();
        renderCart();
    </script>
</x-app-layout>
