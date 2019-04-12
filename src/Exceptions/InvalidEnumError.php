<?php

namespace Spatie\Enum\Laravel\Exceptions;

use TypeError;

final class InvalidEnumError extends TypeError
{
    public static function make(
        string $class,
        string $field,
        string $expectedClass,
        string $actualClass
    ): InvalidEnumError {
        return new self("Expected {$class}::{$field} to be instance of {$expectedClass}, instead got {$actualClass}");
    }
}
