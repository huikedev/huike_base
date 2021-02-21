<?php


namespace huikedev\huike_base\exceptions;

use huike\common\exception\ExceptionConst;
use huikedev\huike_base\facade\AppRequest;
use huikedev\huike_base\facade\ExceptionLang;
use think\Exception;
use Throwable;

/**
 * Desc
 * Class HuikeException
 * Full \huikedev\huike_base\exceptions\HuikeException
 * @package huikedev\huike_base\exceptions
 */
class HuikeException extends Exception
{
    /**
     * @var mixed|string
     */
    protected $appMsg;
    /**
     * @var int
     */
    protected $appCode=0;
    /**
     * @var array
     */
    protected $appData = [];
    /**
     * @var int
     */
    protected $errorLevel;
    /**
     * @var int
     */
    protected $noticeType;
    /**
     * @var bool
     */
    protected $logRequest = false;
    /**
     * @var int|mixed
     */
    protected $defaultCode = 0;
    /**
     * @var
     */
    protected $error;
    /**
     * @var string
     */
    protected $exceptionLang;
    public function __construct(string $exceptionKey,string $appMsg=null,int $appCode=0,int $noticeType=ExceptionConst::NOTICE_TYPE,Throwable $previous = null,$error = null,int $errorLevel=0,array $appData=[])
    {
        $this->parseAppMsg($exceptionKey,$appMsg,$error);
        $this->parseCode($appCode);
        $this->appData = $appData;
        $this->errorLevel = $errorLevel;
        $this->noticeType = $noticeType;
        $this->error = $error;
        parent::__construct($this->appMsg,$this->code,$previous);
    }

    protected function parseAppMsg($exceptionKey,$appMsg,$error)
    {
        $exception = ExceptionLang::setExceptionLangFile($this->exceptionLang)->get($exceptionKey);
        if(is_string($error)){
            $debugMsg = $error;
        }else{
            $debugMsg = $error['error_msg'] ?? '';
        }
        $debugMsg = empty($debugMsg) ? $appMsg : $debugMsg;
        $debugMsg = empty($debugMsg) ? $exception['msg'] : $debugMsg;
        if(AppRequest::isDebug()){
            $this->appMsg = $debugMsg;
        }else{
            $this->appMsg = empty($appMsg) ? $exception['msg'] :$appMsg;
        }
        $this->defaultCode = $exception['code'];
    }

    public function getAppMsg()
    {
        return $this->appMsg;
    }
    public function getAppData()
    {
        return $this->appData;
    }
    public function getAppCode()
    {
        return $this->appCode;
    }
    public function getNoticeType()
    {
        return $this->noticeType;
    }

    public function isLogRequest()
    {
        return $this->logRequest;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getErrorLevel()
    {
        return $this->errorLevel;
    }
    /*
     * 方便根据错误码定位到具体的逻辑
     */
    protected function parseCode(int $appCode)
    {
        if($appCode>0 && $appCode < 100){
            $this->appCode = $this->defaultCode > 0 ? $this->defaultCode + $appCode : $this->defaultCode - $appCode;
        }else{
            $this->appCode = $appCode;
        }
        return $this;
    }

    protected function setExceptionLang(string $exceptionLangFile)
    {
        $this->exceptionLang = $exceptionLangFile;
    }
}