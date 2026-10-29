<?php

namespace Tests\Feature;

use App\Models\HeldTransaction;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_management_requires_an_admin(): void
    {
        $this->get(route('products.index'))->assertRedirect(route('login'));

        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier)->get(route('products.index'))->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('products.index'))->assertOk();
    }

    public function test_cashier_routes_only_allow_known_roles(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier)->get(route('transactions.index'))->assertOk();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('transactions.index'))->assertForbidden();

        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user)->get(route('transactions.index'))->assertForbidden();
    }

    public function test_cashier_catalog_uses_public_urls_for_menu_images_and_storage_urls_for_uploads(): void
    {
        Product::create([
            'name' => 'Espresso',
            'category' => 'Kopi',
            'price' => 18000,
            'stock' => 10,
            'image' => 'images/cafe-menu/espresso.jpg',
            'is_active' => true,
        ]);
        Product::create([
            'name' => 'Bakso',
            'category' => 'Makanan',
            'price' => 15000,
            'stock' => 10,
            'image' => 'products/bakso.jpg',
            'is_active' => true,
        ]);
        Product::create([
            'name' => 'Menu tanpa foto',
            'category' => 'Snack',
            'price' => 10000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs(User::factory()->create(['role' => 'cashier']))
            ->get(route('transactions.index'));

        $response->assertOk()
            ->assertSee('__HELD_TRANSACTION_ID__')
            ->assertDontSee('/kasir/held/')
            ->assertViewHas('posProducts', function (array $products): bool {
                $productsByName = collect($products)->keyBy('name');

                return $productsByName['Espresso']['image'] === asset('images/cafe-menu/espresso.jpg')
                    && $productsByName['Bakso']['image'] === asset('storage/products/bakso.jpg')
                    && $productsByName['Menu tanpa foto']['image'] === null;
            });
    }

    public function test_only_admins_can_access_the_sales_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('dashboard'))->assertOk();

        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier)
            ->get(route('dashboard'))
            ->assertForbidden();

        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user)->get(route('dashboard'))->assertForbidden();

        $unverifiedAdmin = User::factory()->unverified()->create(['role' => 'admin']);
        $this->actingAs($unverifiedAdmin)->get(route('dashboard'))->assertOk();
    }

    public function test_admin_dashboard_alias_redirects_to_the_existing_dashboard_and_remains_admin_only(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard'));

        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_cashiers_can_only_view_their_own_receipts(): void
    {
        $owner = User::factory()->create(['role' => 'cashier']);
        $otherCashier = User::factory()->create(['role' => 'cashier']);
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-001',
            'transaction_number' => 'TRX-001',
            'user_id' => $owner->id,
            'total_harga' => 100,
            'metode_bayar' => 'Tunai',
        ]);

        $this->actingAs($owner)
            ->get(route('transactions.receipt', $transaction))
            ->assertOk();

        $this->actingAs($otherCashier)
            ->get(route('transactions.receipt', $transaction))
            ->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('transactions.receipt', $transaction))
            ->assertForbidden();
    }

    public function test_cashiers_can_only_download_their_own_invoices(): void
    {
        $owner = User::factory()->create(['role' => 'cashier']);
        $otherCashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::create([
            'name' => 'Kopi Susu',
            'category' => 'Kopi',
            'price' => 18000,
            'stock' => 10,
        ]);
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-INV-001',
            'transaction_number' => 'TRX-INV-001',
            'user_id' => $owner->id,
            'customer_type' => 'Umum',
            'subtotal' => 18000,
            'grand_total' => 18000,
            'paid_amount' => 20000,
            'change_amount' => 2000,
            'total_harga' => 18000,
            'metode_bayar' => 'Tunai',
            'payment_method' => 'Tunai',
        ]);
        $transaction->details()->create([
            'product_id' => $product->id,
            'product_code' => 'KOPI-001',
            'product_name' => 'Kopi Susu',
            'price' => 18000,
            'qty' => 1,
            'discount' => 0,
            'subtotal' => 18000,
            'harga' => 18000,
        ]);

        $response = $this->actingAs($owner)
            ->get(route('orders.invoice', $transaction));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());

        $this->actingAs($otherCashier)
            ->get(route('orders.invoice', $transaction))
            ->assertForbidden();
    }

    public function test_cashiers_can_only_list_and_resume_their_own_held_transactions(): void
    {
        $owner = User::factory()->create(['role' => 'cashier']);
        $otherCashier = User::factory()->create(['role' => 'cashier']);
        $ownedHeldTransaction = HeldTransaction::create([
            'user_id' => $owner->id,
            'label' => 'Milik sendiri',
            'payload' => ['items' => []],
        ]);
        $otherHeldTransaction = HeldTransaction::create([
            'user_id' => $otherCashier->id,
            'label' => 'Milik kasir lain',
            'payload' => ['items' => []],
        ]);

        $this->actingAs($owner)
            ->get(route('transactions.held'))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['label' => 'Milik sendiri'])
            ->assertJsonMissing(['label' => 'Milik kasir lain']);

        $this->actingAs($owner)
            ->post(route('transactions.resume', $otherHeldTransaction))
            ->assertForbidden();
        $this->actingAs($owner)
            ->delete(route('transactions.cancel-held', $otherHeldTransaction))
            ->assertForbidden();
        $this->assertDatabaseHas('held_transactions', ['id' => $otherHeldTransaction->id]);

        $this->actingAs($owner)
            ->post(route('transactions.resume', $ownedHeldTransaction))
            ->assertOk()
            ->assertJson(['payload' => ['items' => []]]);
        $this->assertDatabaseMissing('held_transactions', ['id' => $ownedHeldTransaction->id]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('transactions.held'))
            ->assertForbidden();
    }
}
