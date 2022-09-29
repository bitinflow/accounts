<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class Subscriptions
{
    public function __construct(protected User $user)
    {
        //
    }
  
    /**
     * Get subscriptions from user.
     *
     * @return object|null
     */
    public function all(): ?object
    {
        return $this->user->asPaymentsUser('GET', 'subscriptions');
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function get(string $id): ?object
    {
        return $this->user->asPaymentsUser('GET', sprintf('subscriptions/%s', $id));
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
    public function create(string $name, array $attributes, array $payload = [], bool $checkout = false): object|false
    {
        $defaults = ['period' => 'monthly'];
        $attributes = array_merge(array_merge($defaults, $attributes), [
            'payload' => array_merge($payload, [
                'name' => $name,
            ]),
            'checkout' => $checkout
        ]);

        return $this->user->asPaymentsUser('POST', 'subscriptions', $attributes)->data;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name = 'default'): bool
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
    public function checkout(string $id): bool
    {
        $this->user->asPaymentsUser('PUT', sprintf('subscriptions/%s/checkout', $id));

        return true;
    }

    /**
     * Revoke a running subscription.
     *
     * @param string $id
     * @return bool
     */
    public function revoke(string $id): bool
    {
        $this->user->asPaymentsUser('PUT', sprintf('subscriptions/%s/revoke', $id));

        return true;
    }

    /**
     * Resume a running subscription.
     *
     * @param string $id
     * @return bool
     */
    public function resume(string $id): bool
    {
        $this->user->asPaymentsUser('PUT', sprintf('subscriptions/%s/resume', $id));

        return true;
    }
}
