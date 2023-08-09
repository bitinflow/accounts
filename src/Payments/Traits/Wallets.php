<?php

namespace Bitinflow\Payments\Traits;

use Bitinflow\Payments\Result;
use Bitinflow\Payments\BitinflowPayments;

trait Wallets
{
    /**
     * Get all wallets that belong to the user.
     */
    public function getWallets(): Result
    {
        return $this->query('GET', 'wallet');
    }

    /**
     * Set default wallet to given wallet token.
     *
     * @param string $token default payment method token
     */
    public function setDefaultWallet(string $token): Result
    {
        return $this->query('PUT', 'wallet/default', [], null, [
            'token' => $token
        ]);
    }

    public function getWalletSetupIntent(string $successUrl): string
    {
        return sprintf('%swallet/?continue_url=%s', config('bitinflow-accounts.payments.dashboard_url'), urlencode($successUrl));
    }
}
