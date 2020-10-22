<?php

namespace Spatie\Enum\Laravel\Faker;

use Faker\Generator as FakerGenerator;
use Spatie\Enum\Faker\FakerEnumProvider as Base;

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
}
