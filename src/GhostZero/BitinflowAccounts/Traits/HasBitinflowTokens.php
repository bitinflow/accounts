<?php

namespace GhostZero\BitinflowAccounts\Traits;

use stdClass;

trait HasBitinflowTokens
{
    /**
     * The current access token for the authentication user.
     *
     * @var stdClass
     */
    protected $accessToken;

    /**
     * Get the current access token being used by the user.
     *
     * @return stdClass|null
     */
    public function bitinflowToken(): ?stdClass
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function bitinflowTokenCan(string $scope): bool
    {
        $scopes = $this->accessToken ? $this->accessToken->scopes : [];

        return in_array('*', $scopes) || in_array($scope, $this->accessToken->scopes);
    }

    /**
     * Set the current access token for the user.
     *
     * @param stdClass $accessToken
     * @return $this
     */
    public function withBitinflowAccessToken(stdClass $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}