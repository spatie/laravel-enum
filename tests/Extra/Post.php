<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @property \Spatie\Enum\Laravel\Tests\Extra\StatusEnum status
 * @property mixed invalid_enum
 * @property mixed nullable_enum
 * @property mixed array_of_enums
 * @property mixed nullable_array_of_enums
 *
 * @method static self create(array $properties)
 */
class Post extends Model
{
    protected $table = 'posts';

    protected $guarded = [];

    protected $casts = [
        'status' => StatusEnum::class,
        'nullable_enum' => StatusEnum::class.':nullable',
        'array_of_enums' => StatusEnum::class.':collection',
        'nullable_array_of_enums' => StatusEnum::class.':collection,nullable',
    ];

    public static function migrate()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('nullable_enum')->nullable();
            $table->json('array_of_enums')->nullable();
            $table->json('nullable_array_of_enums')->nullable();
            $table->timestamps();
        });
    }
}
