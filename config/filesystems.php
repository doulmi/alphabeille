<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [
        'qiniu' => [
            'driver'  => 'qiniu',
            'domains' => [
                'default'   => 'idux73p.qiniudns.com', //你的七牛域名
                'https'     => 'alpha-beille.com',         //你的HTTPS域名
                'custom'    => 'alpha-beille.com',     //你的自定义域名
            ],
            'access_key'=> 'tjX-2-ARL_7AlQhP5b5H0Mx0Cb1DcFkx50dwsskv',  //AccessKey
            'secret_key'=> 'k6A5lkZUaM2Ft0t9IB7LFu0b5vRKeZjy-ly4MZLM',  //SecretKey
            'bucket'    => 'alphabeille',  //Bucket名字
            'notify_url'=> 'http://alpha-beille.com',  //持久化处理回调地址
        ],

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

    ],

];
