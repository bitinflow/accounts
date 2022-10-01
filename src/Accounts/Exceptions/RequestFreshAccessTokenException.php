<?php

namespace Bitinflow\Accounts\Exceptions;

use DomainException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author René Preuß <rene@bitinflow.com>
 */
class RequestFreshAccessTokenException extends DomainException
{
    private ResponseInterface $response;

    public static function fromResponse(ResponseInterface $response): self
    {
        $instance = new self(sprintf('Refresh token request from bitinflow accounts failed. Status Code is %s.', $response->getStatusCode()));
        $instance->response = $response;

        return $instance;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}