<?php

namespace Spatie\Enum\Laravel\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Spatie\Enum\Enumerable;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;

class EnumIndexCollection extends EnumCollection
{
    public function __construct(string $enumClass, ...$options)
    {
        parent::__construct($enumClass, ...$options);
        $this->shouldStoreIndex = true;
    }
}
