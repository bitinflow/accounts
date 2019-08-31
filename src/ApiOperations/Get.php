<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\ApiOperations;

use GhostZero\BitinflowAccounts\Helpers\Paginator;

trait Get
{
    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);
}