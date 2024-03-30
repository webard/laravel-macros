<?php

declare(strict_types=1);

namespace Webard\LaravelMacros\Macros;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\ServiceProvider;

class BlueprintMacros extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {

    }

    public function boot(): void
    {

        if (! Blueprint::hasMacro('slug')) {
            /**
             * @param  string  $column  Column name
             *
             * @instantiated
             */
            Blueprint::macro('slug', function (string $column = 'slug'): ColumnDefinition {
                /** @var Blueprint $this */

                return $this->string($column)
                    ->unique()
                    ->comment('Human readable identifier. DO NOT MODIFY! Treat as regular ID.');
            });
        }

        if (! Blueprint::hasMacro('phone')) {
            /**
             * Alias for varchar(15), representing phone number.
             *
             * @param  string  $column  Column name
             *
             * @instantiated
             */
            Blueprint::macro('phone', function (string $column): ColumnDefinition {
                /** @var Blueprint $this */

                return $this->string($column, 20)
                    ->comment('Phone number in E.164 format.');
            });
        }

        if (! Blueprint::hasMacro('money')) {
            /**
             * Alias for decimal(19,6), representing money value.
             *
             * @param  string  $column  Column name
             *
             * @instantiated
             */
            Blueprint::macro('money', function (string $column): ColumnDefinition {
                /** @var Blueprint $this */

                return $this->decimal($column, 19, 6);
            });
        }

        if (! Blueprint::hasMacro('timestampsTzWithDefaults')) {
            /**
             * Add created_at, updated_at and optionally deleted_at columns with default value for created_at when
             * inserting and updated_at when inserting and updating row.
             *
             * @param  bool  $withSoftDeletes
             *
             * @instantiated
             */
            Blueprint::macro('timestampsTzWithDefaults', function (bool $withSoftDeletes = false): void {
                /** @var Blueprint $this */
                $this->timestampTz('created_at')
                    ->useCurrent();

                $this->timestampTz('updated_at')
                    ->useCurrent()
                    ->useCurrentOnUpdate();
                if ($withSoftDeletes === true) {
                    $this->softDeletesTz();
                }
            });
        }
    }
}
