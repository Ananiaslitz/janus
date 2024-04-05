<?php

namespace Gateway\Core;

class Endpoint
{
    public $method;
    public $url;
    public $headers;
    public $responsibleTeam;
    public $body;
    public $auth;

    public function __construct(
        string $method,
        string $url,
        array $headers = [],
        $body = null,
        $auth = null,
        string $responsibleTeam = ''
    ) {
        $this->method = $method;
        $this->url = $url;
        $this->headers = $headers;
        $this->body = $body;
        $this->auth = $auth;
        $this->responsibleTeam = $responsibleTeam;
    }
}