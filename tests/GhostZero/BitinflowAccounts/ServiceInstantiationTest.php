<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\BitinflowAccounts;
use GhostZero\BitinflowAccounts\Facades\BitinflowAccounts as BitinflowAccountsFacade;
use GhostZero\BitinflowAccounts\Tests\TestCases\TestCase;

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