<?php

namespace Spatie\Enum\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransformEnums
{
    /** @var array */
    protected $enumRules = [];

    public function __construct(array $enumRules = [])
    {
        $this->enumRules = $enumRules;
    }

    public function handle(Request $request, Closure $next)
    {
        $request->transformEnums($this->enumRules);

        return $next($request);
    }
}
