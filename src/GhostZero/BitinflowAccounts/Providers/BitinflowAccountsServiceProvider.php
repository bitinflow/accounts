<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Providers;

use GhostZero\BitinflowAccounts\Auth\TokenGuard;
use GhostZero\BitinflowAccounts\Auth\UserProvider;
use GhostZero\BitinflowAccounts\BitinflowAccounts;
use GhostZero\BitinflowAccounts\Helpers\JwtParser;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class BitinflowAccountsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../../../config/bitinflow-accounts-api.php' => config_path('bitinflow-accounts-api.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../../../config/bitinflow-accounts-api.php', 'bitinflow-accounts-api'
        );
        $this->app->singleton(BitinflowAccounts::class, function () {
            return new BitinflowAccounts;
        });

        $this->registerGuard();
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [BitinflowAccounts::class];
    }

    /**
     * Register the token guard.
     *
     * @return void
     */
    protected function registerGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->extend('bitinflow-accounts', function ($app, $name, array $config) {
                return tap($this->makeGuard($config), function ($guard) {
                    $this->app->refresh('request', $guard, 'setRequest');
                });
            });
        });
    }

    /**
     * Make an instance of the token guard.
     *
     * @param  array  $config
     * @return RequestGuard
     */
    protected function makeGuard(array $config): RequestGuard
    {
        return new RequestGuard(function ($request) use ($config) {
            return (new TokenGuard(
                new UserProvider(Auth::createUserProvider($config['provider']), $config['provider']),
                $this->app->make('encrypter'),
                $this->app->make(JwtParser::class)
            ))->user($request);
        }, $this->app['request']);
    }
}
