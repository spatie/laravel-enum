<?php

namespace Spatie\Enum\Laravel\Casts;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Spatie\Enum\Enum;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;
use TypeError;

class EnumCollectionCast extends Cast
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_COMMA = 'comma';

    protected string $format = 'json';

    public function __construct(string $enumClass, ...$options)
    {
        parent::__construct($enumClass, ...$options);

        $this->format = in_array(self::FORMAT_COMMA, $options)
            ? self::FORMAT_COMMA
            : self::FORMAT_JSON;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param string|null|mixed $value
     * @param array $attributes
     *
     * @return Enum[]|null
     *
     * @throws BadMethodCallException
     * @throws NotNullableEnumField
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return $this->asEnums($this->fromDatabase($value));
    }

    /**
     * @param Model $model
     * @param string $key
     * @param int[]|string[]|Enum[]|null|mixed $value
     * @param array $attributes
     *
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->handleNullValue($model, $key);
        }

        return $this->toDatabase($this->asEnums(Arr::wrap($value)));
    }

    /**
     * @param int[]|string[]|Enum[] $values
     *
     * @return Enum[]
     *
     * @throws TypeError
     * @throws BadMethodCallException
     */
    protected function asEnums(array $values): array
    {
        return array_map([$this, 'asEnum'], $values);
    }

    /**
     * @param Enum[] $enums
     *
     * @return string
     */
    protected function toDatabase(array $enums): string
    {
        if ($this->format === self::FORMAT_COMMA) {
            return implode(',', $enums);
        }

        return json_encode($enums);
    }

    /**
     * @param string $value
     *
     * @return string[]
     */
    protected function fromDatabase(string $value): array
    {
        if ($this->format === self::FORMAT_COMMA) {
            if (empty($value)) {
                return [];
            }

            return array_map('trim', explode(',', $value));
        }

        return Arr::wrap(json_decode($value, true));
    }
}
