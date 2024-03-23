<?php

namespace Gateway\Core\Controller;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Endpoint;
use Gateway\Core\GatewayService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class GatewayController
{
    private $httpService;
    private $gatewayService;
    private $requestAdapter;

    public function __construct(
        HttpClientInterface $httpService,
        GatewayService $gatewayService
    ) {
        $this->httpService = $httpService;
        $this->gatewayService = $gatewayService;
    }

    public function handle(RequestAdapterInterface $request)
    {
        $routeConfig = $this->gatewayService->getRouteConfigByPath($request->getPath());

        $endpoint = new Endpoint(
            $routeConfig['ms_request_verb'],
            $routeConfig['ms_url_path'],
            $routeConfig['ms_headers'] ?? [],
            $request->all(),
            $routeConfig['auth'] ?? null
        );

        $httpResponse = $this->httpService->request($endpoint);

        return new Response($httpResponse->getBody()->getContents(), $httpResponse->getStatusCode(), $httpResponse->getHeaders());
    }
}
