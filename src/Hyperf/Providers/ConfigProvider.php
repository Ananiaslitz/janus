<?php

namespace Gateway\Hyperf\Providers;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Services\Http\HttpService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Hyperf\Http\Request\RequestAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Hyperf\HttpServer\Request;
use Psr\Http\Message\RequestInterface;
use Hyperf\Di\Annotation\Inject;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                HttpClientInterface::class => HttpService::class,
                ClientInterface::class => Client::class,
                RequestAdapterInterface::class => RequestAdapter::class,
                RequestInterface::class => Request::class,
            ],
            'listeners' => [
                RouteRegisterListener::class
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ]
                ]
            ]
        ];
    }
}
