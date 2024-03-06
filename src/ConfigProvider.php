<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hornor\FileStore;

use Hornor\FileStore\Service\FileStoreAbstract;
use Hornor\FileStore\Service\OssFileStoreService;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                FileStoreAbstract::class => OssFileStoreService::class  //文件管理    oss
            ],

            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],

           'publish' => [
               [
                   'id' => 'config',
                   'description' => 'The config for filestore.',
                   'source' => __DIR__ . '/../publish/filestore.php',
                   'destination' => BASE_PATH . '/config/autoload/filestore.php',
               ],
           ]

        ];
    }
}