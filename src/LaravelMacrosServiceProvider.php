<?php

namespace Webard\LaravelMacros;

use Illuminate\Support\ServiceProvider;
use Webard\LaravelMacros\Macros\BlueprintMacros;
use Webard\LaravelMacros\Macros\DatabaseMacros;
use Webard\LaravelMacros\Macros\RelationMacros;
use Webard\LaravelMacros\Macros\UserIdentitiesMacros;

class LaravelMacrosServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(BlueprintMacros::class);
        $this->app->register(DatabaseMacros::class);
        $this->app->register(RelationMacros::class);
        $this->app->register(UserIdentitiesMacros::class);
    }
}
