<?php

namespace app\common\model;

use think\Model;

/**
 * 商城站点留言模型
 */
class ShopSiteMessage Extends Model
{

    // 表名
    protected $name = 'shop_site_message';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    // 追加属性
    protected $append = [
    ];

    const STATUS_WAIT       = 1; // 待处理
    const STATUS_SUCCESS    = 2; // 已处理

    public function shopsite()
    {
        return $this->belongsTo('ShopSite', 'shop_site_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
