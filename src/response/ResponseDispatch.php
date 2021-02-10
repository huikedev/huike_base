<?php


namespace huikedev\huike_base\response;


use huikedev\huike_base\base\BaseLogic;
use huikedev\huike_base\facade\AppDebug;

/**
 * Desc
 * Class ResponseDispatch
 * Full \huikedev\huike_base\response\ResponseDispatch
 * @package huikedev\huike_base\response
 */
abstract class ResponseDispatch
{
    protected $logic;
    protected $debugInfo;
    abstract public function render();
    public function __construct($logic)
    {
        $this->logic = $logic;
        $this->getDebugInfo();
    }

    protected function getDebugInfo()
    {
        if(app()->isDebug()===false){
            return $this;
        }
        AppDebug::remark('huike_end');
        [$mSec, $sec] = explode(' ', microtime());
        $mSecTime =  (float)sprintf('%.0f', (floatval($mSec) + floatval($sec)) * 1000);
        $this->debugInfo['time_cost']    = AppDebug::getRangeTime('huike_start', 'huike_end');
        $this->debugInfo['mem_cost']     = AppDebug::getRangeMem('huike_start', 'huike_end');
        $this->debugInfo['file_load']    = AppDebug::getFile();
        $this->debugInfo['req']          = AppDebug::getThroughputRate();
        $this->debugInfo['queries']      = app()->db->getQueryTimes();
        $this->debugInfo['cache_reads']  = app('cache')->getReadTimes();
        $this->debugInfo['cache_writes'] = app('cache')->getWriteTimes();
        $this->debugInfo['time_stamp']   = date('Y-m-d H:i:s.').substr($mSecTime, -3);;
        return $this;
    }
}