<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Enums;

/**
 * @author René Preuß <rene@preuss.io>
 */
class Scope
{

    /*
     * v0 API
     */

    // Deprecated scope.
    const API = 'api';

    // Read nonpublic user information, including email address.
    const READ_USER = 'read_user';
}