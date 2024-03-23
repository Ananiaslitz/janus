<?php

namespace Gateway\Core\Cache;

class CacheFactory
{
    public static function create() {
        $cacheType = getenv('CACHE_TYPE') ?? 'memory';
        return match($cacheType) {
            'redis' => new RedisCache(),
            'swoole' => new SwooleTableCache(),
            default => new ArrayCache()
        };
    }
}