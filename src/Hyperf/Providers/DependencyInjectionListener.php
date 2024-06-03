<?php

namespace Gateway\Hyperf\Providers;

use Gateway\Core\Contracts\Http\HttpClientInterface;
use Gateway\Core\Services\Http\HttpService;
use Gateway\Core\Services\Http\Request\RequestAdapterInterface;
use Gateway\Hyperf\Http\Request\RequestAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\HttpServer\Request;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

class DependencyInjectionListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function process(object $event): void
    {
        $this->registerDependencies();
    }

    protected function registerDependencies()
    {
        $this->container->define(HttpClientInterface::class, HttpService::class);
        $this->container->define(ClientInterface::class, Client::class);
        $this->container->define(RequestAdapterInterface::class, RequestAdapter::class);
        $this->container->define(RequestInterface::class, Request::class);
    }
}
