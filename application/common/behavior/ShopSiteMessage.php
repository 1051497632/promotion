<?php

namespace app\common\behavior;

use app\common\library\Sms;

class ShopSiteMessage
{

    // 消息添加成功事件
    public function add_success(&$shopSiteMessageInfo, &$shopSiteInfo)
    {
        $userInfo = model('User')->where('id', $shopSiteInfo['user_id'])->find();
        if ($userInfo) {
            // 发送短信消息
            Sms::notice($userInfo['mobile'], [
                'tel'       => $userInfo['mobile'],
            ], 'SMS_209550746');
        }
        
        return true;
    }

}