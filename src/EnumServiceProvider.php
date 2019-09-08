<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Enum\Laravel\Commands\MakeEnum;
use Spatie\Enum\Laravel\Rules\Enum;
use Spatie\Enum\Laravel\Rules\EnumIndex;
use Spatie\Enum\Laravel\Rules\EnumName;
use Spatie\Enum\Laravel\Rules\EnumValue;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class EnumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/enum'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'enum');

        $this->bootValidationRules();
    }

    public function register()
    {
        $this->app->bind('command.make:enum', MakeEnum::class);

        $this->commands([
            'command.make:enum',
        ]);
    }

    public function bootValidationRules(): void
    {
        Validator::extend('enum', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new Enum($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_index', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumIndex($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_name', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumName($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_value', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumValue($enum))->passes($attribute, $value);
        });
    }
}
