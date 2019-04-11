<?php

namespace Spatie\Enum\Laravel;

use Spatie\Enum\Enum;
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
        return isset($this->enums[$key])
            ? $this->setEnumAttribute($key, $enumObject)
            : parent::setAttribute($key, $enumObject);
    }

    public function getAttribute($key)
    {
        return isset($this->enums[$key])
            ? $this->getEnumAttribute($key)
            : parent::getAttribute($key);
    }

    protected function setEnumAttribute(string $key, object $enum)
    {
        $enumClass = $this->enums[$key];

        if (! is_a($enum, $enumClass)) {
            throw InvalidEnumError::make(
                static::class,
                $key,
                $enumClass,
                get_class($enum)
            );
        }

        $enumValue = $enum->getValue();

        $this->attributes[$key] = $enumClass::$map[$enumValue] ?? $enumValue;

        return $this;
    }

    protected function getEnumAttribute(string $key): Enum
    {
        $enumClass = $this->enums[$key];

        $storedEnumValue = $this->attributes[$key] ?? null;

        try {
            $enumObject = forward_static_call_array(
                $enumClass . '::make',
                [$storedEnumValue]
            );
        } catch (InvalidValueException $exception) {
            $mappedEnumValue = array_search($storedEnumValue, $enumClass::$map ?? []);

            if (! $mappedEnumValue) {
                throw new InvalidValueException($storedEnumValue, $enumClass);
            }

            $enumObject = forward_static_call_array(
                $enumClass . '::make',
                [$mappedEnumValue]
            );
        }

        return $enumObject;
    }
}
