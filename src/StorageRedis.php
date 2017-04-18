<?php
namespace Tback\PrometheusExporter;

use Prometheus\Storage\Redis;

class StorageRedis extends Redis
{
    protected static $defaultOptions = array();

    protected $options;
    protected $redis;
    
    /**
     * @throws StorageException
     */
    protected function openConnection()
    {
        try {
            if ($this->options['persistent_connections']) {
                @$this->redis->pconnect($this->options['host'], $this->options['port'], $this->options['timeout']);
            } else {
                @$this->redis->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
            }
            $this->redis->setOption(\Redis::OPT_READ_TIMEOUT, $this->options['read_timeout']);
            if (isset($this->options['password'])) {
              @$this->redis->auth($this->options['password']);
            }
            if (isset($this->options['database'])) {
              @$this->redis->select($this->options['database']);
            }
        } catch (\RedisException $e) {
            throw new StorageException("Can't connect to Redis server", 0, $e);
        }
    }

}
