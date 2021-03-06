<?php

namespace app\manage\controller\site;

use app\common\controller\Manage;

/**
 * 浏览记录
 */
class BrowseLog extends Manage
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SiteBrowseLog');
    }

    public function index()
    {
        $siteId = $this->request->get('site_id');
        if (!$siteId) {
            $this->error('siteid不能为空!');
        }
        $siteInfo = model('Site')->where('id', $siteId)->where('user_id', $this->auth->id)->find();
        if (!$siteInfo) {
            $this->error('网站不存在!');
        }

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
                ->where('site_id', $siteInfo['id'])
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
