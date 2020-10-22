<?php

namespace Spatie\Enum\Laravel\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Spatie\Enum\Enum;
use Throwable;

class EnumRule implements Rule
{
    /** @var string|Enum */
    protected $enum;

    protected ?string $attribute = null;

    /** @var mixed */
    protected $value;

    public function __construct(string $enum)
    {
        $this->enum = $enum;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        try {
            $this->asEnum($value);

            return true;
        } catch (Throwable $ex) {
            return false;
        }
    }

    public function message(): string
    {
        return Lang::get('enum::validation.enum', [
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
        return Arr::get(
            Lang::get('enum::validation.enums'),
            $this->enum.'.'.Str::slug((string) $this->asEnum($value), '_')
        );
    }

    protected function getOtherValues(): array
    {
        return forward_static_call([$this->enum, 'toValues']);
    }

    /**
     * @param int|string|\Spatie\Enum\Enum $value
     *
     * @return Enum
     *
     * @throws \BadMethodCallException
     */
    protected function asEnum($value): Enum
    {
        return forward_static_call(
            [$this->enum, 'make'],
            $value
        );
    }
}
