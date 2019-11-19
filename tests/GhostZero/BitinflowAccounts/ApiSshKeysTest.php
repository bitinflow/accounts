<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Result;
use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiSshKeysTest extends ApiTestCase
{

    public function testGetSshKeyByUserId(): void
    {
        $this->registerResult($result = $this->getClient()->getSshKeysByUserId(38));
        $this->assertEquals('rene.preuss@check24.de', $result->shift()->name);
        $this->assertGreaterThanOrEqual(2, $result->count());
    }

    public function testSshKeyManagement(): void
    {
        $customName = 'Hello World!';
        $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEA3H7sYVrVCwwYIuRm3on3S9n/BCd2mBJrgCk6xTerbNmt0RyUZ+RtGsK6UYkgnRR2WWq9/Pv2s3RXJXPxbsIEYmKCcTdLUvDk56x9385cIVUX4w016mpe/8lyu+mIdqWYKsJMoab0oReCDX8Y9qBcaffDh8AgmYVN+86gXgoP1ITe9BDYrFiR6U571VyLDVN3OYOYPMF3/L9f0knMfM0T4LrS8oi6faVBCxZHRoBGtGmsTBkE0KwplYQFN2aa4Mxab+rTUFmJr3LYEcJF0J8wNJ3eEDFNOR0254jrjbGGAXGsc+cxJoNzech+GBkRMKMpNU0lds6VxP0ZB25VfzjEmQ== René Preuß';

        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->createSshKey($publicKey, $customName));
        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals('6b:fa:33:da:6c:3a:08:05:6f:71:8b:d8:ed:06:37:b6', $result->data()->fingerprint);
        $this->assertEquals($customName, $result->data()->name);

        $keyId = $result->data()->id;

        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->deleteSshKey($keyId));
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->success());
    }
}