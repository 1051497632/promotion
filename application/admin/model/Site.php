<?php

namespace app\admin\model;

use app\common\model\Site as ModelSite;

// 网站信息模型
class Site extends ModelSite
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
