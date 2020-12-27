<?php

namespace app\admin\controller\site;

use app\common\controller\Backend;

/**
 * 浏览记录
 */
class BrowseLog extends Backend
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SiteBrowseLog');
    }

    public function index()
    {
        $this->searchFields = ['ip'];
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['ip']);

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
        $this->error('不能添加');
    }

    public function edit($ids = '')
    {
        $this->error('不能修改');
    }

    public function del($ids = '')
    {
        $this->error('不能删除');
    }

    public function import()
    {
        $this->error('不能导入！');
    }
    

}
