<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\ApiOperations;

use GhostZero\BitinflowAccounts\Helpers\Paginator;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait Delete
{

    abstract public function delete(string $path = '', array $parameters = [], Paginator $paginator = null);
}