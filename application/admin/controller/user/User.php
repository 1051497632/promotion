<?php

namespace app\admin\controller\user;

use app\admin\validate\CustomInfo;
use app\admin\validate\User as ValidateUser;
use app\common\controller\Backend;
use app\common\library\Auth;
use app\common\model\CustomInfo as ModelCustomInfo;
use app\manage\library\Auth as LibraryAuth;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;

    /**
     * @var \app\admin\model\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['user.nickname', 'custominfo.company_name']);
            $list = $this->model
                ->with(['custominfo'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $k => $v) {
                $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
        }
        
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a", []);
            $custom_info = $this->request->post("custom_info/a", []);
            if ($params) {
                $params = $this->preExcludeFields($params);
                $custom_info = $this->preExcludeFields($custom_info);

                $result = false;

                $userValidate = new ValidateUser();
                if (!$userValidate->check($params, [], 'add')) {
                    $this->error($userValidate->getError());
                }

                $validate = new CustomInfo();
                if (!$validate->check($custom_info, [], 'add')) {
                    $this->error($validate->getError());
                }

                if ($custom_info['promotion_time']) {
                    $custom_info['promotion_time'] = strtotime($custom_info['promotion_time']);
                } else {
                    $custom_info['promotion_time'] = 0;
                }
                if ($custom_info['start_time']) {
                    $custom_info['start_time'] = strtotime($custom_info['start_time']);
                } else {
                    $custom_info['start_time'] = 0;
                }
                if ($custom_info['end_time']) {
                    $custom_info['end_time'] = strtotime($custom_info['end_time']);
                } else {
                    $custom_info['end_time'] = 0;
                }

                if (!empty($custom_info['district_info'])) {
                    $districtInfo = explode('/', $custom_info['district_info']);
                    if (count($districtInfo) >= 1) {
                        $custom_info['province_name'] = current($districtInfo);
                    }
                    if (count($districtInfo) >= 2) {
                        $custom_info['city_name'] = next($districtInfo);
                    }
                    if (count($districtInfo) >= 3) {
                        $custom_info['area_name'] = next($districtInfo);
                    }
                }

                Db::startTrans();
                try {
                    $result = $this->model->allowField(true)->save($params);

                    $custom_info['user_id'] = $this->model['id'];
                    ModelCustomInfo::create($custom_info, true);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
            $this->token();
        }
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a", []);
            $custom_info = $this->request->post("custom_info/a", []);
            if ($params) {
                $params = $this->preExcludeFields($params);
                $custom_info = $this->preExcludeFields($custom_info);

                $result = false;

                $userValidate = new ValidateUser();
                if (!$userValidate->check($params, [], 'edit')) {
                    $this->error($userValidate->getError());
                }

                $validate = new CustomInfo();
                if (!$validate->check($custom_info, [], 'edit')) {
                    $this->error($validate->getError());
                }

                if ($custom_info['promotion_time']) {
                    $custom_info['promotion_time'] = strtotime($custom_info['promotion_time']);
                } else {
                    $custom_info['promotion_time'] = 0;
                }
                if ($custom_info['start_time']) {
                    $custom_info['start_time'] = strtotime($custom_info['start_time']);
                } else {
                    $custom_info['start_time'] = 0;
                }
                if ($custom_info['end_time']) {
                    $custom_info['end_time'] = strtotime($custom_info['end_time']);
                } else {
                    $custom_info['end_time'] = 0;
                }
                
                if (!empty($custom_info['district_info'])) {
                    $districtInfo = explode('/', $custom_info['district_info']);
                    if (count($districtInfo) >= 1) {
                        $custom_info['province_name'] = current($districtInfo);
                    }
                    if (count($districtInfo) >= 2) {
                        $custom_info['city_name'] = next($districtInfo);
                    }
                    if (count($districtInfo) >= 3) {
                        $custom_info['area_name'] = next($districtInfo);
                    }
                }

                Db::startTrans();
                try {
                    $result = $row->allowField(true)->save($params);

                    $customInfo = ModelCustomInfo::getInfoByUserId($row['id']);
                    if ($customInfo) {
                        $customInfo->allowField(true)->save($custom_info);
                    } else {
                        $custom_info['user_id'] = $row['id'];
                        ModelCustomInfo::create($custom_info, true);
                    }
                    
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        } else {
            $this->view->assign("row", $row);
            $customInfo = ModelCustomInfo::getInfoByUserId($row['id']);
            if ($customInfo) {
                if ($customInfo['promotion_time'] > 0) {
                    $customInfo['promotion_time'] = date('Y-m-d H:i:s', $customInfo['promotion_time']);
                } else {
                    $customInfo['promotion_time'] = '';
                }
                if ($customInfo['start_time'] > 0) {
                    $customInfo['start_time'] = date('Y-m-d H:i:s', $customInfo['start_time']);
                } else {
                    $customInfo['start_time'] = '';
                }
                if ($customInfo['end_time'] > 0) {
                    $customInfo['end_time'] = date('Y-m-d H:i:s', $customInfo['end_time']);
                } else {
                    $customInfo['end_time'] = '';
                }
                
                if ($customInfo['province_name']) {
                    $customInfo['district_info'] = $customInfo['province_name'] . '/' .  $customInfo['city_name'] . '/' .  $customInfo['area_name'];
                }
            } else {
                $customInfo = [];
            }
            
            $this->view->assign("custom_info", $customInfo ? $customInfo : []);
        }
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        Auth::instance()->delete($row['id']);
        $this->success();
    }

    /**
     * 自动登录
     */
    public function auto_login()
    {
        $id = $this->request->get('id');
        if (!$id) {
            $this->error('id不能为空！');
        }
        $userInfo = model('User')->where('id', $id)->find();
        if (!$userInfo) {
            $this->error('客户不存在！');
        }
        
        $mamnageAuth = new LibraryAuth();
        $mamnageAuth->doLoginEvent($userInfo);

        $this->success('登录成功！', '/manage/dashboard?ref=addtabs');
    }

}
