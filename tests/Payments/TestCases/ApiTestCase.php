<?php

declare(strict_types=1);

namespace Bitinflow\Payments\Tests\TestCases;

use Bitinflow\Accounts\BitinflowAccounts;
use Bitinflow\Accounts\Result;
use Bitinflow\Payments\BitinflowPayments;

/**
 * @author René Preuß <rene@preuss.io>
 */
abstract class ApiTestCase extends TestCase
{
    protected static $rateLimitRemaining = null;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->getAccountsBaseUrl()) {
            BitinflowAccounts::setBaseUrl($this->getAccountsBaseUrl());
        }
        if ($this->getPaymentsBaseUrl()) {
            BitinflowPayments::setBaseUrl($this->getPaymentsBaseUrl());
        }

        if (!$this->getClientId()) {
            $this->markTestSkipped('No Client-ID given');
        }
        if (self::$rateLimitRemaining !== null && self::$rateLimitRemaining < 3) {
            $this->markTestSkipped('Rate Limit exceeded (' . self::$rateLimitRemaining . ')');
        }
        $this->getAccountsClient()->setClientId($this->getClientId());
        $this->getPaymentsClient()->setClientId($this->getClientId());
    }

    protected function registerResult(Result $result): Result
    {
        self::$rateLimitRemaining = $result->rateLimit('remaining');

        return $result;
    }

    protected function getAccountsBaseUrl()
    {
        return getenv('ACCOUNTS_BASE_URL');
    }

    protected function getPaymentsBaseUrl()
    {
        return getenv('PAYMENTS_BASE_URL');
    }

    protected function getClientId()
    {
        return getenv('CLIENT_ID');
    }

    protected function getClientSecret()
    {
        return getenv('CLIENT_KEY');
    }

    protected function getToken()
    {
        return getenv('CLIENT_ACCESS_TOKEN');
    }

    public function getPaymentsClient(): BitinflowPayments
    {
        return app(BitinflowPayments::class);
    }

    public function getAccountsClient(): BitinflowAccounts
    {
        return app(BitinflowAccounts::class);
    }
}