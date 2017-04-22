<?php
/**
 * redis 悲观锁
 * 
 */
namespace PessmisticLock;

class RedisPessmisticLock 
{
    private $redis;
    private $lockKey;
    private function __construct($redis, $lockKey)
    {
        $this->redis = $redis;
        $this->lockKey = $lockKey;
    } 

    public static function getLock($redis, $lockKey, $lockTime)
    {
        $lockKey = "lock." . $lockKey;
        do {
            $microtime = microtime(true) * 1000;
            $microTimeout = $microtime + $lockTme + 1;
            $isLock = $redis->setnx($lockKey, $microTimeout); // 上锁
            if (!$isLock) {
                $getTime = $redis->get($lockKey);
                if ($getTime > $microtime) {
                    continue; // 未超时继续等待
                }
                // 超时,抢锁,可能有几毫秒级时间差可忽略
                $previousTime = $redis->getSet($lockKey, $microTimeout);
                if ((int)$previousTime < $microtime) {
                    break;
                }
            }
        } while (!$isLock);
        return new RedisPessmisticLock($redis, $lockKey);
    }

    public function unlock()
    {
        return $this->redis->del($this->lockKey);
    }
}