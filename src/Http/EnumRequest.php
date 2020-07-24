<?php

namespace Spatie\Enum\Laravel\Http;

use Closure;
use Illuminate\Support\Arr;
use Spatie\Enum\Enum;

/**
 * @internal This class is only used to get mixed into \Illuminate\Http\Request
 *
 * @mixin \Illuminate\Http\Request
 */
final class EnumRequest
{
    const REQUEST_ROUTE = 'route';
    const REQUEST_QUERY = 'query';
    const REQUEST_REQUEST = 'request';

    public function transformEnums(): Closure
    {
        return function (array $transformations): void {
            if (isset($transformations[self::REQUEST_ROUTE])) {
                $route = $this->route();

                /** @var string|Enum $enumClass */
                foreach ($transformations[self::REQUEST_ROUTE] as $key => $enumClass) {
                    if (! $route->hasParameter($key)) {
                        continue;
                    }

                    $route->setParameter(
                        $key,
                        forward_static_call(
                            [$enumClass, 'make'],
                            $route->parameter($key)
                        )
                    );
                }
            }

            if (isset($transformations[self::REQUEST_QUERY])) {
                /** @var string|Enum $enumClass */
                foreach ($transformations[self::REQUEST_QUERY] as $key => $enumClass) {
                    if (! $this->query->has($key)) {
                        continue;
                    }

                    $this->query->set(
                        $key,
                        forward_static_call(
                            [$enumClass, 'make'],
                            $this->query->get($key)
                        )
                    );
                }
            }

            if (isset($transformations[self::REQUEST_REQUEST])) {
                /** @var string|Enum $enumClass */
                foreach ($transformations[self::REQUEST_REQUEST] as $key => $enumClass) {
                    if (! $this->request->has($key)) {
                        continue;
                    }

                    $this->request->set(
                        $key,
                        forward_static_call(
                            [$enumClass, 'make'],
                            $this->request->get($key)
                        )
                    );
                }
            }

            /** @var string|Enum $enumClass */
            foreach (Arr::except($transformations, [self::REQUEST_ROUTE, self::REQUEST_QUERY, self::REQUEST_REQUEST]) as $key => $enumClass) {
                if (! isset($this[$key])) {
                    continue;
                }

                $this[$key] = forward_static_call(
                    [$enumClass, 'make'],
                    $this[$key]
                );
            }
        };
    }
}
