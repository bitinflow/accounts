<?php

declare(strict_types=1);

namespace Bitinflow\Payments\Tests;

use Bitinflow\Accounts\Contracts\AppTokenRepository;
use Bitinflow\Payments\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiOauthTest extends ApiTestCase
{

    public function testGetOauthToken(): void
    {
        $token = app(AppTokenRepository::class)->getAccessToken();

        $this->getPaymentsClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getPaymentsClient()->createSubscription([

        ]));
        $result->dump();
    }
}