<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Tests\TestCases;

use Bitinflow\Accounts\BitinflowAccounts;
use Bitinflow\Accounts\Providers\BitinflowAccountsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
abstract class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            BitinflowAccountsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'BitinflowAccounts' => BitinflowAccounts::class,
        ];
    }
}