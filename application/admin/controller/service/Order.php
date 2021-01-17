<?php

namespace app\admin\controller\service;

use app\common\controller\Backend;
use app\common\model\Service;
use app\common\model\ServiceOrder;
use think\Db;
use think\Exception;
use think\exception\PDOException;

class Order extends Backend
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ServiceOrder');
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['user.nickname', 'service.name'], true);

            $list = $this->model
                ->where($where)
                ->with(['user', 'service'])
                ->order($sort, $order)
                ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        $this->error('不能添加！');
    }

    public function edit($ids = '')
    {
        $this->error('不能编辑！');
    }

    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        if ($ids) {
            $list = $this->model->where('id', 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $v) {
                    $count += $v->delete();
                    Service::delEvent($v['id']);
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    public function import()
    {
        $this->error('不能导入！');
    }

    // 处理订单
    public function  deal()
    {
        $id = $this->request->get('id');
        $orderInfo = $this->model->where('id', $id)->find();
        if ($orderInfo['status'] == ServiceOrder::STATUS_SUCCESSS) {
            $this->error('订单已处理');
        }

        $orderInfo->save(['status' => ServiceOrder::STATUS_SUCCESSS]);

        $this->success('处理成功');
    }
    
}
