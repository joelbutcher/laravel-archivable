<?php


namespace LaravelArchivable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaravelArchivable\LaravelArchivableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;


class TestCase extends Orchestra
{

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            function(string $modelName){
                return 'LaravelArchivable\\Tests\\Database\\Factories\\' . class_basename($modelName) . 'Factory';
            }
        );
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        Schema::create('archivable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->timestamp('archived_at', 0)->nullable();
        });

        Schema::create('regular_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelArchivableServiceProvider::class,
        ];
    }
}
