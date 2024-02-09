<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Jsonable;
use Spatie\Enum\Enum as BaseEnum;
use Spatie\Enum\Laravel\Casts\EnumCast;
use Spatie\Enum\Laravel\Casts\EnumCollectionCast;
use Spatie\Enum\Laravel\Rules\EnumRule;

abstract class Enum extends BaseEnum implements Jsonable, Castable
{
    public static function castUsing(array $arguments): CastsAttributes
    {
        if (in_array('collection', $arguments)) {
            return new EnumCollectionCast(static::class, ...$arguments);
        }

        return new EnumCast(static::class, ...$arguments);
    }

    public static function toRule(): EnumRule
    {
        return new EnumRule(static::class);
    }

    public static function fromLivewire($enum): self
    {
        if (is_a($enum, self::class)) {
            return $enum;
        }

        return self::from($enum['value']);
    }

    public function toLivewire(): mixed
    {
        return [
            'value' => $this->value,
            'label' => $this->label,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
