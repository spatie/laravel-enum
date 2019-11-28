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
class Post extends Model
{
    use HasEnums;

    protected $table = 'posts';

    protected $guarded = [];

    protected $enums = [
        'status' => StatusEnum::class,
        'invalid_enum' => Post::class,
        'extended_enum' => [
            'class' => StatusEnum::class,
        ],
        'nullable_enum' => [
            'class' => StatusEnum::class,
            'nullable' => true,
        ],
    ];

    public static function migrate()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('extended_enum')->nullable();
            $table->string('nullable_enum')->nullable();
            $table->timestamps();
        });
    }
}
