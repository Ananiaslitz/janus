<?php

namespace Gateway\Core\Controller;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Endpoint;
use Gateway\Core\GatewayService;
use Gateway\Core\Middleware\MiddlewareProcessor;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Core\Transformers\TransformerManager;
use Symfony\Component\HttpFoundation\Response;

class GatewayController
{
    private $httpService;
    private $gatewayService;
    private $middlewareProcessor;
    private $transformerManager;

    public function __construct(
        HttpClientInterface $httpService,
        GatewayService $gatewayService,
        MiddlewareProcessor $middlewareProcessor,
        TransformerManager $transformerManager
    ) {
        $this->httpService = $httpService;
        $this->gatewayService = $gatewayService;
        $this->middlewareProcessor = $middlewareProcessor;
        $this->transformerManager = $transformerManager;

    }

    public function handle(RequestAdapterInterface $request)
    {
        return $this->processRequest($request);
    }


    private function processRequest(RequestAdapterInterface $requestAdapter)
    {
        $routeConfig = $this->gatewayService->getRouteConfigByPath($requestAdapter->getPath());

        $coreAction = function ($requestAdapter) use ($routeConfig) {
            $endpoint = new Endpoint(
                $routeConfig['ms_request_verb'],
                $routeConfig['ms_url_path'],
                $routeConfig['ms_headers'] ?? [],
                $requestAdapter->all(),
                $routeConfig['auth'] ?? null
            );
            $httpResponse = $this->httpService->request($endpoint);

            if (!empty($routeConfig['transformers'])) {
                $httpResponse = $this->transformerManager->transform($httpResponse, $routeConfig['transformers']);
            }

            return $httpResponse;
        };

        $response = $this->middlewareProcessor->process($routeConfig['middlewares'] ?? [], $requestAdapter, $coreAction);

        return new Response($response->getBody()->getContents(), $response->getStatusCode(), $response->getHeaders());
    }
}
