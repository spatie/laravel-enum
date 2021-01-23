<?php

namespace Spatie\Enum\Laravel\Http\Middleware;

use Closure;
use Illuminate\Container\Container;
use Spatie\Enum\Laravel\Routing\ImplicitRouteBinding;

class SubstituteBindings
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Container\Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        ImplicitRouteBinding::resolveForRoute($this->container, $request->route());

        return $next($request);
    }
}
