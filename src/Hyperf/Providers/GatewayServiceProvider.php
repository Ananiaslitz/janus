<?php

namespace Gateway\Hyperf\Providers;

use Gateway\Core\Controller\GatewayController;
use Hyperf\Context\ApplicationContext;
use Hyperf\HttpServer\Router\Router;
use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\GatewayService;
use Gateway\Core\Services\Http\HttpService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Hyperf\Http\Request\RequestAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class GatewayServiceProvider
{
    /**
     * @var GatewayService
     */
    private GatewayService $gatewayService;

    public function __construct(GatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function boot()
    {
        $this->gatewayService->loadRoutesConfig(base_path('gateway.yml'));
        $routesConfig = $this->gatewayService->getRoutesConfig();

        foreach ($routesConfig as $service => $endpoints) {
            foreach ($endpoints as $endpointKey => $endpointConfig) {
                if ($endpointConfig['enabled'] ?? false) {
                    Router::addRoute(
                        [$endpointConfig['ms_request_verb']],
                        $endpointConfig['frontend_url_path'],
                        [GatewayController::class, 'handle']
                    );
                }
            }
        }
    }
}