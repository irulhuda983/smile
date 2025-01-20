<?php
namespace RateLimiter\RedisConn;

class RedisConn {
    private $redis;
    public $clusterUrls;
    public $clusterPassword;
    public function __construct($clusterUrls, $password) {
        $this->clusterUrls = $clusterUrls;
        $this->clusterPassword = $password;

        $urls = explode(",", $this->clusterUrls);
        $this->redis = new \RedisCluster(NULL, $urls, 1.5, 1.5, true, $this->clusterPassword);
    }

    public function set($key, $value, $expiration) {
        $this->redis->set($key, $value, $expiration);
    }
    
    public function get($key) {
        return $this->redis->get($key);
    }

    public function exists($key) {
        return $this->redis->rawCommand($key, "EXISTS", $key);
    }
}
