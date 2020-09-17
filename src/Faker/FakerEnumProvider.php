<?php

namespace Spatie\Enum\Laravel\Faker;

use Faker\Generator as FakerGenerator;
use Faker\Provider\Base;
use InvalidArgumentException;
use Spatie\Enum\Enum;

class FakerEnumProvider extends Base
{
    public static function register(): void
    {
        /** @var FakerGenerator $faker */
        $faker = app(FakerGenerator::class);

        $providers = array_map('get_class', $faker->getProviders());

        if (in_array(static::class, $providers)) {
            return;
        }

        $faker->addProvider(new static($faker));
    }

    /**
     * A random instance of the enum you pass in.
     *
     * @param string $enum
     *
     * @return Enum
     */
    public function randomEnum(string $enum): Enum
    {
        if (! is_subclass_of($enum, Enum::class)) {
            throw new InvalidArgumentException(sprintf(
                'You have to pass the FQCN of a "%s" class but you passed "%s".',
                Enum::class,
                $enum
            ));
        }

        return $enum::make(static::randomElement(array_keys($enum::toArray())));
    }

    /**
     * A random value of the enum you pass in.
     *
     * @param string $enum
     *
     * @return string|int
     */
    public function randomEnumValue(string $enum)
    {
        if (! is_subclass_of($enum, Enum::class)) {
            throw new InvalidArgumentException(sprintf(
                'You have to pass the FQCN of a "%s" class but you passed "%s".',
                Enum::class,
                $enum
            ));
        }

        return static::randomElement(array_keys($enum::toArray()));
    }

    /**
     * A random label of the enum you pass in.
     *
     * @param string $enum
     *
     * @return string
     */
    public function randomEnumLabel(string $enum): string
    {
        if (! is_subclass_of($enum, Enum::class)) {
            throw new InvalidArgumentException(sprintf(
                'You have to pass the FQCN of a "%s" class but you passed "%s".',
                Enum::class,
                $enum
            ));
        }

        return static::randomElement(array_values($enum::toArray()));
    }
}
