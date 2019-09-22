<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use GhostZero\BitinflowAccounts\ApiOperations\Get;
use GhostZero\BitinflowAccounts\ApiOperations\Post;
use GhostZero\BitinflowAccounts\ApiOperations\Put;
use GhostZero\BitinflowAccounts\Result;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait ChargesTrait
{

    use Get, Post, Put;

    /**
     * Create a Charge object
     *
     * @param array $parameters
     *
     * @return Result Result object
     */
    public function createCharge(array $parameters): Result
    {
        return $this->post('charges', $parameters);
    }

    /**
     * Get a Charge object
     *
     * @param string $id
     *
     * @return Result Result object
     */
    public function getCharge(string $id): Result
    {
        return $this->get("charges/$id");
    }

    /**
     * Update a Charge object
     *
     * @param string $id
     * @param array  $parameters
     *
     * @return Result Result object
     */
    public function updateCharge(string $id, array $parameters): Result
    {
        return $this->put("charges/$id", $parameters);
    }

    /**
     * Capture a Charge object
     *
     * @param string $id
     * @param array  $parameters
     *
     * @return Result Result object
     */
    public function captureCharge(string $id, array $parameters = []): Result
    {
        return $this->post("charges/$id/capture", $parameters);
    }
}