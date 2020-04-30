<?php

namespace Spatie\Enum\Laravel\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Spatie\Enum\Enumerable;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;

abstract class Enum implements CastsAttributes
{
    protected string $enumClass;
    protected bool $isNullable = false;
    protected bool $shouldStoreIndex = false;

    public function __construct(string $enumClass, ...$options)
    {
        $enumInterface = Enumerable::class;

        if (! array_key_exists($enumInterface, class_implements($enumClass))) {
            throw new InvalidArgumentException("Expected {$enumClass} to implement {$enumInterface}");
        }

        $this->enumClass = $enumClass;
        $this->isNullable = in_array('nullable', $options);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param int|string|null|mixed $value
     * @param array $attributes
     *
     * @return \Spatie\Enum\Enumerable|null
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
     * @param int|string|\Spatie\Enum\Enumerable|null|mixed $value
     * @param array $attributes
     *
     * @return int|string|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        $enum = $this->asEnum($value);

        return $this->shouldStoreIndex
            ? $enum->getIndex()
            : $enum->getValue();
    }

    /**
     * @param int|string|\Spatie\Enum\Enumerable $value
     *
     * @return \Spatie\Enum\Enumerable
     */
    protected function asEnum($value): Enumerable
    {
        if ($value instanceof Enumerable) {
            return $value;
        }

        return forward_static_call(
            $this->enumClass.'::make',
            $this->shouldStoreIndex && is_numeric($value)
                ? intval($value)
                : $value
        );
    }

    protected function handleNullValue(Model $model, string $key)
    {
        if (! $this->isNullable) {
            throw NotNullableEnumField::make($key, get_class($model));
        }
    }
}
