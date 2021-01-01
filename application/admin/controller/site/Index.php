<?php

namespace app\admin\controller\site;

use app\common\controller\Backend;

class Index extends Backend
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Site');
    }

    public function index()
    {
        $this->searchFields = ['title', 'keyword'];
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['user.nickname', 'site.title'], true);

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

    public function add()
    {
        $this->modelValidate = 'Site.add';
        return parent::add();
    }

    public function edit($ids = '')
    {
        $this->modelValidate = 'Site.edit';
        return parent::edit($ids);
    }

    public function import()
    {
        $this->error('不能导入！');
    }
    

}
