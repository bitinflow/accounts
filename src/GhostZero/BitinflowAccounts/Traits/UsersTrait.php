<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use GhostZero\BitinflowAccounts\ApiOperations\Get;
use GhostZero\BitinflowAccounts\Result;

trait UsersTrait
{

    use Get;

    /**
     * Get currently authed user with Bearer Token
     * @return Result Result object
     */
    public function getAuthedUser(): Result
    {
        return $this->get('users/me');
    }

    /**
     * Creates a new user on behalf of the current user.
     *
     * @param array $parameters
     *
     * @return Result
     */
    public function createUser(array $parameters): Result
    {
        return $this->post('v2/users', $parameters);
    }
}