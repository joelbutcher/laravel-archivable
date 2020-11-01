<?php

namespace LaravelArchivable\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelArchivable\Tests\TestClasses\ArchivableModel;

class ArchivableModelFactory extends Factory
{
    protected $model = ArchivableModel::class;

    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'archived_at' => now(),
            ];
        });
    }

    public function definition()
    {
        return [];
    }
}
