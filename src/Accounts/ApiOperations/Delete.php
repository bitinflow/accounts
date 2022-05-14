<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\ApiOperations;

use Bitinflow\Accounts\Helpers\Paginator;
use Bitinflow\Accounts\Result;

/**
 * @author René Preuß <rene@preuss.io>
 */
trait Delete
{

    abstract public function delete(string $path = '', array $parameters = [], Paginator $paginator = null): Result;
}