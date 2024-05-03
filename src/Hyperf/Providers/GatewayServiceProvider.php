<?php

namespace Gateway\Hyperf\Providers;

use Gateway\Core\Controller\GatewayController;
use Hyperf\HttpServer\Router\Router;
use Gateway\Core\GatewayService;

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
        $this->gatewayService->loadRoutesConfig(file_get_contents(__DIR__ . 'gateway.yml'));
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