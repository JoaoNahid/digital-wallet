<?php

namespace App\Http\Requests\Wallet;

use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Http\FormRequest;

class ReverseTransactionRequest extends FormRequest
{

    public function __construct(
        private readonly WalletService $m_WalletService
    )
    { }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $v_TransactionId = $this->route('transaction');
        $v_Wallet = $this->m_WalletService->getWalletByTransaction($v_TransactionId);
        return $v_Wallet->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array {
        return [
            'reason.required' => 'O motivo do estorno é obrigatório.'
        ];
    }
}
