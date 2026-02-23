<?php

namespace App\Services\Wallet;

use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Exceptions\Wallet\SelfTransferException;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletService
{
    public function __construct(
        private readonly WalletRepositoryInterface $m_WalletRepository,
    ) {}

    public function getWallet(User $user): Wallet {
        $v_Wallet = $this->m_WalletRepository->findByUserId($user->id);

        return $v_Wallet;
    }

    public function getWalletWithLock(string $walletId): Wallet {
        return $this->m_WalletRepository->findByIdWithLock($walletId);
    }

    public function creditWallet(Wallet $p_Wallet, float $p_Amount): void {
        $this->m_WalletRepository->updateBalance($p_Wallet, $p_Amount, 'credit');
    }

    public function debitWallet(Wallet $p_Wallet, float $p_Amount): void {
        $this->m_WalletRepository->updateBalance($p_Wallet, $p_Amount, 'debit');
    }
}