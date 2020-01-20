<?php

namespace Spatie\Enum\Laravel\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Enum\Enumerable;

class EnumRule implements Rule
{
    protected $rule = 'enum';

    /** @var Enumerable */
    protected $enum;

    /** @var string */
    protected $attribute;

    /** @var mixed */
    protected $value;

    public function __construct(string $enum)
    {
        if (! class_exists($enum) || ! isset(class_implements($enum)[Enumerable::class])) {
            throw new InvalidArgumentException("The given class {$enum} does not implement the Enumerable interface.");
        }

        $this->enum = $enum;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        try {
            $this->enum::make($value);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function message(): string
    {
        return Lang::get('enum::validation.'.$this->rule, [
            'attribute' => $this->attribute,
            'value' => $this->value,
            'enum' => $this->enum,
            'other' => implode(', ', $this->getDisplayableOtherValues()),
        ]);
    }

    protected function getDisplayableOtherValues(): array
    {
        return array_map(function ($value): string {
            return $this->getValueTranslation($value) ?? $value;
        }, $this->getOtherValues());
    }

    /**
     * @param string|int $value
     *
     * @return string|null
     */
    protected function getValueTranslation($value): ?string
    {
        return Arr::get(Lang::get('enum::validation.enums'), $this->enum.'.'.Str::slug($this->enum::make($value)->getName(), '_'));
    }

    protected function getOtherValues(): array
    {
        return $this->enum::getValues();
    }
}
