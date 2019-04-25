<?php

namespace Spatie\Enum\Laravel;

use InvalidArgumentException;
use Spatie\Enum\Enumerable;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumError;

trait HasEnums
{
    public function setAttribute($key, $value)
    {
        return $this->isEnumAttribute($key)
            ? $this->setEnumAttribute($key, $value)
            : parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        return $this->isEnumAttribute($key)
            ? $this->getEnumAttribute($key)
            : parent::getAttribute($key);
    }

    /**
     * @param string $key
     * @param int|string|Enumerable $value
     *
     * @return $this
     */
    protected function setEnumAttribute(string $key, $value)
    {
        $enumClass = $this->getEnumClass($key);

        if (is_string($value) || is_int($value)) {
            $value = $this->asEnum($enumClass, $value);
        }

        if (! is_a($value, $enumClass)) {
            throw InvalidEnumError::make(static::class, $key, $enumClass, get_class($value));
        }

        $this->attributes[$key] = $value->getValue();

        return $this;
    }

    protected function getEnumAttribute(string $key): Enumerable
    {
        return $this->asEnum(
            $this->getEnumClass($key),
            $this->attributes[$key] ?? null
        );
    }

    protected function isEnumAttribute(string $key): bool
    {
        return isset($this->enums[$key]);
    }

    protected function getEnumClass(string $key): string
    {
        $enumClass = $this->enums[$key];
        $enumInterface = Enumerable::class;

        if (! isset(class_implements($enumClass)[$enumInterface])) {
            throw new InvalidArgumentException("Expected {$enumClass} to implement {$enumInterface}");
        }

        return $enumClass;
    }

    /**
     * @param string $class
     * @param int|string $value
     *
     * @return Enumerable
     */
    protected function asEnum(string $class, $value): Enumerable
    {
        return forward_static_call(
            $class.'::make',
            $value
        );
    }
}
