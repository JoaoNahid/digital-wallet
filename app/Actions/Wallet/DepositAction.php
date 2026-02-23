<?php

namespace App\Actions\Wallet;

use App\DTOs\Wallet\DepositDTO;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Wallet\TransactionService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class DepositAction
{
    public function __construct(
        private readonly WalletService $m_WalletService,
        private readonly TransactionService $m_TransactionService,
        private readonly TransactionRepositoryInterface $m_TransactionRepository,
    ) {}

    public function execute(DepositDTO $p_Dto): Transaction {
        return DB::transaction(function () use ($p_Dto) {
            // 1. Lock wallet
            $v_Wallet = $this->m_WalletService->getWalletWithLock($p_Dto->walletId);

            // 2. Create pending transaction
            $v_Transaction = $this->m_TransactionRepository->createDeposit($v_Wallet, $p_Dto);

            // 3. Credit wallet
            $this->m_WalletService->creditWallet($v_Wallet, $p_Dto->amount);

            // 4. Complete transaction
            $this->m_TransactionService->completeTransaction($v_Transaction);

            return $v_Transaction->fresh();
        });
    }
}