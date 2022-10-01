<?php

namespace Bitinflow\Accounts\Contracts;

use Bitinflow\Accounts\Exceptions\RequestFreshAccessTokenException;

interface AppTokenRepository
{
    /**
     * @throws RequestFreshAccessTokenException
     *
     * @return string
     */
    public function getAccessToken(): string;
}