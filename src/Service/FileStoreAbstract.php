<?php

namespace Xxh\FileStore\Service;


abstract class FileStoreAbstract
{



    protected $config;
    protected $client = null;
    //初始化配置
    abstract function __construct();


    /*
     *文件是否存在
     * @return bool
     */
    abstract function fileExists($path);

    /*
     * 根据文件的md5保存
     *@return string 保存文件的路径
     */
    abstract function store($file);


    /*
     * 域名 + 文件保存路径
     * @return string 返回文件的完整路径
     */
    abstract function url($path);


    /*
     * @param $filename 文件名
     * @param $str 要写入的字符串
	 * @return 路径
     */
    abstract function put($filename,$str);

    
	//增加云存储文件路径
    protected function addPrefix($filename)
    {
        return  $this->config['save_path'].$filename;
    }

    //链接对象
    protected function getClient()
    {
        return $this->client;
    }


	// 根据文件的md5生成名称
    protected function hashFileName($file)
    {
        return md5_file($file->getPathname()).'.'.$file->getExtension();
    }


}
