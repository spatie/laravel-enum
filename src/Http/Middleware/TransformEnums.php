<?php

namespace Spatie\Enum\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransformEnums
{
    /** @var array */
    protected $transformations = [];

    public function __construct(array $transformations = [])
    {
        $this->transformations = $transformations;
    }

    public function handle(Request $request, Closure $next)
    {
        $request->transformEnums($this->transformations);

        return $next($request);
    }
}
