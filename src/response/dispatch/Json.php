<?php


namespace huikedev\huike_base\response\dispatch;

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
    public function render()
    {
        $response['success'] = $this->logic->getCode()===0;
        $response['showType'] = $this->logic->getNoticeType();
        $response['errorCode'] = $this->logic->getCode();
        $response['errorMessage'] = $this->logic->getMsg();
        $response['data'] = $this->parseLogicData();
        if(is_null($this->debugInfo) === false){
            $response['debug'] = $this->debugInfo;
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