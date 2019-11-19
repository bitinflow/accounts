<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;
use Illuminate\Support\Str;

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

    public function testCreateUser(): void
    {
        $testEmailAddress = $this->createRandomEmail();

        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->createUser([
            'first_name' => 'René',
            'last_name' => 'Preuß',
            'email' => $testEmailAddress,
            'terms_accepted_at' => now()->toDateTimeString(),
        ]));
        $this->assertEquals($testEmailAddress, $result->data()->email);
    }

    private function createRandomEmail(): string
    {
        return sprintf('rene+unittest.%s@bitinflow.com', Str::random());
    }
}