<?php

namespace Gateway\Core\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GatewayController
{
    public function handle(RequestInterface $request, ResponseInterface $response)
    {
        dd($request);
    }
}