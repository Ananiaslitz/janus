<?php

namespace Gateway\Hyperf\Http\Request;

use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
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

    public function setHeaders(array $headers): RequestAdapterInterface
    {
        foreach ($headers as $name => $value) {
            $this->request = $this->request->withHeader($name, $value);
        }

        return $this;
    }


    public function setBody(array $body): RequestAdapterInterface
    {
        $currentBody = (string) $this->request->getBody();
        $decodedBody = json_decode($currentBody, true) ?? [];

        $modifiedBody = array_merge($decodedBody, $body);

        $encodedBody = json_encode($modifiedBody);

        $streamFactory = new Psr17Factory();
        $stream = $streamFactory->createStream($encodedBody);

        $this->request = $this->request->withBody($stream);

        return $this;
    }
}