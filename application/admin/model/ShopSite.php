<?php

namespace app\admin\model;

use app\common\model\ShopSite as ModelShopSite;

// 商城网站信息模型
class ShopSite extends ModelShopSite
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    protected function setOnlineStartTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setOnlineEndTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }
}
