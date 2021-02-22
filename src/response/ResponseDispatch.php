<?php


namespace huikedev\huike_base\response;


use huikedev\huike_base\base\BaseLogic;
use huikedev\huike_base\facade\AppDebug;
use huikedev\huike_base\facade\AppRequest;

/**
 * Desc
 * Class ResponseDispatch
 * Full \huikedev\huike_base\response\ResponseDispatch
 * @package huikedev\huike_base\response
 */
abstract class ResponseDispatch
{
    protected $logic;
    abstract public function render();
    public function __construct($logic)
    {
        $this->logic = $logic;
    }
}