<?php

namespace Gateway\Hyperf\Providers;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Services\Http\HttpService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Hyperf\Http\Request\RequestAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ClientInterface::class => function () {
                    return new Client();
                },
                HttpClientInterface::class => HttpService::class,
                RequestAdapterInterface::class => RequestAdapter::class,
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