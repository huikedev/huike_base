<?php


namespace huikedev\huike_base\base;

use huikedev\huike_base\app_const\NoticeType;
use huikedev\huike_base\app_const\response\AppResponseType;

/**
 * Desc
 * Class LogicTrait
 * Full \huikedev\huike_base\base\LogicTrait
 * @package huikedev\huike_base\base
 */
trait LogicTrait
{
    protected $code=0;
    protected $noticeType=NoticeType::SILENT;
    protected $msg;
    protected $data;
    protected $returnType=AppResponseType::DEFAULT;//html json download redirect
    //url跳转
    protected $redirectUrl;
    //file直接打开
    protected $filePath;
    protected $fileSavedName;
    //file二进制
    protected $fileData;
    protected $fileMime;

    public function getCode()
    {
        return $this->code;
    }
    public function getMsg()
    {
        return $this->msg;
    }
    public function getData()
    {
        return $this->data;
    }
    public function getNoticeType()
    {
        return $this->noticeType;
    }
    public function getReturnType()
    {
        return $this->returnType;
    }
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getFileData()
    {
        return $this->fileData;
    }
    public function getFileSavedName()
    {
        return $this->fileSavedName;
    }
    public function getFileMime()
    {
        return $this->fileMime;
    }
}