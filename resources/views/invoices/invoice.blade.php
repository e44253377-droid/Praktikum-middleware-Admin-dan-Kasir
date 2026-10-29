<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->transaction_number }}</title>
    <style>
        @page { margin: 32px 38px; }
        body { color: #35251d; font: 12px Arial, sans-serif; }
        .brand { border-bottom: 3px solid #16836c; padding-bottom: 14px; }
        .brand-name { color: #16836c; font-size: 24px; font-weight: bold; margin: 0; }
        .brand-note { color: #7b6a60; margin: 5px 0 0; }
        .invoice-title { color: #35251d; font-size: 22px; margin: 20px 0 8px; }
        .meta { width: 100%; margin-bottom: 22px; }
        .meta td { padding: 3px 0; vertical-align: top; }
        .meta-label { color: #7b6a60; width: 110px; }
        .table { border-collapse: collapse; width: 100%; }
        .table th { background: #f3eee8; color: #543c30; text-align: left; }
        .table th, .table td { border-bottom: 1px solid #e6ddd5; padding: 9px 8px; }
        .number { text-align: right; white-space: nowrap; }
        .summary { margin: 16px 0 0 auto; width: 52%; }
        .summary td { padding: 5px 4px; }
        .summary .label { color: #7b6a60; }
        .summary .amount { text-align: right; }
        .summary .grand-total td { border-top: 2px solid #16836c; color: #16836c; font-size: 15px; font-weight: bold; padding-top: 9px; }
        .thanks { border-top: 1px solid #e6ddd5; color: #7b6a60; margin-top: 28px; padding-top: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="brand">
        <p class="brand-name">CAFFE KITA</p>
        <p class="brand-note">Terima kasih telah berbelanja bersama kami.</p>
    </div>

    <h1 class="invoice-title">INVOICE</h1>
    <table class="meta">
        <tr>
            <td class="meta-label">Nomor transaksi</td>
            <td>{{ $invoice->transaction_number ?: $invoice->kode_transaksi }}</td>
            <td class="meta-label">Tanggal</td>
            <td>{{ $invoice->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Kasir</td>
            <td>{{ $invoice->cashier?->name ?? '-' }}</td>
            <td class="meta-label">Pelanggan</td>
            <td>{{ $invoice->customer_type ?: $invoice->nama_pembeli }}</td>
        </tr>
        @if ($invoice->member_number)
            <tr>
                <td class="meta-label">Nomor member</td>
                <td colspan="3">{{ $invoice->member_number }}</td>
            </tr>
        @endif
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="number">Harga</th>
                <th class="number">Jumlah</th>
                <th class="number">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->details as $item)
                @php
                    $quantity = (int) $item->qty;
                    $price = (float) ($item->price ?: $item->harga);
                    $lineTotal = (float) ($item->subtotal ?: $price * $quantity);
                @endphp
                <tr>
                    <td>{{ $item->product_name ?: 'Produk' }}</td>
                    <td class="number">Rp {{ number_format($price, 0, ',', '.') }}</td>
                    <td class="number">{{ $quantity }}</td>
                    <td class="number">Rp {{ number_format($lineTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr><td class="label">Subtotal</td><td class="amount">Rp {{ number_format($invoice->subtotal ?: $invoice->total_harga, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Diskon</td><td class="amount">Rp {{ number_format($invoice->discount, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Pajak</td><td class="amount">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Biaya lain</td><td class="amount">Rp {{ number_format($invoice->other_fee, 0, ',', '.') }}</td></tr>
        <tr class="grand-total"><td>Total</td><td class="amount">Rp {{ number_format($invoice->grand_total ?: $invoice->total_harga, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Metode bayar</td><td class="amount">{{ $invoice->payment_method ?: $invoice->metode_bayar }}</td></tr>
        <tr><td class="label">Dibayar</td><td class="amount">Rp {{ number_format($invoice->paid_amount ?: $invoice->total_harga, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Kembalian</td><td class="amount">Rp {{ number_format($invoice->change_amount, 0, ',', '.') }}</td></tr>
    </table>

    <p class="thanks">Invoice ini diterbitkan oleh CAFFE KITA. Simpan sebagai bukti transaksi.</p>
</body>
</html>