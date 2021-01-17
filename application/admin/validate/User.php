<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'regex:\w{3,32}|unique:user',
        'nickname' => 'require|unique:user',
        'password' => 'regex:\S{6,32}',
        'email'    => 'email|unique:user',
        'mobile'   => 'require|unique:user',
        'company_name'  => 'require|max:50',
        'wechat_number' => 'max:32',
        'wechat_qrcode' => 'max:255',
        'pre_payment'   => 'number',
        'balance_payment'   => 'number',
        'full_payment'   => 'number',
        'cooperative_way'   => 'number',
        'idcard_front'   => 'max:255',
        'idcard_reverse'   => 'max:255',
        'business_certificate'   => 'max:255',
        'industry_name'   => 'max:50',
        'province_name'   => 'max:20',
        'city_name'   => 'max:10',
        'area_name'   => 'max:10',
        'is_edit'   => 'number',
        'promotion_time'   => 'max:50',
        'start_time'   => 'date',
        'end_time'   => 'date',
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
        'add'  => ['username', 'email', 'nickname', 'password', 'email', 'mobile'],
        'edit' => ['username', 'email', 'nickname', 'password', 'email', 'mobile'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'username' => __('Username'),
            'nickname' => __('Nickname'),
            'password' => __('Password'),
            'email'    => __('Email'),
            'mobile'   => __('Mobile')
        ];
        parent::__construct($rules, $message, $field);
    }

}
