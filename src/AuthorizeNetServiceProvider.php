<?php

namespace JawadAhmed\LaravelAuthorizeNet;

use Illuminate\Support\ServiceProvider;

final class AuthorizeNetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/authorize-net.php', 'authorize-net');
        $this->app->singleton(AuthorizeNetGateway::class, fn () => new AuthorizeNetGateway(
            (string) config('authorize-net.login_id'),
            (string) config('authorize-net.transaction_key'),
            (string) config('authorize-net.mode', 'sandbox'),
        ));
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/authorize-net.php' => config_path('authorize-net.php')], 'authorize-net-config');
    }
}
