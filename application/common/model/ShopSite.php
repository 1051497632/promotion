<?php

namespace app\common\model;

use think\Model;

// 商城网站信息模型
class ShopSite extends Model
{

    // 表名
    protected $name = 'shop_site';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    // 是否显示
    const SHOW_PAGE_YES     = 1; // 显示
    const SHOW_PAGE_NO      = 2; // 不显示

}
