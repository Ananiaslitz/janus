<?php

namespace Gateway\Hyperf\Http\Request;

use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Psr\Http\Message\RequestInterface;

class RequestAdapter implements RequestAdapterInterface
{
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getPath()
    {
        return $this->request->getUri()->getPath();
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    public function getBody()
    {
        return $this->request->getBody()->getContents();
    }
}