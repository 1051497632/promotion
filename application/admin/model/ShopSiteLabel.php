<?php

namespace app\admin\model;

use app\common\model\ShopSiteLabel as ModelShopSiteLabel;

// 商城网站标签模型
class ShopSiteLabel extends ModelShopSiteLabel
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
