<?php

namespace LaravelArchivable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;

class LaravelArchivableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureMacros();
    }

    /**
     * Configure the macros to be used.
     *
     * @return void
     */
    protected function configureMacros()
    {
        Blueprint::macro('archivedAt', function ($column = 'archived_at', $precision = 0) {
            return $this->timestamp($column, $precision)->nullable();
        });

        Blueprint::macro('dropArchivedAt', function ($column = 'archived_at') {
            return $this->dropColumn($column);
        });

        Route::macro('withArchived', function (bool $withArchived = true) {
            $this->withArchivedBindings = $withArchived;

            return $this;
        });

        Route::macro('allowsArchivedBindings', function () {
            return $this->withArchivedBindings ?? false;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
