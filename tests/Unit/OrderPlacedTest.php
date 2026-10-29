<?php

namespace Tests\Unit;

use App\Events\OrderPlaced;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase as ApplicationTestCase;

class OrderPlacedTest extends ApplicationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');

        Schema::create('transactions', function ($table): void {
            $table->id();
            $table->string('kode_transaksi');
            $table->string('transaction_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('grand_total')->default(0);
            $table->integer('total_harga')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('metode_bayar')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function test_broadcast_contains_transaction_and_dashboard_totals(): void
    {
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-TEST-001',
            'transaction_number' => 'TRX-TEST-001',
            'grand_total' => 25000,
            'total_harga' => 25000,
            'payment_method' => 'Tunai',
            'metode_bayar' => 'Tunai',
            'status' => 'lunas',
        ]);
        $transaction->setRelation('cashier', null);

        $event = new OrderPlaced($transaction);
        $payload = $event->broadcastWith();

        $this->assertInstanceOf(ShouldBroadcast::class, $event);
        $this->assertSame('order.placed', $event->broadcastAs());
        $this->assertSame('private-admin.orders', $event->broadcastOn()[0]->name);
        $this->assertSame('TRX-TEST-001', $payload['transaction']['number']);
        $this->assertSame('Tunai', $payload['transaction']['payment_method']);
        $this->assertSame(25000.0, $payload['today_revenue']);
        $this->assertSame(1, $payload['today_transaction_count']);
    }

    public function test_only_admins_can_subscribe_to_order_updates(): void
    {
        $authorize = Broadcast::getChannels()['admin.orders'];

        $this->assertTrue($authorize(new User(['role' => 'admin'])));
        $this->assertFalse($authorize(new User(['role' => 'cashier'])));
    }
}
