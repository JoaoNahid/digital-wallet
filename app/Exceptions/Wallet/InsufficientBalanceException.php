<?php

namespace App\Exceptions\Wallet;

use Exception;
use Illuminate\Http\{JsonResponse, Request};

class InsufficientBalanceException extends Exception
{
    public function __construct(
        public readonly float $available,
        public readonly float $required,
    ) {
        parent::__construct('Saldo insuficiente para realizar esta operação.');
    }

    public function render(Request $request): JsonResponse {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => [
                'amount' => ['Saldo insuficiente. Disponível: R$ ' . number_format($this->available, 2, ',', '.')],
            ],
        ], 422);
    }
}
