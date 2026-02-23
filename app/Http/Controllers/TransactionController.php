<?php

namespace App\Http\Controllers;

use App\Actions\Wallet\ReverseTransactionAction;
use App\DTOs\Wallet\ReverseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\ReverseTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\Wallet\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{

    use AuthorizesRequests;

    public function __construct(
        private readonly TransactionService $m_TransactionService,
    ) {}

    public function show($p_TransactionId): Response {
        $v_Transaction = $this->m_TransactionService->findTransaction($p_TransactionId);
        
        $this->authorize('view', $v_Transaction);
        return Inertia::render('transaction/Show', [
            'transaction' => TransactionResource::make($v_Transaction)->toArray(request()),
        ]);
    }

    public function reverse(ReverseTransactionRequest $p_Request, string $p_TransactionId, ReverseTransactionAction $p_Action ): RedirectResponse {
        $v_Dto = ReverseDTO::fromRequest($p_Request->validated(), $p_TransactionId);

        $p_Action->execute($v_Dto);

        return redirect()
            ->route('wallet.index')
            ->with('success', 'Transação revertida com sucesso!');
    }
}
