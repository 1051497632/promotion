<?php

namespace app\manage\controller\user;

use app\admin\validate\MoneyLog as ValidateMoneyLog;
use app\common\controller\Manage;
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
class MoneyLog extends Manage
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('MoneyLog');
    }
    
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['memo'], true);

            $list = $this->model
                ->where($where)
                ->where('user_id', $this->auth->id)
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
        $this->error('不能添加');
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
