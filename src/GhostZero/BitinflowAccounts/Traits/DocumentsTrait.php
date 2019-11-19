<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use Carbon\CarbonInterface;
use GhostZero\BitinflowAccounts\ApiOperations\Get;
use GhostZero\BitinflowAccounts\ApiOperations\Post;
use GhostZero\BitinflowAccounts\Result;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait DocumentsTrait
{

    use Get, Post;

    /**
     * Create a Documents object
     *
     * @param array $parameters
     *
     * @return Result
     */
    public function createDocument(array $parameters): Result
    {
        return $this->post('documents', $parameters);
    }

    /**
     * Create a Documents download url
     *
     * @param mixed                $identifier
     * @param CarbonInterface|null $expiresAt
     *
     * @return Result
     */
    public function createDocumentDownloadUrl($identifier, ?CarbonInterface $expiresAt = null): Result
    {
        return $this->post("documents/$identifier/download-url", [
            'expires_at' => $expiresAt
                ? $expiresAt->toDateTimeString()
                : now()->addHour()->toDateTimeString(),
        ]);
    }
}