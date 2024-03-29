<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Traits;

use Bitinflow\Accounts\ApiOperations\Get;
use Bitinflow\Accounts\Result;

trait UsersTrait
{

    use Get;

    /**
     * Get currently authed user with Bearer Token
     *
     * @return Result Result object
     */
    public function getAuthedUser(): Result
    {
        return $this->get('v3/user');
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
        return $this->post('v3/users', $parameters);
    }

    /**
     * Checks if the given email exists.
     *
     * @param string $email
     *
     * @return Result
     */
    public function isEmailExisting(string $email): Result
    {
        return $this->post('v3/users/check', [
            'email' => $email,
        ]);
    }
}
