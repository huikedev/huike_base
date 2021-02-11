<?php


namespace huikedev\huike_base\interceptor\auth\contract;


interface AuthInterface
{
    public function handle();
    public function getToken();
    public function getNullableUserId();
    public function getUserId();
    public function login(int $userId);
}