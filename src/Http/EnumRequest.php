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
    public function transformEnums(): Closure
    {
        return function (array $transformations) {
            if (isset($transformations['route'])) {
                $route = $this->route();

                foreach ($transformations['route'] as $key => $enumClass) {
                    if (!$route->hasParameter($key)) {
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
            }

            if (isset($transformations['query'])) {
                foreach ($transformations['query'] as $key => $enumClass) {
                    if (!$this->query->has($key)) {
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
            }

            if (isset($transformations['request'])) {
                foreach ($transformations['request'] as $key => $enumClass) {
                    if (!$this->request->has($key)) {
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
            }
        };
    }
}
