<?php


namespace Xxh\FileStore\Service;


use Hyperf\HttpMessage\Upload\UploadedFile;
use OSS\OssClient;
use Qcloud\Cos\Client;
class CosFileStoreService extends FileStoreAbstract
{



    public function __construct()
    {
        $this->config = config('filestore.cos');

        if( $this->getClient() == null)
        $this->initClient();
    }

    public function initClient()
    {
        try {
            
            $this->client = new Client([
            'region' => $this->config['region'],
            'credentials' => [
                'secretId' => $this->config['secretId'],
                'secretKey' => $this->config['secretKey']
            ]]);
                        
        } catch (Exception $e) {
            throw new \RuntimeException('Link fail  '.$e->getMessage());
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

        if( $this->fileExists($saveFilePath))
        return $saveFilePath;

        $this->put($saveFilePath,
            fopen($file->getPathname(),'rb')
        );
        return $saveFilePath;
    }



    public function put($filename,$str)
    {
      try {
        $filename = $this->addPrefix($filename); 
        $result = $this->getClient()->putObject([
            'Bucket' => $this->config['bucket'],
            'Key' => $filename,
            'Body' => $str
        ]);
        
        return $filename;
     } catch (\Exception $e) {
        throw new \RuntimeException("put fail " . $e->getMessage());
     }

    }


    public function url($path)
    {
        return $this->getClient()->getObjectUrl($this->config['bucket'],$path,'+10 minutes');
    }





}