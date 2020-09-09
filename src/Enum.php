<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Jsonable;
use Spatie\Enum\Enum as BaseEnum;
use Spatie\Enum\Laravel\Casts\EnumCast;
use Spatie\Enum\Laravel\Casts\EnumCollectionCast;

abstract class Enum extends BaseEnum implements Jsonable, Castable
{
    public static function castUsing(array $arguments): CastsAttributes
    {
        if (in_array('collection', $arguments)) {
            return new EnumCollectionCast(static::class, ...$arguments);
        }

        return new EnumCast(static::class, ...$arguments);
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
