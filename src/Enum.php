<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Contracts\Database\Eloquent\Castable;
use ReflectionClass;
use Spatie\Enum\Enum as BaseEnum;

abstract class Enum extends BaseEnum
{
    protected static function resolveFromStaticMethods(ReflectionClass $reflection): array
    {
        $values = parent::resolveFromStaticMethods($reflection);

        if (array_key_exists(Castable::class, class_implements(static::class))) {
            unset($values[array_search('castUsing', $values)]);
        }

        return $values;
    }
}
