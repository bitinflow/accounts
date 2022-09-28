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
        return $this->getPaymentsUser()->data->balance;
    }

    /**
     * Deposit given amount from bank to account.
     *
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function depositBalance(float $amount, string $decription): bool
    {
        $this->asPaymentsUser('PUT', sprintf('wallet/deposit', [
            'amount' => $amount,
            'decription' => $decription,
        ]));
    }

    /**
     * Charge given amount from account.
     *
     * @param float $amount
     * @param string $decription
     * @return bool
     */
    public function chargeBalance(float $amount, string $decription): bool
    {
        $order = $this->createOrder([
            'name' => $decription,
            'description' => 'one-time payment',
            'amount' => 1,
            'price' => $amount,
        ]);

        return $this->checkoutOrder($order->id);
    }

    /**
     * Get vat from user.
     *
     * @return int|null
     */
    public function getVat(): ?int
    {
        return $this->getPaymentsUser()->data->taxation->vat;
    }

    /**
     * Get all wallets that belongs to the user.
     *
     * @return array|null
     */
    public function getWallets(): ?array
    {
        return $this->getPaymentsUser()->data->wallets;
    }

    /**
     * Check if user has an active wallet.
     *
     * @return bool
     * @throws GuzzleException
     */
    public function hasWallet(): ?bool
    {
        return $this->getPaymentsUser()->data->has_wallet;
    }

    /**
     * Set default wallet to given wallet token.
     *
     * @param string $token default payment method token
     * @return bool
     */
    public function setDefaultWallet(string $token): bool
    {
        $this->asPaymentsUser('PUT', sprintf('wallets/default', [
            'token' => $token
        ]));

        return true;
    }

    /**
     * Get subscriptions from user.
     *
     * @return object|null
     */
    public function getSubscriptions(): ?object
    {
        return $this->asPaymentsUser('GET', 'subscriptions');
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function getSubscription(string $id): ?object
    {
        return $this->asPaymentsUser('GET', sprintf('subscriptions/%s', $id));
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
        $defaults = ['period' => 'monthly'];
        $attributes = array_merge(array_merge($defaults, $attributes), [
            'payload' => array_merge($payload, [
                'name' => $name,
            ]),
            'checkout' => $checkout
        ]);

        return $this->asPaymentsUser('POST', 'subscriptions', $attributes)->data;
    }

    public function createSubscriptionCheckoutIntent($subscription, $success_path = null)
    {
        return sprintf('%ssubscriptions/%s/?continue_url=%s', config('bitinflow-accounts.payments.dashboard_url'), $subscription, urlencode(url()->to($success_path)));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasSubscribed(string $name = 'default'): bool
    {
        $subscription = $this->getSubscription($name);

        return $subscription && $subscription->status === 'settled' || $subscription && $subscription->resumeable;
    }

    /**
     * Checkout given subscription.
     *
     * @param string $id
     * @return bool
     */
    public function checkoutSubscription(string $id): bool
    {
        $this->asPaymentsUser('PUT', sprintf('subscriptions/%s/checkout', $id));

        return true;
    }

    /**
     * Revoke a running subscription.
     *
     * @param string $id
     * @return bool
     */
    public function revokeSubscription(string $id): bool
    {
        $this->asPaymentsUser('PUT', sprintf('subscriptions/%s/revoke', $id));

        return true;
    }

    /**
     * Resume a running subscription.
     *
     * @param string $id
     * @return bool
     */
    public function resumeSubscription(string $id): bool
    {
        $this->asPaymentsUser('PUT', sprintf('subscriptions/%s/resume', $id));

        return true;
    }

    /**
     * Get orders from user.
     *
     * @return object|null
     */
    public function getOrders(): ?object
    {
        return $this->asPaymentsUser('GET', 'orders');
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function getOrder(string $id): ?object
    {
        return $this->asPaymentsUser('GET', sprintf('orders/%s', $id));
    }

    /**
     * Create a new order.
     *
     * @param array $order_items
     * @param array $attributes
     * @param bool $checkout optional checkout it directly
     * @return object|false
     */
    public function createOrder(array $order_items = [], array $attributes = [], bool $checkout = false): object|false
    {
        $attributes = array_merge($attributes, [
            'order_items' => $order_items,
            'checkout' => $checkout
        ]);

        return $this->asPaymentsUser('POST', 'orders', $attributes)->data;
    }

    /**
     * Checkout given subscription.
     *
     * @param string $id
     * @return bool
     */
    public function checkoutOrder(string $id): bool
    {
        $this->asPaymentsUser('PUT', sprintf('orders/%s/checkout', $id));

        return true;
    }

    /**
     * Revoke a running subscription.
     *
     * @param string $id
     * @return bool
     */
    public function revokeOrder(string $id): bool
    {
        $this->asPaymentsUser('PUT', sprintf('orders/%s/revoke', $id));

        return true;
    }

    public function createCheckoutSession(array $payload)
    {
        return $this->asPaymentsUser('POST', 'checkout-sessions', $payload);
    }

    public function getCheckoutSession(string $id)
    {
        return $this->asPaymentsUser('GET', sprintf('checkout-sessions/%s', $id));
    }

    public function checkoutCheckoutSession(string $id)
    {
        return $this->asPaymentsUser('PUT', sprintf('checkout-sessions/%s/checkout', $id));
    }

    public function revokeCheckoutSession(string $id)
    {
        return $this->asPaymentsUser('PUT', sprintf('checkout-sessions/%s/revoke', $id));
    }

    /**
     * A setup intent guides you through the process of setting up and saving
     * a customer's payment credentials for future payments.
     *
     * @param string $success_path
     * @return string
     */
    public function createSetupIntent(string $success_path = null): string
    {
        return sprintf('%swallet/?continue_url=%s', config('bitinflow-accounts.payments.dashboard_url'), urlencode(url()->to($success_path)));
    }
}
