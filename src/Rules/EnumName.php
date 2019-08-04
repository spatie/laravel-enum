<?php

namespace Spatie\Enum\Laravel\Rules;

class EnumName extends Enum
{
    protected $rule = 'enum_name';

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        return $this->enum::isValidName($value);
    }
}
