<?php

namespace Gateway\Core\Cache;

use Gateway\Core\Contracts\Cache\CacheInterface;

class ArrayCache implements CacheInterface
{
    private $storage = [];

    public function get($key)
    {
        return $this->storage[$key] ?? null;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->storage[$key] = $value;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->storage);
    }
}