<?php

$projectConfigPath = __DIR__ . '/../config/autoload/dependencies.php';

$packageConfigPath = __DIR__ . '/../../vendor/ananiaslitz/src/Hyperf/config/dependencies.php';

$projectDependencies = file_exists($projectConfigPath) ? include $projectConfigPath : [];
$packageDependencies = include $packageConfigPath;

$mergedDependencies = array_merge($projectDependencies, $packageDependencies);

file_put_contents($projectConfigPath, '<?php return ' . var_export($mergedDependencies, true) . ';' . PHP_EOL);