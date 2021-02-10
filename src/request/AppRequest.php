<?php


namespace huikedev\huike_base\request;

use think\Exception;
use think\facade\Config;
use think\facade\Request;
use think\helper\Str;

/**
 * Desc
 * Class AppRequest
 * Full \huikedev\huike_base\request\AppRequest
 * @package huikedev\huike_base\request
 */
class AppRequest
{
    /**
     * @var int | null
     */
    protected $setId;
    protected $huikeConfig;
    protected $module;
    public function __construct()
    {
        $this->huikeConfig = Config::get('huike');
    }
    public function setId(int $id)
    {
        $this->setId = $id;
        return $this;
    }
    public function has(string $name, string $type = 'param', bool $checkEmpty = true)
    {
        return \think\facade\Request::has($name, $type, $checkEmpty);
    }

    public function safeBoolean(string $name):bool
    {
        return boolval(Request::param($name.'/b'));
    }

    public function safeInteger(string $name):int
    {
        // 如果$name不存在，则返回0 需要自行处理
        return intval(Request::param($name.'/d'));
    }

    public function safeFloat(string $name):float
    {
        return floatval(Request::param($name.'/f'));
    }

    public function safeString(string $name):string
    {
        return trim(strip_tags(strval(Request::param($name,''))));
    }

    public function safeArray(string $name):array
    {
        return Request::param($name.'/a',[]);
    }

    public function getFullActionName()
    {
        return Request::controller().'/'.Request::action();
    }

    public function getTokenName()
    {
        $adminKey = $this->huikeConfig['admin_token_key'] ?? false;
        $tokenName = $this->huikeConfig['token']['token_name'] ?? 'authorization';
        if($adminKey !== false){
            if($this->has($adminKey)){
                return $adminKey;
            }
            if($this->has($adminKey,'header')){
                return $adminKey;
            }
        }
        if($this->has($tokenName)){
            return $tokenName;
        }
        if($this->has($tokenName,'header')){
            return $tokenName;
        }
        return null;
    }
    public function getToken()
    {
        $tokenName = $this->getTokenName();
        if(is_null($tokenName)){
            return null;
        }
        if($this->has($tokenName)){
            return $this->safeString($tokenName);
        }
        if($this->has($tokenName,'header')){
            return Request::header($tokenName);
        }
        return \think\facade\Request::middleware('token');
    }
    public function getRuleId()
    {
        return \think\facade\Request::middleware('rule_id');
    }

    public function getLongIp()
    {
        return ip2long(\think\facade\Request::ip());
    }

    public function pageSize():int
    {
        $varPageSize = $this->huikeConfig['paginator']['var_pageSize'] ?? 'pageSize';
        $default = $this->huikeConfig['paginator']['pageSize'] ?? 10;
        return $this->has($varPageSize) ? Request::param($varPageSize.'/d') : $default;
    }

    public function current(int $default = 1):int
    {
        $varPage = $this->huikeConfig['paginator']['var_page'] ?? 'current';
        return $this->has($varPage) ? Request::param($varPage.'/d') : $default;
    }

    public function id(int $default = 0):int
    {
        if(is_null($this->setId) === false){
            return $this->setId;
        }
        return $this->has('id') ? Request::param('id/d') : $default;
    }

    public function module()
    {
        if(is_null($this->module)){
            throw new Exception('module is null');
        }
        return $this->module;
    }

    public function setModule(string $module)
    {
        $this->module  = $module;
    }

    /**
     * 检测是否为debug模式
     * @return bool
     */
    public function isDebug()
    {
        if(app()->isDebug()){
            return true;
        }
        $onlineDebug = $this->huikeConfig['online_debug'] ?? ['key'=>'huike','value'=>'debug'];
        if($this->has($onlineDebug['key'],'header') && Request::header($onlineDebug['key'])===$onlineDebug['value']){
            return true;
        }
        if($this->has($onlineDebug['key']) && Request::param($onlineDebug['key'])===$onlineDebug['value']){
            return true;
        }
        return false;
    }

    public function getOs(): string
    {
        $agent = \think\facade\Request::header('user-agent');
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.1/i', $agent)) {
            return 'Windows 7';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.2/i', $agent)) {
            return 'Windows 8';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 10.0/i', $agent)) {
            return 'Windows 10';#添加win10判断
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 5.1/i', $agent)) {
            return 'Windows XP';
        }
        if (false !== stripos($agent, 'linux')) {
            return 'Linux';
        }
        if (false !== stripos($agent, 'mac')) {
            return 'Mac';
        }

        return '未知';
    }
    public function getBrowser(): string
    {
        $agent = \think\facade\Request::header('user-agent');
        if (false !== stripos($agent, "MSIE")) {
            return 'MSIE';
        }
        if (false !== stripos($agent, "Firefox")) {
            return 'Firefox';
        }
        if (false !== stripos($agent, "Chrome")) {
            return 'Chrome';
        }
        if (false !== stripos($agent, "Safari")) {
            return 'Safari';
        }
        if (false !== stripos($agent, "Opera")) {
            return 'Opera';
        }

        return '未知';
    }

    public function __call($name, $arguments)
    {
        $function = '\think\facade\Request::' . $name;
        return call_user_func_array($function, $arguments);
    }
}