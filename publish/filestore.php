<?php

return [


    // 将配置更换成你的  
    'oss' => [
        'appid' => '',
        'appsec' => '',
        'bucket' => '',
        'endpoint' => '',
        'socket_timeout' => '5184000', // 设置Socket层传输数据的超时时间
        'connection_timeout' => '10', //建立链接的超时时间
        'save_path' => 'tem/storage/',  //存储目录   
     ],

   
     'cos' => [    
        'secretId' => '',   //
        'secretKey' => '',
        'bucket' => '',
        'region' => '',
        'save_path' => 'tem/'//存储目录 
    ]


];