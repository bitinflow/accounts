<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Facades;

use Bitinflow\Accounts\BitinflowAccounts as BitinflowAccountsService;
use Illuminate\Support\Facades\Facade;

/**
 * @author René Preuß <rene@preuss.io>
 */
class BitinflowAccounts extends Facade
{

    protected static function getFacadeAccessor()
    {
        return BitinflowAccountsService::class;
    }
}