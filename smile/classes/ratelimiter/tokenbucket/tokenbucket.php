<?php
namespace RateLimiter\TokenBucket;

require_once __DIR__ . '/../ratelimiter.php';

use RateLimiter\RateLimiterInterface;

class TokenBucket implements RateLimiterInterface {
    public $prefId;
    public $id;
    public $information;
    public $bucketId;
    public $maxRequest;
    public $expiryTime;
    public $createdDate;
    private $redisconn;

    public function __construct($prefId, $id, $information, $maxRequest, $expiryTime, $redisconn) {
        $this->prefId = $prefId;
        $this->id = $id;
        $this->information = $information;
        $this->maxRequest = $maxRequest;
        $this->expiryTime = $expiryTime;
        $this->redisconn = $redisconn;
        $this->bucketId = $this->prefId . "_" . hash('sha256', $id . $information);
    }

    private function generateTokenBucket() {
        $data = array(
            "prefId" => $this->prefId,
            "id" => $this->id,
            "information" => $this->information,
            "maxRequest" => $this->maxRequest,
            "expiryTime" => $this->expiryTime,
            "createdDate" => $this->createdDate,
            "bucketId" => $this->bucketId,
            "refIdTokenSize" => $this->bucketId . "_TOKEN"
        );
       return $data;
    }

    public function create() : string {
        if ($this->redisconn->exists($this->bucketId)) {
            $bucket = $this->redisconn->get($this->bucketId);
            $tokenSize = $this->redisconn->get($this->bucketId . "_TOKEN");
        } else {
            $newBucket = $this->generateTokenBucket();
            $newBucket["createdDate"] = date('Y-m-d H:i:s');
            $tokenSize = $newBucket["maxRequest"];
            $this->redisconn->set($this->bucketId, json_encode($newBucket), $this->expiryTime);
            $this->redisconn->set($this->bucketId . "_TOKEN", $tokenSize, $this->expiryTime);
        }
        return $this->bucketId;
    }

    public function request($numOfRequest) : bool {
        if (!$this->redisconn->exists($this->bucketId)) {
            return false;
        }
        $bucket = $this->redisconn->get($this->bucketId);
        $tokenSize = $this->redisconn->get($this->bucketId . "_TOKEN");
        
        $leftToken = $tokenSize - $numOfRequest;
        if ($leftToken < 0) {
            return false;
        }
        $this->redisconn->set($this->bucketId . "_TOKEN", $leftToken, $this->expiryTime);
        return true;
    }
}