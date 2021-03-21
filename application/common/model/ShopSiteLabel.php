<?php

namespace app\common\model;

use think\Model;

// 商城网站标签模型
class ShopSiteLabel extends Model
{

    // 表名
    protected $name = 'shop_site_label';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

}
