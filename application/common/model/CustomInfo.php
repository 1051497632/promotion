<?php

namespace app\common\model;

use think\Model;

/**
 * 客户信息模型
 */
class CustomInfo Extends Model
{

    // 表名
    protected $name = 'custom_info';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = '';
    protected $updateTime = '';
    // 追加属性
    protected $append = [
    ];

    // 是否可以编辑网站
    const EDIT_YES  = 1; // 可以编辑
    const EDIT_NO   = 2; // 不可以编辑

    // 合作方式
    const COOPERATIVE_WAY_COMMON = 1; // 普通推广
    const COOPERATIVE_WAY_BDIAU  = 2; // 百度竞价
    const COOPERATIVE_WAY_NEWS   = 3; // 新闻优化
    const COOPERATIVE_WAY_THOUSANDS_WORD = 4; // 万词霸屏

    public static function getInfoByUserId($userId, $fields = ['*'])
    {
        return model('CustomInfo')->where('user_id', $userId)->order('id DESC')->find();
    }
}
