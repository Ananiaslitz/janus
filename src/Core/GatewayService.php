<?php

namespace Gateway\Core;

use Symfony\Component\Yaml\Yaml;

class GatewayService
{
    private array $routesConfig = [];

    public function loadRoutesConfig($configPath)
    {
        if (!file_exists($configPath)) {
            throw new \RuntimeException("Config file not found at {$configPath}");
        }

        $this->routesConfig = Yaml::parseFile($configPath);
    }

    public function getRoutesConfig()
    {
        return $this->routesConfig;
    }

    public function getEnabledRoutes()
    {
        $enabledRoutes = [];
        foreach ($this->routesConfig as $service => $endpoints) {
            foreach ($endpoints as $endpointKey => $endpointConfig) {
                if ($endpointConfig['enabled'] ?? false) {
                    $enabledRoutes[$service][$endpointKey] = $endpointConfig;
                }
            }
        }
        return $enabledRoutes;
    }
}