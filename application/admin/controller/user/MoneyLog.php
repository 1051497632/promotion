<?php

namespace app\admin\controller\user;

use app\admin\validate\MoneyLog as ValidateMoneyLog;
use app\common\controller\Backend;
use app\common\model\MoneyLog as ModelMoneyLog;
use app\common\model\User;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 会员余额变动管理
 *
 * @icon fa fa-circle-o
 */
class MoneyLog extends Backend
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('MoneyLog');
    }
    
    public function index()
    {
        // group
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['user.nickname', 'money_log.memo'], true);

            $list = $this->model
                ->with(['user'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    // 余额充值
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;

                $validate = new ValidateMoneyLog();
                if (!$validate->check($params, [], 'add')) {
                    $this->error($validate->getError());
                }
                if ($params['money'] == 0) {
                    $this->error('充值金额不能为空！');
                }

                Db::startTrans();
                try {
                    $result = User::money($params['money'], $params['user_id'], $params['memo'], $params['money'] > 0 ? ModelMoneyLog::TARGET_TYPE_RECHARGE : ModelMoneyLog::TARGET_TYPE_CONSUME, 0);
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

    // 编辑
    public function edit($ids = '')
    {
        $this->error('不能编辑');
    }

    // 删除
    public function del($ids = '')
    {
        $this->error('不能删除');
    }

    public function import()
    {
        parent::import();
    }

}
