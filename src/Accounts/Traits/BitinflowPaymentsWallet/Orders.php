<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class Orders
{
    public function __construct(protected User $user)
    {
        //
    }
  
    /**
     * Get orders from user.
     *
     * @return object|null
     */
    public function all(): ?object
    {
        return $this->user->asPaymentsUser('GET', 'orders');
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function get(string $id): ?object
    {
        return $this->user->asPaymentsUser('GET', sprintf('orders/%s', $id));
    }

    /**
     * Create a new order.
     *
     * @param array $order_items
     * @param array $attributes
     * @param bool $checkout optional checkout it directly
     * @return object|false
     */
    public function create(array $order_items = [], array $attributes = [], bool $checkout = false): object|false
    {
        $attributes = array_merge($attributes, [
            'order_items' => $order_items,
            'checkout' => $checkout
        ]);

        return $this->user->asPaymentsUser('POST', 'orders', $attributes)->data;
    }

    /**
     * Checkout given subscription.
     *
     * @param string $id
     * @return bool
     */
    public function checkout(string $id): bool
    {
        $this->user->asPaymentsUser('PUT', sprintf('orders/%s/checkout', $id));

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
        $this->user->asPaymentsUser('PUT', sprintf('orders/%s/revoke', $id));

        return true;
    }
}
