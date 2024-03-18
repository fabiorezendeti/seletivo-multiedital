<?php

namespace App\Services;

class UploadFileService
{

    private int $uploadMaxSize;

    public function __construct()
    {

        $this->uploadMaxSize = (int) ini_get("upload_max_filesize");
    }

    public function getUploadMaxSizeInBytes()
    {
        return $this->uploadMaxSize * 1024;
    }

    public function getUploadMaxSizeToView()
    {
        return $this->uploadMaxSize - 1;
    }


}