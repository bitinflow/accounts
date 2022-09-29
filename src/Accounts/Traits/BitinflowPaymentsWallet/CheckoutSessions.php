<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class CheckoutSessions
{
    public function __construct(protected User $user)
    {
        //
    }
  
    public function createCheckoutSession(array $payload)
    {
        return $this->user->asPaymentsUser('POST', 'checkout-sessions', $payload);
    }

    public function getCheckoutSession(string $id)
    {
        return $this->user->asPaymentsUser('GET', sprintf('checkout-sessions/%s', $id));
    }

    public function checkoutCheckoutSession(string $id)
    {
        return $this->user->asPaymentsUser('PUT', sprintf('checkout-sessions/%s/checkout', $id));
    }

    public function revokeCheckoutSession(string $id)
    {
        return $this->user->asPaymentsUser('PUT', sprintf('checkout-sessions/%s/revoke', $id));
    }
}
