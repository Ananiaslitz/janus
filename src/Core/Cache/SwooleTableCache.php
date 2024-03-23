<?php

namespace Gateway\Core\Cache;

use Gateway\Core\Contracts\Cache\CacheInterface;

class SwooleTableCache implements CacheInterface
{
    protected $table;

    public function __construct()
    {
        $this->table = new \Swoole\Table(1024);
        $this->table->column('data', \Swoole\Table::TYPE_STRING, 1024);
        $this->table->create();
    }

    public function get($key)
    {
        $value = $this->table->get($key);
        return $value ? $value['data'] : null;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->table->set($key, ['data' => $value]);
    }

    public function has($key)
    {
        return $this->table->exist($key);
    }

}