<?php

namespace app\manage\controller;

use app\common\controller\Manage;
use app\common\model\ServiceOrder;
use think\Config;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Manage
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $siteclicknumber = model('SiteBrowseLog')->alias('sbl')->join('__SITE__ s', 'sbl.site_id = s.id', 'INNER')->where('user_id', $this->auth->id)->count('sbl.id');

        // 用户信息
        $userInfo = model('User')->where('id', $this->auth->id)->find();
        // 获取服务列表
        $serviceList = model('Service')->order('id ASC')->select();
        foreach ($serviceList as &$serviceItem) {
            $serviceItem['wait_order_count'] = model('ServiceOrder')->where('user_id', $this->auth->id)->where('service_id', $serviceItem['id'])->where('status', ServiceOrder::STATUS_WAIT)->count();
            unset($serviceItem);
        }
        $this->view->assign([
            'totalviews'       => model('Site')->where('user_id', $this->auth->id)->count(),
            'totalorderamount' => $userInfo['money'],
            'siteclicknumber'   => $siteclicknumber,
            'serviceList'       => $serviceList,
        ]);

        return $this->view->fetch();
    }

    public function customer_info()
    {
        $this->assign('customer_tel', config('site.customer_tel'));
        $this->assign('customer_qrcode', config('site.customer_qrcode'));
        return $this->view->fetch();
    }

    /**
     * 创建订单
     */
    public function create_order()
    {
        $id = $this->request->post('id');
        if (!$id) {
            $this->error('id不能为空！');
        }
        $serviceInfo = model('Service')->where('id', $id)->find();
        if (!$serviceInfo) {
            $this->error('服务不存在！');
        }
        $orderCount = model('ServiceOrder')->where('user_id', $this->auth->id)->where('service_id', $serviceInfo['id'])->where('status', ServiceOrder::STATUS_WAIT)->count();
        if ($orderCount > 0) {
            $this->error('有正在审核中的服务！');
        }
        ServiceOrder::create([
            'user_id'   => $this->auth->id,
            'service_id'    => $serviceInfo['id'],
            'status'        => ServiceOrder::STATUS_WAIT
        ]);
        $this->success('操作成功，等待处理！');
    }
}
