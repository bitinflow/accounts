<?php

namespace Bitinflow\Accounts;

use Bitinflow\Accounts\ApiOperations;
use Bitinflow\Accounts\Exceptions\RequestRequiresAuthenticationException;
use Bitinflow\Accounts\Exceptions\RequestRequiresClientIdException;
use Bitinflow\Accounts\Exceptions\RequestRequiresRedirectUriException;
use Bitinflow\Accounts\Helpers\Paginator;
use Bitinflow\Accounts\Traits;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @author René Preuß <rene@preuss.io>
 */
class BitinflowAccounts
{

    use Traits\OauthTrait;
    use Traits\SshKeysTrait;
    use Traits\UsersTrait;

    use Traits\HasBitinflowPaymentsWallet;

    use ApiOperations\Delete;
    use ApiOperations\Get;
    use ApiOperations\Post;
    use ApiOperations\Put;

    /**
     * The name for API token cookies.
     *
     * @var string
     */
    public static $cookie = 'bitinflow_token';
    /**
     * Indicates if Bitinflow Accounts should ignore incoming CSRF tokens.
     *
     * @var bool
     */
    public static $ignoreCsrfToken = false;
    /**
     * Indicates if Bitinflow Accounts should unserializes cookies.
     *
     * @var bool
     */
    public static $unserializesCookies = false;
    private static $baseUrl = 'https://accounts.bitinflow.com/api/';
    /**
     * Guzzle is used to make http requests.
     *
     * @var Client
     */
    protected $client;

    /**
     * Paginator object.
     *
     * @var Paginator
     */
    protected $paginator;

    /**
     * bitinflow Accounts OAuth token.
     *
     * @var string|null
     */
    protected $token = null;

    /**
     * bitinflow Accounts client id.
     *
     * @var string|null
     */
    protected $clientId = null;

    /**
     * bitinflow Accounts client secret.
     *
     * @var string|null
     */
    protected $clientSecret = null;

