<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Enums;

/**
 * @author René Preuß <rene@preuss.io>
 */
class Scope
{

    /*
     * v3 API
     */

    public const USER = 'user';
    public const USER_READ = 'user:read';

    public const USERS = 'users';
    public const USERS_READ = 'users:read';
    public const USERS_CREATE = 'users:create';

    public const PAYMENTS = 'payments';
    public const PAYMENTS_READ = 'payments:read';
    public const PAYMENTS_CREATE = 'payments:create';
    public const PAYMENTS_CHECKOUT = 'payments:checkout';
    public const PAYMENTS_REVOKE = 'payments:revoke';
    public const PAYMENTS_RESUME = 'payments:resume';

    public const PAYMENT_ORDERS = 'payment.orders';
    public const PAYMENT_ORDERS_READ = 'payment.orders:read';
    public const PAYMENT_ORDERS_CREATE = 'payment.orders:create';
    public const PAYMENT_ORDERS_CHECKOUT = 'payment.orders:checkout';
    public const PAYMENT_ORDERS_REVOKE = 'payment.orders:revoke';

    public const PAYMENT_INVOICES = 'payment.invoices';
    public const PAYMENT_INVOICES_READ = 'payment.invoices:read';

    public const PAYMENT_SUBSCRIPTIONS = 'payment.subscriptions';
    public const PAYMENT_SUBSCRIPTIONS_READ = 'payment.subscriptions:read';
    public const PAYMENT_SUBSCRIPTIONS_CREATE = 'payment.subscriptions:create';
    public const PAYMENT_SUBSCRIPTIONS_CHECKOUT = 'payment.subscriptions:checkout';
    public const PAYMENT_SUBSCRIPTIONS_REVOKE = 'payment.subscriptions:revoke';
    public const PAYMENT_SUBSCRIPTIONS_RESUME = 'payment.subscriptions:resume';

    public const PAYMENT_WALLETS = 'payment.wallets';
    public const PAYMENT_WALLETS_READ = 'payment.wallets:read';
    public const PAYMENT_WALLETS_CREATE = 'payment.wallets:create';

    public const PAYMENT_CHECKOUT_SESSIONS = 'payment.checkout-sessions';
    public const PAYMENT_CHECKOUT_SESSIONS_READ = 'payment.checkout-sessions:read';
    public const PAYMENT_CHECKOUT_SESSIONS_CREATE = 'payment.checkout-sessions:create';
    public const PAYMENT_CHECKOUT_SESSIONS_CHECKOUT = 'payment.checkout-sessions:checkout';
    public const PAYMENT_CHECKOUT_SESSIONS_REVOKE = 'payment.checkout-sessions:revoke';

    /**
     * v2 API
     */

    /*
     * v1 API
     */

    // Read authorized user´s email address.
    public const USERS_READ_EMAIL = 'users:read:email';

    // Manage a authorized user object.
    public const USERS_EDIT = 'users:edit';

    // also available in v3
    // public const USERS_CREATE = 'users:create';

    // Read authorized user´s transactions.
    public const TRANSACTIONS_READ = 'transactions:read';

    // Create a new charge for the authorized user.
    public const CHARGES_CREATE = 'charges:create';

    /*
     * v0 API
     */

    // Deprecated scope.
    public const API = 'api';

    // Read nonpublic user information, including email address.
    public const READ_USER = 'read_user';
}
