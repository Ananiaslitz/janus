<?php

namespace Gateway\Core\Contracts\Http\Request;

use Gateway\Core\Services\Http\Request\RequestAdapterInterface;

interface RequestDecoratorInterface
{
    public function supports(RequestAdapterInterface $request, array $routeConfig): bool;

    public function decorate(RequestAdapterInterface $request, array $routeConfig): RequestAdapterInterface;
}