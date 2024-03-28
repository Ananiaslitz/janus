<?php

namespace Gateway\Core\Middleware;

class MiddlewareProcessor
{
    public function process(array $middlewares, $request, callable $next)
    {
        $middlewareStack = array_reverse($middlewares);

        $processor = function ($request) use (&$processor, &$middlewareStack, $next) {
            if ($middleware = array_pop($middlewareStack)) {
                $middlewareInstance = new $middleware();
                return $middlewareInstance->handle($request, $processor);
            } else {
                return $next($request);
            }
        };

        return $processor($request);
    }
}
