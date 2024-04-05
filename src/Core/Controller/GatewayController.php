<?php

namespace Gateway\Core\Controller;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Endpoint;
use Gateway\Core\GatewayService;
use Gateway\Core\Middleware\MiddlewareProcessor;
use Gateway\Core\Mutator\MutatorManager;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Core\Transformers\TransformerManager;
use Symfony\Component\HttpFoundation\Response;

class GatewayController
{
    public function __construct(
        private HttpClientInterface $httpService,
        private GatewayService $gatewayService,
        private MiddlewareProcessor $middlewareProcessor,
        private TransformerManager $transformerManager,
        private MutatorManager $mutatorManager
    ) {
    }

    public function handle(RequestAdapterInterface $request)
    {
        $routeConfig = $this->gatewayService->getRouteConfigByPath($request->getPath());

        if (!empty($routeConfig['request']['mutators'])) {
            $request = $this->mutatorManager->mutate($request, $routeConfig['request']['mutators']);
        }

        $response = $this->processRequest($request, $routeConfig);

        if (!empty($routeConfig['response']['transformers'])) {
            $response = $this->transformerManager->transform($response, $routeConfig['transformers']);
        }

        return $response;
    }

    private function processRequest(RequestAdapterInterface $requestAdapter, $routeConfig)
    {
        $coreAction = function ($requestAdapter) use ($routeConfig) {
            $endpoint = new Endpoint(
                $routeConfig['ms_request_verb'],
                $routeConfig['ms_url_path'],
                $routeConfig['ms_headers'] ?? [],
                $requestAdapter->all(),
                $routeConfig['auth'] ?? null,
                $routeConfig['responsible_team'] ?? ''
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
