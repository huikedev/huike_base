<?php


namespace huikedev\huike_base\base;

use huikedev\huike_base\exceptions\CoreException;
use think\Validate;

/**
 * Desc
 * Class BaseValidate
 * Full \huikedev\huike_base\base\BaseValidate
 * @package huikedev\huike_base\base
 */
abstract class BaseValidate extends Validate
{
    protected $exceptionKey;
    public function getExceptionKey()
    {
        if(is_null($this->exceptionKey)){
            throw new CoreException('validate exceptionKey error','验证类错误:未找到exceptionKey');
        }
        return $this->exceptionKey;
    }
}