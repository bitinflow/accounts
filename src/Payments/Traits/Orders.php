<?php

namespace Bitinflow\Payments\Traits;

use Bitinflow\Payments\Result;
use Bitinflow\Payments\BitinflowPayments;
use GuzzleHttp\Exception\GuzzleException;

trait Orders
{
    /**
     * Get orders from user.
     *
     * @throws GuzzleException
     */
    public function getOrders(): Result
    {
        return $this->query('GET', 'orders');
    }

    /**
     * @param string $id
     */
    public function getOrder(string $id): Result
    {
        return $this->query('GET', sprintf('orders/%s', $id));
    }

    /**
     * Create a new order.
     *
     * @throws GuzzleException
     */
    public function createOrder(array $parameters = []): Result
    {
        return $this->query('POST', 'orders', $parameters)->data;
    }

    /**
     * Checkout given subscription.
     *
     * @param string $id
     */
    public function checkoutOrder(string $id):Result
    {
        return $this->query('PUT', sprintf('orders/%s/checkout', $id));
    }

    /**
     * Revoke a running subscription.
     *
     * @param string $id
     */
    public function revokeOrder(string $id):Result
    {
        return $this->query('PUT', sprintf('orders/%s/revoke', $id));
    }
}
