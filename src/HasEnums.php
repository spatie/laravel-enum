<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Enum\Enumerable;
use Spatie\Enum\Laravel\Exceptions\NoSuchEnumField;

/**
 * @mixin Model
 */
trait HasEnums
{
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

    protected function isEnumAttribute(string $key): bool
    {
        return isset($this->enums[$key]);
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
            throw NoSuchEnumField::make($key, static::class);
        }

        $enumerables = is_array($enumerables) ? $enumerables : [$enumerables];

        $builder->$method(
            $key,
            array_map(function ($value) use ($key) {
                return $this->getStoredValue($key, $this->asEnum($key, $value));
            }, $enumerables)
        );
    }
}
