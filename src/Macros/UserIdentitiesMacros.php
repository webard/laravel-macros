<?php

declare(strict_types=1);

namespace Webard\LaravelMacros\Macros;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class UserIdentitiesMacros extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {

    }

    public function boot(): void
    {
        if (! Blueprint::hasMacro('creator')) {
            Blueprint::macro('creator', function (bool|string $foreignKeyTable = 'users'): void {
                /** @var Blueprint $this */
                $this->unsignedBigInteger('created_by')
                    ->nullable()
                    ->default(null);

                if ($foreignKeyTable !== false && \is_string($foreignKeyTable)) {
                    $this->foreign('created_by')
                        ->references('id')
                        ->on($foreignKeyTable)
                        ->nullOnDelete()
                        ->cascadeOnUpdate();
                }
            });
        }

        if (! Blueprint::hasMacro('editor')) {
            Blueprint::macro('editor', function (bool|string $foreignKeyTable = 'users'): void {
                /** @var Blueprint $this */
                $this->unsignedBigInteger('updated_by')
                    ->nullable()
                    ->default(null);

                if ($foreignKeyTable !== false && \is_string($foreignKeyTable)) {

                    $this->foreign('updated_by')
                        ->references('id')
                        ->on($foreignKeyTable)
                        ->nullOnDelete()
                        ->cascadeOnUpdate();
                }
            });
        }

        if (! Blueprint::hasMacro('destroyer')) {
            Blueprint::macro('destroyer', function (bool|string $foreignKeyTable = 'users'): void {
                /** @var Blueprint $this */
                $this->unsignedBigInteger('deleted_by')
                    ->nullable()
                    ->default(null);

                if ($foreignKeyTable !== false && \is_string($foreignKeyTable)) {
                    $this->foreign('deleted_by')
                        ->references('id')
                        ->on($foreignKeyTable)
                        ->nullOnDelete()
                        ->cascadeOnUpdate();
                }
            });
        }

        if (! Blueprint::hasMacro('dropDestroyer')) {
            Blueprint::macro('dropDestroyer', function (): void {
                /** @var Blueprint $this */
                $this->dropForeign(['deleted_by']);
                $this->dropColumn('deleted_by');
            });
        }

        if (! Blueprint::hasMacro('dropEditor')) {
            Blueprint::macro('dropEditor', function (): void {
                /** @var Blueprint $this */
                $this->dropForeign(['updated_by']);
                $this->dropColumn('updated_by');
            });
        }

        if (! Blueprint::hasMacro('dropCreator')) {
            Blueprint::macro('dropCreator', function (): void {
                /** @var Blueprint $this */
                $this->dropForeign(['created_by']);
                $this->dropColumn('created_by');
            });
        }

        if (! Blueprint::hasMacro('dropUserIdentities')) {
            Blueprint::macro('dropUserIdentities', function (): void {
                /** @var Blueprint $this */
                $this->dropCreator();
                $this->dropEditor();
                $this->dropDestroyer();
            });
        }

        if (! Blueprint::hasMacro('userIdentities')) {
            /**
             * Add created_by, updated_by and optionally deleted_by columns with optional foreign keys to identity
             * table.
             *
             * @param  bool  $withDestroyer
             * @param  bool|string  $foreignKeyTable
             *
             * @instantiated
             */
            Blueprint::macro('userIdentities', function (
                bool $withDestroyer = false,
                bool|string $foreignKeyTable = 'users'
            ): void {
                /** @var Blueprint $this */
                $this->creator($foreignKeyTable);

                $this->editor($foreignKeyTable);

                if ($withDestroyer === true) {
                    $this->destroyer($foreignKeyTable);
                }
            });
        }
    }
}
