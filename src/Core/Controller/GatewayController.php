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
    private HttpClientInterface $httpService;
    private GatewayService $gatewayService;
    private MiddlewareProcessor $middlewareProcessor;
    private TransformerManager $transformerManager;
    private $mutatorManater;

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
        $routeConfig = $this->gatewayService->getRouteConfigByPath($request->getPath());

        if (!empty($routeConfig['request']['decorators'])) {
            $request = $this->mutatorManater->mutate($request, $routeConfig);
        }

        $responsed = $this->processRequest($request, $routeConfig);

        if (!empty($routeConfig['transformers'])) {
            $responsed = $this->transformerManager->transform($responsed, $routeConfig['transformers']);
        }

        return $responsed;
    }

    private function processRequest(RequestAdapterInterface $requestAdapter, $routeConfig)
    {
        $coreAction = function ($requestAdapter) use ($routeConfig) {
            $endpoint = new Endpoint(
                $routeConfig['ms_request_verb'],
                $routeConfig['ms_url_path'],
                $routeConfig['ms_headers'] ?? [],
                $requestAdapter->all(),
                $routeConfig['auth'] ?? null
            );

            return $this->httpService->request($endpoint);
        };

        $response = $this->middlewareProcessor->process(
            $routeConfig['middlewares'] ?? [],
            $requestAdapter,
            $coreAction
        );

        return new Response($response->getBody()->getContents(), $response->getStatusCode(), $response->getHeaders());
    }
}
