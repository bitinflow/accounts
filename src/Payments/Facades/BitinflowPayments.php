<?php

declare(strict_types=1);

namespace Bitinflow\Payments\Facades;

use Bitinflow\Payments\BitinflowPayments as BitinflowPaymentsService;
use Illuminate\Support\Facades\Facade;

/**
 * @author René Preuß <rene@preuss.io>
 */
class BitinflowPayments extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BitinflowPaymentsService::class;
    }
}