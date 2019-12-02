<?php

namespace GhostZero\BitinflowAccounts;

use GhostZero\BitinflowAccounts\ApiOperations;
use GhostZero\BitinflowAccounts\Exceptions\RequestRequiresAuthenticationException;
use GhostZero\BitinflowAccounts\Exceptions\RequestRequiresClientIdException;
use GhostZero\BitinflowAccounts\Exceptions\RequestRequiresRedirectUriException;
use GhostZero\BitinflowAccounts\Helpers\Paginator;
use GhostZero\BitinflowAccounts\Traits;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * @author René Preuß <rene@preuss.io>
 */
class BitinflowAccounts
{

    use Traits\ChargesTrait;
    use Traits\DocumentsTrait;
    use Traits\PaymentIntentsTrait;
    use Traits\SshKeysTrait;
    use Traits\UsersTrait;

    use ApiOperations\Delete;
    use ApiOperations\Get;
    use ApiOperations\Post;
    use ApiOperations\Put;

    private static $baseUrl = 'https://accounts.bitinflow.com/api/';

    /**
     * Guzzle is used to make http requests.
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Paginator object.
     * @var Paginator
     */
    protected $paginator;

    /**
     * bitinflow Accounts OAuth token.
     * @var string|null
     */
    protected $token = null;

    /**
     * bitinflow Accounts client id.
     * @var string|null
     */
    protected $clientId = null;

    /**
     * bitinflow Accounts client secret.
     * @var string|null
     */
    protected $clientSecret = null;

    /**
     * bitinflow Accounts OAuth redirect url.
     * @var string|null
     */
    protected $redirectUri = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if ($clientId = config('bitinflow-accounts-api.client_id')) {
            $this->setClientId($clientId);
        }
        if ($clientSecret = config('bitinflow-accounts-api.client_secret')) {
            $this->setClientSecret($clientSecret);
        }
        if ($redirectUri = config('bitinflow-accounts-api.redirect_url')) {
            $this->setRedirectUri($redirectUri);
        }
        if ($redirectUri = config('bitinflow-accounts-api.base_url')) {
            self::setBaseUrl($redirectUri);
        }
        $this->client = new Client([
            'base_uri' => self::$baseUrl,
        ]);
    }

    /**
     * @internal only for internal and debug purposes.
     * @param string $baseUrl
     */
    public static function setBaseUrl(string $baseUrl): void
    {
        self::$baseUrl = $baseUrl;
    }

    /**
     * Get client id.
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
     * @param string         $path
     * @param array          $parameters
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
     * @param string         $path
     * @param array          $parameters
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
     * @param string         $path
     * @param array          $parameters
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
     * @param string         $path
     * @param array          $parameters
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
     * @param string     $method
     * @param string     $path
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

    /**
     * Build query & execute.
     *
     * @param string     $method HTTP method
     * @param string     $path Query path
     * @param array      $parameters Query parameters
     * @param Paginator  $paginator Paginator object
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
            $value = (array) $value;
            array_walk_recursive($value, function ($value) use (&$parts, $name) {
                $parts[] = urlencode($name) . '=' . urlencode($value);
            });
        }

        return implode('&', $parts);
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
        ];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        if ($json) {
            $headers['Content-Type'] = 'application/json';
            $headers['Accept'] = 'application/json';
        }

        return $headers;
    }
}