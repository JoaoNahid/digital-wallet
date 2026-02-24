<?php

namespace App\Actions\Wallet;

use App\DTOs\Wallet\ReverseDTO;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Wallet\TransactionService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class ReverseTransactionAction
{
    public function __construct(
        private readonly WalletService $m_WalletService,
        private readonly TransactionService $m_TransactionService,
        private readonly TransactionRepositoryInterface $m_TransactionRepository,
    ) {}

    public function execute(ReverseDTO $p_Dto): Transaction {
        return DB::transaction(function () use ($p_Dto) {
            // 1. Find Transaction
            $v_TransactionToReverse = $this->m_TransactionService->findTransaction($p_Dto->transactionId);
            
            // 2. Validate reversal
            $this->m_TransactionService->validateCanReverse($v_TransactionToReverse);
            
            // 3. Get pair transaction if exists
            $v_PairTransaction = $this->m_TransactionService->getPairIfExists($v_TransactionToReverse);

            // 4. Create reversal transaction
            $v_OriginalReverse = $this->reverseTransaction($v_TransactionToReverse, $p_Dto->reason);
            if ($v_PairTransaction) {
                $this->reverseTransaction($v_PairTransaction, $p_Dto->reason);
            }

            return $v_OriginalReverse;
        });
    }

    private function reverseTransaction(Transaction $p_Transaction, ?string $p_Reason): Transaction {
        // 1. Lock wallet(s)
        $v_Wallet = $this->m_WalletService->getWalletWithLock($p_Transaction->wallet_id);

        // 2. Create reversal transaction
        $v_Reversal = $this->m_TransactionRepository->createReversal($p_Transaction, $p_Reason);

        // 3. Update wallet balance
        if ($p_Transaction->type->isCredit()) {
            $this->m_WalletService->debitWallet($v_Wallet, $p_Transaction->amount);
        } else {
            $this->m_WalletService->creditWallet($v_Wallet, $p_Transaction->amount);
        }

        // 4. Mark as reversed
        $this->m_TransactionService->markAsReversed($p_Transaction);

        return $v_Reversal;
    }
}