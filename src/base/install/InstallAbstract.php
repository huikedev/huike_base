<?php


namespace huikedev\huike_base\base\install;


use huikedev\huike_base\app_const\HuikeConfig;
use huikedev\huike_base\base\BaseCommand;
use huikedev\huike_generator\migration\RunMigration;
use huikedev\huike_generator\migration\RunSeed;

abstract class InstallAbstract extends BaseCommand
{
    protected $rootPath;
    protected $migrateStatus = true;
    protected $sqlFile;
    public function __construct()
    {
        $this->setRootPath();
        parent::__construct();
    }

    /**
     * @desc 设置模块的根目录路径
     * @return mixed
     */
    abstract protected function setRootPath():void;

    protected function getStubContent(string $name)
    {
        return file_get_contents($this->rootPath.DIRECTORY_SEPARATOR.'command'.DIRECTORY_SEPARATOR.'stub'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.$name.'.stub');
    }

    protected function copyFile(string $relateFilename,bool $overwrite=false):void
    {
        $file = app()->getRootPath().$relateFilename.'.php';
        if (is_dir(dirname($file)) === false){
            mkdir(dirname($file),0755,true);
        }
        if (file_exists($file)){
            if($overwrite === false){
                file_put_contents($file.'.bak-'.HuikeConfig::VERSION,file_get_contents($file));
            }
        }
        file_put_contents($file,$this->getStubContent($relateFilename));
    }

    protected function makeEmptyDir(string $relateDirName):void
    {
        $dir = $this->app->getRootPath().$relateDirName;
        if (is_dir($dir) === false){
            mkdir($dir,0755,true);
        }
    }

    protected function runMigrates()
    {
        $answer = strtolower($this->output->ask($this->input, '是否需要创建数据表? (Y/N) '));
        if($answer === 'y' || $answer==='yes'){
            $migratePath = $this->rootPath.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations';
            try {
                (new RunMigration())->setPath($migratePath)->handle();
            }catch (\Exception $e){
                $this->migrateStatus = false;
                $this->output->warning("创建数据表失败:".$e->getMessage());
                $this->output->info("您可以手动导入数据:".$migratePath.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.$this->sqlFile);
                return true;
            }
            $this->output->info("创建数据表成功！");
            return true;
        }
    }

    protected function runSeeds()
    {
        if($this->migrateStatus === false){
            $this->output->info("由于数据表创建失败，无法执行数据迁移！");
        }
        $answer = strtolower($this->output->ask($this->input, '是否需要同步数据表数据? (Y/N) '));
        if($answer === 'y' || $answer==='yes'){
            $seedsPath = $this->rootPath.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'seeds';
            try {
                (new RunSeed())->setPath($seedsPath)->handle();
            }catch (\Exception $e){
                $this->output->warning("数据迁移失败:".$e->getMessage());
                $this->output->info("您可以手动导入数据:".$seedsPath.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.$this->sqlFile);
                return true;
            }
            $this->output->info("数据迁移成功！");
            return true;
        }
        return true;
    }

    protected function makeRoute(string $name,string $content,bool $overwrite = false)
    {
        $file = app()->getRootPath().'huike'.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.$name.'.php';
        if (is_dir(dirname($file)) === false){
            mkdir(dirname($file),0755,true);
        }
        if (file_exists($file)){
            if($overwrite === false){
                $originContent = file_get_contents($file);
                file_put_contents($file.'.bak-'.HuikeConfig::VERSION,$originContent);
            }
        }
        file_put_contents($file,$content);
        $this->output->info("路由生成成功:".$file);
    }
}