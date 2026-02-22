<?php

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletService
{
    public function __construct(
        private readonly WalletRepositoryInterface $walletRepository,
    ) {}

    public function getWallet(User $user): Wallet {
        $v_Wallet = $this->walletRepository->findByUserId($user->id);

        return $v_Wallet;
    }
}