<?php


namespace huikedev\huike_base\response\dispatch;

use huikedev\huike_base\response\ResponseDispatch;

/**
 * Desc
 * Class Html
 * Full \huikedev\huike_base\response\dispatch\Html
 * @package huikedev\huike_base\response\dispatch
 */
class Html extends ResponseDispatch
{
    public function render()
    {
        return response($this->logic->getData());
    }
}