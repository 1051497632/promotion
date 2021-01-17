<?php

namespace app\admin\controller\service;

use app\common\controller\Backend;
use app\common\model\Service;
use think\Db;
use think\Exception;
use think\exception\PDOException;

class Index extends Backend
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Service');
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['name']);

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        $this->modelValidate = 'Service.add';
        return parent::add();
    }

    public function edit($ids = '')
    {
        $this->modelValidate = 'Service.edit';
        return parent::edit($ids);
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
    

}
