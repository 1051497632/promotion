<?php

namespace app\manage\controller\site;

use app\common\controller\Manage;

class Message extends Manage
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SiteMessage');
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['site_message.username', 'site_message.phone', 'site.title'], true);

            $list = $this->model
                ->with(['site'])
                ->where('site.user_id', $this->auth->id)
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
    

}
