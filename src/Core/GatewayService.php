<?php

namespace Gateway\Core;

use Gateway\Core\Cache\CacheFactory;
use Symfony\Component\Yaml\Yaml;

class GatewayService
{
    private array $routesConfig = [];

    public function loadRoutesConfig($configPath)
    {
        if (!is_file($configPath)) {
            throw new \RuntimeException("Config file not found at {$configPath}");
        }

        $this->routesConfig = Yaml::parseFile($configPath);
    }

    public function getRoutesConfig()
    {
        return $this->routesConfig;
    }

    public function getEnabledRoutes($configPath)
    {
        $cacheKey = 'enabledRoutesConfig';
        $cache = CacheFactory::create();

        if ($cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $this->loadRoutesConfig($configPath);

        $enabledRoutes = [];
        foreach ($this->routesConfig as $service => $endpoints) {
            foreach ($endpoints as $endpointKey => $endpointConfig) {
                if ($endpointConfig['enabled'] ?? false) {
                    $enabledRoutes[$service][$endpointKey] = $endpointConfig;
                }
            }
        }

        $cache->set($cacheKey, $enabledRoutes);
        return $enabledRoutes;
    }

    public function getRouteConfigByPath($requestPath)
    {
        foreach ($this->getEnabledRoutes(base_path('gateway.yml')) as $service => $endpoints) {
            foreach ($endpoints as $endpointKey => $endpointConfig) {
                if ($endpointConfig['enabled']) {
                    $pattern = preg_quote($endpointConfig['frontend_url_path'], '#');
                    $pattern = preg_replace('/\\\{[^\}]+\\\}/', '([^/]+)', $pattern);

                    if (preg_match("#^{$pattern}$#", $requestPath, $matches)) {
                        return $endpointConfig;
                    }
                }
            }
        }
        throw new \RuntimeException("Route config not found for path: {$requestPath}");
    }
}
