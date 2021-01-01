<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\model\CustomInfo;
use app\common\model\Site as ModelSite;
use app\common\model\SiteBrowseLog;
use app\common\model\SiteMessage;

class Site extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        $id = $this->request->param('id');
        if (!$id) {
            $this->error('id不能为空！', url('/index'));
        }

        $siteInfo = model('Site')->where('id', $id)->find();
        if (!$siteInfo) {
            $this->error('id错误！', url('/index'));
        }
        if ($siteInfo['show_page'] != ModelSite::SHOW_PAGE_YES) {
            $this->error('id错误！', url('/index'));
        }

        $currTime = time();
        $logInfo = SiteBrowseLog::create([
            'site_id'       => $siteInfo['id'],
            'start_time'    => $currTime,
            'end_time'      => $currTime,
            'time'          => 0,
            'ip'            => $this->request->ip()
        ]);

        $siteInfo['browse_log_id'] = $logInfo['id'];
        $siteInfo['custom_info'] = CustomInfo::getInfoByUserId($siteInfo['user_id'], ['wechat_number', 'wechat_qrcode']);
        $this->assign('isMobile', $this->request->isMobile());
        $this->assign('siteInfo', $siteInfo);
        return $this->view->fetch();
    }

    // 延长登录ip时长
    public function extended_duration()
    {
        $currTime = time();
        $browseLogId = $this->request->post('browse_log_id', 0);
        if (!$browseLogId) {
            $this->error('id不能为空!');
        }
        
        $browseLogInfo = model('SiteBrowseLog')->where('id', $browseLogId)->field(['id', 'ip', 'start_time', 'end_time'])->find();
        if (!$browseLogInfo) {
            $this->error('id错误!');
        }
        if ($browseLogInfo['ip'] != $this->request->ip()) {
            $this->error('ip不正确!');
        }

        $endTime = $browseLogInfo['end_time'] > 0 ? $browseLogInfo['end_time'] : $browseLogInfo['start_time'];
        if ($currTime - $endTime > 5) {
            $this->error('请求错误!');
        }
        
        $time = $browseLogInfo['end_time'] - $browseLogInfo['start_time'];

        $browseLogInfo->save(['end_time' => $currTime, 'time'   => $time > 0 ? $time : 0]);

        $this->success('OK');
    }

    // 发送留言
    public function send_message()
    {
        $userName = $this->request->post('username');
        $phone = $this->request->post('phone');
        $siteId = $this->request->post('site_id');
        if (empty($userName)) {
            $this->error('请输入姓名!');
        }
        if (empty($phone)) {
            $this->error('请输入手机号!');
        }
        if (!$siteId) {
            $this->error('site_id不能为空!');
        }
        $siteInfo = model('Site')->where('id', $siteId)->where('show_page', ModelSite::SHOW_PAGE_YES)->find();
        if (!$siteInfo) {
            $this->error('网站不存在!');
        }

        SiteMessage::create([
            'site_id'      => $siteId,
            'username'  => $userName,
            'phone'     => $phone,
            'status'    => SiteMessage::STATUS_WAIT,
        ]);

        $this->success('发送成功');
    }

}
