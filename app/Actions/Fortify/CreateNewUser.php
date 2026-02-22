<?php

namespace App\Actions\Fortify;

use App\Actions\Wallet\CreateWalletAction;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function __construct(
        private readonly CreateWalletAction $m_CreateWalletAction,
    ) { }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $p_Input): User
    {
        Validator::make($p_Input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $v_User = User::create([
            'name' => $p_Input['name'],
            'email' => $p_Input['email'],
            'password' => $p_Input['password'],
        ]);

        if ($v_User) {
            $this->m_CreateWalletAction->execute($v_User);
        }

        return $v_User;
    }
}
