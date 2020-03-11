<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Enum\Laravel\Commands\MakeEnum;
use Spatie\Enum\Laravel\Http\EnumRequest;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Rules\EnumNameRule;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class EnumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/enum'),
        ], 'translation');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'enum');

        $this->bootValidationRules();
    }

    public function register()
    {
        $this->app->bind('command.make:enum', MakeEnum::class);

        $this->commands([
            'command.make:enum',
        ]);

        $this->registerRequestTransformMacro();
    }

    protected function registerRequestTransformMacro()
    {
        Request::mixin(new EnumRequest);
    }

    public function bootValidationRules(): void
    {
        Validator::extend('enum', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumRule($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_index', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumIndexRule($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_name', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumNameRule($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_value', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumValueRule($enum))->passes($attribute, $value);
        });
    }
}
