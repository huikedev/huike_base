<?php


namespace huikedev\huike_base\base\caching;


abstract class AppSettingCacheAbstract extends CacheAbstract
{
    protected $prefix;
    abstract protected function setPrefix();
    public function __construct()
    {
        $this->setPrefix();
        parent::__construct();
    }

    protected function setCacheKey()
    {
        if($this->update===false){
            return $this;
        }
        $this->cacheKey = $this->prefix;
        return $this;
    }

    protected function getSettingData(): self
    {
        if(is_null($this->cacheData)===false && $this->update =false){
            return $this;
        }
        $this->getCacheData();
        return $this;
    }
}