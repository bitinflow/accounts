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

    /*
     * v1 API
     */

    // Read authorized user´s email address.
    const USERS_READ_EMAIL = 'users:read:email';

    // Manage a authorized user object.
    const USERS_EDIT = 'users:edit';

    // Read authorized user´s transactions.
    const TRANSACTIONS_READ = 'transactions:read';

    // Create a new charge for the authorized user.
    const CHARGES_CREATE = 'charges:create';
}