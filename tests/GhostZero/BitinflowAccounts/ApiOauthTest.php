<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiOauthTest extends ApiTestCase
{

    public function testGetOauthToken(): void
    {
        $this->getClient()->withClientId('5');
        $this->getClient()->withClientSecret('jejmtAJJWeEesW1siWwojjLn6zW9AIcWH1wqfFPq');
        $this->registerResult($result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => '',
        ]));
        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data()->access_token);
    }
}