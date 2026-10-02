<?php

namespace App\Providers;

use App\Models\HeldTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\HeldTransactionPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-products', fn (User $user): bool => $user->isAdmin());
        Gate::define('view-sales-report', fn (User $user): bool => $user->isAdmin());
        Gate::policy(HeldTransaction::class, HeldTransactionPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
    }
}
