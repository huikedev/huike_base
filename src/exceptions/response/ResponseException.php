<?php


namespace huikedev\huike_base\exceptions\response;

use huikedev\huike_base\exceptions\HuikeException;

/**
 * Desc
 * Class ResponseException
 * Full \huikedev\huike_base\exceptions\ResponseException
 * @package huikedev\huike_base\exceptions
 */
class ResponseException extends HuikeException
{
    protected $errorLevel = 999;
    protected $exceptionKey = 'response error';
    public function __construct( string $logMsg ,int $code , int $noticeType = 4,\Throwable $previous=null)
    {
        parent::__construct($this->exceptionKey,null,$code ,$noticeType,$previous, $logMsg,$this->errorLevel);
    }
}