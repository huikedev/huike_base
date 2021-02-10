<?php


namespace huikedev\huike_base\base;

use huikedev\huike_base\exceptions\UtilsException;
use think\db\Query;
use think\Model;

/**
 * Desc
 * Class BaseModel
 * Full \huikedev\huike_base\base\BaseModel
 * @package huikedev\huike_base\base
 * @mixin Model
 * @method Query modelSort(string $param=null,string $default = 'id.asc',array $allowField=[]) static 查询排序
 */
abstract class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;
    protected $defaultSoftDelete = 0;

    protected $appendScene = [];

    /*
     * 查询排序
     */
    public function scopeModelSort($query,string $param=null,string $default = 'id.asc',array $allowField=[])
    {
        $sortString =  is_null($param) ? $default : $param;
        $sortString = trim($sortString);
        $sortArray = explode('.',$sortString);
        $sortType = array_pop($sortArray);
        if(is_string($sortType)===false){
            throw new UtilsException('排序参数错误:不是String类型',1);
        }
        $sortType = strtoupper($sortType);
        if(in_array($sortType,['ASC','DESC'])===false){
            throw new UtilsException('排序参数错误:未找到ASC/DESC',2);
        }
        $sortField = count($sortArray) > 0 ? $sortArray[0] :false;
        if(is_string($sortField)===false){
            throw new UtilsException('排序参数错误:排序字段错误',3);
        }
        if(count($allowField)>0&&in_array($sortField,$allowField)===false){
            throw new  UtilsException('排序参数错误:['.$allowField.']不在允许范围',4);
        }
        $query->order($sortField,$sortType);
    }

}