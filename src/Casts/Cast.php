<?php

namespace Spatie\Enum\Laravel\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Enum\Enum;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField as NotNullableEnumFieldAlias;

abstract class Cast implements CastsAttributes
{
    /**
     * @var string
     * @psalm-var class-string<\Spatie\Enum\Enum>
     */
    protected string $enumClass;

    protected bool $isNullable = false;

    /**
     * Cast constructor.
     * @param string $enumClass
     * @psalm-param class-string<\Spatie\Enum\Enum> $enumClass
     * @param string[] ...$options
     */
    public function __construct(string $enumClass, ...$options)
    {
        $this->enumClass = $enumClass;

        $this->isNullable = in_array('nullable', $options);
    }

    /**
     * @param int|string|\Spatie\Enum\Enum $value
     *
     * @return \Spatie\Enum\Enum
     *
     * @throws \TypeError
     * @throws \BadMethodCallException
     *
     * @see \Spatie\Enum\Enum::make()
     */
    protected function asEnum($value): Enum
    {
        if ($value instanceof Enum) {
            return $value;
        }

        return forward_static_call(
            [$this->enumClass, 'make'],
            $value
        );
    }

    /**
     * @param Model $model
     * @param string $key
     *
     * @return null
     *
     * @throws \Spatie\Enum\Laravel\Exceptions\NotNullableEnumField
     */
    protected function handleNullValue(Model $model, string $key)
    {
        if ($this->isNullable) {
            return null;
        }

        throw NotNullableEnumFieldAlias::make($key, get_class($model));
    }
}
