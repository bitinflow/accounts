<?php

namespace Bitinflow\Accounts\Traits;

use Bitinflow\Accounts\Traits\BitinflowPaymentsWallet\Balance;
use Bitinflow\Accounts\Traits\BitinflowPaymentsWallet\CheckoutSessions;
use Bitinflow\Accounts\Traits\BitinflowPaymentsWallet\Orders;
use Bitinflow\Accounts\Traits\BitinflowPaymentsWallet\Subscriptions;
use Bitinflow\Accounts\Traits\BitinflowPaymentsWallet\Taxation;
use Bitinflow\Accounts\Traits\BitinflowPaymentsWallet\Wallets;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\PendingRequest;

/**
 * @property Balance balance
 * @property CheckoutSessions checkout_sessions
 * @property Orders orders
 * @property Subscriptions subscriptions
 * @property Taxation taxation
 * @property Wallets wallets
 */
trait HasBitinflowPaymentsWallet
{
    protected $paymentsUser = null;

    /**
     * Create a new payment gateway request.
     *
     * @param string $method
     * @param string $url
     * @param array $attributes
     * @return mixed
     * @throws GuzzleException
     */
    public function asPaymentsUser(string $method, string $url, array $attributes = []): mixed
    {
        $client = new Client([
            'base_uri' => config('bitinflow-accounts.payments.base_url'),
        ]);

        $response = $client->request($method, $url, [
            RequestOptions::JSON => $attributes,
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $this->getAttribute(config('auth.providers.sso-users.access_token_field'))),
            ],
        ]);

        return json_decode($response->getBody());
    }

    /**
     * Get user from payments gateway.
     *
     * @return object|null
     * @throws GuzzleException
     */
    public function getPaymentsUser(): ?object
    {
        if (is_null($this->paymentsUser)) {
            $this->paymentsUser = $this->asPaymentsUser('GET', 'user');
        }

        return $this->paymentsUser;
    }

    public function getBalanceAttribute(): Balance
    {
        return new Balance($this);
    }

    public function getCheckoutSessionsAttribute(): CheckoutSessions
    {
        return new CheckoutSessions($this);
    }

    public function getOrdersAttribute(): Orders
    {
        return new Orders($this);
    }

    public function getSubscriptionsAttribute(): Subscriptions
    {
        return new Subscriptions($this);
    }

    public function getTaxationAttribute(): Taxation
    {
        return new Taxation($this);
    }

    public function getWalletsAttribute(): Wallets
    {
        return new Wallets($this);
    }
}
