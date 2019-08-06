<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Spatie\Enum\Laravel\Commands\MakeEnum;
use Spatie\Enum\Laravel\Http\EnumRequest;

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

    protected function registerRequestTransformMacro()
    {
        Request::mixin(new EnumRequest);
    }
}
