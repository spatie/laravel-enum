<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property \Spatie\Enum\Laravel\Tests\Extra\StatusEnum status
 *
 * @method static self create(array $properties)
 */
class Post extends Model
{
    use HasEnums;

    protected $guarded = [];

    protected $enums = [
        'status' => StatusEnum::class,
    ];

    public static function migrate()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->timestamps();
        });
    }
}
