<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Spatie\Enum\Laravel\HasEnums;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

/**
 * @property \Spatie\Enum\Laravel\Tests\Extra\StatusEnum status
 * @property mixed invalid_nullable_enum
 *
 * @method static self create(array $properties)
 */
class InvalidNullablePost extends Model
{
    use HasEnums;

    protected $table = 'invalid_nullable_posts';

    protected $guarded = [];

    protected $enums = [
        'invalid_nullable_enum' => [
            'classss' => StatusEnum::class,
            'nullable' => true,
        ],
    ];

    public static function migrate()
    {
        Schema::create('invalid_nullable_enum', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invalid_nullable_enum');
            $table->timestamps();
        });
    }
}
