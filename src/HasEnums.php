<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Enum\Enumerable;
use Spatie\Enum\Exceptions\InvalidValueException;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumError;
use Spatie\Enum\Laravel\Exceptions\NoSuchEnumField;

trait HasEnums
{
    public function scopeWhereEnum(
        Builder $builder,
        string $key,
        $enumerables
    ): void {
        if (! $this->isEnumAttribute($key)) {
            throw NoSuchEnumField::make($key, get_class($this));
        }

        $builder->whereIn(
            $key,
            $this->resolveEnumerables($key, $enumerables)
        );
    }

    public function scopeWhereNotEnum(
        Builder $builder,
        string $key,
        $enumerables
    ): void {
        if (! $this->isEnumAttribute($key)) {
            throw NoSuchEnumField::make($key, get_class($this));
        }

        $builder->whereNotIn(
            $key,
            $this->resolveEnumerables($key, $enumerables)
        );
    }

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
        if ($value instanceof Enumerable) {
            return $value;
        }

        return forward_static_call_array(
            $class . '::make',
            [$value]
        );
    }

    private function resolveEnumerables($key, $enumerables): array
    {
        $enumClass = $this->enums[$key];

        $enumerables = is_array($enumerables) ? $enumerables : [$enumerables];

        return array_map(function ($value) use ($enumClass): string {
            try {
                $enumValue = $this->asEnum($enumClass, $value)->getValue();
            } catch (InvalidValueException $invalidValueException) {
                $enumValue = $value;
            }

            if (isset($enumClass::$map[$enumValue])) {
                return $enumClass::$map[$enumValue];
            }

            return $enumValue;
        }, $enumerables);
    }
}
