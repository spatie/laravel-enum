<?php

namespace Spatie\Enum\Laravel\Casts;

class EnumIndexCollection extends EnumCollection
{
    public function __construct(string $enumClass, ...$options)
    {
        parent::__construct($enumClass, ...$options);
        $this->shouldStoreIndex = true;
    }
}
