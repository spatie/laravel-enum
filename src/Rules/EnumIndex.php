<?php

namespace Spatie\Enum\Laravel\Rules;

class EnumIndex extends Enum
{
    protected $rule = 'enum_index';

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        return $this->enum::isValidIndex($value);
    }
}
