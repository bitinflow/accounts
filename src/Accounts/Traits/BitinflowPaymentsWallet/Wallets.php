<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class Wallets
{
    public function __construct(protected User $user)
    {
        //
    }
  
    /**
     * Get all wallets that belongs to the user.
     *
     * @return array|null
     */
    public function get(): ?array
    {
        return $this->user->getPaymentsUser()->data->wallets;
    }

    /**
     * Check if user has an active wallet.
     *
     * @return bool
     * @throws GuzzleException
     */
    public function has(): ?bool
    {
        return $this->user->getPaymentsUser()->data->has_wallet;
    }

    /**
     * Set default wallet to given wallet token.
     *
     * @param string $token default payment method token
     * @return bool
     */
    public function setDefault(string $token): bool
    {
        $this->user->asPaymentsUser('PUT', sprintf('wallets/default', [
            'token' => $token
        ]));

        return true;
    }
}
