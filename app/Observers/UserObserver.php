<?php

namespace App\Observers;

use App\Actions\Wallet\CreateWalletAction;
use App\Models\User;

class UserObserver
{

    public function __construct(
        private readonly CreateWalletAction $m_CreateWalletAction
    ) { }
    /**
     * Handle the User "created" event.
     */
    public function created(User $p_User): void {
        $this->m_CreateWalletAction->execute($p_User);
    }
}
