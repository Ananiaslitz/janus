<?php

namespace Gateway\Laravel\Providers;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Controller\GatewayController;
use Gateway\Core\GatewayService;
use Gateway\Core\Services\Http\HttpService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Laravel\Http\Request\RequestAdapter;
use Illuminate\Support\Facades\Route;
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
                        $endpointConfig['frontend_url_path'], [GatewayController::class, 'handle']
                    )->middleware($endpointConfig['middlewares'] ?? [])->name($endpointKey);
                }
            }
        }
    }

    public function register()
    {
        $this->app->bind(HttpClientInterface::class, HttpService::class);
        $this->app->bind(RequestAdapterInterface::class, RequestAdapter::class);
    }
}