<?php

namespace Gateway\Core\Services;

use Gateway\Core\Contracts\HttpClientInterface;
use Gateway\Core\Endpoint;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpService implements HttpClientInterface
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function request(Endpoint $endpoint): ResponseInterface
    {
        $options = ['headers' => $endpoint->headers];

        if ($endpoint->body !== null) {
            $options['json'] = $endpoint->body;
        }

        if ($endpoint->auth !== null) {
            // A implementação da autenticação dependerá do tipo de autenticação necessário
        }

        return $this->client->request($endpoint->method, $endpoint->url, $options);
    }

}