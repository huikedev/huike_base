<?php


namespace huikedev\huike_base\exceptions;

use huike\common\exception\ExceptionConst;

/**
 * Desc
 * Class CoreException
 * Full \huikedev\huike_base\exceptions\CoreException
 * @package huikedev\huike_base\exceptions
 */
class CoreException extends HuikeException
{
    protected $errorLevel = 999;
    protected $logRequest = true;
    public function __construct(string $exceptionKey,string $logMsg ,\Throwable $previous = null, int $noticeType = ExceptionConst::NOTICE_TYPE,string $appMsg = null, int $code = 0,  array $appData = [])
    {
        parent::__construct($exceptionKey, $appMsg, $code, $noticeType,$previous, $logMsg,$this->errorLevel,$appData);
    }
}