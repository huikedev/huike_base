<?php


namespace huikedev\huike_base\contract;

use think\App;

/**
 * Desc
 * Class InitAbstract
 * Full \huikedev\huike_base\contract\InitAbstract
 * @package huikedev\huike_base\contract
 */
class InitAbstract
{
    /**
     * @var App
     */
    protected $app;
    protected $config;
    protected $huikePath;
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->config = $this->app->config->get('huike');
        $this->huikePath = $this->app->getRootPath().'huike';
    }
}