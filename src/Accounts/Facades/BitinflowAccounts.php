<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Facades;

use Bitinflow\Accounts\BitinflowAccounts as BitinflowAccountsService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string|static cookie(string $cookie = null)
 * @method static Authenticatable actingAs($user, $scopes = [], $guard = 'api')
 * @method static static withClientId(string $clientId): self
 * @method static string getClientSecret(): string
 */
class BitinflowAccounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BitinflowAccountsService::class;
    }
}