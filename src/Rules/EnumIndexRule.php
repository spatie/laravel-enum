<?php

namespace Spatie\Enum\Laravel\Rules;

class EnumIndexRule extends EnumRule
{
    protected $rule = 'enum_index';

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        return is_int($value) && $this->enum::isValidIndex($value);
    }

    protected function getOtherValues(): array
    {
        return $this->enum::getIndices();
    }
}
