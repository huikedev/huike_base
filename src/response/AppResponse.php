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

class AppResponse
{
    protected $defaultControllerPath = 'controller';
    protected $defaultValidatePath = 'validate';
    protected $logicNamespacePrefix;
    protected $validateNamespacePrefix;
    protected $dispatch;
    public function setLogicNamespacePrefix(?string $logicNamespacePrefix): AppResponse
    {
        if(empty($logicNamespacePrefix)===false){
            $this->logicNamespacePrefix = $logicNamespacePrefix;
        }
        return $this;
    }

    public function setValidateNamespacePrefix(string $validateNamespacePrefix): AppResponse
    {
        if(empty($validateNamespacePrefix)===false){
            $this->validateNamespacePrefix = $validateNamespacePrefix;
        }
        return $this;
    }

    public function setLogicControllerPath(string $path): AppResponse
    {
        if(empty($path)===false){
            $this->defaultControllerPath = $path;
        }
        return $this;
    }

    public function setDispatch(string $dispatchClass): AppResponse
    {
        if(empty($dispatchClass)===false){
            $this->dispatch = $dispatchClass;
        }
        return $this;
    }
    /*
     * 自动验证
     */
    public function validate(?string $validate=null,?string $scene = null): AppResponse
    {
        if(is_null($validate)){
            $validate = $this->getValidateClass();
        }
        if(class_exists($validate)===false){
            throw new CoreException('validate not found',$validate.' 未找到');
        }
        $validateClass = validate($validate);
        if($validateClass instanceof BaseValidate===false){
            throw new CoreException('validate extends error',$validate.' 必须继承huikedev\huike_base\base\BaseValidate');
        }
        try {
            if(is_null($scene)===false){
                $validateClass->scene($scene);
            }
            $validateClass->check(AppRequest::param());
        }catch (ValidateException $e){
            throw new AppValidateException($validateClass->getExceptionKey(),$e->getMessage());
        }
        return $this;
    }

    public function render()
    {
        if(AppRequest::isDebug()){
            return $this->debugRender();
        }
        return $this->productionRender();
    }

    /**
     * @desc 生成环境下响应
     * @return mixed
     * @throws ResponseException
     */
    protected function productionRender()
    {
        try {
            $logicClass = $this->getLogicClass();
            $action          = AppRequest::action();
            $logic           = app($logicClass)->$action();
            $returnTypeDispatch = is_null($this->dispatch) ? 'huikedev\huike_base\response\dispatch\\'.Str::studly(strtolower($logic->getReturnType())) : $this->dispatch;
        }catch (\Throwable $e){
            throw new ResponseException($e->getMessage(),4);
        }

        return app($returnTypeDispatch,[$logic],true)->render();
    }

    /**
     * @desc Debug模式下响应
     * @return mixed
     * @throws ResponseException
     */
    protected function debugRender()
    {
        $logicClass = $this->getLogicClass();
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
        if(Config::get('huike.base_logic_check',true) &&$logic instanceof BaseLogic === false){
            throw new ResponseException($logicClass . '必须返回'.BaseLogic::class.'实例',3);
        }

        $returnTypeDispatch = is_null($this->dispatch) ? 'huikedev\huike_base\response\dispatch\\'.Str::studly(strtolower($logic->getReturnType())) : $this->dispatch;
        return app($returnTypeDispatch,[$logic],true)->render();
    }

    protected function getControllerInfo(): string
    {
        $appController = AppRequest::controller();
        $appController = str_replace('.','\\',$appController);
        return str_replace(AppRequest::module(),'',$appController);
    }

    protected function getLogicClass():string
    {
        if(is_null($this->logicNamespacePrefix) === false){
            $class = $this->logicNamespacePrefix;
        }else{
            $class = AppRequest::namespace();
            if(AppRequest::namespace()==='huike'){
                $class.='\\'.AppRequest::module();
            }
            $class .= '\logic\\'.$this->defaultControllerPath.'\\';
        }
        $class .= $this->getControllerInfo();
        return UtilsTools::replaceNamespace($class);
    }

    protected function getValidateClass():string
    {
        if(is_null($this->validateNamespacePrefix) === false){
            $class = $this->validateNamespacePrefix;
        }else{
            $class = AppRequest::namespace();
            if(AppRequest::namespace()==='huike'){
                $class.='\\'.AppRequest::module();
            }
            $class .= '\\'.$this->defaultValidatePath;
        }

        $namespaceInfo = pathinfo($this->getControllerInfo());
        $class .='\\'.$namespaceInfo['dirname'].'\\'.Str::snake($namespaceInfo['filename']).'\\'.Str::studly(AppRequest::action());
        return UtilsTools::replaceNamespace($class);
    }

}