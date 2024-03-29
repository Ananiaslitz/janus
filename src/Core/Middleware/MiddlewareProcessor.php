<?php

namespace Gateway\Core\Middleware;

class MiddlewareProcessor
{
    private $middlewareInstances = [];

    public function process(array $middlewares, $request, callable $next)
    {
        $middlewareStack = array_reverse($middlewares);

        while ($middlewareClass = array_pop($middlewareStack)) {
            if (!isset($this->middlewareInstances[$middlewareClass])) {
                $this->middlewareInstances[$middlewareClass] = new $middlewareClass();
            }

            $middlewareInstance = $this->middlewareInstances[$middlewareClass];
            $request = $middlewareInstance->handle($request, function ($request) use ($next, &$middlewareStack) {
                if (empty($middlewareStack)) {
                    return $next($request);
                }
                return $request;
            });
        }

        return $request;
    }
}
