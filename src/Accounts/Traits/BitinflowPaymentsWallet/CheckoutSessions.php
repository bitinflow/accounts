<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class CheckoutSessions
{
    public function __construct(protected User $user)
    {
        //
    }
  
    public function create(array $payload)
    {
        return $this->user->asPaymentsUser('POST', 'checkout-sessions', $payload);
    }

    public function get(string $id)
    {
        return $this->user->asPaymentsUser('GET', sprintf('checkout-sessions/%s', $id));
    }

    public function checkout(string $id)
    {
        return $this->user->asPaymentsUser('PUT', sprintf('checkout-sessions/%s/checkout', $id));
    }

    public function revoke(string $id)
    {
        return $this->user->asPaymentsUser('PUT', sprintf('checkout-sessions/%s/revoke', $id));
    }
}
