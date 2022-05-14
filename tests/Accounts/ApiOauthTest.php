<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Tests;

use Bitinflow\Accounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiOauthTest extends ApiTestCase
{

    public function testGetOauthToken(): void
    {
        $this->registerResult($result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => '',
        ]));
        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data()->access_token);
    }
}