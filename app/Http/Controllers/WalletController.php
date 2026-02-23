<?php

namespace App\Http\Controllers;

use App\Actions\Wallet\DepositAction;
use App\DTOs\Wallet\DepositDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\DepositRequest;
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
    ) {}

    public function index(Request $p_Request): Response {
        $v_Wallet = $this->m_WalletService->getWallet($p_Request->user());

        return Inertia::render('wallet/Index', [
            'wallet' => WalletResource::make($v_Wallet)->toArray($p_Request)
        ]);
    }

    public function deposit(DepositRequest $p_Request, DepositAction $p_Action): RedirectResponse {
        $v_Wallet = $this->m_WalletService->getWallet($p_Request->user());
        $v_Dto = DepositDTO::fromRequest($p_Request->validated(), $v_Wallet->id);

        $p_Action->execute($v_Dto);

        return back()->with('success', 'Depósito realizado com sucesso!');
    }
}
