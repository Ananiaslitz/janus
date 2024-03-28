<?php

namespace Gateway\Core\Services\Http;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Endpoint;
use GuzzleHttp\ClientInterface;
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

        if ($endpoint->auth) {
            if ($endpoint->auth['type'] == 'basic') {
                $options['auth'] = [$endpoint->auth['username'], $endpoint->auth['password']];
            } elseif ($endpoint->auth['type'] == 'bearer') {
                $options['headers']['Authorization'] = 'Bearer ' . $endpoint->auth['token'];
            }
        }

        try {
            return $this->client->request($endpoint->method, $endpoint->url, $options);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new \RuntimeException("HTTP request failed: " . $e->getMessage());
        }
    }
}
