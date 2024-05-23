<?php

namespace Gateway\Hyperf\Providers;

use Gateway\Core\Controller\GatewayController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Framework\Event\AfterWorkerStart;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Gateway\Core\GatewayService;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;
use Psr\Container\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class RouteRegisterListener implements ListenerInterface
{
    /**
     * @Inject
     * @var GatewayService
     */
    private GatewayService $gatewayService;

    /**
     * @var DispatcherFactory
     */
    private $dispatcherFactory;

    public function __construct(ContainerInterface $container, DispatcherFactory $dispatcherFactory)
    {
        $this->gatewayService = $container->get(GatewayService::class);
        $this->dispatcherFactory = $dispatcherFactory;
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function process(object $event): void
    {
        $configPath = BASE_PATH . '/gateway.yml';
        if (!is_file($configPath)) {
            throw new \RuntimeException("Config file not found at {$configPath}");
        }

        $configContent = file_get_contents($configPath);
        if ($configContent === false) {
            throw new \RuntimeException("Failed to read config file at {$configPath}");
        }

        $routesConfig = Yaml::parse($configContent);
        $router = $this->dispatcherFactory->getRouter('http');

        foreach ($routesConfig as $service => $endpoints) {
            foreach ($endpoints as $endpointKey => $endpointConfig) {
                if ($endpointConfig['enabled'] ?? false) {
                    $router->addRoute(
                        [$endpointConfig['ms_request_verb']],
                        $endpointConfig['frontend_url_path'],
                        [GatewayController::class, 'handle']
                    );
                }
            }
        }
    }
}
