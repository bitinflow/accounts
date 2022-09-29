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
    private function asPaymentsUser(string $method, string $url, array $attributes = []): mixed
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
    
    public function balance(): Balance
    {
        return new Balance($this);
    }
    
    public function checkoutSessions(): CheckoutSessions
    {
        return new CheckoutSessions($this);
    }
    
    public function orders(): Orders
    {
        return new Orders($this);
    }
    
    public function subscriptions(): Subscriptions
    {
        return new Subscriptions($this);
    }
    
    public function taxation(): Taxation
    {
        return new Taxation($this);
    }

    public function wallets(): Wallets
    {
        return new Wallets($this);
    }
}
