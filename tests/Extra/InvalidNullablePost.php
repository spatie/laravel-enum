<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property \Spatie\Enum\Laravel\Tests\Extra\StatusEnum status
 * @property mixed invalid_enum
 * @property mixed nullable_enum
 *
 * @method static self create(array $properties)
 */
class InvalidNullablePost extends Model
{
    use HasEnums;

    protected $table = 'invalid_nullable_posts';

    protected $guarded = [];

    protected $enums = [
        'status' => StatusEnum::class,
        'invalid_nullable_enum' => StatusEnum::class.':nulllllable',
    ];

    public static function migrate()
    {
        Schema::create('invalid_nullable_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('invalid_nullable_enum')->nullable();
            $table->timestamps();
        });
    }
}
