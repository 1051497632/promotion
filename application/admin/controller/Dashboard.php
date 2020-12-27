<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
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
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $config = Config::get("composer");
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $todayStartTime = strtotime(date('Y-m-d'));
        $todayendTime = $todayStartTime + 24 * 3600;
        $seventEndTime = time();
        $seventStartTime = $seventEndTime - 7 * 24;
        $this->view->assign([
            'totaluser'        => model('User')->count(),
            'totalviews'       => model('SiteBrowseLog')->count(),
            'totalorder'       => 32143,
            'totalorderamount' => model('MoneyLog')->where('money', 'GT', 0)->sum('money'),
            'todayuserlogin'   => model('User')->where('logintime', 'ELT', $todayendTime)->where('logintime', 'GT', $todayStartTime)->count(),
            'todayusersignup'  => model('User')->where('createtime', 'ELT', $todayendTime)->where('createtime', 'GT', $todayStartTime)->count(),
            'todayorder'       => model('SiteBrowseLog')->where('createtime', 'ELT', $todayendTime)->where('createtime', 'GT', $todayStartTime)->count(),
            'unsettleorder'    => 132,
            'sevendnu'         => '80%',
            'sevendau'         => model('SiteBrowseLog')->where('createtime', 'ELT', $seventEndTime)->where('createtime', 'GT', $seventStartTime)->count(),
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'addonversion'       => $addonVersion,
            'uploadmode'       => $uploadmode,
            'attachmentCount'   => model('Attachment')->count()
        ]);

        return $this->view->fetch();
    }

}
