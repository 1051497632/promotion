<?php

namespace app\manage\validate;

use think\Validate;

class CustomInfo extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'wechat_number' => 'max:32',
        'wechat_qrcode' => 'max:255',
    ];

    /**
     * 字段描述
     */
    protected $field = [
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
        'edit' => ['wechat_number', 'wechat_qrcode'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'wechat_number' => __('Wechat_number'),
            'wechat_qrcode' => __('Wechat_qrcode'),
        ];
        parent::__construct($rules, $message, $field);
    }

}
