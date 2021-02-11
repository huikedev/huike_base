<?php


namespace huikedev\huike_base\exceptions;

use huike\common\exception\ExceptionConst;

/**
 * Desc
 * Class AppValidateException
 * Full \huikedev\huike_base\exceptions\AppValidateException
 * @package huikedev\huike_base\exceptions
 */
class AppValidateException extends HuikeException
{
    public function __construct(string $exceptionKey, string $appMsg = null, int $code = ExceptionConst::VALIDATE_ERROR, int $noticeType = ExceptionConst::NOTICE_TYPE)
    {
        parent::__construct($exceptionKey,$appMsg,$code ,$noticeType);
    }
}