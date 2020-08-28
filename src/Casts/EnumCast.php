<?php

namespace Spatie\Enum\Laravel\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Enum\Enum;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField as NotNullableEnumFieldAlias;

class EnumCast implements CastsAttributes
{
    /** @var string|Enum */
    protected string $enumClass;

    protected bool $isNullable = false;

    public function __construct(string $enumClass, ...$options)
    {
        $this->enumClass = $enumClass;
        $this->isNullable = in_array('nullable', $options);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param int|string|null|mixed $value
     * @param array $attributes
     *
     * @return \Spatie\Enum\Enum|null
     *
     * @throws \BadMethodCallException
     * @throws \Spatie\Enum\Laravel\Exceptions\NotNullableEnumField
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return $this->asEnum($value);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param int|string|\Spatie\Enum\Enum|null|mixed $value
     * @param array $attributes
     *
     * @return int|string|null
     *
     * @throws \BadMethodCallException
     * @throws \Spatie\Enum\Laravel\Exceptions\NotNullableEnumField
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return $this->asEnum($value)->value;
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
