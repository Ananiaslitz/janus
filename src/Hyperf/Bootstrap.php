<?php

namespace Gateway\Hyperf;

use Gateway\Core\GatewayService;
use Gateway\Hyperf\Providers\GatewayServiceProvider;
use Hyperf\Di\Container;

class Bootstrap
{
    public function handle(Container $container)
    {
        $gatewayService = $container->get(GatewayService::class);
        $provider = new GatewayServiceProvider($gatewayService);
        $provider->boot();
    }
}