<?php

namespace app\common\model;

use think\Model;

//商城网站商品模型
class ShopSiteGoods extends Model
{

    // 表名
    protected $name = 'shop_site_goods';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    const RECOMMEND_NO  = 1; // 不推荐
    const RECOMMEND_YES = 2; // 推荐

}
