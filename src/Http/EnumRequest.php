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
    public const REQUEST_ROUTE = 'route';
    public const REQUEST_QUERY = 'query';
    public const REQUEST_REQUEST = 'request';

    public function transformEnums(): Closure
    {
        return function (array $transformations): void {
            if (isset($transformations[EnumRequest::REQUEST_ROUTE])) {
                $route = $this->route();

                /** @var string|Enum $enumClass */
                foreach ($transformations[EnumRequest::REQUEST_ROUTE] as $key => $enumClass) {
                    if (! $route->hasParameter($key)) {
                        continue;
                    }

                    $route->setParameter(
                        $key,
                        $enumClass::make($route->parameter($key))
                    );
                }
            }

            if (isset($transformations[EnumRequest::REQUEST_QUERY])) {
                /** @var string|Enum $enumClass */
                foreach ($transformations[EnumRequest::REQUEST_QUERY] as $key => $enumClass) {
                    if (! $this->query->has($key)) {
                        continue;
                    }

                    $this->query->set(
                        $key,
                        $enumClass::make($this->query->get($key))
                    );
                }
            }

            if (isset($transformations[EnumRequest::REQUEST_REQUEST])) {
                /** @var string|Enum $enumClass */
                foreach ($transformations[EnumRequest::REQUEST_REQUEST] as $key => $enumClass) {
                    if (! $this->request->has($key)) {
                        continue;
                    }

                    $this->request->set(
                        $key,
                        $enumClass::make($this->request->get($key))
                    );
                }
            }

            /** @var string|Enum $enumClass */
            foreach (Arr::except($transformations, [EnumRequest::REQUEST_ROUTE, EnumRequest::REQUEST_QUERY, EnumRequest::REQUEST_REQUEST]) as $key => $enumClass) {
                if (! isset($this[$key])) {
                    continue;
                }

                $input = $this->all();

                Arr::set($input, $key, $enumClass::make($this[$key]));

                $this->replace($input);
            }
        };
    }
}
