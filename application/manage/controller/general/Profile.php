<?php

namespace app\manage\controller\general;

use app\common\controller\Manage;
use app\common\model\CustomInfo;
use app\common\model\User;
use app\manage\validate\CustomInfo as ValidateCustomInfo;
use fast\Random;
use think\Db;
use think\Exception;
use think\Session;
use think\Validate;

/**
 * 个人配置
 *
 * @icon fa fa-user
 */
class Profile extends Manage
{

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            $this->model = model('AdminLog');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->where($where)
                ->where('admin_id', $this->auth->id)
                ->order($sort, $order)
                ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        } else {
            $custom_info = CustomInfo::getInfoByUserId($this->auth->id);
            if (!$custom_info) {
                $custom_info = [];
            }
            $this->assign('custom_info', $custom_info);
        }
        return $this->view->fetch();
    }

    /**
     * 更新个人信息
     */
    public function update()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a",[]);
            $params = array_filter(array_intersect_key(
                $params,
                array_flip(array('email', 'nickname', 'password'))
            ));

            $customInfoParams = $this->request->post('custom_info/a', []);
            $customInfoParams = array_filter(array_intersect_key(
                $customInfoParams,
                array_flip(array('wechat_qrcode', 'wechat_number'))
            ));
            
            $customInfoValidate = new ValidateCustomInfo();
            if (!$customInfoValidate->check($customInfoParams, [], 'edit')) {
                $this->error($customInfoValidate->getError());
            }
            
            if (!Validate::is($params['email'], "email")) {
                $this->error(__("Please input correct email"));
            }
            if (isset($params['password'])) {
                if (!Validate::is($params['password'], "/^[\S]{6,16}$/")) {
                    $this->error(__("Please input correct password"));
                }
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
            }
            $exist = User::where('email', $params['email'])->where('id', '<>', $this->auth->id)->find();
            if ($exist) {
                $this->error(__("Email already exists"));
            }
            if ($params) {
                Db::startTrans();
                try {
                    $admin = User::get($this->auth->id);
                    $admin->save($params);

                    if (!empty($customInfoParams)) {
                        $custom_info = CustomInfo::getInfoByUserId($this->auth->id);
                        if ($custom_info) {
                            $custom_info->save($customInfoParams);
                        } else {
                            $customInfoParams['user_id'] = $this->auth->id;
                            CustomInfo::create($customInfoParams);
                        }
                    }

                    //因为个人资料面板读取的Session显示，修改自己资料后同时更新Session
                    Session::set("manage", $admin->toArray());

                    Db::commit();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                
                $this->success();
            }
            $this->error();
        }
        return;
    }
}
