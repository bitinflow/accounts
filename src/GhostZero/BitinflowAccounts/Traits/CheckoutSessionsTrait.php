<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use GhostZero\BitinflowAccounts\ApiOperations\Get;
use GhostZero\BitinflowAccounts\ApiOperations\Post;
use GhostZero\BitinflowAccounts\Result;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait CheckoutSessionsTrait
{

    use Get, Post;

    /**
     * Get a Session object
     *
     * @param string $id
     *
     * @return Result         Result object
     */
    public function getCheckoutSession(string $id): Result
    {
        return $this->get("checkout/sessions/$id");
    }

    /**
     * Create a Session object
     *
     * @param array $parameters
     *
     * @return Result
     */
    public function createCheckoutSession(array $parameters): Result
    {
        return $this->post('payments/sessions', $parameters);
    }
}