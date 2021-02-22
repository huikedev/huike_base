<?php


namespace huikedev\huike_base\response\dispatch;

use huikedev\huike_base\debug\AppTrace;
use huikedev\huike_base\facade\AppRequest;
use huikedev\huike_base\response\ResponseDispatch;
use think\Model;
use think\model\Collection;
use think\Paginator;

/**
 * Desc
 * Class Json
 * Full \huikedev\huike_base\response\dispatch\Json
 * @package huikedev\huike_base\response\dispatch
 */
class Json extends ResponseDispatch
{
    public function render(): \think\response\Json
    {
        $response['success'] = $this->logic->getCode()===0;
        $response['showType'] = $this->logic->getNoticeType();
        $response['errorCode'] = $this->logic->getCode();
        $response['errorMessage'] = $this->logic->getMsg();
        $response['data'] = $this->parseLogicData();
        if (AppRequest::isDebug()) {
            AppTrace::traceDebug($response);
        }
        return json($response);
    }

    protected function parseLogicData()
    {
        $data = $this->logic->getData();
        if($data instanceof Model || $data instanceof Collection || $data instanceof Paginator){
            return $data->toArray();
        }
        return $data;
    }
}