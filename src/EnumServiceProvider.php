<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Enum\Laravel\Commands\MakeEnum;
use Spatie\Enum\Laravel\Http\EnumRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EnumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/enum'),
            ], 'translation');
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'enum');

        $this->bootValidationRules();
    }

    public function register(): void
    {
        $this->app->bind('command.make:enum', MakeEnum::class);

        $this->commands([
            'command.make:enum',
        ]);

        $this->registerRequestTransformMacro();
    }

    protected function registerRequestTransformMacro(): void
    {
        Request::mixin(new EnumRequest);
    }

    public function bootValidationRules(): void
    {
        Validator::extend('enum', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumRule($enum))->passes($attribute, $value);
        });
    }
}
