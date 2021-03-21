<?php

namespace app\manage\controller\shop_site;

use app\common\controller\Manage;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

class Goods extends Manage
{
    
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ShopSiteGoods');
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
                ->where('user_id', $this->auth->id)
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
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                $site = model('ShopSite')->where('id', $params['shop_site_id'])->where('user_id', $this->auth->id)->find();
                if (!$site) {
                    $this->error('网站不存在！');
                }
                $params['user_id'] = $site['user_id'];

                $result = false;
                Db::startTrans();
                try {
                    $this->model->validateFailException(true)->validate('ShopSiteGoods.add');
                    $result = $this->model->allowField(true)->save($params);
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

    public function edit($ids = '')
    {
        $this->modelValidate = 'ShopSiteGoods.edit';
        return parent::edit($ids);
    }

    public function import()
    {
        $this->error('不能导入！');
    }
    

}
