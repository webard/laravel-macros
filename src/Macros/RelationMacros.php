<?php

declare(strict_types=1);

namespace Webard\LaravelMacros\Macros;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\ServiceProvider;

class RelationMacros extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {

    }

    public function boot(): void
    {
        BelongsToMany::macro('withUserIdentities', function (): BelongsToMany {
            /** @var BelongsToMany $this */
            return $this->with('userIdentities');
        });
    }
}
