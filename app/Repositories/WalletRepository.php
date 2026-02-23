<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{

    public function createForUser(User $p_User): Wallet {
        return Wallet::create([
            'user_id' => $p_User->id,
            'balance' => 0,
        ]);
    }

    public function findByUserId(int $p_UserId): ?Wallet {
        return Wallet::where('user_id', $p_UserId)->first();
    }

    public function findById(string $p_Id): ?Wallet {
        return Wallet::find($p_Id);
    }
    
    public function findByIdWithLock(string $p_Id): ?Wallet {
        return Wallet::lockForUpdate()->find($p_Id);
    }

    public function findByUserEmail(string $p_Email): ?Wallet {
        return Wallet::whereHas('user', fn ($c_Query) => $c_Query->where('email', $p_Email))->first();
    }

    public function findByTransactionId(string $id): ?Wallet {
        return Wallet::whereHas('transactions', fn ($c_Query) => $c_Query->where('id', $id))->first();
    }

    public function updateBalance(Wallet $p_Wallet, float $p_Amount, string $p_Operation): void {
        match ($p_Operation) {
            'credit' => $p_Wallet->credit($p_Amount),
            'debit' => $p_Wallet->debit($p_Amount),
        };
    }
}