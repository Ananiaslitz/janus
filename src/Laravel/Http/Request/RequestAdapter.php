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

    public function setHeaders(array $headers): self
    {
        $this->request->headers->add($headers);
        return $this;
    }

    public function setBody(array $body): RequestAdapterInterface
    {
        $currentBody = $this->request->json()->all();
        $modifiedBody = array_merge($currentBody, $body);

        $this->request->json()->replace($modifiedBody);

        return $this;
    }

    public function getBody()
    {
        return $this->request->getContent();
    }

    public function all() {
        return $this->request->all();
    }
}
