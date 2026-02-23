<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function view(User $p_User, Transaction $p_Transaction): bool {
        return $p_Transaction->wallet->user_id === $p_User->id
            || $p_Transaction->targetWallet?->user_id === $p_User->id;
    }

    public function reverse(User $p_User, Transaction $p_Transaction): bool {
        return $p_Transaction->wallet->user_id === $p_User->id
            && $p_Transaction->can_be_reversed;
    }
}