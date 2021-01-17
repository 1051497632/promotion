<?php

namespace app\admin\validate;

use think\Validate;

class Service extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'name'          => 'require|max:255',
        'price'         => 'number',
        'discount'      => 'number',
        'service_time'  => 'require|max:50',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'user_id.gt'    => '客户不能为空'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['name', 'price', 'discount', 'service_time'],
        'edit' => ['name', 'price', 'discount', 'service_time'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'name' => __('Name'),
            'price' => __('Price'),
            'discount' => __('Discount'),
            'service_time'    => __('Service_time'),
        ];
        parent::__construct($rules, $message, $field);
    }
    
}
