<?php

namespace Gateway\Laravel\Http\Request;

use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Illuminate\Http\Request;

class RequestAdapter implements RequestAdapterInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPath()
    {
        return $this->request->path();
    }

    public function getMethod()
    {
        return $this->request->method();
    }

    public function getHeaders()
    {
        return $this->request->headers->all();
    }

    public function getBody()
    {
        return $this->request->getContent();
    }

    public function all() {
        return $this->request->all();
    }
}
