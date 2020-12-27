<?php

namespace app\admin\validate;

use think\Validate;

class CustomInfo extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
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
        'promotion_time'   => 'date',
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
        'add'  => ['company_name', 'wechat_number', 'wechat_qrcode', 'pre_payment', 'balance_payment', 'full_payment', 'cooperative_way', 'idcard_front', 'idcard_reverse', 'business_certificate', 'industry_name', 'province_name', 'city_name', 'area_name', 'is_edit', 'promotion_time', 'start_time', 'end_time'],
        'edit' => ['company_name', 'wechat_number', 'wechat_qrcode', 'pre_payment', 'balance_payment', 'full_payment', 'cooperative_way', 'idcard_front', 'idcard_reverse', 'business_certificate', 'industry_name', 'province_name', 'city_name', 'area_name', 'is_edit', 'promotion_time', 'start_time', 'end_time'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'company_name'  => __('Company_name'), 
            'wechat_number' => __('Wechat_number'),
            'wechat_qrcode' => __('Wechat_qrcode'),
            'pre_payment'   => __('Pre_payment'),
            'balance_payment'   => __('Balance_payment'),
            'full_payment'      => __('Full_payment'),
            'cooperative_way'   => __('Cooperative_way'),
            'idcard_front'      => __('Idcard_front'),
            'idcard_reverse'    => __('Idcard_reverse'),
            'business_certificate'  => __('Business_certificate'),
            'industry_name'     => __('Industry_name'),
            'province_name'     => __('Province_name'),
            'city_name'         => __('City_name'),
            'area_name'         => __('Area_name'),
            'is_edit'           => __('Is_edit'),
            'promotion_time'    => __('Promotion_time'),
            'start_time'        => __('Start_time'),
            'end_time'          => __('End_time'), 
        ];
        parent::__construct($rules, $message, $field);
    }

}
