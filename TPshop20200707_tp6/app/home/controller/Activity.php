<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */
namespace app\home\controller;
use think\facade\View;

use app\common\logic\ActivityLogic;
use app\common\logic\GoodsLogic;
use app\common\model\FlashSale;
use app\common\model\GroupBuy;
use app\common\model\PreSell;
use think\AjaxPage;
use think\Page;
use think\facade\Db;

class Activity extends Base
{
    /**
     * 团购活动列表
     */
    public function group_list()
    {
        $GroupBuy = new GroupBuy();
        $where = array(
            'gb.start_time'        =>array('<=',time()),
            'gb.end_time'          =>array('>=',time()),
            'gb.is_end'            =>0,
            'g.is_on_sale'         =>1
        );
        $count = $GroupBuy->alias('gb')->join('goods g', 'g.goods_id = gb.goods_id')->alias('gb')->where($where)->count('gb.goods_id');// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        View::assign('page', $show);// 赋值分页输出
        $list = $GroupBuy
            ->alias('gb')
            ->with(['goods','specGoodsPrice'])
            ->join('goods g', 'g.goods_id = gb.goods_id')
            ->where($where)
            ->limit($Page->firstRow,$Page->listRows)
            ->select();
        View::assign('list', $list);
        return View::fetch();
    }

    // 促销活动页面
    public function promoteList()
    {
        $type=input('type/d',0);
        $now_time = time();
        $where = " start_time <= $now_time and end_time >= $now_time and is_end = 0 and type=$type";
        $count = Db::name('prom_goods')->where($where)->count();  // 查询满足要求的总记录数
        $pagesize = config('PAGESIZE');  //每页显示数
        $Page  = new Page($count,$pagesize); //分页类
//        $promote = Db::name('prom_goods')->field('id,title,start_time,end_time,prom_img')->where($where)->limit($Page->firstRow,$Page->listRows)->select();    //查询活动列表
        $promote = Db::name('prom_goods')->field('id,title,start_time,end_time,prom_img')->where($where)->select();    //查询活动列表
        $brandList = Db::name('brand')->cache(true)->column('id,name,logo','id');
        View::assign('brandList',$brandList);
        View::assign('promote',$promote);
        return View::fetch();
         
    }
        // 促销商品页面
        public function promote_goods()
        {
            $prom_id = input('id/d');    //活动ID
            if($prom_id){
                $goods_where['g.prom_id']  = $prom_id;
            }
            $goods_where['p.start_time']  = array('<',time());
            $goods_where['p.end_time']  = array('>',time());
            $goods_where['p.is_end']  = 0;
            $goods_where['g.prom_type']  = 3;
            $goods_where['g.is_on_sale']  = 1;
            $goodsList = Db::name('goods')
                ->field('g.*,p.end_time,s.item_id,s.price')
                ->alias('g')
                ->join('prom_goods p', 'g.prom_id = p.id')
                ->join('spec_goods_price s','g.prom_id = s.prom_id AND s.goods_id = g.goods_id','LEFT')
                ->group('g.goods_id')
                ->where($goods_where)
                ->cache(true,5)
                ->select();
            $brandList = Db::name('brand')->cache(true)->column('id,name,logo','id');
            View::assign('brandList',$brandList);
            View::assign('goodsList',$goodsList);
            return View::fetch();

        }

    /**
     * 抢购活动列表
     */
    public function flash_sale_list()
    {
        $time_space = flash_sale_time_space();
        View::assign('time_space', $time_space);
        return View::fetch();
    }
    /**
     * 抢购活动列表ajax
     */
    public function ajax_flash_sale()
    {
        $p = input('p',1);
        $start_time = input('start_time');
        $end_time = input('end_time');
        $where = array(
            'fl.start_time'=>array('>=',$start_time),
            'fl.end_time'=>array('<=',$end_time),
            'g.is_on_sale'=>1,
            'fl.is_end'=>0
        );
        $FlashSale = new FlashSale();
        $flash_sale_goods = $FlashSale->alias('fl')->join('goods g', 'g.goods_id = fl.goods_id')->with(['specGoodsPrice','goods'])
            ->field('*,100*(FORMAT(buy_num/goods_num,2)) as percent')
            ->where($where)
            ->page($p,10)
            ->select();
        View::assign('flash_sale_goods',$flash_sale_goods);
        View::assign('now',time());
        return View::fetch();
    }

    public function coupon_list()
    {
        $atype = input('atype', 1);
        $user = session('user');
        $p = input('p', 0);

        $activityLogic = new ActivityLogic();
        $result = $activityLogic->getCouponList($atype, $user['user_id'], $p);
        View::assign('coupon_list', $result);
        if (request()->isAjax()) {
            return View::fetch('ajax_coupon_list');
        }
        return View::fetch();
    }

    /**
     * 领券
     */
    public function get_coupon()
    {
        $id = input('coupon_id/d');
        if (empty($id)){
            $this->error('参数错误');
        }
        $user = session('user');
        if ($user) {
            $activityLogic = new ActivityLogic();
            $result = $activityLogic->get_coupon($id, $user['user_id']);
        } else {
            return redirect(url('User/login'));
        }
        View::assign('res',$result);
        return View::fetch();
    }
    public function pre_sell_list()
    {
        $PreSell = new PreSell();
        $count = $PreSell->where(['sell_end_time'=>['>',time()],'is_finished' => 0])->count();
        $page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $pre_sell_list = $PreSell->where(['sell_end_time'=>['>',time()],'is_finished' => 0])->order(['pre_sell_id' => 'desc'])->limit($page->firstRow,$page->listRows)->select();
        View::assign('pre_sell_list', $pre_sell_list);
        View::assign('page', $page);
        return View::fetch();
    }
}