<?php


namespace huikedev\huike_base\log;


use huikedev\huike_base\exceptions\HuikeException;
use huikedev\huike_base\facade\AppRequest;
use think\facade\Config;
use think\facade\Log;

class HuikeLog
{
    public static function error(\Throwable $e): bool
    {
        //错误级别判断是否记录日志  是否为Error错误
        if($e instanceof huikeException && $e->getErrorLevel() < Config::get('huike.log_error_level')){
            return true;
        }
        $logData['controller'] = AppRequest::controller();
        $logData['action'] = AppRequest::action();
        if($e instanceof huikeException){
            $logData['app_error'] = $e->getError();
            $logData['exception']['msg'] =  $e->getAppMsg();
            $logData['exception']['code'] =  $e->getAppCode() ;
            if($e->isLogRequest()){
                $logData['param'] = AppRequest::param();
                $logData['header'] = AppRequest::header();
            }
        }else{
            $logData['exception']['msg'] =  $e->getMessage();
            $logData['exception']['code'] =  $e->getCode() ;
        }

        $logData['exception']['line'] = $e->getLine();
        $logData['exception']['file'] = $e->getFile();
        $logData['exception']['exception'] = get_class($e);
        Log::error($logData);
        return true;
    }

    public static function record($msg, string $type = 'info', array $context = [], bool $lazy = true)
    {
        return Log::record($msg,$type,$context,$lazy);
    }

    public function info($message, array $context = []): void
    {
        Log::info($message,$context);
    }
}