<?php

namespace Spatie\Enum\Laravel;

use Spatie\Enum\Enumerable;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Enum\Laravel\Exceptions\NoSuchEnumField;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumError;

/**
 * @mixin Model
 */
trait HasEnums
{
    public function setAttribute($key, $value)
    {
        if (! $this->isEnumAttribute($key) || $this->isNullableEnum($key, $value)) {
            return parent::setAttribute($key, $value);
        }

        return $this->setEnumAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (! $this->isEnumAttribute($key) || $this->isNullableEnum($key, $value)) {
            return $value;
        }

        return $this->getEnumAttribute($key, $value);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $key
     * @param int|string|\Spatie\Enum\Enumerable|int[]|string[]|\Spatie\Enum\Enumerable[] $enumerables
     *
     * @see \Illuminate\Database\Eloquent\Builder::whereIn()
     */
    public function scopeWhereEnum(
        Builder $builder,
        string $key,
        $enumerables
    ): void {
        $this->buildEnumScope(
            $builder,
            'whereIn',
            $key,
            $enumerables
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $key
     * @param int|string|\Spatie\Enum\Enumerable|int[]|string[]|\Spatie\Enum\Enumerable[] $enumerables
     *
     * @see \Illuminate\Database\Eloquent\Builder::orWhereIn()
     */
    public function scopeOrWhereEnum(
        Builder $builder,
        string $key,
        $enumerables
    ): void {
        $this->buildEnumScope(
            $builder,
            'orWhereIn',
            $key,
            $enumerables
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $key
     * @param int|string|\Spatie\Enum\Enumerable|int[]|string[]|\Spatie\Enum\Enumerable[] $enumerables
     *
     * @see \Illuminate\Database\Eloquent\Builder::whereNotIn()
     */
    public function scopeWhereNotEnum(
        Builder $builder,
        string $key,
        $enumerables
    ): void {
        $this->buildEnumScope(
            $builder,
            'whereNotIn',
            $key,
            $enumerables
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $key
     * @param int|string|\Spatie\Enum\Enumerable|int[]|string[]|\Spatie\Enum\Enumerable[] $enumerables
     *
     * @see \Illuminate\Database\Eloquent\Builder::orWhereNotIn()
     */
    public function scopeOrWhereNotEnum(
        Builder $builder,
        string $key,
        $enumerables
    ): void {
        $this->buildEnumScope(
            $builder,
            'orWhereNotIn',
            $key,
            $enumerables
        );
    }

    /**
     * @param string $key
     * @param int|string|\Spatie\Enum\Enumerable $value
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

        $this->attributes[$key] = $this->getStoredValue($key, $value);

        return $this;
    }

    /**
     * @param string $key
     * @param \Spatie\Enum\Enumerable $enum
     *
     * @return int|string
     */
    protected function getStoredValue(string $key, Enumerable $enum)
    {
        return $this->hasCast($key, ['int', 'integer'])
            ? $enum->getIndex()
            : $enum->getValue();
    }

    /**
     * @param string $key
     * @param int|string $value
     *
     * @return \Spatie\Enum\Enumerable
     */
    protected function getEnumAttribute(string $key, $value): Enumerable
    {
        return $this->asEnum($this->getEnumClass($key), $value);
    }

    protected function isEnumAttribute(string $key): bool
    {
        return isset($this->enums[$key]);
    }

    protected function isNullableEnum(string $key, $value): bool
    {
        $enum = $this->enums[$key];

        if (! is_array($enum)) {
            return false;
        }

        if (! isset($enum['nullable'])) {
            return false;
        }

        return $enum['nullable'] && is_null($value);
    }

    protected function getEnumClass(string $key): string
    {
        $enumClass = $this->enums[$key];

        if (is_array($enumClass)) {
            $enumClass = $enumClass['class'] ?? null;
        }

        $enumInterface = Enumerable::class;

        if (is_null($enumClass)) {
            throw new InvalidArgumentException("Could not find Enumerable class in \$enums property {$key}");
        }

        $classImplementsEnumerable = class_implements($enumClass)[$enumInterface] ?? false;

        if (! $classImplementsEnumerable) {
            throw new InvalidArgumentException("Expected {$enumClass} to implement {$enumInterface}");
        }

        return $enumClass;
    }

    /**
     * @param string $class
     * @param int|string $value
     *
     * @return \Spatie\Enum\Enumerable
     */
    protected function asEnum(string $class, $value): Enumerable
    {
        if ($value instanceof Enumerable) {
            return $value;
        }

        return forward_static_call(
            $class.'::make',
            $value
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $method
     * @param string $key
     * @param int|string|\Spatie\Enum\Enumerable|int[]|string[]|\Spatie\Enum\Enumerable[] $enumerables
     */
    protected function buildEnumScope(
        Builder $builder,
        string $method,
        string $key,
        $enumerables
    ): void {
        if (! $this->isEnumAttribute($key)) {
            throw NoSuchEnumField::make($key, get_class($this));
        }

        $enumerables = is_array($enumerables) ? $enumerables : [$enumerables];

        $builder->$method(
            $key,
            array_map(function ($value) use ($key) {
                return $this->getStoredValue($key, $this->getEnumAttribute($key, $value));
            }, $enumerables)
        );
    }
}
