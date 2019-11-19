<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use GhostZero\BitinflowAccounts\ApiOperations\Get;
use GhostZero\BitinflowAccounts\ApiOperations\Post;
use GhostZero\BitinflowAccounts\Result;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait PaymentIntentsTrait
{

    use Get, Post;

    /**
     * Get a Payment Intent object
     *
     * @param string $id
     *
     * @return Result         Result object
     */
    public function getPaymentIntent(string $id): Result
    {
        return $this->get("payment-intents/$id");
    }

    /**
     * Create a Payment Intent object
     *
     * @param array $parameters
     *
     * @return Result
     */
    public function createPaymentIntent(array $parameters): Result
    {
        return $this->post('payment-intents', $parameters);
    }
}