    /**
     * bitinflow Accounts OAuth redirect url.
     *
     * @var string|null
     */
    protected $redirectUri = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if ($clientId = config('bitinflow-accounts.client_id')) {
            $this->setClientId($clientId);
        }
        if ($clientSecret = config('bitinflow-accounts.client_secret')) {
            $this->setClientSecret($clientSecret);
        }
        if ($redirectUri = config('bitinflow-accounts.redirect_url')) {
            $this->setRedirectUri($redirectUri);
        }
        if ($redirectUri = config('bitinflow-accounts.base_url')) {
            self::setBaseUrl($redirectUri);
        }
        $this->client = new Client([
            'base_uri' => self::$baseUrl,
        ]);
    }

    /**
     * @param string $baseUrl
     *
     * @internal only for internal and debug purposes.
     */
    public static function setBaseUrl(string $baseUrl): void
    {
        self::$baseUrl = $baseUrl;
    }

    /**
     * Get or set the name for API token cookies.
     *
     * @param string|null $cookie
     * @return string|static
     */
    public static function cookie($cookie = null)
    {
        if (is_null($cookie)) {
            return static::$cookie;
        }

        static::$cookie = $cookie;

        return new static;
    }

    /**
     * Set the current user for the application with the given scopes.
     *
     * @param Authenticatable|Traits\HasBitinflowTokens $user
     * @param array $scopes
     * @param string $guard
     * @return Authenticatable
     */
    public static function actingAs($user, $scopes = [], $guard = 'api')
    {
        $user->withBitinflowAccessToken((object)[
            'scopes' => $scopes
        ]);

        if (isset($user->wasRecentlyCreated) && $user->wasRecentlyCreated) {
            $user->wasRecentlyCreated = false;
        }

        app('auth')->guard($guard)->setUser($user);

        app('auth')->shouldUse($guard);

        return $user;
    }

    /**
     * Fluid client id setter.
     *
     * @param string $clientId bitinflow Accounts client id.
     *
     * @return self
     */
    public function withClientId(string $clientId): self
    {
        $this->setClientId($clientId);

        return $this;
    }

    /**
     * Get client secret.
     *
     * @return string
     * @throws RequestRequiresClientIdException
     */
    public function getClientSecret(): string
    {
        if (!$this->clientSecret) {
            throw new RequestRequiresClientIdException;
        }

        return $this->clientSecret;
    }

    /**
     * Set client secret.
     *
     * @param string $clientSecret bitinflow Accounts client secret
     *
     * @return void
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Fluid client secret setter.
     *
     * @param string $clientSecret bitinflow Accounts client secret
     *
     * @return self
     */
    public function withClientSecret(string $clientSecret): self
    {
        $this->setClientSecret($clientSecret);

        return $this;
    }

    /**
     * Get redirect url.
     *
     * @return string
     * @throws RequestRequiresRedirectUriException
     */
    public function getRedirectUri(): string
    {
        if (!$this->redirectUri) {
            throw new RequestRequiresRedirectUriException;
        }

        return $this->redirectUri;
    }

    /**
     * Set redirect url.
     *
     * @param string $redirectUri
     *
     * @return void
     */
    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Fluid redirect url setter.
     *
     * @param string $redirectUri
     *
     * @return self
     */
    public function withRedirectUri(string $redirectUri): self
    {
        $this->setRedirectUri($redirectUri);

        return $this;
    }

    /**
     * Get OAuth token.
     *
     * @return string        bitinflow Accounts token
     * @return string|null
     * @throws RequestRequiresAuthenticationException
     */
    public function getToken()
    {
        if (!$this->token) {
            throw new RequestRequiresAuthenticationException;
        }

        return $this->token;
    }

    /**
     * Set OAuth token.
     *
     * @param string $token bitinflow Accounts OAuth token
     *
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Fluid OAuth token setter.
     *
     * @param string $token bitinflow Accounts OAuth token
     *
     * @return self
     */
    public function withToken(string $token): self
    {
        $this->setToken($token);

        return $this;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function get(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('GET', $path, $parameters, $paginator);
    }

    /**
     * Build query & execute.
     *
     * @param string $method       HTTP method
     * @param string $path         Query path
     * @param array $parameters    Query parameters
     * @param Paginator $paginator Paginator object
     * @param mixed|null $jsonBody JSON data
     *
     * @return Result     Result object
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, $jsonBody = null): Result
    {
        if ($paginator !== null) {
            $parameters[$paginator->action] = $paginator->cursor();
        }
        try {
            $response = $this->client->request($method, $path, [
                'headers' => $this->buildHeaders($jsonBody ? true : false),
                'query' => $this->buildQuery($parameters),
                'json' => $jsonBody ?: null,
            ]);
            $result = new Result($response, null, $paginator);
        } catch (RequestException $exception) {
            $result = new Result($exception->getResponse(), $exception, $paginator);
        }
        $result->bitinflow = $this;

        return $result;
    }

    /**
     * Build headers for request.
     *
     * @param bool $json Body is JSON
     *
     * @return array
     * @throws RequestRequiresClientIdException
     */
    private function buildHeaders(bool $json = false): array
    {
        $headers = [
            'Client-ID' => $this->getClientId(),
            'Accept' => 'application/json',
        ];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        if ($json) {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }

    /**
     * Get client id.
     *
     * @return string
     * @throws RequestRequiresClientIdException
     */
    public function getClientId(): string
    {
        if (!$this->clientId) {
            throw new RequestRequiresClientIdException;
        }

        return $this->clientId;
    }

    /**
     * Set client id.
     *
     * @param string $clientId bitinflow Accounts client id
     *
     * @return void
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * Build query with support for multiple smae first-dimension keys.
     *
     * @param array $query
     *
     * @return string
     */
    public function buildQuery(array $query): string
    {
        $parts = [];
        foreach ($query as $name => $value) {
            $value = (array)$value;
            array_walk_recursive($value, function ($value) use (&$parts, $name) {
                $parts[] = urlencode($name) . '=' . urlencode($value);
            });
        }

        return implode('&', $parts);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function post(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('POST', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function delete(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('DELETE', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function put(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('PUT', $path, $parameters, $paginator);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array|null $body
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function json(string $method, string $path = '', array $body = null): Result
    {
        if ($body) {
            $body = json_encode(['data' => $body]);
        }

        return $this->query($method, $path, [], null, $body);
    }
}
