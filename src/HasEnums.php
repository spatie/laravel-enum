<?php

namespace Spatie\Enum\Laravel;

use Spatie\Enum\Enumerable;
use Spatie\Enum\Exceptions\InvalidValueException;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumError;

trait HasEnums
{
    /**
     * @param $key
     * @param \Spatie\Enum\Enum $enumObject
     *
     * @return mixed
     */
    public function setAttribute($key, $enumObject)
    {
        return $this->isEnumAttribute($key)
            ? $this->setEnumAttribute($key, $enumObject)
            : parent::setAttribute($key, $enumObject);
    }

    public function getAttribute($key)
    {
        return $this->isEnumAttribute($key)
            ? $this->getEnumAttribute($key)
            : parent::getAttribute($key);
    }

    protected function setEnumAttribute(string $key, $value)
    {
        $enumClass = $this->enums[$key];

        if (is_string($value) || is_int($value)) {
            $mappedValue = array_search($value, $enumClass::$map ?? []) ?: $value;

            $value = $this->asEnum($enumClass, $mappedValue);
        }

        if (! is_a($value, $enumClass)) {
            throw InvalidEnumError::make(static::class, $key, $enumClass, get_class($value));
        }

        $enumValue = $value->getValue();

        $this->attributes[$key] = $enumClass::$map[$enumValue] ?? $enumValue;

        return $this;
    }

    protected function getEnumAttribute(string $key): Enumerable
    {
        $enumClass = $this->enums[$key];

        $storedEnumValue = $this->attributes[$key] ?? null;

        try {
            $enumObject = $this->asEnum($enumClass, $storedEnumValue);
        } catch (InvalidValueException $exception) {
            $mappedEnumValue = array_search($storedEnumValue, $enumClass::$map ?? []);

            if (! $mappedEnumValue) {
                throw new InvalidValueException($storedEnumValue, $enumClass);
            }

            $enumObject = $this->asEnum($enumClass, $mappedEnumValue);
        }

        return $enumObject;
    }

    protected function isEnumAttribute(string $key): bool
    {
        return isset($this->enums[$key]);
    }

    protected function asEnum(string $class, $value): Enumerable
    {
        return forward_static_call_array(
            $class.'::make',
            [$value]
        );
    }
}
