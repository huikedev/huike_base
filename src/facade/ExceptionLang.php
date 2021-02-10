<?php


namespace huikedev\huike_base\facade;

use think\Facade;

/**
 * Desc
 * Class ExceptionLang
 * Full \huikedev\huike_base\facade\ExceptionLang
 * @package huikedev\huike_base\facade
 * @method static array get(string $exceptionKey)
 * @method static array all()
 * @method static array allKey()
 * @method static array allCode()
 * @method static \huikedev\huike_base\exception\ExceptionLang setExceptionLangFile($exceptionLangFile)
 */
class ExceptionLang extends Facade
{
    protected static function getFacadeClass()
    {
        return \huikedev\huike_base\exception\ExceptionLang::class;
    }
}