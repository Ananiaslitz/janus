<?php

namespace Gateway\Core;

class Endpoint
{
    public $method;
    public $url;
    public $headers;
    public $body;
    public $auth;

    public function __construct(string $method, string $url, array $headers = [], $body = null, $auth = null)
    {
        $this->method = $method;
        $this->url = $url;
        $this->headers = $headers;
        $this->body = $body;
        $this->auth = $auth;
    }
}