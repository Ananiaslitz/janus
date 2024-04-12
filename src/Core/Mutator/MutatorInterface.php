<?php

namespace Gateway\Core\Mutator;

use Gateway\Core\Services\Http\Request\RequestAdapterInterface;

interface MutatorInterface
{
    public function mutate(RequestAdapterInterface $request, array $routeConfig);
}