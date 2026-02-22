<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Services\Wallet\WalletService;
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
            'wallet' => WalletResource::make($v_Wallet)->toArray($p_Request),
        ]);
    }
}
