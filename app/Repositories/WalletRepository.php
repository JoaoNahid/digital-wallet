<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{

    public function createForUser(User $user): Wallet {
        return Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
    }

    public function findByUserId(int $userId): ?Wallet {
        return Wallet::where('user_id', $userId)->first();
    }
}