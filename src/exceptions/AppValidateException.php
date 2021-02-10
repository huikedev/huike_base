<?php


namespace huikedev\huike_base\exceptions;

/**
 * Desc
 * Class AppValidateException
 * Full \huikedev\huike_base\exceptions\AppValidateException
 * @package huikedev\huike_base\exceptions
 */
class AppValidateException extends HuikeException
{
    public function __construct(string $exceptionKey, string $appMsg = null, int $code = 0, int $noticeType = 2)
    {
        parent::__construct($exceptionKey,$appMsg,$code ,$noticeType);
    }
}