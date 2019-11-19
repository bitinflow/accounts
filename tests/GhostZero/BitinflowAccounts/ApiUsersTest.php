<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiUsersTest extends ApiTestCase
{

    public function testGetAuthedUser(): void
    {
        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->getAuthedUser());
        $this->assertEquals('rene@preuss.io', $result->data()->email);
    }
}