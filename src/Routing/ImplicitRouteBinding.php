<?php

namespace Spatie\Enum\Laravel\Routing;

use BadMethodCallException;
use Illuminate\Container\Container;
use Illuminate\Routing\Route;
use Illuminate\Support\Reflector;
use Illuminate\Support\Str;
use Spatie\Enum\Enum;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumValueException;

class ImplicitRouteBinding
{
    public static function resolveForRoute(Container $container, Route $route): void
    {
        $parameters = $route->parameters();

        foreach ($route->signatureParameters(Enum::class) as $parameter) {
            if (! $parameterName = static::getParameterName($parameter->getName(), $parameters)) {
                continue;
            }

            $parameterValue = $parameters[$parameterName];

            if ($parameterValue instanceof Enum) {
                continue;
            }

            try {
                $instance = $container->make(Reflector::getParameterClassName($parameter), ['value' => $parameterValue]);
            } catch (BadMethodCallException $e) {
                throw new InvalidEnumValueException($e->getMessage(), $e);
            }

            $route->setParameter($parameterName, $instance);
        }
    }

    protected static function getParameterName(string $name, array $parameters): ?string
    {
        if (array_key_exists($name, $parameters)) {
            return $name;
        }

        if (array_key_exists($snakedName = Str::snake($name), $parameters)) {
            return $snakedName;
        }
    }
}
