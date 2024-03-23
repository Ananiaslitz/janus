<?php

namespace Gateway\Core\Contracts\Cache;

interface CacheInterface
{
    public function get($key);
    public function set($key, $value, $ttl = null);
    public function has($key);
}