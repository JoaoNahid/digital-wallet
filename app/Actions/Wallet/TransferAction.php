<?php

namespace App\Actions\Wallet;

use App\DTOs\Wallet\TransferDTO;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Wallet\TransactionService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class TransferAction
{
    public function __construct(
        private readonly WalletService $m_WalletService,
        private readonly TransactionService $m_TransactionService,
        private readonly TransactionRepositoryInterface $m_TransactionRepository,
    ) {}

    /**
     * @return Transaction[] [outTransaction, inTransaction]
     */
    public function execute(TransferDTO $p_Dto): array {
        return DB::transaction(function () use ($p_Dto) {
            // 1. Lock wallets
            $v_FromWallet = $this->m_WalletService->getWalletWithLock($p_Dto->fromWalletId);
            $v_ToWallet = $this->m_WalletService->getWalletWithLock($p_Dto->toWalletId);

            // 2. Validations
            $this->m_WalletService->validateNotSelfTransfer($v_FromWallet, $v_ToWallet);
            $this->m_WalletService->validateSufficientBalance($v_FromWallet, $p_Dto->amount);

            // 3. Create transaction pair
            [$v_OutTransaction, $v_InTransaction] = $this->m_TransactionRepository
                ->createTransferPair($v_FromWallet, $v_ToWallet, $p_Dto);

            // 4. Update balances
            $this->m_WalletService->debitWallet($v_FromWallet, $p_Dto->amount);
            $this->m_WalletService->creditWallet($v_ToWallet, $p_Dto->amount);

            // 5. Complete transactions
            $this->m_TransactionService->completeTransaction($v_OutTransaction);
            $this->m_TransactionService->completeTransaction($v_InTransaction);

            return [$v_OutTransaction->fresh(), $v_InTransaction->fresh()];
        });
    }
}