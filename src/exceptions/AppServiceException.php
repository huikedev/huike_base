<?php


namespace huikedev\huike_base\exceptions;

/**
 * Desc
 * Class AppServiceException
 * Full \huikedev\huike_base\exceptions\AppServiceException
 * @package huikedev\huike_base\exceptions
 */
abstract class AppServiceException extends HuikeException
{
    protected $exceptionKey;
    public function __construct(string $appMsg, int $code, int $noticeType = 1,\Throwable $previous = null, string $error = null, int $errorLevel = 0,array $appData = [] )
    {
        $this->setExceptionKey();
        parent::__construct($this->exceptionKey, $appMsg,$code,$noticeType,$previous,$error,$errorLevel,$appData);
    }
    abstract protected function setExceptionKey();
    public function getExceptionKey()
    {
        return $this->exceptionKey;
    }
}