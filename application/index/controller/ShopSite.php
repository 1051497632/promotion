<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Sms;
use app\common\model\ShopSite as ModelShopSite;
use app\common\model\ShopSiteGoods;
use app\common\model\ShopSiteMessage;
use think\Db;
use think\Hook;

class ShopSite extends Frontend
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

        $siteInfo = model('ShopSite')->where('id', $id)->find();
        if (!$siteInfo) {
            $this->error('id错误！', url('/index'));
        }
        if ($siteInfo['show_page'] != ModelShopSite::SHOW_PAGE_YES) {
            $this->error('id错误！', url('/index'));
        }

        $siteInfo['recommend_goods'] = model('ShopSiteGoods')->where('is_recommend', ShopSiteGoods::RECOMMEND_YES)->where('shop_site_id', $id)->order('weigh DESC, id ASC')->limit(0, 10)->select();
        $siteInfo['other_goods'] = model('ShopSiteGoods')->where('is_recommend', ShopSiteGoods::RECOMMEND_NO)->where('shop_site_id', $id)->order('weigh DESC, id ASC')->limit(0, 10)->select();

        $siteInfo['labels'] = [];
        if (!empty($siteInfo['label_id'])) {
            $siteInfo['labels'] = model('ShopSiteLabel')->where('id', 'IN', $siteInfo['label_id'])->order(Db::raw('FIELD(id,' . $siteInfo['label_id'] . ')'))->select();
        }
        $this->assign('siteInfo', $siteInfo);

        if ($this->request->isMobile()) {
            return $this->view->fetch('mobile_index');
        } else {
            return $this->view->fetch();
        }
    }

    // 发送留言
    public function send_message()
    {
        $phone = $this->request->post('phone');
        $shopSiteId = $this->request->post('shop_site_id');
        if (empty($phone)) {
            $this->error('请输入手机号!');
        }
        if (!$shopSiteId) {
            $this->error('shop_site_id不能为空!');
        }
        $siteInfo = model('ShopSite')->where('id', $shopSiteId)->where('show_page', ModelShopSite::SHOW_PAGE_YES)->find();
        if (!$siteInfo) {
            $this->error('网站不存在!');
        }
        
        $shopSiteMessage = ShopSiteMessage::create([
            'shop_site_id'  => $shopSiteId,
            'phone'         => $phone,
            'status'        => ShopSiteMessage::STATUS_WAIT,
        ]);

        // Hook::listen('shop_site_message_add_success', $shopSiteMessage, $siteInfo, true);

        $this->success('发送成功');
    }

}
