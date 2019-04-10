<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Database\Eloquent\Model;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property \Spatie\Enum\Laravel\Tests\Extra\TestModelStatus status
 *
 * @method static self create(array $properties)
 */
final class TestModel extends Model
{
    use HasEnums;

    protected $enums = [
        'status' => TestModelStatus::class,
    ];

    protected $guarded = [];

    protected $table = 'test';
}
