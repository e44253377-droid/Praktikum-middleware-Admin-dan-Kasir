<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><title>Struk {{ $transaction->transaction_number }}</title>
<style>
body{font:12px/1.4 Arial,sans-serif;margin:0;color:#111}.receipt{width:72mm;margin:0 auto;padding:8px}.center{text-align:center}.store{font-size:16px;font-weight:700;margin:0}.muted{color:#555}.line{border-top:1px dashed #222;margin:7px 0}.meta{display:flex;justify-content:space-between}.items{width:100%;border-collapse:collapse}.items td{padding:3px 0}.items td:last-child{text-align:right}.total{font-weight:700;font-size:14px}.receipt-actions{display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin-top:16px}.print-button,.back-button{display:inline-block;padding:8px 16px;border:0;background:#16836c;color:#fff;border-radius:4px;text-decoration:none;font:12px Arial;cursor:pointer}.back-button.secondary{background:#64748b}@media print{.receipt-actions{display:none}.receipt{width:58mm;padding:0}@page{margin:3mm}}
</style></head>
<body onload="window.print()"><div class="receipt">
    <div class="center"><p class="store">CAFFE KITA</p><div class="muted">Jl. Contoh Raya No. 10</div></div>
    <div class="line"><div class="meta"><span>{{ $transaction->transaction_number }}</span><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div><div>Kasir: {{ $transaction->cashier?->name ?? '-' }}</div></div>
    <table class="items">@foreach($transaction->details as $detail)<tr><td colspan="2">{{ $detail->product_name }}</td></tr><tr><td>{{ $detail->qty }} x {{ number_format($detail->price,0,',','.') }}</td><td>Rp {{ number_format($detail->subtotal,0,',','.') }}</td></tr>@endforeach</table>
    <div class="line"><div class="meta"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal,0,',','.') }}</span></div><div class="meta"><span>Diskon</span><span>Rp {{ number_format($transaction->discount,0,',','.') }}</span></div><div class="meta"><span>Pajak</span><span>Rp {{ number_format($transaction->tax,0,',','.') }}</span></div><div class="meta"><span>Biaya lain</span><span>Rp {{ number_format($transaction->other_fee,0,',','.') }}</span></div><div class="meta total"><span>TOTAL</span><span>Rp {{ number_format($transaction->grand_total,0,',','.') }}</span></div><div class="meta"><span>Bayar ({{ $transaction->payment_method }})</span><span>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</span></div><div class="meta"><span>Kembalian</span><span>Rp {{ number_format($transaction->change_amount,0,',','.') }}</span></div></div>
    <p class="center muted">Terima kasih atas kunjungan Anda.</p>
    <div class="receipt-actions">
        <button class="print-button" onclick="window.print()">Cetak Lagi</button>
        <a class="back-button" href="{{ route('orders.invoice', $transaction) }}">Unduh Invoice</a>
        <a class="back-button" href="{{ route('transactions.index') }}">Kembali ke Kasir</a>
    </div>
</div></body></html>
