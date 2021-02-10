<?php


namespace huikedev\huike_base\interceptor\auth;



use huikedev\huike_base\interceptor\auth\contract\AuthInterface;

class Auth implements AuthInterface
{
    public function handle()
    {
        return app('auth')->handle();
    }
    public function getToken()
    {
        return app('auth')->getToken();
    }
    public function getNullableUserId()
    {
        return app('auth')->getExceptionId();
    }
    public function getUserId()
    {
        return app('auth')->getUserId();
    }
    public function login(int $uid)
    {
        return app('auth')->login($uid);
    }
    public function logout()
    {
        return app('auth')->logout();
    }
}