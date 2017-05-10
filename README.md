# redis 悲观锁
`
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);
$stockKey = 'goods.stock';
$lockTime = 3000;
$count = 5;
$redisLock = RedisPessmisticLock::getLock($redis, $stockKey, $lockTime);
$stocks = (int) $redis->get($stockKey);
if ($stocks >= $count) {
    $redis->decrBy($stockKey, $count);
} else {
    echo "库存不足，目前剩余库存：" . $stocks;
}
$redisLock->unlock();
`
