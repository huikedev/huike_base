<?php


namespace huikedev\huike_base\interceptor\auth\contract;


use huikedev\huike_base\interceptor\auth\exception\AuthException;

abstract class AuthAbstract implements AuthInterface
{
    protected $userId;
    protected $token;
    protected $client;
    public function __construct()
    {
        $this->setClient();
    }
    abstract protected function setClient();

    abstract protected function isAuthNecessary();

    abstract protected function auth();

    abstract public function login(int $userId);

    abstract public function logout();


    public function handle()
    {
        if($this->isAuthNecessary()){
            $this->auth();
        }
    }

    public function getToken()
    {
        if(is_null($this->userId)){
            throw new AuthException('登陆验证错误，请重新登录',1);
        }
        return $this->token;
    }

    protected function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getNullableUserId(): int
    {
        return intval($this->userId);
    }

    public function getUserId()
    {
        if(is_null($this->userId)){
            throw new AuthException('登陆验证错误，请重新登录',2);
        }
        return $this->userId;
    }
}