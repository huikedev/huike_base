<?php


namespace huikedev\huike_base\base;


use think\Exception;

trait GetterSetter
{
    public function __call($name, $arguments)
    {
        if(strpos($name,'get')===0){
            $property = str_replace('get','',$name);
            $property = lcfirst($property);
            return $this->$property;
        }
        if(strpos($name,'set')===0){
            $property = str_replace('set','',$name);
            $property = lcfirst($property);
            if(isset($arguments[0])===false){
                throw new Exception('缺少必要参数');
            }
            $this->$property = $arguments[0];
            return $this;
        }
        return true;
    }
}