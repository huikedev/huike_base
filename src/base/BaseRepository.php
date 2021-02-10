<?php


namespace huikedev\huike_base\base;

abstract class BaseRepository
{
    protected $model;
    protected $instance;
    protected $hasWhereCount = 0;
    protected $hasCount = 0;
    abstract protected function setInstance();
    abstract protected function getNewQuery();
    public function __construct()
    {
        $this->setInstance();
    }

    public function newInstance()
    {
        $this->setInstance();
        return $this;
    }
}