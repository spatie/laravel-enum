<?php

namespace Spatie\Enum\Laravel;

use BadMethodCallException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Enum\Laravel\Commands\MakeEnum;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumValueException;
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
        $this->registerRouteBindingMacro();
    }

    protected function registerRequestTransformMacro(): void
    {
        Request::mixin(new EnumRequest());
    }

    protected function registerRouteBindingMacro(): void
    {
        Router::macro('enum', function (string $key, string $class) {
            /** @var \Illuminate\Routing\Router $this */
            $this->bind($key, function ($value) use ($class) {
                try {
                    return forward_static_call(
                        [$class, 'make'],
                        $value
                    );
                } catch (BadMethodCallException $e) {
                    throw new InvalidEnumValueException($e->getMessage(), $e);
                }
            });
        });
    }

    public function bootValidationRules(): void
    {
        Validator::extend('enum', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new EnumRule($enum))->passes($attribute, $value);
        });
    }
}
