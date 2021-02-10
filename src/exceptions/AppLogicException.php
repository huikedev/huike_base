<?php


namespace huikedev\huike_base\exceptions;

/**
 * Desc
 * Class AppLogicException
 * Full \huikedev\huike_base\exceptions\AppLogicException
 * @package huikedev\huike_base\exceptions
 */
class AppLogicException extends HuikeException
{
    public function __construct(AppServiceException $serviceException)
    {
        parent::__construct($serviceException->getExceptionKey(), $serviceException->getAppMsg(), $serviceException->getAppCode(), $serviceException->getNoticeType(), $serviceException,$serviceException->getError(),$serviceException->getErrorLevel(),$serviceException->getAppData());
    }
}