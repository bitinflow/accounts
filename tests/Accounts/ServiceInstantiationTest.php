<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Tests;

use Bitinflow\Accounts\BitinflowAccounts;
use Bitinflow\Accounts\Facades\BitinflowAccounts as BitinflowAccountsFacade;
use Bitinflow\Accounts\Tests\TestCases\TestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ServiceInstantiationTest extends TestCase
{

    public function testInstance(): void
    {
        $this->assertInstanceOf(BitinflowAccounts::class, app(BitinflowAccounts::class));
    }

    public function testFacade(): void
    {
        $this->assertInstanceOf(BitinflowAccounts::class, BitinflowAccountsFacade::getFacadeRoot());
    }
}