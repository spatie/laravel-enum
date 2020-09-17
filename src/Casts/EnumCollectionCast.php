<?php

namespace Spatie\Enum\Laravel\Casts;

use Illuminate\Support\Arr;

class EnumCollectionCast extends Cast
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param string|null|mixed $value
     * @param array $attributes
     *
     * @return \Spatie\Enum\Enum[]|null
     *
     * @throws \BadMethodCallException
     * @throws \Spatie\Enum\Laravel\Exceptions\NotNullableEnumField
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return $this->asEnums(
            Arr::wrap(json_decode($value, true))
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param int[]|string[]|\Spatie\Enum\Enum[]|null|mixed $value
     * @param array $attributes
     *
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return json_encode($this->asEnums(Arr::wrap($value)));
    }

    /**
     * @param int[]|string[]|\Spatie\Enum\Enum[] $values
     *
     * @return \Spatie\Enum\Enum[]
     *
     * @throws \TypeError
     * @throws \BadMethodCallException
     */
    protected function asEnums(array $values): array
    {
        return array_map([$this, 'asEnum'], $values);
    }
}
