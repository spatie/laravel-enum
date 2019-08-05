<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Spatie\Enum\Laravel\Commands\MakeEnum;

class EnumServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('command.make:enum', MakeEnum::class);

        $this->commands([
            'command.make:enum',
        ]);

        $this->registerRequestTransformMacro();
    }

    public function registerRequestTransformMacro()
    {
        if (! Request::hasMacro('transformEnums')) {
            Request::macro('transformEnums', function (array $transformations) {
                /** @var Request $request */
                $request = $this;

                foreach ($transformations as $key => $enumClass) {
                    if (empty($request[$key])) {
                        continue;
                    }

                    $request[$key] = forward_static_call(
                        $enumClass.'::make',
                        $request[$key]
                    );
                }

                return $this;
            });
        }
    }
}
