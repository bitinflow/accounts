# bitinflow Accounts

<a href="https://packagist.org/packages/ghostzero/bitinflow-accounts"><img src="https://img.shields.io/packagist/dt/ghostzero/bitinflow-accounts" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/ghostzero/bitinflow-accounts"><img src="https://img.shields.io/packagist/v/ghostzero/bitinflow-accounts" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/ghostzero/bitinflow-accounts"><img src="https://img.shields.io/packagist/l/ghostzero/bitinflow-accounts" alt="License"></a>
<a href="https://ghostzero.dev/discord"><img src="https://discordapp.com/api/guilds/590942233126240261/embed.png?style=shield" alt="Discord"></a>

PHP bitinflow Accounts API Client for Laravel 5+

## Table of contents

1. [Installation](#installation)
2. [Event Listener](#event-listener)
3. [Configuration](#configuration)
4. [Examples](#examples)
5. [Documentation](#documentation)
6. [Development](#Development)

## Installation

```
composer require ghostzero/bitinflow-accounts
```

**If you use Laravel 5.5+ you are already done, otherwise continue.**

Add Service Provider to your `app.php` configuration file:

```php
Bitinflow\Accounts\Providers\BitinflowAccountsServiceProvider::class,
```

## Event Listener

- Add `SocialiteProviders\Manager\SocialiteWasCalled` event to your `listen[]` array in `app/Providers/EventServiceProvider`.
- Add your listeners (i.e. the ones from the providers) to the `SocialiteProviders\Manager\SocialiteWasCalled[]` that you just created.
- The listener that you add for this provider is `'GhostZero\\BitinflowAccounts\\Socialite\\BitinflowExtendSocialite@handle',`.
- Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.


```
/**
 * The event handler mappings for the application.
 *
 * @var array
 */
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // add your listeners (aka providers) here
        'Bitinflow\\Accounts\\Socialite\\BitinflowExtendSocialite@handle',
    ],
];
```

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider="Bitinflow\Accounts\Providers\BitinflowAccountsServiceProvider"
```

Add environmental variables to your `.env`

```
BITINFLOW_ACCOUNTS_KEY=
BITINFLOW_ACCOUNTS_SECRET=
BITINFLOW_ACCOUNTS_REDIRECT_URI=http://localhost
```

You will need to add an entry to the services configuration file so that after config files are cached for usage in production environment (Laravel command `artisan config:cache`) all config is still available.

**Add to `config/services.php`:**

```php
'bitinflow-accounts' => [
    'client_id' => env('BITINFLOW_ACCOUNTS_KEY'),
    'client_secret' => env('BITINFLOW_ACCOUNTS_SECRET'),
    'redirect' => env('BITINFLOW_ACCOUNTS_REDIRECT_URI')
],
```

## Examples

#### Basic

```php
$bitinflowAccounts = new Bitinflow\Accounts\BitinflowAccounts();

$bitinflowAccounts->setClientId('abc123');

// Get SSH Key by User ID
$result = $bitinflowAccounts->getSshKeysByUserId(38);

// Check, if the query was successfull
if ( ! $result->success()) {
    die('Ooops: ' . $result->error());
}

// Shift result to get single key data
$sshKey = $result->shift();

echo $sshKey->name;
```

#### Setters

```php
$bitinflowAccounts = new Bitinflow\Accounts\BitinflowAccounts();

$bitinflowAccounts->setClientId('abc123');
$bitinflowAccounts->setClientSecret('abc456');
$bitinflowAccounts->setToken('abcdef123456');

$bitinflowAccounts = $bitinflowAccounts->withClientId('abc123');
$bitinflowAccounts = $bitinflowAccounts->withClientSecret('abc123');
$bitinflowAccounts = $bitinflowAccounts->withToken('abcdef123456');
```

#### OAuth Tokens

```php
$bitinflowAccounts = new Bitinflow\Accounts\BitinflowAccounts();

$bitinflowAccounts->setClientId('abc123');
$bitinflowAccounts->setToken('abcdef123456');

$result = $bitinflowAccounts->getAuthedUser();

$user = $userResult->shift();
```

```php
$bitinflowAccounts->setToken('uvwxyz456789');

$result = $bitinflowAccounts->getAuthedUser();
```

```php
$result = $bitinflowAccounts->withToken('uvwxyz456789')->getAuthedUser();
```

#### Facade

```php
use Bitinflow\Accounts\Facades\BitinflowAccounts;

BitinflowAccounts::withClientId('abc123')->withToken('abcdef123456')->getAuthedUser();
```

## Documentation

### Oauth

```php
public function retrievingToken(string $grantType, array $attributes)
```

### PaymentIntents

```php
public function getPaymentIntent(string $id)
public function createPaymentIntent(array $parameters)
```

### SshKeys

```php
public function getSshKeysByUserId(int $id)
public function createSshKey(string $publicKey, string $name = NULL)
public function deleteSshKey(int $id)
```

### Users

```php
public function getAuthedUser()
public function createUser(array $parameters)
```

[**OAuth Scopes Enums**](https://github.com/ghostzero/bitinflow-accounts/blob/master/src/Enums/Scope.php)

## Development

#### Run Tests

```shell
composer test
```

```shell
BASE_URL=xxxx CLIENT_ID=xxxx CLIENT_KEY=yyyy CLIENT_ACCESS_TOKEN=zzzz composer test
```

#### Generate Documentation

```shell
composer docs
```

---

Join the bitinflow Discord!

[![Discord](https://discordapp.com/api/guilds/373468864098336768/embed.png?style=banner2)](https://discord.gg/2ZrCe2h)
