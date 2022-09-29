<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class Taxation
{
    public function __construct(protected User $user)
    {
        //
    }
  
    /**
     * Get vat from user.
     *
     * @return int|null
     */
    public function get(): ?int
    {
        return $this->user->getPaymentsUser()->data->taxation->vat;
    }
}
