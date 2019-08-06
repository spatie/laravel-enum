<?php

namespace Spatie\Enum\Laravel\Http;

use Closure;

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
        return function (array $transformations) {
            if (isset($transformations[EnumRequest::REQUEST_ROUTE])) {
                $route = $this->route();

                foreach ($transformations[EnumRequest::REQUEST_ROUTE] as $key => $enumClass) {
                    if (! $route->hasParameter($key)) {
                        continue;
                    }

                    $route->setParameter(
                        $key,
                        forward_static_call(
                            $enumClass.'::make',
                            $route->parameter($key)
                        )
                    );
                }

                unset($transformations[EnumRequest::REQUEST_ROUTE]);
            }

            if (isset($transformations[EnumRequest::REQUEST_QUERY])) {
                foreach ($transformations[EnumRequest::REQUEST_QUERY] as $key => $enumClass) {
                    if (! $this->query->has($key)) {
                        continue;
                    }

                    $this->query->set(
                        $key,
                        forward_static_call(
                            $enumClass.'::make',
                            $this->query->get($key)
                        )
                    );
                }

                unset($transformations[EnumRequest::REQUEST_QUERY]);
            }

            if (isset($transformations[EnumRequest::REQUEST_REQUEST])) {
                foreach ($transformations[EnumRequest::REQUEST_REQUEST] as $key => $enumClass) {
                    if (! $this->request->has($key)) {
                        continue;
                    }

                    $this->request->set(
                        $key,
                        forward_static_call(
                            $enumClass.'::make',
                            $this->request->get($key)
                        )
                    );
                }

                unset($transformations[EnumRequest::REQUEST_REQUEST]);
            }

            foreach ($transformations as $key => $enumClass) {
                if (! isset($this[$key])) {
                    continue;
                }

                $this[$key] = forward_static_call(
                    $enumClass.'::make',
                    $this[$key]
                );
            }
        };
    }
}
