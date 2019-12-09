<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Traits;

use GhostZero\BitinflowAccounts\Result;
use GuzzleHttp\Exception\RequestException;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait OauthTrait
{

    /**
     * Retrieving a oauth token using a given grant type.
     *
     * @param string $grantType
     * @param array  $attributes
     *
     * @return Result
     */
    public function retrievingToken(string $grantType, array $attributes): Result
    {
        try {
            $response = $this->client->request('POST', '/oauth/token', $attributes + [
                    'form_params' => [
                        'grant_type' => $grantType,
                        'client_id' => $this->getClientId(),
                        'client_secret' => $this->getClientSecret(),
                    ],
                ]);

            $result = new Result($response, null);
        } catch (RequestException $exception) {
            $result = new Result($exception->getResponse(), $exception);
        }

        $result->bitinflow = $this;

        return $result;
    }
}