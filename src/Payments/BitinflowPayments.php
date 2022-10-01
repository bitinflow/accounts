<?php /** @noinspection DuplicatedCode */

namespace Bitinflow\Payments;

use Bitinflow\Accounts\Exceptions\RequestRequiresAuthenticationException;
use Bitinflow\Accounts\Exceptions\RequestRequiresClientIdException;
use Bitinflow\Accounts\Helpers\Paginator;
use Bitinflow\Payments\Result;
use Bitinflow\Accounts\ApiOperations;
use Bitinflow\Payments\Traits;
use Bitinflow\Support\Query;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class BitinflowPayments
{
    use Traits\Balance;
    use Traits\Wallets;
    use Traits\Orders;
    use Traits\Subscriptions;
    use Traits\CheckoutSessions;
    use Traits\Taxation;

    use ApiOperations\Validation;

    private static string $baseUrl = 'https://api.pay.bitinflow.com/v1/';

    /**
     * bitinflow Payments OAuth token.
     */
    protected ?string $token = null;

    /**
     * bitinflow Accounts client id.
     */
    protected ?string $clientId = null;

    /**
     * bitinflow Accounts client secret.
     */
    protected ?string $clientSecret = null;

    private Client $client;

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
        if ($redirectUri = config('bitinflow-accounts.payments.base_url')) {
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
     * Get OAuth token.
     *
     * @return string        bitinflow Accounts token
     * @return string|null
     * @throws RequestRequiresAuthenticationException
     */
    public function getToken(): ?string
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
     * Build query & execute.
     *
     * @param string $method       HTTP method
     * @param string $path         Query path
     * @param array $parameters    Query parameters
     * @param Paginator|null $paginator Paginator object
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
                'headers' => $this->buildHeaders((bool)$jsonBody),
                'query' => Query::build($parameters),
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

    public function getBaseUrl()
    {
        return self::$baseUrl;
    }
}