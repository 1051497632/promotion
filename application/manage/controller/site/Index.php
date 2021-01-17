<?php

namespace app\manage\controller\site;

use app\common\controller\Manage;
use app\common\model\CustomInfo;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * @icon fa fa-circle-o
 */
class Index extends Manage
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(['title'], true);

            $list = $this->model
                ->where($where)
                ->where('user_id', $this->auth->id)
                ->order($sort, $order)
                ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        } else {
            $customInfo = CustomInfo::getInfoByUserId($this->auth->id, ['is_edit']);
            if (!$customInfo ||  $customInfo['is_edit'] == CustomInfo::EDIT_NO) {
                $isEdit = CustomInfo::EDIT_NO;
            } else {
                $isEdit = CustomInfo::EDIT_YES;
            }
            $this->assign('isEdit', $isEdit);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        $customInfo = CustomInfo::getInfoByUserId($this->auth->id, ['is_edit']);
        if (!$customInfo ||  $customInfo['is_edit'] == CustomInfo::EDIT_NO) {
            $this->error('您不能编辑网站');
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                $params['user_id'] = $this->auth->id;
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    $this->model->validateFailException(true)->validate('Site.add');
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

    public function edit($ids = null)
    {
        $customInfo = CustomInfo::getInfoByUserId($this->auth->id, ['is_edit']);
        if (!$customInfo ||  $customInfo['is_edit'] == CustomInfo::EDIT_NO) {
            $this->error('您不能编辑网站');
        }

        return parent::edit($ids);
    }

    public function import()
    {
        $this->error('不能导入！');
    }
    

}
