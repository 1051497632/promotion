<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用行为扩展定义文件
return [
    // 应用结束
    'app_end'      => [
        'app\\admin\\behavior\\AdminLog',
    ],
    'user_delete_successed' => [
        ['app\\common\\behavior\\User', 'user_delete_successed']
    ],
    'sms_notice'     => [
        ['app\\common\\behavior\\Sms', 'notice']
    ]
];
