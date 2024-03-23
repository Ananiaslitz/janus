<?php

namespace Gateway\Laravel\Providers;

use Gateway\Core\GatewayService;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    public function boot(GatewayService $gatewayService)
    {
        $gatewayService->loadRoutesConfig(base_path('gateway.yml'));
        $routesConfig = $gatewayService->getRoutesConfig();

        foreach ($routesConfig as $service => $endpoints) {
            foreach ($endpoints as $endpointKey => $endpointConfig) {
                if ($endpointConfig['enabled'] ?? false) {
                    Route::{$endpointConfig['ms_request_verb']}(
                        $endpointConfig['frontend_url_path'], function () use ($endpointConfig) {

                        }
                    )->middleware($endpointConfig);
                }
            }
        }
    }

    public function register()
    {

    }
}