<?php

namespace Spatie\Enum\Laravel\Casts;

class EnumIndex extends Enum
{
    public function __construct(string $enumClass, ...$options)
    {
        parent::__construct($enumClass, ...$options);
        $this->shouldStoreIndex = true;
    }
}
