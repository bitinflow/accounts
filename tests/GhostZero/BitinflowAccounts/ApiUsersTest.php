<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Result;
use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiUsersTest extends ApiTestCase
{

    public function testGetAuthedUser()
    {
        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->getAuthedUser());
        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals('rene@preuss.io', $result->data()->email);
    }
}