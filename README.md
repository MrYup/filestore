## 更新

1.  移除本地存储, 要存储在本地,用`hf`的方法就好了。

2.  增加[cos](https://cloud.tencent.com/document/product/436/34282)存储

3.  增加`put`方法

4.  改善之前的代码

[扩展源码](https://gitee.com/lyxxxh/filestore/tree/master/src/Service)

## 安装

1. `composer require mryup/filestore`
​
2. `php bin/hyperf.php vendor:publish mryup/filestore`  // 发布配置
​
3. 配置`config/autoload/filestore.php`       //配置oss
```
'oss' => [
		'appid' => '',
		'appsec' => '',
		'bucket' => '',
		'endpoint' => '',
		'socket_timeout' => '5184000', // 设置Socket层传输数据的超时时间
		'connection_timeout' => '10', //建立链接的超时时间
		'save_path' => 'tem/storage/',  //存储目录
]
```

## 控制器使用
```
use Xxh\FileStore\Service\FileStoreAbstract;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
​

/**
* 文件管理
* @Inject
* @var FileStoreAbstract
*/
private $file;
​
//接收文件上传
public function filestore(RequestInterface $request, ResponseInterface $response)
{
	$path = $this->file->store(
	 $request->file('img')
	);
	return $this->file->url($path);    
	//http://r-card.oss-cn-beijing.aliyuncs.com/tem/storage/4b7dd3231926a340ab84d53316f17e03.png
}
​


public function put()
{
	$path = $this->file->put('1.txt','Hello World');
	return $this->file->url($path);
		  //http://r-card.oss-cn-beijing.aliyuncs.com/tem/storage/1.txt

}

```

## 使用cos


1.  新增`config/autoload/dependencies.php`配置
```
 Xxh\FileStore\Service\FileStoreAbstract::class => Xxh\FileStore\Service\CosFileStoreService::class 
```
2.  配置`config/autoload/filestore.php`的`cos`的信息



## 扩展方法 或者 重写 (以oss为例)

1.  随便创建一个文件 例:`app/Services/OssFileStoreService.php`

2.  修改`config/autoload/dependencies.php`为  
```
Xxh\FileStore\Service\FileStoreAbstract::class => App\Services\OssFileStoreService::class
```
3.  `OssFileStoreService.php内容`

```
class OssFileStoreService extends Xxh\FileStore\Service\OssFileStoreService
{
//重写oss put方法
public function put($filename,$str)
{
	..... 
}

//新增oss delObject方法  
public function delObject($filename) 
{ 
	$this->getClient()->deleteObjects($this->config['bucket'],$filename);  
}

}

```


## 扩展提供的方法

见[FileStoreAbstract.php](https://gitee.com/lyxxxh/filestore/blob/master/src/Service/FileStoreAbstract.php)

## 超过2m无法上传

默认只能上传2M的文件，
请看[swoole文档 (package_max_length)](https://wiki.swoole.com/wiki/page/301.htmlhttps://wiki.swoole.com/wiki/page/301.html)


可在config/server.php的
```
settings =>[
    'package_max_length' => 5 * 1024 * 1024 , //5M
    .....
]
```