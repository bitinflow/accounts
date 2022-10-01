<?php

namespace Bitinflow\Payments\Traits;

use Bitinflow\Accounts\Exceptions\RequestRequiresClientIdException;
use Bitinflow\Accounts\Exceptions\RequestRequiresMissingParametersException;
use Bitinflow\Accounts\Helpers\Paginator;
use Bitinflow\Payments\Result;
use GuzzleHttp\Exception\GuzzleException;

trait Subscriptions
{
    /**
     * Get subscriptions from user.
     *
     * @return object|null
     */
    public function getSubscriptions(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('GET', 'subscriptions', $parameters, $paginator);
    }

    /**
     * @param string $id
     */
    public function getSubscription(string $id): Result
    {
        return $this->query('GET', sprintf('subscriptions/%s', $id));
    }

    /**
     * Create a new subscription.
     *
     * @param array $parameters array which requires following attributes:
     *                            name, description, period, price
     *                            and following attributes are optional:
     *                            vat, payload, ends_at, webhook_url, webhook_secret
     * @return object|false       the subscription object
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     * @throws RequestRequiresMissingParametersException
     */
    public function createSubscription(array $parameters): ?object
    {
        $this->validateRequired($parameters, ['name', 'description', 'period', 'price']);

        return $this->query('POST', 'subscriptions', [], null, $parameters);
    }

    /**
     * Force given subscription to check out (trusted apps only).
     *
     * @param string $id
     * @return Result
     * @throws RequestRequiresClientIdException
     * @throws GuzzleException
     */
    public function checkoutSubscription(string $id): Result
    {
        return $this->query('PUT', sprintf('subscriptions/%s/checkout', $id));
    }

    /**
     * Revoke a running subscription.
     *
     * @param string $id
     */
    public function revokeSubscription(string $id): Result
    {
        return $this->query('PUT', sprintf('subscriptions/%s/revoke', $id));
    }

    /**
     * Resume a running subscription.
     *
     * @param string $id
     */
    public function resumeSubscription(string $id): Result
    {
        return $this->query('PUT', sprintf('subscriptions/%s/resume', $id));
    }
}
