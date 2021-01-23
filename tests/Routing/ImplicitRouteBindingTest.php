<?php

namespace Spatie\Enum\Laravel\Tests\Routing;

use Illuminate\Container\Container;
use Illuminate\Routing\Route;
use Spatie\Enum\Laravel\Routing\ImplicitRouteBinding;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

class ImplicitRouteBindingTest extends TestCase
{
    /** @test */
    public function it_can_resolve_the_implicit_route_transformations_for_the_given_route()
    {
        $action = ['uses' => function (StatusEnum $status) {
            return $status;
        }];

        $route = new Route('GET', '/test', $action);
        $route->parameters = ['status' => StatusEnum::draft()->value];
        $route->prepareForSerialization();

        $container = Container::getInstance();

        ImplicitRouteBinding::resolveForRoute($container, $route);

        $this->assertTrue(StatusEnum::draft()->equals($route->parameter('status')));
    }
}
