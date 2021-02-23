<?php


namespace huikedev\huike_base\exception;

use think\Exception;
use think\facade\Lang;

/**
 * Desc
 * Class ExceptionLang
 * Full \huikedev\huike_base\exception\ExceptionLang
 * @package huikedev\huike_base\exception
 */
class ExceptionLang
{
    protected $exceptionLang;
    protected $exceptionLangFile;
    protected $appErrors = [
        'database update error'       => ['code' => -9999, 'msg' => '数据库更新失败'],
        'database insert error'       => ['code' => -9998, 'msg' => '数据库写入失败'],
        'redis crash'                 => ['code' => -9997, 'msg' => 'Redis服务无法连接'],
        'core file not found'         => ['code' => -9996, 'msg' => '核心文件缺失'],
        'logic dispatch error'        => ['code' => -9995, 'msg' => '逻辑分发错误'],
        'validate not found'          => ['code' => -9994, 'msg' => '验证类错误:未找到验证类'],
        'validate exceptionKey error' => ['code' => -9993, 'msg' => '验证类错误:未找到exceptionKey'],
        'validate extends error'      => ['code' => -9992, 'msg' => '验证类错误:未继承基类huikedev\huike_base\base\BaseValidate'],
        'response error'              => ['code' => -9000, 'msg' => '系统错误，请稍候再试'],
        'response download error'     => ['code' => -9100, 'msg' => '系统错误，请稍候再试'],
        'utils error'                 => ['code' => -9200, 'msg' => '系统错误，请稍候再试'],
        'route loaded error'       => ['code' => -9300, 'msg' => '路由加载失败'],
        'auth field'                  => ['code' => -99900, 'msg' => '登录验证错误，请重新登录'],
        'permission field'=> ['code' => -9400, 'msg' => '登录验证错误，请重新登录'],
    ];
    public function setExceptionLangFile($exceptionLangFile): ExceptionLang
    {
        $this->exceptionLangFile = $exceptionLangFile;
        return $this;
    }
    public function get(string $exceptionKey): array
    {
        if(isset($this->appErrors[$exceptionKey])){
            return $this->appErrors[$exceptionKey];
        }
        try{
            $this->getAppExceptionLang();
            if(isset($this->exceptionLang[$exceptionKey])){
                return $this->exceptionLang[$exceptionKey];
            }
            throw new Exception('exceptionKey "'. $exceptionKey .'" Not Found');
        }catch (\Exception $e){
            throw new Exception('Exception Load Error:'.$e->getMessage());
        }
    }

    public function all():array
    {
        $this->getAppExceptionLang();

        return array_merge($this->appErrors,$this->exceptionLang);
    }

    public function allKey():array
    {
        return array_keys($this->all());
    }

    public function allCode():array
    {
        return array_column(array_values($this->all()),'code');
    }


    protected function getAppExceptionLang(): ExceptionLang
    {
        if(is_array($this->exceptionLang)){
            return $this;
        }
        $exceptionFile = is_null($this->exceptionLangFile) ? app()->getRootPath().'huike'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.Lang::defaultLangSet().DIRECTORY_SEPARATOR.'exception.php' : $this->exceptionLangFile;
        if(is_file($exceptionFile)){
            $this->exceptionLang = include $exceptionFile;
            return $this;
        }else{
            throw new Exception($exceptionFile.' Not Found',500);
        }
    }
}