<?php


namespace huikedev\huike_base\exceptions;

use huikedev\huike_base\app_const\NoticeType;

/**
 * Desc
 * Class UtilsException
 * Full \huikedev\huike_base\exceptions\UtilsException
 * @package huikedev\huike_base\exceptions
 */
class UtilsException extends HuikeException
{
    protected $exceptionKey = 'utils error';
    public function __construct( string $logMsg ,int $code ,\Throwable $previous = null, int $noticeType = NoticeType::DIALOG_ERROR)
    {
        parent::__construct($this->exceptionKey, null, $code, $noticeType,$previous, $logMsg);
    }
}