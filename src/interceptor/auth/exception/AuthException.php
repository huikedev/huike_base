<?php


namespace huikedev\huike_base\interceptor\auth\exception;


use huikedev\huike_base\exceptions\AppServiceException;

class AuthException extends AppServiceException
{
    protected function setExceptionKey()
    {
        $this->exceptionKey = 'auth field';
    }
}
