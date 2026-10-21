<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        $today = now()->startOfDay();

        return view('dashboard', [
            'todayRevenue' => (float) Transaction::whereBetween('created_at', [$today, now()])->sum('grand_total'),
            'todayTransactionCount' => Transaction::whereBetween('created_at', [$today, now()])->count(),
            'productCount' => Product::where('is_active', true)->count(),
            'lowStockCount' => Product::where('is_active', true)->where('stock', '<=', 5)->count(),
            'recentTransactions' => Transaction::with('cashier')->latest()->take(5)->get(),
            'lowStockProducts' => Product::where('is_active', true)->where('stock', '<=', 5)->orderBy('stock')->orderBy('name')->limit(5)->get(),
        ]);
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('admin.dashboard');

    Route::resource('products', ProductController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::post('/transactions/hold', [TransactionController::class, 'hold'])->name('transactions.hold');
    Route::get('/transactions/held', [TransactionController::class, 'held'])->name('transactions.held');
    Route::post('/transactions/{heldTransaction}/resume', [TransactionController::class, 'resume'])->name('transactions.resume');
    Route::delete('/transactions/{heldTransaction}/cancel-held', [TransactionController::class, 'cancel'])
        ->name('transactions.cancel-held');
});

require __DIR__.'/auth.php';