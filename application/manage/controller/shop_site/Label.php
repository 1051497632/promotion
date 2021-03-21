<?php

namespace app\manage\controller\shop_site;

use app\common\controller\Manage;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

class Label extends Manage
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ShopSiteLabel');
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['name'], true);

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

    public function add()
    {
        $this->modelValidate = 'ShopSiteLabel.edit';
        return parent::add();
    }

    public function edit($ids = '')
    {
        $this->modelValidate = 'ShopSiteLabel.edit';
        return parent::edit($ids);
    }

    public function import()
    {
        $this->error('不能导入！');
    }
    

}
