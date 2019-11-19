<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiChargesTest extends ApiTestCase
{

    public function testCaptureWithoutCapture(): void
    {
        $this->getClient()->withToken($this->getToken());

        $result = $this->getClient()->createCharge([
            'amount' => 2000,
            'currency' => 'usd',
            'source' => 'tok_visa',
            'description' => 'Charge for jenny.rosen@example.com',
        ]);
        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('id', $result->data());
        $this->assertEquals(2000, $result->data()->amount);
        $this->assertTrue($result->data()->captured);
    }

    public function testChargeWithCapture(): void
    {
        $this->getClient()->withToken($this->getToken());

        $result = $this->getClient()->createCharge([
            'amount' => 2000,
            'currency' => 'usd',
            'source' => 'tok_visa',
            'description' => 'Charge for jenny.rosen@example.com',
            'capture' => false, // default is true for instant capture
            'metadata' => [
                'foo' => 'bar',
            ],
            'receipt_email' => 'rene+unittest@bitinflow.com',
        ]);
        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('id', $result->data());
        $this->assertEquals(2000, $result->data()->amount);
        $this->assertFalse($result->data()->captured);

        $charge = $result->data();

        $result = $this->getClient()->captureCharge($charge->id);
        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('id', $result->data());
        $this->assertEquals(2000, $result->data()->amount);
        $this->assertTrue($result->data()->captured);
    }
}