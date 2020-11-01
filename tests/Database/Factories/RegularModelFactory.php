<?php

namespace LaravelArchivable\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelArchivable\Tests\TestClasses\RegularModel;

class RegularModelFactory extends Factory
{
    protected $model = RegularModel::class;

    public function definition()
    {
        return [];
    }
}
