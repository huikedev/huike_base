<?php


namespace huikedev\huike_base\utils;

use huikedev\huike_base\facade\AppRequest;
use think\facade\Config;

/**
 * Desc
 * Class UtilsRequest
 * @package huikedev\huike_base\utils
 */
class UtilsRequest
{
    public static function setHeader()
    {
        //跨域
        $originHeaders   = [   //原始header
                              'Content-Type',
                              'If-Match',
                              'If-Modified-Since',
                              'If-None-Match',
                              'If-Unmodified-Since',
                              'X-Requested-With'
        ];
        $optionHeaders[] = Config::get('huike.token.token_name', 'authorization');
        $optionHeaders   = array_merge($optionHeaders, Config::get('huike.cors.optionHeaders',[]));
        $allowHeaderString    = implode(',', array_unique(array_merge($originHeaders, $optionHeaders)));
        $allowMethodString   = implode(',', Config::get('huike.cors.optionMethods'));
        if(AppRequest::isDebug()){
            $allowOriginString = '*';
        }else{
            $allowOrigins    =  Config::get('huike.cors.optionOrigin');
            $allowOriginString = implode(',',$allowOrigins);
        }

        header('Access-Control-Allow-Origin: '.$allowOriginString);
        header('Access-Control-Allow-Methods:' . $allowMethodString);
        header('Access-Control-Allow-Headers:' . $allowHeaderString);
    }
}