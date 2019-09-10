<?php

namespace Spatie\Enum\Laravel\Rules;

class EnumNameRule extends EnumRule
{
    protected $rule = 'enum_name';

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        return is_string($value) && $this->enum::isValidName($value);
    }

    protected function getOtherValues(): array
    {
        return $this->enum::getNames();
    }
}
