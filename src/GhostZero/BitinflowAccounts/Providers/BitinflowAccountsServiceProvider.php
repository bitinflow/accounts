<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Providers;

use GhostZero\BitinflowAccounts\BitinflowAccounts;
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
            dirname(__DIR__) . '/../config/bitinflow-accounts-api.php' => config_path('bitinflow-accounts-api.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../config/bitinflow-accounts-api.php', 'bitinflow-accounts-api'
        );
        $this->app->singleton(BitinflowAccounts::class, function () {
            return new BitinflowAccounts;
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [BitinflowAccounts::class];
    }
}