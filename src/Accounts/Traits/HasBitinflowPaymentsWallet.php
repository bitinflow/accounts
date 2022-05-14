<?php

namespace Bitinflow\Accounts\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\PendingRequest;

/**
 * @property string access_token todo: can we get this from HasBitinflowTokens ?
 * @property PendingRequest $paymentsGatewayUser
 */
trait HasBitinflowPaymentsWallet
{
    protected $paymentsUser = null;

    /**
     * Check if user has an active wallet.
     *
     * @return bool
     * @throws GuzzleException
     */
    public function hasWallet(): bool
    {
        return $this->getPaymentsUser()->data->has_wallet;
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
            $this->paymentsUser = $this->paymentsGatewayRequest('GET', 'user');
        }

        return $this->paymentsUser;
    }

    /**
     * Create a new payment gateway request.
     *
     * @param string $method
     * @param string $url
     * @param array $attributes
     * @return mixed
     * @throws GuzzleException
     */
    private function paymentsGatewayRequest(string $method, string $url, array $attributes = []): mixed
    {
        $client = new Client([
            'base_uri' => config('bitinflow-accounts.payments.base_url'),
        ]);

        $response = $client->request($method, $url, [
            RequestOptions::JSON => $attributes,
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $this->access_token),
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function getWalletSetupIntent(string $success_path = ''): string
    {
        return sprintf('%swallet?continue_url=%s', config('bitinflow-accounts.payments.dashboard_url'), url($success_path));
    }

    /**
     * Get balance from user.
     *
     * @return float
     * @throws GuzzleException
     */
    public function getBalance(): float
    {
        return $this->getPaymentsUser()->data->balance;
    }

    /**
     * Get vat from user.
     *
     * @return int|null
     * @throws GuzzleException
     */
    public function getVat(): ?int
    {
        return $this->getPaymentsUser()->data->taxation->vat;
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
     * @return object             the subscription object
     * @throws GuzzleException
     */
    public function createSubscription(string $name, array $attributes, array $payload = [], bool $checkout = false): object
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

        return $this->paymentsGatewayRequest('POST', 'subscriptions', $attributes)->data;
    }

    /**
     * Checkout given subscription.
     *
     * @param string $id
     * @return void
     * @throws GuzzleException
     */
    public function checkoutSubscription(string $id): void
    {
        $this->paymentsGatewayRequest('PUT', sprintf('subscriptions/%s/checkout', $id));
    }

    /**
     * Revoke a running subscription.
     *
     * @param $id
     * @return void
     * @throws GuzzleException
     */
    public function revokeSubscription($id): void
    {
        $this->paymentsGatewayRequest('PUT', sprintf('subscriptions/%s/revoke', $id));
    }

    /**
     * Resume a running subscription.
     *
     * @param $id
     * @return void
     * @throws GuzzleException
     */
    public function resumeSubscription($id): void
    {
        $this->paymentsGatewayRequest('PUT', sprintf('subscriptions/%s/resume', $id));
    }
}