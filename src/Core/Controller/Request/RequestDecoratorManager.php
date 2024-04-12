<?php

namespace Gateway\Core\Controller\Request;

use Gateway\Core\Contracts\Http\Request\RequestDecoratorInterface;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;

class RequestDecoratorManager
{
    /**
     * @var RequestDecoratorInterface[]
     */
    private array $decorators;

    public function __construct(array $decorators = [])
    {
        $this->decorators = $decorators;
    }

    public function addDecorator(RequestDecoratorInterface $decorator)
    {
        $this->decorators[] = $decorator;
    }

    public function decorate(RequestAdapterInterface $request, array $routeConfig): RequestAdapterInterface
    {
        if (empty($this->decorators) || empty($routeConfig['request']['decorators'])) {
            return $request;
        }

        foreach ($this->decorators as $decorator) {
            if ($decorator->supports($request, $routeConfig)) {
                $request = $decorator->decorate($request, $routeConfig);
            }
        }

        return $request;
    }
}
