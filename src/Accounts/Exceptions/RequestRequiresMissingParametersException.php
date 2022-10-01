<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Exceptions;

use Exception;

/**
 * @author René Preuß <rene@preuss.io>
 */
class RequestRequiresMissingParametersException extends Exception
{
    public function __construct($message = 'Request requires missing parameters', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromValidateRequired(array $given, array $required): RequestRequiresMissingParametersException
    {
        return new self(sprintf(
            'Request requires missing parameters. Required: %s. Given: %s',
            implode(', ', $required),
            implode(', ', $given)
        ));
    }
}