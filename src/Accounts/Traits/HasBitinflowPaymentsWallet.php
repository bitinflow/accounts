<?php

namespace Bitinflow\Accounts\Traits;

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

    /**
     * Get balance from user.
     *
     * @return float|null
     */
    public function getBalance(): ?float
    {
        try {
            return $this->getPaymentsUser()->data->balance;
        } catch (GuzzleException $e) {
            return null;
        }
    }

    /**
     * Deposit given amount from bank to account.
     *
     * @param $amount
     * @param $description
     * @return bool
     */
    public function depositBalance($amount, $decription): bool
    {
        try {
            $this->asPaymentsUser('PUT', sprintf('wallet/deposit', [
                'amount' => $amount,
                'decription' => $decription,
            ]));
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Charge given amount from account.
     *
     * @param $amount
     * @param $description
     * @param $payload
     * @return bool
     */
    public function chargeBalance($amount, $decription, $payload = []): bool
    {
        try {
            $order = $this->asPaymentsUser('POST', sprintf('orders', [
                'order_items' => [
                    'name' => 'One time charge',
                    'description' => $decription,
                    'amount' => 1,
                    'price' => $amount,
                    'payload' => $payload,
                ],
                'checkout' => true,
            ]));

            return $order->data->status === 'completed';
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Get vat from user.
     *
     * @return int|null
     */
    public function getVat(): ?int
    {
        try {
            return $this->getPaymentsUser()->data->taxation->vat;
        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function getWallets(): ?array
    {
        try {
            return $this->getPaymentsUser()->data->wallets;
        } catch (GuzzleException $e) {
            return null;
        }
    }

    /**
     * Check if user has an active wallet.
     *
     * @return bool
     * @throws GuzzleException
     */
    public function hasWallet(): ?bool
    {
        try {
            return $this->getPaymentsUser()->data->has_wallet;
        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function setDefaultWallet($walletId): bool
    {
        // TODO
    }

    public function hasSubscribed($name = 'default'): bool
    {
        $subscription = $this->getSubscription($name);

        return $subscription && $subscription->status === 'settled' || $subscription && $subscription->resumeable;
    }

    public function getSubscription($name = 'default'): ?object
    {
        foreach ($this->getSubscriptions() as $subscription) {
            if (isset($subscription->payload->name) && $subscription->payload->name === $name) {
                return $subscription;
            }
        }

        return null;
    }

    /**
     * Get vat from user.
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function getSubscriptions(): ?array
    {
        $subscriptions = $this->getPaymentsUser()->data->subscriptions;

        foreach ($subscriptions as $key => $subscription) {
            if (!isset($subscription->payload->client_id) || $subscription->payload->client_id !== config('bitinflow-accounts.client_id')) {
                unset($subscriptions[$key]);
            }
        }

        return $subscriptions;
    }

    /**
     * Create a new subscription.
     *
     * @param array $attributes array which requires following attributes:
     *                            name, description, period, price
     *                            and following attributes are optional:
     *                            vat, payload, ends_at, webhook_url, webhook_secret
     * @param array $payload optional data that is stored in the subscription
     * @param bool $checkout optional checkout it directly
     * @return object|false       the subscription object
     * @throws GuzzleException
     */
    public function createSubscription(string $name, array $attributes, array $payload = [], bool $checkout = false): object|false
    {
        $client = [
            'name' => $name,
            'client_id' => config('bitinflow-accounts.client_id')
        ];
        $defaults = ['period' => 'monthly'];
        $attributes = array_merge(array_merge($defaults, $attributes), [
            'payload' => array_merge($payload, $client),
            'checkout' => $checkout
        ]);

        try {
            return $this->asPaymentsUser('POST', 'subscriptions', $attributes)->data;
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Checkout given subscription.
     *
     * @param string $id
     * @return bool
     */
    public function checkoutSubscription(string $id): bool
    {
        try {
            return (bool)$this->asPaymentsUser('PUT', sprintf('subscriptions/%s/checkout', $id));
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Revoke a running subscription.
     *
     * @param $id
     * @return bool
     */
    public function revokeSubscription($id): bool
    {
        try {
            return (bool)$this->asPaymentsUser('PUT', sprintf('subscriptions/%s/revoke', $id));
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Resume a running subscription.
     *
     * @param $id
     * @return bool
     */
    public function resumeSubscription($id): bool
    {
        try {
            return (bool)$this->asPaymentsUser('PUT', sprintf('subscriptions/%s/resume', $id));
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * A setup intent guides you through the process of setting up and saving
     * a customer's payment credentials for future payments.
     *
     * @return string
     */
    public function createSetupIntent($success_path = null): string
    {
        return sprintf('%swallet?continue_url=%s', config('bitinflow-accounts.payments.dashboard_url'), urlencode(url()->to($success_path)));
    }
}
