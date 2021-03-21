<?php

namespace app\admin\validate;

use think\Validate;

class ShopSiteGoods extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'shop_site_id'  => 'require|number|gt:0',
        'name'          => 'require',
        'image'         => 'require',
        'price'         => 'require|number',
        'is_recommend'  => 'require',
        'weigh'       => 'number|gt:0',
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
        'add'  => ['shop_site_id', 'name', 'image', 'price', 'is_recommend', 'weigh'],
        'edit' => ['name', 'image', 'price', 'is_recommend', 'weigh'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'shop_site_id'  => __('Shop_site_id'),
            'name'      => __('Name'),
            'image'     => __('Image'),
            'price'     => __('Price'),
            'is_recommend'  => __('Is_recommend'),
            'weigh'     => __('Weigh'),
        ];
        parent::__construct($rules, $message, $field);
    }
    
}
