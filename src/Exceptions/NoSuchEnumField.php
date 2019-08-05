<?php

namespace Spatie\Enum\Laravel\Exceptions;

use InvalidArgumentException;

final class NoSuchEnumField extends InvalidArgumentException
{
    public static function make(string $field, string $model): NoSuchEnumField
    {
        return new self("No enum field {$field} registered on model {$model}");
    }
}
