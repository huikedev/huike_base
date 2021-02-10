<?php


namespace huikedev\huike_base\base\caching;


use think\cache\Driver;
use think\facade\Cache;

abstract class CacheAbstract
{
    protected $cacheKey;
    protected $update = true;
    protected $expire;
    protected $cacheData;
    protected $redisCount = 0;

    public function __construct()
    {
        // 设置随机过期时间，防止同一时间大量缓存过期引起IO阻塞
        $this->expire = rand(3600,3600*24*3);
    }

    abstract protected function getDataSource();

    abstract protected function setCacheKey();

    protected function getCacheData()
    {
        $this->setCacheKey();
        $this->cacheData = $this->cacheHandle()->remember($this->cacheKey,function (){
            return $this->getDataSource();
        },intval($this->expire));
        $this->redisCount = $this->redisCount +1;
        return $this;
    }

    public function cacheHandle():Driver
    {
        return Cache::store(Cache::getDefaultDriver());
    }

    public function getRedisCount():int
    {
        return $this->redisCount;
    }

    public function force():self
    {
        $this->update = true;
        return $this;
    }

    public function setExpire(int $expire):self
    {
        $this->expire = $expire>0? $expire :0;
        return $this;
    }

    public function deleteCache():self
    {
        $this->setCacheKey();
        $this->cacheHandle()->delete($this->cacheKey);
        $this->update = true;
        return $this;
    }
}