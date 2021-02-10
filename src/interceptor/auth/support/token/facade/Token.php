<?php

namespace huikedev\huike_base\interceptor\auth\support\token\facade;


use think\Facade;
/**
 * @see \huikedev\huike_base\interceptor\auth\support\token\Token
 * @mixin \huikedev\huike_base\interceptor\auth\support\token\Token
 * @method \huikedev\huike_base\interceptor\auth\support\token\Token setClient(string $client='index') static 设置客户端属性
 * @method  string createToken(int $uid,int $expire=0,string $secretKey='') static 生成用户token
 * @method  mixed verifyToken(string $jwt,string $secretKey='') static 验证token
 * @method bool destroy(int $uid) static 销毁所有token
 * @method bool delete(int $uid,string $token) static 删除指定token
 */
class Token extends Facade
{

    protected static function getFacadeClass()
    {
        return \huikedev\huike_base\interceptor\auth\support\token\Token::class;
    }
}