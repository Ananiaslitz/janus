<?php

namespace Gateway\Core\Cache;

use Gateway\Core\Contracts\Cache\CacheInterface;
use Predis\Client as RedisClient;

class RedisCache implements CacheInterface
{
    private $redis;

    public function __construct()
    {
        $this->redis = new RedisClient([
            'scheme' => 'tcp',
            'host'   => getenv('REDIS_HOST'),
            'port'   => getenv('REDIS_PORT'),
        ]);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value, $ttl = null)
    {
        $this->redis->set($key, $value);
        if ($ttl) {
            $this->redis->expire($key, $ttl);
        }
    }

    public function has($key)
    {
        return (bool) $this->redis->exists($key);
    }
}
