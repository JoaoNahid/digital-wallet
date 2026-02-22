<?php

namespace App\Actions\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class CreateWalletAction
{
    public function __construct(
        private readonly WalletRepositoryInterface $m_WalletRepository,
    ) {}

    public function execute(User $user): Wallet {
        return $this->m_WalletRepository->createForUser($user);
    }
}