<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Enums;

/**
 * @author René Preuß <rene@preuss.io>
 */
class Scope
{

    /*
     * v0 API
     */

    // Deprecated scope.
    public const API = 'api';

    // Read nonpublic user information, including email address.
    public const READ_USER = 'read_user';

    /*
     * v1 API
     */

    // Read authorized user´s email address.
    public const USERS_READ_EMAIL = 'users:read:email';

    // Manage a authorized user object.
    public const USERS_EDIT = 'users:edit';

    public const USERS_CREATE = 'users:create';

    // Read authorized user´s transactions.
    public const TRANSACTIONS_READ = 'transactions:read';

    // Create a new charge for the authorized user.
    public const CHARGES_CREATE = 'charges:create';
}