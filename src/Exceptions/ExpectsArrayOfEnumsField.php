<?php

namespace Spatie\Enum\Laravel\Exceptions;

use InvalidArgumentException;

final class ExpectsArrayOfEnumsField extends InvalidArgumentException
{
    public static function make(string $field, string $model, string $enumClass): ExpectsArrayOfEnumsField
    {
        return new self("Field {$field} on model {$model} expects an array of {$enumClass}");
    }
}
