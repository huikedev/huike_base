<?php


namespace huikedev\huike_base\base;


use think\Collection;

trait BatchTransTrait
{
    /**
     * @var Collection
     */
    protected $transList;
    /*
     * 在handle方法头部调用此方法重置$this->transList为空数据集
     * 在handle方法内部使用$this->transList->push()添加模型对象
     */
    protected function initialize()
    {
        $this->transList = new Collection();
    }
    /*
     * 批量回滚
     */
    public function rollback(): bool
    {
        if($this->transList->isEmpty()){
            return true;
        }
        foreach ($this->transList as $object){
            $object->rollback();
        }
        return true;
    }
    /*
     * 批量提交
     */
    public function commit(): bool
    {
        if($this->transList->isEmpty()){
            return true;
        }
        foreach ($this->transList as $object){
            $object->commit();
        }
        return true;
    }
}