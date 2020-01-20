<?php

namespace Spatie\Enum\Laravel\Rules;

class EnumValueRule extends EnumRule
{
    protected $rule = 'enum_value';

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        return is_string($value) && $this->enum::isValidValue($value);
    }

    protected function getOtherValues(): array
    {
        return $this->enum::getValues();
    }
}
