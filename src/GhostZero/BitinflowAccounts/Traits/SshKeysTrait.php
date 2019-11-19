<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use GhostZero\BitinflowAccounts\ApiOperations\Delete;
use GhostZero\BitinflowAccounts\ApiOperations\Get;
use GhostZero\BitinflowAccounts\ApiOperations\Post;
use GhostZero\BitinflowAccounts\Result;

trait SshKeysTrait
{

    use Get, Post, Delete;

    /**
     * Get currently authed user with Bearer Token
     *
     * @param int $id
     *
     * @return Result Result object
     */
    public function getSshKeysByUserId(int $id): Result
    {
        return $this->get("users/$id/keys/json", [], null);
    }

    /**
     * Creates ssh key for the currently authed user
     *
     * @param string      $publicKey
     * @param string|null $name
     *
     * @return Result Result object
     */
    public function createSshKey(string $publicKey, string $name = null): Result
    {
        return $this->post('ssh-keys', [
            'public_key' => $publicKey,
            'name' => $name,
        ]);
    }

    /**
     * Deletes a given ssh key for the currently authed user
     *
     * @param int $id
     *
     * @return Result Result object
     */
    public function deleteSshKey(int $id): Result
    {
        return $this->delete("ssh-keys/$id", []);
    }
}