<?php

namespace app\manage\validate;

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
        'add'  => ['title', 'keyword', 'mobile', 'remark', 'page_show'],
        'edit' => ['title', 'keyword', 'mobile', 'remark', 'page_show'],
    ];
    
    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'title' => __('Title'),
            'keyword' => __('Keyword'),
            'mobile' => __('Mobile'),
            'remark'    => __('Remark'),
            'page_show'    => __('Page_show'),
        ];
        parent::__construct($rules, $message, $field);
    }
    
}
