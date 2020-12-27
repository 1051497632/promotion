<?php

namespace app\common\behavior;

use app\common\model\User as ModelUser;

class User
{

    // 用户删除成功事件
    public function user_delete_successed(&$user)
    {
        ModelUser::delEvelt($user['id']);
        return true;
    }

}
