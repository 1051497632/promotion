<?php

namespace app\admin\validate\site;

use think\Validate;

class Site extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'         => 'require|max:255',
        'keyword'       => 'require|max:255',
        'mobile'        => 'require|max:11',
        'remark'        => 'max:255',
        'pc_content'    => '',
        'mobile_content'    => '',
        'page_show'     => 'number',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['title', 'keyword', 'mobile', 'remark', 'pc_content', 'mobile_content', 'page_show'],
        'edit' => ['title', 'keyword', 'mobile', 'remark', 'pc_content', 'mobile_content', 'page_show'],
    ];
    
}
