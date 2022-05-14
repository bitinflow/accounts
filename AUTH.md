# Implementing Auth

This method should typically be called in the `boot` method of your `AuthServiceProvider` class:

```php
use Bitinflow\Accounts\BitinflowAccounts;
use Bitinflow\Accounts\Providers\BitinflowAccountsSsoUserProvider;
use Illuminate\Http\Request;

/**
 * Register any authentication / authorization services.
 *
 * @return void
 */
public function boot()
{
    Auth::provider('sso-users', function ($app, array $config) {
        return new BitinflowAccountsSsoUserProvider(
            $app->make(BitinflowAccounts::class),
            $app->make(Request::class),
            $config['model'],
            $config['fields'] ?? [],
            $config['assess_token_field'] ?? null
        );
    });
}
```

reference the guard in the `guards` configuration of your `auth.php` configuration file:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'bitinflow-accounts',
        'provider' => 'sso-users',
    ],
],
```

reference the provider in the `providers` configuration of your `auth.php` configuration file:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    
    'sso-users' => [
        'driver' => 'sso-users',
        'model' => App\Models\User::class,
        'fields' => ['first_name', 'last_name', 'email'],
        'assess_token_field' => 'sso_access_token',
    ],
],
```