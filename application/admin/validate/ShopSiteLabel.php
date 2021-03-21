<?php

namespace app\admin\validate;

use think\Validate;

class ShopSiteLabel extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'user_id'       => 'require|number|gt:0',
        'name'          => 'require|max:50',
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
        'add'  => ['user_id', 'name'],
        'edit' => ['name'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'user_id'  => __('User_id'),
            'name'      => __('Name'),
        ];
        parent::__construct($rules, $message, $field);
    }
    
}
