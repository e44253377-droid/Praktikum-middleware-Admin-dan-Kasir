<?php

namespace App\Policies;

use App\Models\HeldTransaction;
use App\Models\User;

class HeldTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCashier();
    }

    public function view(User $user, HeldTransaction $heldTransaction): bool
    {
        return $user->isCashier() && $user->id === $heldTransaction->user_id;
    }

    public function update(User $user, HeldTransaction $heldTransaction): bool
    {
        return $this->view($user, $heldTransaction);
    }

    public function delete(User $user, HeldTransaction $heldTransaction): bool
    {
        return $this->view($user, $heldTransaction);
    }
}
