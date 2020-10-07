<?php

namespace LaravelArchivable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

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
