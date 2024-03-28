<?php

namespace Gateway\Laravel\Providers;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Controller\GatewayController;
use Gateway\Core\GatewayService;
use Gateway\Core\Services\Http\HttpService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Laravel\Http\Request\RequestAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\Contrib\Otlp\LogsExporter;
use OpenTelemetry\Contrib\Otlp\MetricExporter;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Common\Export\Stream\StreamTransportFactory;
use OpenTelemetry\SDK\Logs\LoggerProvider;
use OpenTelemetry\SDK\Logs\Processor\SimpleLogRecordProcessor;
use OpenTelemetry\SDK\Metrics\MeterProvider;
use OpenTelemetry\SDK\Metrics\MetricReader\ExportingReader;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
use OpenTelemetry\SDK\Sdk;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\Sampler\ParentBased;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SemConv\ResourceAttributes;

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
        $this->app->bind(ClientInterface::class, function () {
            return new Client();
        });
        $this->app->bind(HttpClientInterface::class, HttpService::class);
        $this->app->bind(RequestAdapterInterface::class, RequestAdapter::class);
    }
}