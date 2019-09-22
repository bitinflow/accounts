<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Facades;

use GhostZero\BitinflowAccounts\BitinflowAccounts as BitinflowAccountsService;
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