<?php

namespace app\common\model;

use think\Model;

// 服务订单模型
class ServiceOrder extends Model
{

    // 表名
    protected $name = 'service_order';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    const STATUS_WAIT       = 1; // 待处理
    const STATUS_SUCCESSS   = 2; // 已处理

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function service()
    {
        return $this->belongsTo('Service', 'service_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
