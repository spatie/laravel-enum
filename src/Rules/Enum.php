<?php

namespace Spatie\Enum\Laravel\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;
use Spatie\Enum\Enumerable;

class Enum implements Rule
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
        if (! isset(class_implements($enum)[Enumerable::class])) {
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
        return Lang::trans('enum::validation.'.$this->rule, [
            'attribute' => $this->attribute,
            'value' => $this->value,
            'enum' => $this->enum,
        ]);
    }
}
