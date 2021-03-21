<?php

namespace app\admin\model;

use app\common\model\ShopSiteGoods as ModelShopSiteGoods;

// 商城网站信息模型
class ShopSiteGoods extends ModelShopSiteGoods
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
