<?php

namespace app\admin\controller\shop_site;

use app\common\controller\Backend;
use app\common\model\ShopSiteMessage;
use think\Db;
use think\Exception;
use think\exception\PDOException;

class Message extends Backend
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ShopSiteMessage');
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['shop_site_message.phone', 'shopsite.title'], true);

            $list = $this->model
                ->with(['shopsite'])
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
        $this->error('不能添加！');
    }

    public function edit($ids = '')
    {
        $this->error('不能修改！');
    }

    public function delete($ids = '')
    {
        $this->error('不能删除');
    }

    public function import()
    {
        $this->error('不能导入！');
    }
    
    /**
     * 处理消息
     */
    public function deal($ids)
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
                    if ($v['status'] == ShopSiteMessage::STATUS_WAIT) {
                        $v->save([
                            'status'    => ShopSiteMessage::STATUS_SUCCESS,
                            'deal_time' => time()
                        ]);
                        ++$count;
                    }
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
                $this->error(__('未处理任何行'));
            }
        }
    }
}
