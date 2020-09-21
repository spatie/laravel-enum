<?php

namespace Spatie\Enum\Laravel\Casts;

class EnumCast extends Cast
{
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
}
