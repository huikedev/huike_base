<?php


namespace huikedev\huike_base\interceptor\auth\facade;


use think\Facade;
/**
 * @see \huikedev\huike_base\interceptor\auth\Auth
 * @mixin \huikedev\huike_base\interceptor\auth\Auth
 * @method string getToken() static 获取token
 * @method mixed getNullableUserId() static 获取ExceptionId
 * @method mixed getUserId() static 获取UserId
 * @method string login(int $uid) static 登录
 * @method bool logout() static 退出
*/
class Auth extends Facade
{
    protected static function getFacadeClass()
    {
        return \huikedev\huike_base\interceptor\auth\Auth::class;
    }
}