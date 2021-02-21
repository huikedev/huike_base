<?php


namespace huikedev\huike_base\debug;


use huikedev\huike_base\exceptions\HuikeException;
use huikedev\huike_base\facade\AppRequest;

class DebugRouteLoaded
{
    public function handle()
    {
        $route = app()->getAppPath().'controller'.DIRECTORY_SEPARATOR.AppRequest::module().DIRECTORY_SEPARATOR.'route.php';
        if(file_exists($route) === false){
            throw new HuikeException('route loaded error','未找到路由文件【'.$route.'】');
        }
        include $route;
    }
}