<?php

namespace Bitinflow\Payments\Traits;

use Bitinflow\Accounts\Exceptions\RequestRequiresClientIdException;
use Bitinflow\Payments\Result;
use GuzzleHttp\Exception\GuzzleException;

trait CheckoutSessions
{
    /**
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function createCheckoutSession(array $parameters): Result
    {
        return $this->query('POST', 'checkout-sessions', [], null, $parameters);
    }

    /**
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function getCheckoutSession(string $id): Result
    {
        return $this->query('GET', sprintf('checkout-sessions/%s', $id));
    }

    /**
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function checkoutCheckoutSession(string $id): Result
    {
        return $this->query('PUT', sprintf('checkout-sessions/%s/checkout', $id));
    }

    /**
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function revokeCheckoutSession(string $id): Result
    {
        return $this->query('PUT', sprintf('checkout-sessions/%s/revoke', $id));
    }
}
