<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiPaymentIntentsTest extends ApiTestCase
{
    private $paymentIntent;

    public function testCreatePaymentIntent(): void
    {
        $this->getClient()->withToken($this->getToken());

        $result = $this->getClient()->createPaymentIntent([
            'payment_method_types' => ['card'],
            'amount' => 1000,
            'currency' => 'usd',
            'application_fee_amount' => 123,
        ]);
        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('id', $result->data());
        $this->assertArrayHasKey('redirect_url', $result->data());
        $this->assertEquals(1000, $result->data()->amount);

        // use this payment intent for our next tests
        $this->paymentIntent = $result->data();
    }

    public function testGetPaymentIntent(): void
    {
        $this->getClient()->withToken($this->getToken());

        $result = $this->getClient()->getPaymentIntent($this->paymentIntent->id);
        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('id', $result->data());
        $this->assertEquals(1000, $result->data()->amount);
    }
}