<?php

namespace Gateway\Core\Contracts;

use Gateway\Core\Endpoint;
use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function request(Endpoint $endpoint): ResponseInterface;
}