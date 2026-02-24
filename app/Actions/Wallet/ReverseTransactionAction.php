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
            // 1. Find and validate transaction
            $v_Original = $this->m_TransactionService->findTransaction($p_Dto->transactionId);
            $this->m_TransactionService->validateCanReverse($v_Original);

            // 2. Lock wallet(s)
            $v_Wallet = $this->m_WalletService->getWalletWithLock($v_Original->wallet_id);

            // 3. Create reversal transaction
            $v_Reversal = $this->m_TransactionRepository->createReversal($v_Original, $p_Dto->reason);

            // 4. Update wallet balance
            if ($v_Original->type->isCredit()) {
                $this->m_WalletService->debitWallet($v_Wallet, $v_Original->amount);
            } else {
                $this->m_WalletService->creditWallet($v_Wallet, $v_Original->amount);
            }

            // 5. Handle transfer reversal (reverse the other side too)
            if ($v_Original->type === TransactionType::TransferOut && $v_Original->target_wallet_id) {
                $this->reverseTransferIn($v_Original, $p_Dto->reason);
            }

            // 6. Mark original as reversed
            $this->m_TransactionService->markAsReversed($v_Original);

            return $v_Reversal;
        });
    }

    private function reverseTransferIn(Transaction $p_OutTransaction, ?string $p_Reason): void {
        $v_InTransaction = Transaction::where('reference_id', $p_OutTransaction->id)
            ->transferIn()
            ->first();

        if ($v_InTransaction && $v_InTransaction->can_be_reversed) {
            // 1. Lock target wallet
            $v_TargetWallet = $this->m_WalletService->getWalletWithLock($v_InTransaction->wallet_id);

            // 2. Create reversal transaction
            $this->m_TransactionRepository->createReversal($v_InTransaction, $p_Reason);

            // 3. Update wallet balance
            $this->m_WalletService->debitWallet($v_TargetWallet, $v_InTransaction->amount);

            // 4. Mark as reversed
            $this->m_TransactionService->markAsReversed($v_InTransaction);
        }
    }
}