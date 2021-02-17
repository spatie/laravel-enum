<?php

namespace Spatie\Enum\Laravel\Tests;

use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

class FeatureExplicitRouteBindingTest extends TestCase
{
    /** @test */
    public function it_resolves_binding()
    {
        $router = $this->createRouter();

        $router->get('test/{status}', [
            'middleware' => SubstituteBindings::class,
            'uses' => function ($status) {
                return $status->toJson();
            },
        ]);
        $router->enum('status', StatusEnum::class);

        $request = Request::create('test/draft');
        $result = $router->dispatch($request)->getContent();

        $this->assertEquals($result, StatusEnum::draft()->toJson());
    }

    protected function createRouter()
    {
        $container = new Container();

        $router = new Router(new Dispatcher, $container);
        $container->singleton(Registrar::class, fn () => $router);

        return $router;
    }
}
