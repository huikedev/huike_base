<?php


namespace huikedev\huike_base\response;


use huikedev\huike_base\app_const\NoticeType;
use huikedev\huike_base\base\BaseLogic;
use huikedev\huike_base\base\BaseValidate;
use huikedev\huike_base\exceptions\AppValidateException;
use huikedev\huike_base\exceptions\CoreException;
use huikedev\huike_base\exceptions\response\ResponseException;
use huikedev\huike_base\facade\AppRequest;
use huikedev\huike_base\utils\UtilsTools;
use think\exception\ValidateException;
use think\facade\Config;
use think\helper\Str;

/**
 * Desc
 * Class AppResponse
 * Full \huikedev\huike_base\response\AppResponse
 * @package huikedev\huike_base\response
 */
class AppResponse
{
    protected $defaultControllerPath = 'controller';
    protected $defaultValidatePath = 'validate';
    protected $namespace = 'huike\logic';
    protected $dispatch;
    public function setNamespace(string $namespace=null): AppResponse
    {
        if(is_null($namespace)===false){
            $this->namespace = $namespace;
        }
        return $this;
    }

    public function setLogicController(string $path=null): AppResponse
    {
        if(is_null($path)===false){
            $this->defaultControllerPath = $path;
        }
        return $this;
    }

    public function setDispatch(string $dispatchClass): AppResponse
    {
        $this->dispatch = $dispatchClass;
        return $this;
    }
    /*
     * 自动验证
     */
    public function validate($validate=null)
    {
        if(is_null($validate)){
            $validate = AppRequest::getSnakePathFromController($this->namespace.'\\'.$this->defaultValidatePath.'\\').'\\'.Str::studly(AppRequest::action());
        }
        if(class_exists($validate)===false){
            throw new CoreException('validate not found',$validate.' 未找到');
        }
        $validateClass = validate($validate);
        if($validateClass instanceof BaseValidate){
            throw new CoreException('validate extends error',$validate.' 必须继承huikedev\huike_base\base\BaseValidate');
        }
        try {
            $validateClass->check(AppRequest::param());
        }catch (ValidateException $e){
            throw new AppValidateException($validateClass->getExceptionKey(),$e->getMessage());
        }
    }

    public function render()
    {
        try{
            $logicClass = $this->getLogicClass();
            $action          = AppRequest::action();
            $logicReflection = new \ReflectionClass($logicClass);
        }catch (\Exception $e){
            throw new ResponseException($e->getMessage(),1,NoticeType::DIALOG_ERROR,$e);
        }
        //是否有对应的方法
        if ($logicReflection->hasMethod($action) === false) {
            throw new ResponseException($logicClass . '::' . $action . '方法不存在',2);
        }

        $logic           = app($logicClass)->$action();
        //是否继承BaseLogic
        if(Config::get('huike.base_logic_check',true) && $logic instanceof BaseLogic === false){
            throw new ResponseException($logicClass . '必须返回'.BaseLogic::class.'实例',3);
        }

        $returnTypeDispatch = is_null($this->dispatch) ? 'huikedev\huike_base\response\dispatch\\'.Str::studly(strtolower($logic->getReturnType())) : $this->dispatch;
        return app($returnTypeDispatch,[$logic],true)->render();
    }

    protected function getLogicClass(): string
    {
        $appController = AppRequest::controller();
        $appController = str_replace('.','\\',$appController);
        return UtilsTools::replaceNamespace($this->namespace.'\\'.$this->defaultControllerPath.'\\'.$appController);
    }
}