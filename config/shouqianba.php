<?php

    return [
        'url'      => [
            'activate'  => 'https://api.shouqianba.com/terminal/activate' ,
            'checkin'   => 'https://api.shouqianba.com/terminal/checkin' ,
            'pay'       => 'https://api.shouqianba.com/upay/v2/pay' ,
            'query'     => 'https://api.shouqianba.com/upay/v2/query' ,
            'cancel'    => 'https://api.shouqianba.com/upay/v2/cancel' ,
            'refund'    => 'https://api.shouqianba.com/upay/v2/refund' ,
            'uploadLog' => 'https://api.shouqianba.com/terminal/upload Log' ,
        ] ,
        // device_id' => '11223344', //测试设备ID
        //'device_id' => '9001801000', //正式设备ID
        'device_id' => '800700000', //测试设备ID
        'subject'  => 'Sing Cut美发服务' ,
        'operator' => 'system' ,
    ];
