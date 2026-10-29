<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Transaction $transaction) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.orders')];
    }

    public function broadcastAs(): string
    {
        return 'order.placed';
    }

    public function broadcastWith(): array
    {
        $today = now()->startOfDay();

        return [
            'transaction' => [
                'id' => $this->transaction->id,
                'number' => $this->transaction->transaction_number ?: $this->transaction->kode_transaksi,
                'cashier' => $this->transaction->cashier?->name ?? '-',
                'payment_method' => $this->transaction->payment_method ?: $this->transaction->metode_bayar,
                'total' => (float) ($this->transaction->grand_total ?: $this->transaction->total_harga),
                'status' => $this->transaction->status,
                'created_at' => $this->transaction->created_at->toIso8601String(),
            ],
            'today_revenue' => (float) Transaction::whereBetween('created_at', [$today, now()])->sum('grand_total'),
            'today_transaction_count' => Transaction::whereBetween('created_at', [$today, now()])->count(),
        ];
    }
}
