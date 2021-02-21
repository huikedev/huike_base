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
    protected $module = 'default_module';
    protected $namespace = 'huike';
    public function __construct()
    {
        $this->huikeConfig = Config::get('huike');
    }

    /**
     * @desc 设置默认ID
     * @param int $id
     * @return $this
     */
    public function setId(int $id): AppRequest
    {
        $this->setId = $id;
        return $this;
    }

    /**
     * @desc 检测请求中是否包含指定参数，空字符串也会返回false
     * @param string $name
     * @param string $type
     * @param bool $checkEmpty
     * @return bool
     */
    public function has(string $name, string $type = 'param', bool $checkEmpty = true):bool
    {
        return Request::has($name, $type, $checkEmpty);
    }

    /**
     * @desc 获取指定字段的boolean值
     * @param string $name
     * @return bool
     */
    public function safeBoolean(string $name):bool
    {
        return boolval(Request::param($name.'/b'));
    }

    /**
     * @desc 获取指定字段的integer值
     * @param string $name
     * @return int
     */
    public function safeInteger(string $name):int
    {
        // 如果$name不存在，则返回0 需要自行处理
        return intval(Request::param($name.'/d'));
    }

    /**
     * @desc 获取指定字段的float值
     * @param string $name
     * @return float
     */
    public function safeFloat(string $name):float
    {
        return floatval(Request::param($name.'/f'));
    }

    /**
     * @desc 获取指定字段的string值，并使用strip_tags、trim过滤
     * @param string $name
     * @return string
     */
    public function safeString(string $name):string
    {
        return trim(strip_tags(strval(Request::param($name,''))));
    }

    /**
     * @desc 获取指定字段的array值
     * @param string $name
     * @return array
     */
    public function safeArray(string $name):array
    {
        return Request::param($name.'/a',[]);
    }

    /**
     * @desc 获取被访问的方法（包含控制器）,如user.Index/profile
     * @return string
     */
    public function getFullActionName(): string
    {
        return Request::controller().'/'.Request::action();
    }

    /**
     * @desc 获取Token的键名
     * @return string|null
     */
    public function getTokenName(): ?string
    {
        $adminKey = $this->huikeConfig['admin_token_key'] ?? false;
        $tokenName = $this->huikeConfig['token']['token_name'] ?? 'authorization';
        if($adminKey !== false){
            if(self::has($adminKey)){
                return $adminKey;
            }
            if(self::has($adminKey,'header')){
                return $adminKey;
            }
        }
        if(self::has($tokenName)){
            return $tokenName;
        }
        if(self::has($tokenName,'header')){
            return $tokenName;
        }
        return null;
    }

    /**
     * @desc 获取当前请求携带的Token的的值
     * @return string|null
     */
    public function getToken(): ?string
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
        return Request::param('token');
    }

    /**
     * @desc 获取当前请求中的分页参数的每页数据条数
     * @return int
     */
    public function pageSize():int
    {
        $varPageSize = $this->huikeConfig['paginator']['var_pageSize'] ?? 'pageSize';
        $default = $this->huikeConfig['paginator']['pageSize'] ?? 10;
        return self::has($varPageSize) ? Request::param($varPageSize.'/d') : $default;
    }

    /**
     * @desc 获取当前请求中的分页参数的当前页数
     * @param int $default
     * @return int
     */
    public function current(int $default = 1):int
    {
        $varPage = $this->huikeConfig['paginator']['var_page'] ?? 'current';
        return self::has($varPage) ? Request::param($varPage.'/d') : $default;
    }

    /**
     * @desc 获取当前请求中或通过setId设置的ID参数的值
     * @param int $default
     * @return int
     */
    public function id(int $default = 0):int
    {
        if(is_null($this->setId) === false){
            return $this->setId;
        }
        return self::has('id') ? Request::param('id/d') : $default;
    }

    /**
     * @desc 获取模块名称
     * @return mixed
     * @throws Exception
     */
    public function module():string
    {
        if(is_null($this->module)){
            throw new Exception('module is null');
        }
        return $this->module;
    }

    /**
     * @desc 设置模块名称
     * @param string $module
     */
    public function setModule(string $module):void
    {
        $this->module  = $module;
    }

    /**
     * @desc 获取当前模块的根命名空间
     * @return string
     */
    public function namespace():string
    {
        return $this->namespace;
    }

    /**
     * @desc 设置当前模块的根命名空间
     * @param string $namespace
     */
    public function setNamespace(string $namespace):void
    {
        $this->namespace  = $namespace;
    }

    /**
     * 检测是否为debug模式
     * @return bool
     */
    public function isDebug(): bool
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

    /**
     * @desc 获取客户端系统类型
     * @return string
     */
    public function getOs(): string
    {
        $agent = Request::header('user-agent');
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

    /**
     * @desc 获取客户端浏览器类型
     * @return string
     */
    public function getBrowser(): string
    {
        $agent = Request::header('user-agent');
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