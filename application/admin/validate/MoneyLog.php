<?php

namespace app\admin\validate;

use think\Validate;

class MoneyLog extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'money'         => 'number',
        'user_id'       => 'require',
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
        'add'  => ['money', 'user_id'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'money' => __('Money'),
            'user_id' => __('User_id'),
        ];
        parent::__construct($rules, $message, $field);
    }
    
}
