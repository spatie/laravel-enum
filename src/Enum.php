<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Jsonable;
use Spatie\Enum\Enum as BaseEnum;
use Spatie\Enum\Laravel\Casts\EnumCast;

abstract class Enum extends BaseEnum implements Jsonable, Castable
{
    public static function castUsing(): CastsAttributes
    {
        return new EnumCast(static::class);
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
