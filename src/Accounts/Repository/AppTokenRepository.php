<?php

namespace Bitinflow\Accounts\Repository;

use Bitinflow\Accounts\BitinflowAccounts;
use Bitinflow\Accounts\Contracts\AppTokenRepository as Repository;
use Bitinflow\Accounts\Exceptions\RequestFreshAccessTokenException;
use Illuminate\Support\Facades\Cache;

class AppTokenRepository implements Repository
{
    public const ACCESS_TOKEN_CACHE_KEY = 'bitinflow-accounts:access_token';

    private BitinflowAccounts $client;

    public function __construct()
    {
        $this->client = app(BitinflowAccounts::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessToken(): string
    {
        $accessToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY);

        if ($accessToken) {
            return $accessToken;
        }

        return $this->requestFreshAccessToken('*');
    }

    /**
     * @param string $scope
     *
     * @throws RequestFreshAccessTokenException
     *
     * @return mixed
     */
    private function requestFreshAccessToken(string $scope)
    {
        $result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => $scope,
        ]);

        if ( ! $result->success()) {
            throw RequestFreshAccessTokenException::fromResponse($result->response());
        }

        Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $accessToken = $result->data()->access_token, now()->addWeek());

        return $accessToken;
    }

    private function getClient(): BitinflowAccounts
    {
        return $this->client;
    }
}