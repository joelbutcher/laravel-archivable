<?php

namespace LaravelArchivable\Tests\TestClasses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelArchivable\Archivable;

class ArchivableModel extends Model
{
    use Archivable;
    use HasFactory;
}
