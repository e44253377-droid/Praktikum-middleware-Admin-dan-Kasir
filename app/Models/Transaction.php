<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'kode_transaksi', 'transaction_number', 'user_id', 'customer_id',
        'customer_type', 'member_number', 'nama_pembeli', 'subtotal',
        'discount', 'tax', 'other_fee', 'grand_total', 'paid_amount',
        'change_amount', 'total_harga', 'metode_bayar', 'payment_method', 'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2', 'discount' => 'decimal:2', 'tax' => 'decimal:2',
        'other_fee' => 'decimal:2', 'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2', 'change_amount' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
