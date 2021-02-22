<?php


namespace huikedev\huike_base\command;


use huikedev\huike_base\app_const\HuikeConfig;
use huikedev\huike_base\base\install\InstallAbstract;
use think\helper\Str;

class HuikeInstall extends InstallAbstract
{
    protected $description = 'install huikedev base library';
    /**
     * @var array
     */
    protected $copyFiles = [
        'huike.common.exception.ExceptionConst',
        'huike.common.exception.HuikeExceptionHandle',
        'huike.common.init.HuikeConsole',
        'huike.common.init.HuikeExtraValidate',
        'huike.common.init.HuikeLoadRoutes',
        'huike.common.init.HuikePaginator',
        'huike.common.init.HuikeQuery',
        'huike.common.middlewares.app.GlobalBeforeMiddleware',
        'huike.common.middlewares.HuikeModuleRouteMiddleware',
        'huike.huike_module.logic.controller.index.Index',
        'huike.huike_module.service.index.facade.IndexService',
        'huike.huike_module.service.index.provider.Html',
        'huike.huike_module.service.index.provider.Index',
        'huike.huike_module.service.index.provider.Validate',
        'huike.huike_module.service.index.IndexService',
        'huike.huike_module.validate.index.index.Validate',
        'huike.lang.zh-cn.exception',
        'huike.routes.huike_module',
        'app.controller.huike_module.index.Index'
    ];
    /**
     * @var array
     */
    protected $emptyDirs = [
        'huike.command',
        'huike.default_module.logic.controller',
        'huike.default_module.service',
        'huike.default_module.validate'
    ];
    protected function setRootPath(): void
    {
        $this->rootPath = dirname(__dir__);
    }

    protected function handle()
    {
        try {
            $this->copyFiles();
            $this->makeEmptyDirs();
            $this->overwriteConfig();
        }catch (\Exception $e){
            $this->commandOutput->error($e->getMessage());
            return false;
        }
        $this->commandOutput->info(sprintf(<<<EOT
 __    __   __    __   __   __  ___  _______    _______   ___________    ____ 
|  |  |  | |  |  |  | |  | |  |/  / |   ____|  |       \ |   ____\   \  /   / 
|  |__|  | |  |  |  | |  | |  '  /  |  |__     |  .--.  ||  |__   \   \/   /  
|   __   | |  |  |  | |  | |    <   |   __|    |  |  |  ||   __|   \      /   
|  |  |  | |  `--'  | |  | |  .  \  |  |____ __|  '--'  ||  |____   \    /    
|__|  |__|  \______/  |__| |__|\__\ |_______(__)_______/ |_______|   \__/     
                                                                              
当前版本：%s
项目作者：%s
文档地址：https://huike.dev
EOT
,HuikeConfig::VERSION,HuikeConfig::AUTHOR));
        return true;
    }

    // 复制全部文件
    protected function copyFiles()
    {
        foreach ($this->copyFiles as $file){
            $this->copyFile(str_replace('.',DIRECTORY_SEPARATOR,$file));
        }
    }


    protected function makeEmptyDirs()
    {
        foreach ($this->emptyDirs as $dir){
            $this->makeEmptyDir(str_replace('.',DIRECTORY_SEPARATOR,$dir));
        }
    }

    protected function overwriteConfig()
    {
        $adminKey = Str::random(10);
        $tokenSecret = md5(Str::random(10));
        $configContent = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR.'stub'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'huike.stub');
        $configContent .="\n";
        $configContent .="\t // 超级管理token key，可用于线上调试\n";
        $configContent .="\t'admin_token_key'=>'".$adminKey."',\n";
        $configContent .="\t // token秘钥，用于token加密\n";
        $configContent .="\t'token_secret'=>'".$tokenSecret."',\n";
        $configContent .="\t // 线上环境开启调试模式\n";
        $configContent .="\t 'online_debug'=>[\n";
        $configContent .="\t\t 'key'=>'".Str::random(5)."',\n";
        $configContent .="\t\t 'value'=>'".Str::random(10)."',\n";
        $configContent .="\t ],\n";
        $configContent .="];";
        file_put_contents(app()->getConfigPath().'huike.php',$configContent);
    }

}