<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests\TestCases;

use GhostZero\BitinflowAccounts\BitinflowAccounts;
use GhostZero\BitinflowAccounts\Providers\BitinflowAccountsServiceProvider;
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