<?php

namespace LaravelArchivable\Tests;

use LaravelArchivable\Tests\TestClasses\ArchivableModel;
use LaravelArchivable\Tests\TestClasses\RegularModel;

class ArchivableTest extends TestCase
{
    /** @test */
    public function a_model_can_be_archived()
    {
        $model = ArchivableModel::factory()->create();

        $this->assertNull($model->fresh()->archived_at);

        $model->archive();

        $this->assertNotNull($model->fresh()->archived_at);
    }

    /** @test */
    public function a_model_can_be_unarchived()
    {
        $model = ArchivableModel::factory()->archived()->create();

        $this->assertNotNull($model->fresh()->archived_at);

        $model->unarchive();

        $this->assertNull($model->fresh()->archived_at);
    }

    /** @test */
    public function a_model_cannot_be_queried_normally_when_archived()
    {
        ArchivableModel::factory()->archived()->create();

        ArchivableModel::factory()->create();

        $this->assertDatabaseCount('archivable_models', 2);

        $this->assertCount(1, ArchivableModel::all());
    }

    /** @test */
    public function all_models_can_be_found_with_the_withArchived_scope()
    {
        ArchivableModel::factory()->archived()->create();
        ArchivableModel::factory()->create();

        $this->assertCount(2, ArchivableModel::withArchived()->get());
    }

    /** @test */
    public function only_archived_models_can_be_found_with_the_onlyArchived_scope()
    {
        ArchivableModel::factory()->archived()->create();
        ArchivableModel::factory()->create();

        $this->assertCount(1, ArchivableModel::onlyArchived()->get());
    }

    /** @test */
    public function models_without_the_archivable_trait_are_not_scoped()
    {
        RegularModel::factory()->create();

        $this->assertCount(1, RegularModel::all());
    }
}
