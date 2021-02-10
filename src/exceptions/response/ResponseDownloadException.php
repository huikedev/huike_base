<?php


namespace huikedev\huike_base\exceptions\response;

use huikedev\huike_base\exceptions\HuikeException;

/**
 * Desc
 * Class ResponseDownloadException
 * Full \huikedev\huike_base\exceptions\response\ResponseDownloadException
 * @package huikedev\huike_base\exceptions\response
 */
class ResponseDownloadException extends HuikeException
{
    protected $errorLevel = 999;
    protected $exceptionKey = 'response download error';
    public function __construct( string $logMsg ,int $code , int $noticeType = 4,\Throwable $previous=null)
    {
        parent::__construct($this->exceptionKey,null,$code ,$noticeType,$previous, $logMsg,$this->errorLevel);
    }
}