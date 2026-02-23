<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipient_email' => [
                'required',
                'email',
                'exists:users,email',
                Rule::notIn([$this->user()->email]),
            ],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array {
        return [
            'recipient_email.required' => 'O e-mail do destinatário é obrigatório.',
            'recipient_email.email' => 'Informe um e-mail válido.',
            'recipient_email.exists' => 'Usuário não encontrado.',
            'recipient_email.not_in' => 'Não é possível transferir para si mesmo.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.min' => 'O valor mínimo é R$ 0,01.',
        ];
    }
}
