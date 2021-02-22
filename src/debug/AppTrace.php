<?php


namespace huikedev\huike_base\debug;


class AppTrace
{

    public static function traceDebug(array &$data):void
    {

        $request     = app()->request;
        // 获取基本信息
        $runtime = number_format(microtime(true) - app()->getBeginTime(), 10, '.', '');
        $reqs    = $runtime > 0 ? number_format(1 / $runtime, 2) : '∞';
        $mem     = number_format((memory_get_usage() - app()->getBeginMem()) / 1024, 2);

        $debug['base']['request_time'] = date('Y-m-d H:i:s', $request->time() ?: time());
        $debug['base']['runtime'] = number_format((float) $runtime, 6).' s';
        $debug['base']['request_method'] = $request->method();
        $debug['base']['mem_cost'] = $mem.' KB';
        $debug['base']['file_load'] = count(get_included_files());
        $debug['db']['queries']= app()->db->getQueryTimes();
        $debug['db']['reqs']= $reqs.' req/s';
        $debug['cache']['reads'] = app()->cache->getReadTimes();
        $debug['cache']['writes'] = app()->cache->getReadTimes();
        $data['debug'] = $debug;
    }


}