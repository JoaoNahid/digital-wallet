<?php

namespace App\Http\Controllers;

use App\Actions\Wallet\DepositAction;
use App\Actions\Wallet\TransferAction;
use App\DTOs\Wallet\DepositDTO;
use App\DTOs\Wallet\TransferDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\DepositRequest;
use App\Http\Requests\Wallet\TransferRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Services\Wallet\TransactionService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletService $m_WalletService,
        private readonly TransactionService $m_TransactionService,
    ) {}

    public function index(Request $p_Request): Response {
        $v_Wallet = $this->m_WalletService->getWallet($p_Request->user());
        $v_Transactions = $this->m_TransactionService->getPaginatedTransactions(
            $v_Wallet->id,
            5
        );

        return Inertia::render('wallet/Index', [
            'wallet' => WalletResource::make($v_Wallet)->toArray($p_Request),
            'transactions' => TransactionResource::collection($v_Transactions)
        ]);
    }

    public function deposit(DepositRequest $p_Request, DepositAction $p_Action): RedirectResponse {
        $v_Wallet = $this->m_WalletService->getWallet($p_Request->user());
        $v_Dto = DepositDTO::fromRequest($p_Request->validated(), $v_Wallet->id);

        $p_Action->execute($v_Dto);

        return back()->with('success', 'Depósito realizado com sucesso!');
    }

    public function transfer(TransferRequest $p_Request, TransferAction $p_Action): RedirectResponse {
        $v_Wallet = $this->m_WalletService->getWallet($p_Request->user());
        $v_TargetWallet = $this->m_WalletService->findWalletByEmail($p_Request->recipient_email);

        $v_Dto = new TransferDTO(
            fromWalletId: $v_Wallet->id,
            toWalletId: $v_TargetWallet->id,
            amount: (float) $p_Request->amount,
            description: $p_Request->description,
        );

        $p_Action->execute($v_Dto);

        return back()->with('success', 'Transferência realizada com sucesso!');
    }
}
