<?php

namespace LaravelArchivable\Tests;

use LaravelArchivable\Tests\TestClasses\ArchivableModel;
use Illuminate\Support\Facades\Route;

class ArchivableRouteTest extends TestCase
{
    /** @test */
    public function fail_if_archived_model_is_found()
    {

        Route::get('/archivable-models/{archivableModel}', function (ArchivableModel $archivableModel) {
            return $archivableModel;
        })->middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class);

        $model = ArchivableModel::factory()->archived()->create();


        $this->get('/archivable-models/'.$model->getKey())
            ->assertNotFound();
    }


    /** @test */
    public function it_finds_archived_model_when_withArchived_is_used()
    {
        Route::get('/archivable-models/{archivableModel}', function (ArchivableModel $archivableModel) {
            return $archivableModel;
        })->middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class)->withArchived();

        $model = ArchivableModel::factory()->archived()->create();


        $this->get('/archivable-models/'.$model->getKey())
            ->assertOk();
    }
}
