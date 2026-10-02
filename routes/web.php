<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::get('/dashboard', function () {
    if (auth()->user()->isCashier()) {
        return redirect()->route('transactions.index');
    }

    Gate::authorize('view-sales-report');

    $today = now()->startOfDay();

    return view('dashboard', [
        'productCount' => Product::where('is_active', true)->count(),
        'lowStockCount' => Product::where('is_active', true)->where('stock', '<=', 5)->count(),
        'todayTransactionCount' => Transaction::where('created_at', '>=', $today)->count(),
        'todayRevenue' => Transaction::where('created_at', '>=', $today)
            ->selectRaw('COALESCE(SUM(CASE WHEN grand_total > 0 THEN grand_total ELSE total_harga END), 0) as total')
            ->value('total') ?? 0,
        'recentTransactions' => Transaction::with('cashier')->latest()->limit(6)->get(),
        'lowStockProducts' => Product::where('is_active', true)->where('stock', '<=', 5)
            ->orderBy('stock')->limit(5)->get(),
    ]);
})->middleware(['auth', 'role:admin'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('products', ProductController::class)
    ->middleware(['auth', 'admin']);

Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/kasir', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/kasir', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/kasir/{transaction}/receipt', [TransactionController::class, 'receipt'])
        ->middleware('can:view,transaction')
        ->name('transactions.receipt');
    Route::post('/kasir/hold', [TransactionController::class, 'hold'])->name('transactions.hold');
    Route::get('/kasir/held/list', [TransactionController::class, 'held'])->name('transactions.held');
    Route::post('/kasir/held/{heldTransaction}/resume', [TransactionController::class, 'resume'])->name('transactions.resume');
    Route::delete('/kasir/held/{heldTransaction}', [TransactionController::class, 'cancel'])->name('transactions.cancel-held');
});

require __DIR__.'/auth.php';
