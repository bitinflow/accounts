<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\ApiOperations;

use GhostZero\BitinflowAccounts\Helpers\Paginator;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait Put
{

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}