<?php

return [
    GuzzleHttp\ClientInterface::class => function () {
        return new GuzzleHttp\Client();
    },
    Gateway\Core\Contracts\Http\HttpClientInterface::class => Gateway\Core\Services\Http\HttpService::class,
    Gateway\Core\Services\Http\Request\RequestAdapterInterface::class => Gateway\Hyperf\Http\Request\RequestAdapter::class,
];