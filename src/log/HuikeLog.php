<?php


namespace huikedev\huike_base\log;


use think\facade\Log;

class HuikeLog
{
    public static function error($message, array $context = []): void
    {
        Log::error($message,$context) ;
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