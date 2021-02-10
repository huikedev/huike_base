<?php


namespace huikedev\huike_base;

use huike\common\init\HuikeConsole;
use huike\common\init\HuikeExtraValidate;
use huike\common\init\HuikePaginator;
use huike\common\init\HuikeQuery;
use huike\common\middlewares\app\GlobalBeforeMiddleware;
use huike\common\exception\HuikeExceptionHandle;
use huikedev\huike_base\command\HuikeInstall;
use think\exception\Handle;
use think\Service;

/**
 * Desc
 * Class HuikeService
 * Full \huikedev\huike_base\HuikeService
 * @package huike
 */
class HuikeService extends Service
{
    public function register()
    {
        if($this->app->config->get('huike.is_installed',false)){
            $this->registerCommands();
            $this->registerGlobalMiddleWares();
            $this->registerQuery();
            $this->registerExceptionHandle();
            $this->registerPaginator();
            $this->appendValidateRule();
        }else{
            $this->commands(HuikeInstall::class);
        }
    }

    public function boot()
    {
        // 服务启动
    }

    /**
     * 注册命令行指令
     */
    protected function registerCommands()
    {
        if(PHP_SAPI ==='cli'){
            $huikeConsole = new HuikeConsole($this->app);
            $this->commands($huikeConsole->getCommands());
        }

    }

    /**
     * 注册全局中间件，路由中间件在路由中设置
     */
    protected function registerGlobalMiddleWares()
    {
        $this->app->middleware->add(GlobalBeforeMiddleware::class);
    }

    protected function registerQuery(): void
    {
        if($this->app->config->get('huike.replace_query',true)){
            $connections = $this->app->config->get('database.connections');

            $connections['mysql']['query'] = HuikeQuery::class;

            $this->app->config->set([
                'connections' => $connections
            ], 'database');
        }
    }

    protected function registerExceptionHandle()
    {
        $this->app->bind(Handle::class, HuikeExceptionHandle::class);
    }

    protected function registerPaginator()
    {
        if($this->app->config->get('huike.paginator.replace',true)) {
            $this->app->bind('think\Paginator', HuikePaginator::class);
        }
    }

    protected function appendValidateRule()
    {
        if($this->app->config->get('huike.extra_validate',true)) {
            (new HuikeExtraValidate())->handle();
        }
    }
}