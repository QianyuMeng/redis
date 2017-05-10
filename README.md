# redis 悲观锁

可以对redis指定的key进行锁定，支持指定锁定时间（毫秒），超出锁定时间将自动释放锁，使用示例如下：
```
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
```
