<?php

namespace app\admin\model;

use app\common\model\MoneyLog as ModelMoneyLog;

/**
 * 会员余额日志模型
 */
class MoneyLog Extends ModelMoneyLog
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
