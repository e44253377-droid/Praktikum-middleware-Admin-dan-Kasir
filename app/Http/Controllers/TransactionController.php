<?php

namespace App\Http\Controllers;

use App\Models\HeldTransaction;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('transactions.pos', [
            'products' => $products,
            'posProducts' => $products->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->pos_code,
                    'barcode' => $product->barcode,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'price' => (float) $product->price,
                    'stock' => $product->stock,
                ];
            })->values()->all(),
            'heldTransactions' => HeldTransaction::latest()->get(),
            'transactionNumber' => $this->nextTransactionNumber(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'discount' => ['required', 'numeric', 'min:0'],
            'tax' => ['required', 'numeric', 'min:0'],
            'other_fee' => ['required', 'numeric', 'min:0'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:Tunai,QRIS,Debit,Kredit,E-Wallet,Transfer'],
            'customer_type' => ['required', 'in:Umum,Member,Member VIP'],
            'member_number' => ['nullable', 'string', 'max:50'],
        ]);

        if ((float) $data['paid_amount'] < (float) $data['grand_total']) {
            throw ValidationException::withMessages(['paid_amount' => 'Uang dibayar kurang dari total akhir.']);
        }

        $transaction = DB::transaction(function () use ($data, $request) {
            $items = [];
            $calculatedSubtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail();
                if ($product->stock < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi (tersedia {$product->stock}).",
                    ]);
                }

                $price = (float) $product->price;
                $lineSubtotal = $price * $item['qty'];
                $calculatedSubtotal += $lineSubtotal;
                $items[] = compact('product', 'price', 'lineSubtotal') + ['qty' => $item['qty']];
            }

            $number = $this->nextTransactionNumber();
            $transaction = Transaction::create([
                'kode_transaksi' => $number,
                'transaction_number' => $number,
                'user_id' => Auth::id(),
                'customer_type' => $data['customer_type'],
                'member_number' => $data['member_number'] ?? null,
                'nama_pembeli' => $data['customer_type'],
                'subtotal' => $calculatedSubtotal,
                'discount' => $data['discount'],
                'tax' => $data['tax'],
                'other_fee' => $data['other_fee'],
                'grand_total' => $data['grand_total'],
                'paid_amount' => $data['paid_amount'],
                'change_amount' => $data['paid_amount'] - $data['grand_total'],
                'total_harga' => $data['grand_total'],
                'metode_bayar' => $data['payment_method'],
                'payment_method' => $data['payment_method'],
                'status' => 'lunas',
            ]);

            foreach ($items as $item) {
                $product = $item['product'];
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_code' => $product->pos_code,
                    'product_name' => $product->name,
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'discount' => 0,
                    'subtotal' => $item['lineSubtotal'],
                    'harga' => $item['price'],
                ]);
                $product->decrement('stock', $item['qty']);
            }

            return $transaction;
        });

        return response()->json([
            'message' => 'Transaksi berhasil disimpan.',
            'receipt_url' => route('transactions.receipt', $transaction),
        ]);
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['details', 'cashier']);
        return view('transactions.receipt', compact('transaction'));
    }

    public function hold(Request $request)
    {
        $data = $request->validate([
            'payload' => ['required', 'array'],
            'label' => ['nullable', 'string', 'max:100'],
        ]);

        HeldTransaction::create([
            'user_id' => Auth::id(),
            'label' => $data['label'] ?: 'Transaksi ' . now()->format('H:i'),
            'payload' => $data['payload'],
        ]);

        return response()->json(['message' => 'Transaksi ditahan.']);
    }

    public function held()
    {
        return response()->json(HeldTransaction::latest()->get());
    }

    public function resume(HeldTransaction $heldTransaction)
    {
        $payload = $heldTransaction->payload;
        $heldTransaction->delete();
        return response()->json(['payload' => $payload]);
    }

    public function cancel(HeldTransaction $heldTransaction)
    {
        $heldTransaction->delete();
        return response()->json(['message' => 'Transaksi ditahan dihapus.']);
    }

    private function nextTransactionNumber(): string
    {
        $prefix = 'TRX-' . now()->format('Ymd') . '-';
        $last = Transaction::where('transaction_number', 'like', $prefix . '%')
            ->orderByDesc('transaction_number')->value('transaction_number');
        $sequence = $last ? ((int) substr($last, -3)) + 1 : 1;
        return $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
