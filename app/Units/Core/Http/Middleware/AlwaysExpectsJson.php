<?php


namespace Confee\Units\Core\Http\Middleware;


use Closure;

class AlwaysExpectsJson
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \Closure|Closure $next
     * @return mixed
     * @internal param null|string $guard
     */
    public function handle($request, Closure $next)
    {
        $request->headers->add(['Accept' => 'application/json']);
        return $next($request);
    }
}