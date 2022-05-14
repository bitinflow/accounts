<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Exceptions;

use Exception;

/**
 * @author René Preuß <rene@preuss.io>
 */
class RequestRequiresAuthenticationException extends Exception
{
    public function __construct($message = 'Request requires authentication', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}