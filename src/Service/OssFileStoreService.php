<?php


namespace Xxh\FileStore\Service;


use Hyperf\HttpMessage\Upload\UploadedFile;
use OSS\OssClient;

class OssFileStoreService extends FileStoreAbstract
{

    public function __construct()
    {
        $this->config = config('filestore.oss');
        if( $this->getClient() == null)
        $this->initClient();
    }

    public function initClient()
    {

        try {
            $this->client = new OssClient($this->config['appid'], $this->config['appsec'], $this->config['endpoint']);
            $this->client->setTimeout( $this->config['socket_timeout']);
            $this->client->setConnectTimeout( $this->config['connection_timeout']);
        } catch (OssException $e) {
            throw new \RuntimeException('Link failure  '.$e->getMessage());
        }
    }


    public function fileExists($file)
    {
        return $this->getClient()->doesObjectExist($this->config['bucket'],$file)
            ? true : false;
    }

    public function store($file)
    {
        if(! $file instanceof  UploadedFile)
        throw new \RuntimeException('file must be Hyperf\HttpMessage\Upload\UploadedFile');

        $saveFilePath = $this->addPrefix(
            $this->hashFileName($file)
        );

        if( $this->fileExists($saveFilePath))  //如果文件存在了
        return $saveFilePath;

        $this->getClient()->uploadFile($this->config['bucket'],$saveFilePath,$file->getPathname());
        return $saveFilePath;
    }



    public function url($path)
    {
        return $this->getClient()->signUrl($this->config['bucket'],$path,3600);
    }


    public function getClient()
    {
        return $this->client;
    }


    public function put($filename,$str)
    {
       try{
           $this->getClient()->putObject($this->config['bucket'],$this->config['save_path'] . $filename,$str);
           return $this->config['save_path'] . $filename;
        } catch(\Exception  $e) {
            throw new \RuntimeException("put fail " . $e->getMessage());
       }
    }


}