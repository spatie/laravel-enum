<?php

namespace Spatie\Enum\Laravel\Casts;

class EnumValueCollection extends EnumCollection
{
    public function __construct(string $enumClass, ...$options)
    {
        parent::__construct($enumClass, ...$options);
        $this->shouldStoreIndex = false;
    }
}
