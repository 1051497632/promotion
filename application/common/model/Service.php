<?php

namespace app\common\model;

use think\Model;

// 服务模型
class Service extends Model
{

    // 表名
    protected $name = 'service';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    public static function delEvent($id)
    {
        model('ServiceOrder')->where('service_id', $id)->delete();
    }

}
