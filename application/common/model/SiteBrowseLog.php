<?php

namespace app\common\model;

use think\Model;

/**
 * 站点浏览日志模型
 */
class SiteBrowseLog Extends Model
{

    // 表名
    protected $name = 'site_browse_log';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    // 追加属性
    protected $append = [
    ];
}
