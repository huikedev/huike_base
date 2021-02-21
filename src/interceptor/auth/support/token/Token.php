<?php


namespace huikedev\huike_base\interceptor\auth\support\token;


use Firebase\JWT\JWT;
use huikedev\huike_base\app_const\AppSpecialValue;
use huikedev\huike_base\interceptor\auth\support\token\exception\JwtConfigFileNotFound;
use huikedev\huike_base\interceptor\auth\support\token\exception\JwtException;
use huikedev\huike_base\interceptor\auth\support\token\exception\TokenCacheFailed;
use think\facade\Cache;
use think\facade\Config;

class Token
{
    protected $config;
    //客户端，区分不同模块所需token，如admin、user等
    protected $client = 'index';
    protected $cachePrefix;
    protected $timestamp;
    protected $tokens;

    public function __construct(?string $client=null)
    {
        $this->config = Config::get('huike.token',[]);
        if(empty($this->config)){
            throw new JwtConfigFileNotFound('Jwt Config File Not Found',11);
        }
        $this->client = $client;
        $this->timestamp    =   time();
    }

    public function setClient(string $client): Token
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @desc 删除指定token
     * @param int $uid
     * @param string $token
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function delete(int $uid,string $token):bool
    {
        $tokens = Cache::store(Cache::getDefaultDriver())->get($this->getPrefix().$uid);
        $tokens = is_array($tokens) ? $tokens :[];
        if(in_array(md5($token),$tokens)===false){
            return true;
        }
        $newTokens = array_values(array_diff($tokens,[md5($token)]));
        Cache::store(Cache::getDefaultDriver())->set($this->getPrefix().$uid,$newTokens);
        return true;
    }

    /**
     * @desc 删除用户所有token
     * @param int $uid
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function destroy(int $uid):bool
    {
        return Cache::store('redis')->delete($this->getPrefix().$uid);
    }

    /**
     * @desc 生成用户token
     * @param int $uid
     * @return string
     * @throws TokenCacheFailed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function createToken(int $uid): string
    {
        //token发布者
        $payload['iss']  = $this->config['token_iss'] ?? 'huike.dev';
        //用户ID
        $payload['uid']  =  $uid ;
        //发布时间
        $payload['iat']  =  $this->timestamp;
        if(isset($this->config['token_lifetime']) && is_numeric($this->config['token_lifetime'])){
            $payload['exp'] =   $payload['iat'] +   $this->config['token_lifetime'];
        }else{
            $payload['exp'] = AppSpecialValue::FOREVER_TIMESTAMP_UNSIGNED;
        }

        //todo:缓存设备信息ID
        $key = $this->getSecretKey();
        $jwt    =   JWT::encode($payload,$key);
        $this->tokenCache($uid,$jwt);
        return $jwt;
    }

    /**
     * @desc 验证Token
     * @param string $jwt
     * @return array
     * @throws JwtException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verifyToken(string $jwt):array
    {
        try{
            $key    =  $this->getSecretKey();
            $payload    =   JWT::decode($jwt,$key,[$this->config['token_alg']]);
            if(isset($payload->uid) === false){
                throw new JwtException('登陆状态验证错误，请重新登录',1);
            }
            $tokenCached    =   Cache::store(Cache::getDefaultDriver())->get($this->getPrefix().$payload->uid);
            if(is_array($tokenCached)===false){
                throw new JwtException('登陆状态错误，请重新登录',2);
            }
        }catch (\Exception $e){
            throw new JwtException('登陆验证错误，请重新登录',3);
        }
        $needVerifyToken = md5($jwt);
        if(in_array($needVerifyToken,$tokenCached)===false){
            throw new JwtException('登陆已失效，请重新登录',4);
        }
        return ['uid'=>$payload->uid];
    }
    /**
     * @desc    获取加密secret
     * @author Liuqian
     * @param ?string $secretKey
     * @return string
     */
    protected function getSecretKey(?string $secretKey=null): string
    {
        if(is_null($secretKey)){
            return  Config::get('huike.token_secret')  ??  'huike.dev';
        }else{
            return $secretKey;
        }
    }

    /**
     * @desc 获取缓存前缀
     * @return string
     */
    protected function getPrefix(): string
    {
        return $this->client.'_'.$this->cachePrefix;
    }

    /**
     * @desc 存储token
     * @param int $uid
     * @param string $token
     * @throws TokenCacheFailed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function tokenCache(int $uid,string $token):void
    {
        $tokens = Cache::store(Cache::getDefaultDriver())->get($this->getPrefix().$uid,[]);
        $tokens = is_array($tokens) ? $tokens : [];
        $tokens[] = md5($token);
        //只保留最近的三条记录
        $maxClient = $this->config['max_client'] ?? 3;
        if(count($tokens)>$maxClient){
            array_splice($tokens,0,count($tokens)-$maxClient);
        }
        try{
            Cache::store(Cache::getDefaultDriver())->set($this->getPrefix().$uid,$tokens);
        }catch (\Exception $e){
            throw new TokenCacheFailed('Token Cache Failed',12);
        }
    }
}