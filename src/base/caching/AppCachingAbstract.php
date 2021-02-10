<?php


namespace huikedev\huike_base\base\caching;


use think\Exception;

abstract class AppCachingAbstract extends CacheAbstract
{
    protected $prefix;
    protected $id;
    abstract protected function setPrefix();
    public function __construct()
    {
        $this->setPrefix();
        parent::__construct();
    }

    public function setId($id):self
    {
        if($this->id !== $id || is_null($this->cacheData)){
            $this->id = $id;
            $this->update=true;
        }
        return $this;
    }

    protected function setCacheKey()
    {
        if($this->update===false){
            return $this;
        }
        if(is_null($this->id)){
            throw new Exception('id 不能为空');
        }
        $this->cacheKey = $this->prefix.$this->id;
        return $this;
    }
}