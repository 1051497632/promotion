<?php

namespace app\admin\validate;

use think\Validate;

class ShopSite extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'user_id'       => 'require|number|gt:0',
        'title'         => 'require|max:255',
        'keyword'       => 'require|max:255',
        'desc'          => 'max:11',
        'logo'          => 'require|max:255',
        'banner_images' => 'require|max:1000',
        'about_us'      => 'max:3000',
        'mobile'        => 'number',
        'qrcode_image'      => 'max:255',
        'online_start_time' => 'require|date',
        'online_end_time'   => 'require|date',
        'show_page'         => 'require|number',
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
        'add'  => ['user_id', 'title', 'keyword', 'desc', 'logo', 'banner_images', 'about_us', 'mobile', 'qrcode_image', 'online_start_time', 'online_end_time', 'page_show'],
        'edit' => ['title', 'keyword', 'desc', 'logo', 'banner_images', 'about_us', 'mobile', 'qrcode_image', 'online_start_time', 'online_end_time', 'page_show'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'user_id'   => __('User_id'),
            'title'     => __('Title'),
            'keyword'   => __('Keyword'),
            'desc'      => __('Desc'),
            'logo'      => __('Logo'),
            'banner_images' => __('Banner_images'),
            'about_us'  => __('About_us'),
            'mobile'    => __('Mobile'),
            'qrcode_image'  => __('Qrcode_image'),
            'online_start_time' => __('Online_start_time'),
            'online_end_time'   => __('Online_end_time'),
            'show_page'         => __('Show_page'),
        ];
        parent::__construct($rules, $message, $field);
    }
    
}
