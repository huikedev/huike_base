<?php


namespace huikedev\huike_base\response\dispatch;

use huikedev\huike_base\exceptions\response\ResponseDownloadException;
use huikedev\huike_base\response\ResponseDispatch;
use think\File;

/**
 * Desc
 * Class Download
 * Full \huikedev\huike_base\response\dispatch\Download
 * @package huikedev\huike_base\response\dispatch
 */
class Download extends ResponseDispatch
{
    public function render()
    {
        $file = $this->logic->getFilePath();
        $fileData = $this->logic->getFileData();
        if(is_null($file)===false){
            return $this->fileRender($file);
        }
        if(is_null($fileData)===false){
            return $this->fileDataRender($fileData);
        }
        throw new ResponseDownloadException('文件路径或文件流为NULL',3);
    }

    protected function fileRender($file)
    {
        if(is_string($file) && file_exists($file)){
            $filePath = str_replace('\\',DIRECTORY_SEPARATOR,$file);
            $filePath = str_replace('/',DIRECTORY_SEPARATOR,$filePath);
            $saveName = $this->logic->getFileSavedName();
            if(empty($saveName)){
                $saveName = pathinfo($file,PATHINFO_FILENAME);
            }
        }elseif ($file instanceof File){
            $filePath = $file->getPathname();
            $saveName = $this->logic->getFileSavedName();
            if(empty($saveName)){
                $saveName = $file->getFilename();
            }
        }else{
            throw new ResponseDownloadException($file.'不存在',1);
        }
        return download($filePath,$saveName)->expire(0);
    }
    /*
     * 二进制文件流下载
     */
    protected function fileDataRender($fileData)
    {
        if(empty($this->logic->getFileMime())){
            throw new ResponseDownloadException('二进制文件流下载请设置FileMime',2);
        }
        $saveName = $this->logic->getFileSavedName();
        if(empty($saveName)){
            $saveName = '';
        }
        return download($fileData,$saveName)->isContent(true)->mimeType($this->logic->getFileMime())->expire(0);

    }
}