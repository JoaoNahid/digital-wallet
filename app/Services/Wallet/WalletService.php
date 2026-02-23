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

    public function findWalletByEmail(string $p_Email): ?Wallet {
        return $this->m_WalletRepository->findByUserEmail($p_Email);
    }

    public function validateNotSelfTransfer(Wallet $p_From, Wallet $p_To): void {
        if ($p_From->id === $p_To->id) {
            throw new SelfTransferException();
        }
    }

    public function validateSufficientBalance(Wallet $p_Wallet, float $p_Amount): void {
        if (!$p_Wallet->hasSufficientBalance($p_Amount)) {
            throw new InsufficientBalanceException($p_Wallet->balance, $p_Amount);
        }
    }

    public function getWalletByTransaction(string $p_TransactionId): Wallet {
        return $this->m_WalletRepository->findByTransactionId($p_TransactionId);
    }
}