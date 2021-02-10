<?php


namespace huikedev\huike_base\command;


use huikedev\huike_base\app_const\HuikeConfig;
use huikedev\huike_base\base\BaseCommand;
use think\helper\Str;

class HuikeInstall extends BaseCommand
{
    protected function handle()
    {
        try {
            $this->copyFiles();
            $this->makeEmptyDirs();
            $this->overwriteConfig();
        }catch (\Exception $e){
            $this->commandOutput->error($e->getMessage());
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
    }

    // 复制全部文件
    protected function copyFiles()
    {
        // 异常接管类
        $this->copyFile('common'.DIRECTORY_SEPARATOR.'exception'.DIRECTORY_SEPARATOR.'HuikeExceptionHandle');
        // 自动检测命令行类
        $this->copyFile('common'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'HuikeConsole');
        // 扩展验证规则
        $this->copyFile('common'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'HuikeExtraValidate');
        // 分页类
        $this->copyFile('common'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'HuikePaginator');
        // Query类
        $this->copyFile('common'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'HuikeQuery');
        // 全局中间件
        $this->copyFile('common'.DIRECTORY_SEPARATOR.'middlewares'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'GlobalBeforeMiddleware');
        // 异常文件
        $this->copyFile('lang'.DIRECTORY_SEPARATOR.'zh-cn'.DIRECTORY_SEPARATOR.'exception');
    }

    // 获取初始文件
    protected function getStubContent(string $name): string
    {
        return file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.$name.'.stub');
    }

    // 复制文件
    protected function copyFile(string $name)
    {
        $file = app()->getRootPath().'huike'.DIRECTORY_SEPARATOR.$name.'.php';
        $dir = pathinfo($file,PATHINFO_DIRNAME);
        if (is_dir($dir) === false){
            mkdir($dir,0755,true);
        }
        file_put_contents($file,$this->getStubContent($name));
    }

    protected function makeEmptyDirs()
    {
        $this->makeEmptyDir('command');
        $this->makeEmptyDir('demo'.DIRECTORY_SEPARATOR.'logic'.DIRECTORY_SEPARATOR.'controller');
        $this->makeEmptyDir('demo'.DIRECTORY_SEPARATOR.'validate');
        $this->makeEmptyDir('demo'.DIRECTORY_SEPARATOR.'service');
    }

    protected function makeEmptyDir(string $name)
    {
        $dir = app()->getRootPath().'huike'.DIRECTORY_SEPARATOR.$name;
        if (is_dir($dir) === false){
            mkdir($dir,0755,true);
        }
    }

    protected function overwriteConfig()
    {
        $adminKey = Str::random(10);
        $tokenSecret = md5(Str::random(10));
        $configContent = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'huike.stub');
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