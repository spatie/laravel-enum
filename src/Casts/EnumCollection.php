<?php

namespace Spatie\Enum\Laravel\Casts;

use Illuminate\Support\Arr;
use Spatie\Enum\Enumerable;

abstract class EnumCollection extends Enum
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param int[]|string[]|null|mixed $value
     * @param array $attributes
     *
     * @return \Spatie\Enum\Enumerable[]|null
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
     * @param int[]|string[]|\Spatie\Enum\Enumerable[]|null|mixed $value
     * @param array $attributes
     *
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return json_encode(
            array_map(
                fn (Enumerable $enum) => $this->shouldStoreIndex ? $enum->getIndex() : $enum->getValue(),
                $this->asEnums(Arr::wrap($value))
            )
        );
    }

    /**
     * @param int[]|string[]|\Spatie\Enum\Enumerable[] $values
     *
     * @return \Spatie\Enum\Enumerable[]
     */
    protected function asEnums(array $values): array
    {
        return array_map([$this, 'asEnum'], $values);
    }
}
