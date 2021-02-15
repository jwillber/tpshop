<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * Author: 当燃
 * Date: 2015-12-21
 */

namespace app\admin\controller;
use think\facade\View;

use app\admin\logic\GoodsLogic;
use app\common\model\Order;
use think\facade\Db;
use think\Page;

class Report extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (input('start_time')) {
            $begin = input('start_time');
            $end = input('end_time');
        } else {
            $begin = date('Y-m-d', strtotime("-3 month"));//30天前
            $end = date('Y-m-d', time());
            $this->end = time();
        }
        ($this->begin > $this->end) && $this->error('起止时间选择错误！！！');
        $now = strtotime(date('Y-m-d'));
        $today['today_amount'] = Db::name('order')->where("add_time>$now AND (pay_status=1 or pay_code='cod') and order_status in(1,2,4)")->sum('total_amount');//今日销售总额
        $today['today_order'] = Db::name('order')->where("add_time>$now and (pay_status=1 or pay_code='cod') and order_status!=3")->count();//今日订单数
        $today['cancel_order'] = Db::name('order')->where("add_time>$now AND order_status=3")->count();//今日取消订单
        if ($today['today_order'] == 0) {
            $today['sign'] = round(0, 2);
        } else {
            $today['sign'] = round($today['today_amount'] / $today['today_order'], 2);
        }
        View::assign('today', $today);
        $select_year = $this->select_year;
        $res = Db::name("order" . $select_year)
            ->field(" COUNT(*) as tnum,sum(total_amount) as amount, FROM_UNIXTIME(add_time,'%Y-%m-%d') as gap ,1 as user_num")
            ->where(" add_time >$this->begin and add_time < $this->end AND (pay_status=1 or pay_code='cod') and order_status in(1,2,4) ")
            ->group("gap, user_id")
            ->select();

        foreach ($res as $val) {
            $arr[$val['gap']] += $val['tnum'];
            $brr[$val['gap']] += $val['amount'];
            $crr[$val['gap']] += $val['user_num'];
            // $tnum += $val['tnum'];
            // $tamount += $val['amount'];
        }

        for ($i = $this->begin; $i <= $this->end; $i = $i + 24 * 3600) {
            $tmp_num = empty($arr[date('Y-m-d', $i)]) ? 0 : $arr[date('Y-m-d', $i)];
            $tmp_amount = empty($brr[date('Y-m-d', $i)]) ? 0 : $brr[date('Y-m-d', $i)];
            $tmp_user_num = empty($crr[date('Y-m-d', $i)]) ? 0 : $crr[date('Y-m-d', $i)];
            $tmp_sign = empty($tmp_num) ? 0 : round($tmp_amount / $tmp_num, 2);
            $order_arr[] = $tmp_num;
            $amount_arr[] = $tmp_amount;
            $sign_arr[] = $tmp_sign;
            $date = date('Y-m-d', $i);
            $list[] = array('day' => $date, 'order_num' => $tmp_num, 'amount' => $tmp_amount, 'user_num' => $tmp_user_num, 'sign' => $tmp_sign, 'end' => date('Y-m-d', $i + 24 * 60 * 60));
            $day[] = $date;
        }
        !empty($list) && rsort($list);
        View::assign('list', $list);
        $result = array('order' => $order_arr, 'amount' => $amount_arr, 'sign' => $sign_arr, 'time' => $day);
        View::assign('result', json_encode($result));
        View::assign('start_time', $begin);
        View::assign('end_time', $end);
        $this->begin = strtotime($begin);
        $this->end = strtotime($end) + 86399;
        return View::fetch();
    }

    /**
    * 会员画像
    */
    public function portrait()
    {
        $start_time = input('start_time2');
        if($start_time){
            $time = strtotime($start_time);
            if($time > time()){
                $time =time();
            }
        }else{
            $time = time();
        }
        $type = input('cat_id/d',1);
        View::assign('start_time2',date("Y-m-d",$time));
        View::assign('cat_id',$type);
        $this->user_visit();
        $portrait = new \app\common\logic\Portrait();
        $data = $portrait->portrait_data($time,$type);
        View::assign('data', $data);
        return View::fetch();
    }

    /**
     * 收藏转化率列表
     * 用户昵称，商品名称，收藏时间，是否已支付
     * @return mixed
     */
    public function user_collect()
    {
        $mobile = input('mobile');
        $order_where = [
            'portrait_collect.add_time' => ['Between', "$this->begin,$this->end"],
        ];
        if ($mobile) {
            $order_where['users.mobile'] = $mobile;
        }

        $count =Db::view('portrait_collect','id,order_id,is_collect,add_time')
            ->view('goods','goods_id,goods_name','goods.goods_id=portrait_collect.goods_id','LEFT')
            ->view('users','user_id,nickname,mobile','users.user_id=portrait_collect.user_id','LEFT')
            ->where($order_where)->count();  //统计数量
        $Page = new Page($count, $this->page_size);
        $list = Db::view('portrait_collect','id,order_id,is_collect,add_time')
            ->view('goods','goods_id,goods_name','goods.goods_id=portrait_collect.goods_id','LEFT')
            ->view('users','user_id,nickname,mobile','users.user_id=portrait_collect.user_id','LEFT')
            ->where($order_where)
            ->order('portrait_collect.id DESC')
            ->limit($Page->firstRow, $Page->listRows)
            ->cache(true)->select();   //以用户ID分组查询
        View::assign('page', $Page);
        View::assign('p', input('p/d', 1));
        View::assign('page_size', $this->page_size);
        View::assign('list', $list);
        return View::fetch();
    }

    /**
     * 加入购物车转化率 列表
     * @return mixed
     */
    public function user_cart()
    {
        $mobile = input('mobile');
        $order_where = [
            'portrait_cart.add_time' => ['Between', "$this->begin,$this->end"],
        ];
        if ($mobile) {
            $order_where['users.mobile'] = $mobile;
        }

        $count =Db::view('portrait_cart','id,order_id,add_time')
            ->view('goods','goods_id,goods_name','goods.goods_id=portrait_cart.goods_id','LEFT')
            ->view('users','user_id,nickname,mobile','users.user_id=portrait_cart.user_id','LEFT')
            ->where($order_where)->count();  //统计数量
        $Page = new Page($count, $this->page_size);
        $list = Db::view('portrait_cart','id,order_id,add_time')
            ->view('goods','goods_id,goods_name','goods.goods_id=portrait_cart.goods_id','LEFT')
            ->view('users','user_id,nickname,mobile','users.user_id=portrait_cart.user_id','LEFT')
            ->where($order_where)
            ->order('portrait_cart.id DESC')
            ->limit($Page->firstRow, $Page->listRows)
            ->cache(true)->select();   //以用户ID分组查询
        View::assign('page', $Page);
        View::assign('p', input('p/d', 1));
        View::assign('page_size', $this->page_size);
        View::assign('list', $list);
        return View::fetch();
    }

    /**
     * 订单转化率 订单列表
     * @return mixed
     */
    public function user_order(){
        $orderModel = new Order();

        // 搜索条件
        $condition = [
            'add_time' => ['Between', "$this->begin,$this->end"],
        ];

        $count = $orderModel->where($condition)->count();
        $Page = new Page($count, $this->page_size);
        $orderList = $orderModel->where($condition)
            ->limit($Page->firstRow,$Page->listRows)->order('add_time desc')->select();
        View::assign('orderList', $orderList);

        View::assign('page', $Page);// 赋值分页输出
        return View::fetch();
    }



    /**
     * 销量排行
     * @return mixed
     */
    public function saleTop()
    {
        $goods_name = input('goods_name');
        $where = [
            'od.pay_time' => ['Between', "$this->begin,$this->end"],
            'od.order_status' => ['notIN', '3,5'],
            'og.is_send' => 1,
        ];
        if (!empty($goods_name)) $where['og.goods_name'] = ['like', "%$goods_name%"];
        $count = Db::name('order_goods')->alias('og')
            ->field('sum(og.goods_num) as sale_num,sum(og.goods_num*og.goods_price) as sale_amount ')
            ->join('order od', 'og.order_id=od.order_id', 'LEFT')
            ->where($where)->group('og.goods_id')->count();
        $Page = new Page($count, $this->page_size);
        $res = Db::name('order_goods')->alias('og')
            ->field('og.goods_name,og.goods_id,og.goods_sn,sum(og.goods_num) as sale_num,sum(og.goods_num*og.goods_price) as sale_amount ')
            ->join('order od', 'og.order_id=od.order_id', 'LEFT')
            ->where($where)->group('og.goods_id')->order('sale_num DESC')
            ->limit($Page->firstRow, $Page->listRows)->cache(true, 3600)->select();
        View::assign('list', $res);
        View::assign('page', $Page);
        View::assign('p', input('p/d', 1));
        View::assign('page_size', $this->page_size);
        return View::fetch();
    }

    /**
     * 统计报表 - 会员排行
     * @return mixed
     */
    public function userTop()
    {

        $mobile = input('mobile');
        $email = input('email');
        $order_where = [
            'o.add_time' => ['Between', "$this->begin,$this->end"],
            'o.pay_status' => 1,
            'o.order_status' => ['notIn', '3,5']
        ];
        if ($mobile) {
            $user_where['mobile'] = $mobile;
        }
        if ($email) {
            $user_where['email'] = $email;
        }
        if ($user_where) {   //有查询单个用户的条件就去找出user_id
            $user_id = Db::name('users')->where($user_where)->value('user_id');
            $order_where['o.user_id'] = $user_id;
        }

        $count = Db::name('order')->alias('o')->where($order_where)->group('o.user_id')->count();  //统计数量
        $Page = new Page($count, $this->page_size);
        $list = Db::name('order')->alias('o')
            ->field('count(o.order_id) as order_num,sum(o.total_amount) as amount,o.user_id,u.mobile,u.email,u.nickname')
            ->join('users u', 'o.user_id=u.user_id', 'LEFT')
            ->where($order_where)
            ->group('o.user_id')
            ->order('amount DESC')
            ->limit($Page->firstRow, $Page->listRows)
            ->cache(true)->select();   //以用户ID分组查询
        View::assign('page', $Page);
        View::assign('p', input('p/d', 1));
        View::assign('page_size', $this->page_size);
        View::assign('list', $list);
        return View::fetch();
    }

    /**
     * 用户订单
     * @return mixed
     */
    public function userOrder()
    {
        $orderModel = new Order();
        $user_id = trim(input('user_id'));
        // 搜索条件
        $condition = [
            'add_time' => ['Between', "$this->begin,$this->end"],
            'pay_status' => 1,
            'user_id' => $user_id,
            'order_status' => ['notIn', '3,5'],
        ];
        $keyType = input("keytype");
        $keywords = input('keywords', '', 'trim');

        $pay_code = input('pay_code');
        $order_sn = ($keyType && $keyType == 'order_sn') ? $keywords : input('order_sn');
        $order_sn ? $condition['order_sn'] = trim($order_sn) : false;
        $pay_code != '' ? $condition['pay_code'] = $pay_code : false;   //支付方式


        $count = $orderModel->where($condition)->count();
        $Page = new Page($count, $this->page_size);
        $orderList = $orderModel->where($condition)
            ->limit($Page->firstRow,$Page->listRows)->order('add_time desc')->select();

        View::assign('orderList', $orderList);
        View::assign('user_id', $user_id);
        View::assign('keywords', $keywords);
        View::assign('page', $Page);// 赋值分页输出
        return View::fetch();
    }

    public function saleOrder()
    {
        $start_time = input('start_time');
        $end_time = input('end_time');
        $end_time2 = $this->begin + 24 * 60 * 60;
        $order_where = "o.add_time>$this->begin and o.add_time<$end_time2";  //交易成功的有效订单
        $order_count = Db::name('order')->alias('o')->where($order_where)->whereIn('order_status', '1,2,4')->count();
        $Page = new Page($order_count, 20);
        $order_list = Db::name('order')->alias('o')
            ->field('o.order_id,o.order_sn,o.goods_price,o.shipping_price,o.total_amount,o.add_time,u.user_id,u.nickname')
            ->join('users u', 'u.user_id = o.user_id', 'left')
            ->where($order_where)->whereIn('order_status', '1,2,4')
            ->limit($Page->firstRow, $Page->listRows)->select();
        View::assign('order_list', $order_list);
        View::assign('page', $Page);
        View::assign('start_time', $start_time);
        View::assign('end_time', $end_time);
        return View::fetch();
    }

    /**
     * 销售明细列表
     */
    public function saleList()
    {
        $cat_id = input('cat_id', 0);
        $brand_id = input('brand_id', 0);
        $goods_id = input('goods_id', 0);
        $goods_name = input('goods_name', 0);
        $where = "o.add_time>$this->begin and o.add_time<$this->end and o.order_status in(1,2,4) and og.is_send = 1 ";  //交易成功的有效订单
        if ($cat_id > 0) {
            $where .= " and (g.cat_id=$cat_id or g.extend_cat_id=$cat_id)";
            View::assign('cat_id', $cat_id);
        }
        if ($brand_id > 0) {
            $where .= " and g.brand_id=$brand_id";
            View::assign('brand_id', $brand_id);
        }

        if ($goods_id > 0) {
            $where .= " and og.goods_id=$goods_id";
        }
        if (!empty($goods_name)) {
            $where .= " and og.goods_name like '%$goods_name%'";
        }
        $count = Db::name('order_goods')->alias('og')
            ->join('order o', 'og.order_id=o.order_id ', 'left')
            ->join('goods g', 'og.goods_id = g.goods_id', 'left')
            ->where($where)->count();  //统计数量
        $Page = new Page($count, 20);
        $show = $Page->show();

        $res = Db::name('order_goods')->alias('og')->field('og.*,o.user_id,o.order_sn,o.shipping_name,o.pay_name,o.add_time,og.spec_key_name')
            ->join('order o', 'og.order_id=o.order_id ', 'left')
            ->join('goods g', 'og.goods_id = g.goods_id', 'left')
            ->where($where)->limit($Page->firstRow, $Page->listRows)
            ->order('o.add_time desc')->select();
        View::assign('list', $res);
        View::assign('pager', $Page);
        View::assign('page', $show);

        $GoodsLogic = new GoodsLogic();
        $brandList = $GoodsLogic->getSortBrands();  //获取排好序的品牌列表
        $categoryList = $GoodsLogic->getSortCategory(); //获取排好序的分类列表
        View::assign('categoryList', $categoryList);
        View::assign('brandList', $brandList);
        View::assign('cat_id', $cat_id);
        View::assign('brand_id', $brand_id);
        View::assign('goods_name', $goods_name);
        return View::fetch();
    }

    public function user()
    {
        $today = strtotime(date('Y-m-d'));
        $month = strtotime(date('Y-m-01'));
        $user['today'] = Db::name('users')->where("reg_time > $today")->count();//今日新增会员
        $user['month'] = Db::name('users')->where("reg_time > $month")->count();//本月新增会员
        $user['total'] = Db::name('users')->count();//会员总数
        $user['user_money'] = Db::name('users')->sum('user_money');//会员余额总额
        $res = Db::name('order')->cache(true)->distinct(true)->field('user_id')->select();
        $user['hasorder'] = count($res);
        View::assign('user', $user);
        $sql = "SELECT COUNT(*) as num,FROM_UNIXTIME(reg_time,'%Y-%m-%d') as gap from __PREFIX__users where reg_time>$this->begin and reg_time<$this->end group by gap";
        $new = DB::query($sql);//新增会员趋势
        foreach ($new as $val) {
            $arr[$val['gap']] = $val['num'];
        }

        for ($i = $this->begin; $i <= $this->end; $i = $i + 24 * 3600) {
            $brr[] = empty($arr[date('Y-m-d', $i)]) ? 0 : $arr[date('Y-m-d', $i)];
            $day[] = date('Y-m-d', $i);
        }
        $result = array('data' => $brr, 'time' => $day);
        View::assign('result', json_encode($result));
        return View::fetch();
    }

    public function login()
    {

        $sql = "SELECT COUNT(*) as num,FROM_UNIXTIME(login_time,'%Y-%m-%d') as gap from __PREFIX__user_login where login_time>$this->begin and login_time<$this->end group by gap";
        $new = DB::query($sql);//新增会员趋势
        foreach ($new as $val) {
            $arr[$val['gap']] = $val['num'];
        }

        for ($i = $this->begin; $i <= $this->end; $i = $i + 24 * 3600) {
            $brr[] = empty($arr[date('Y-m-d', $i)]) ? 0 : $arr[date('Y-m-d', $i)];
            $day[] = date('Y-m-d', $i);
        }
        $result = array('data' => $brr, 'time' => $day);
        View::assign('result', json_encode($result));
        return View::fetch();
    }

    public function expense_log()
    {
        $map = array();
        $admin_id = input('user_name');
        if ($this->begin && $this->end) {
            $map['addtime'] = array('between', "$this->begin,$this->end");
        }
        if ($admin_id) {
            $map['a.user_name'] = $admin_id;
        }
        $count = Db::name('expense_log')->alias('e')->join("admin a", "a.admin_id = e.admin_id")->where($map)->count();
        $page = new Page($count);
        $lists = Db::name('expense_log')->alias('e')->join("admin a", "a.admin_id = e.admin_id")->where($map)->limit($page->firstRow,$page->listRows)->order('id desc')->select();
        View::assign('page', $page->show());
        View::assign('total_count', $count);
        View::assign('list', $lists);
        $admin = Db::name('admin')->column('user_name','admin_id');
        View::assign('admin', $admin);
        $typeArr = array('', '会员提现', '订单取消', '订单退款');//数据库设计问题  原订单退款=订单取消，其他=订单退款
        View::assign('typeArr', $typeArr);
        return View::fetch();
    }

    /**
     * 会员画像 访问统计
     * @return mixed
     */
    public function user_visit(){
        $begin = $this->begin;
        $end_time = $this->end;
        if($begin >= $end_time){
            $this->error('开始结束时间不对 ');
            exit;
        }
        $portrait = new \app\common\logic\Portrait();
        $data = $portrait->visit_ok($begin,$end_time);
        $list = [];
        $nums_arr = [];
        $sex0_arr = [];
        if(!empty($data)){
            // 数据临时特殊处理
            function get_day_num($list, $time, $key)
            {
                if (empty($list)) return 0;
                foreach ($list as $k => $arr) {
                    if ($arr['time'] == $time) {
                        return $arr[$key];
                    }
                }
                return 0;
            }

            for ($i = $begin,$j=0; $i <= $end_time; $i = $i + 24 * 3600) {   //循环时间
                $nums = get_day_num($data,$i,'nums');
                $date = $day[] = date('Y-m-d', $i);
                $everyday_end_time = $i + 24 * 3600;
                //拼装输出到图表的数据
                $nums_arr[] = $nums; // 总次数
                $sex0_arr[] = get_day_num($data,$i,'sex0');
                $sex1_arr[] = get_day_num($data,$i,'sex1');
                $sex2_arr[] = get_day_num($data,$i,'sex2');
                $reg_arr[] = get_day_num($data,$i,'reg');
                $buy_arr[] = get_day_num($data,$i,'buy');

                $list[] = [
                    'day' => $date,
                    'nums_arr' => $nums,
                    'sex0_arr' => $sex0_arr[$j],
                    'sex1_arr' => $sex1_arr[$j],
                    'sex2_arr' => $sex2_arr[$j],
                    'reg_arr' => $reg_arr[$j],
                    'buy_arr' => $buy_arr[$j],
                    'end' => $everyday_end_time,
                ];  //拼装列表
                $j++;
            }
        }else{
            for ($i = $begin; $i <= $end_time; $i = $i + 24 * 3600) {   //循环时间
                $nums = 0;
                $date = $day[] = date('Y-m-d', $i);
                $everyday_end_time = $i + 24 * 3600;
                //拼装输出到图表的数据
                $nums_arr[] = $nums; // 总次数
                $sex0_arr[] = 0;
                $sex1_arr[] = 0;
                $sex2_arr[] = 0;
                $reg_arr[] = 0;
                $buy_arr[] = 0;

                $list[] = [
                    'day' => $date,
                    'nums_arr' => $nums,
                    'sex0_arr' => 0,
                    'sex1_arr' => 0,
                    'sex2_arr' => 0,
                    'reg_arr' => 0,
                    'buy_arr' => 0,
                    'end' => $everyday_end_time,
                ];  //拼装列表
            }
        }
        rsort($list);
        View::assign('list', $list);
        $result = ['nums_arr' => $nums_arr,
            'sex0_arr' => $sex0_arr,
            'sex1_arr' => $sex1_arr,
            'sex2_arr' => $sex2_arr,
            'reg_arr' => $reg_arr,
            'buy_arr' => $buy_arr,
            'time' => $day];
        View::assign('result', json_encode($result));
    }

    //财务统计
    public function finance()
    {
        $begin = $this->begin;
        $end_time = $this->end;
        $order = Db::name('order')->alias('o')
            ->where(['o.pay_status' => 1])->whereTime('o.add_time', 'between', [$begin, $end_time])
            ->order('o.add_time asc')->column('*','order_id');  //以时间升序
        $order_id_arr = get_arr_column($order, 'order_id');
        $order_ids = implode(',', $order_id_arr);            //订单ID组
        $order_goods = Db::name('order_goods')->where(['is_send' => ['in', '1,2'], 'order_id' => ['in', $order_ids]])->group('order_id')
            ->order('order_id asc')->column('order_id,sum(goods_num*cost_price) as cost_price,sum(goods_num*member_goods_price) as goods_amount','order_id');  //订单商品退货的不算
        $frist_key = key($order);  //第一个key
        $sratus_date = $order["$frist_key"]['add_time'] ? strtotime(date('Y-m-d', $order["$frist_key"]['add_time'])) : $begin;  //有数据那天为循环初始时间，大范围查询可以避免前面输出一堆没用的数据
        $key = array_keys($order);
        $lastkey = end($key);//最后一个key
        //拼装输出到图表的数据
        $end_date = $order["$lastkey"]['add_time'] ? strtotime(date('Y-m-d', $order["$lastkey"]['add_time'])) + 24 * 3600 : $end_time;  //数据最后时间为循环结束点，大范围查询可以避免前面输出一堆没用的数据

        for ($i = $sratus_date; $i <= $end_date; $i = $i + 24 * 3600) {   //循环时间
            $date = $day[] = date('Y-m-d', $i);
            $everyday_end_time = $i + 24 * 3600;
            $goods_amount = $cost_price = $shipping_amount = $coupon_amount = $order_prom_amount = $total_amount = 0.00; //初始化变量
            foreach ($order as $okey => $oval) {   //循环订单
                $for_order_id = $oval['order_id'];
                if (!isset($order_goods["$for_order_id"])) {
                    unset($order[$for_order_id]);           //去掉整个订单都了退货后的
                }
                if ($oval['add_time'] >= $i && $oval['add_time'] < $everyday_end_time) {      //统计同一天内的数据
                    $goods_amount += $oval['goods_price'];
                    $total_amount += $oval['total_amount'];
                    $cost_price += $order_goods["$for_order_id"]['cost_price']; //订单成本价
                    $shipping_amount += $oval['shipping_price'];
                    $coupon_amount += $oval['coupon_price'];
                    $order_prom_amount += $oval['order_prom_amount'];
                    unset($order[$okey]);  //省的来回循环
                }
            }
            //拼装输出到图表的数据
            $goods_arr[] = $goods_amount;
            $total_arr[] = $total_amount;
            $cost_arr[] = $cost_price;
            $shipping_arr[] = $shipping_amount;
            $coupon_arr[] = $coupon_amount;

            $list[] = [
                'day' => $date,
                'goods_amount' => $goods_amount,
                'total_amount' => $total_amount,
                'cost_amount' => $cost_price,
                'shipping_amount' => $shipping_amount,
                'coupon_amount' => $coupon_amount,
                'order_prom_amount' => $order_prom_amount,
                'end' => $everyday_end_time,
            ];  //拼装列表
        }

        rsort($list);
        View::assign('list', $list);
        $result = ['goods_arr' => $goods_arr, 'cost_arr' => $cost_arr, 'shipping_arr' => $shipping_arr, 'coupon_arr' => $coupon_arr, 'time' => $day];
        View::assign('result', json_encode($result));
        return View::fetch();
    }

    /**
     * 运营概况详情
     * @return mixed
     */
    public function financeDetail()
    {
        $begin = $this->begin;
        $end_time = $this->begin + 24 * 60 * 60;
        $order_where = [
            'o.pay_status' => 1,
            'o.shipping_status' => 1,
            'og.is_send' => ['in', '1,2']];  //交易成功的有效订单
        $order_count = Db::name('order')->alias('o')
            ->join('order_goods og', 'o.order_id = og.order_id', 'left')->join('users u', 'u.user_id = o.user_id', 'left')
            ->whereTime('o.add_time', 'between', [$begin, $end_time])->where($order_where)
            ->group('o.order_id')->count();
        $Page = new Page($order_count, 50);

        $order_list = Db::name('order')->alias('o')
            ->field('o.*,u.user_id,u.nickname,SUM(og.cost_price) as coupon_amount')
            ->join('order_goods og', 'o.order_id = og.order_id', 'left')->join('users u', 'u.user_id = o.user_id', 'left')
            ->where($order_where)->whereTime('o.add_time', 'between', [$begin, $end_time])
            ->group('o.order_id')->limit($Page->firstRow, $Page->listRows)->select();
        View::assign('order_list', $order_list);
        View::assign('page', $Page);
        return View::fetch();
    }

    /**
     *导出销售概况
     */
    public function export_report()
    {
        $selected_times = input('selected_times');
        $where = " 1 = 1 ";

        if ($selected_times) {
            $tmp_arr = explode(",", input('selected_times'));
            foreach ($tmp_arr as $k => $v) {
                $day_start = strtotime($v);
                $day_end = strtotime($v) + 86399;
                $joint = $k == 0 ? 'and' : 'or';
                $where .= $joint . " (add_time >= $day_start and add_time <=  $day_end) ";
            }
        } else {
            $where .= " and add_time >= $this->begin and add_time <= $this->end";
        }

        $res = Db::name("order")
            ->field(" COUNT(*) as tnum,sum(total_amount) as amount, FROM_UNIXTIME(add_time,'%Y-%m-%d') as gap, 1 as user_num")
            ->where($where)
            ->where(" (pay_status=1 or pay_code='cod') and order_status in(1,2,4) ")
            ->group('gap, user_id')
            ->select();

        foreach ($res as $val) {
            $arr[$val['gap']] += $val['tnum'];
            $brr[$val['gap']] += $val['amount'];
            $crr[$val['gap']] += $val['user_num'];
        }
        if ($selected_times) {
            $tmp_arr = explode(",", input('selected_times'));
            foreach ($tmp_arr as $k => $v) {
                $tmp_num = empty($arr[$v]) ? 0 : $arr[$v];
                $tmp_amount = empty($brr[$v]) ? 0 : $brr[$v];
                $tmp_user_num = empty($crr[$v]) ? 0 : $crr[$v];
                $tmp_sign = empty($tmp_num) ? 0 : round($tmp_amount / $tmp_num, 2);
                $order_arr[] = $tmp_num;
                $amount_arr[] = $tmp_amount;
                $sign_arr[] = $tmp_sign;
                $date = $v;
                $list[] = array('day' => $date, 'order_num' => $tmp_num, 'amount' => $tmp_amount, 'user_num' => $tmp_user_num, 'sign' => $tmp_sign, 'end' => date('Y-m-d', strtotime($v) + 24 * 60 * 60));
            }
        } else {
            for ($i = $this->begin; $i <= $this->end; $i = $i + 24 * 3600) {
                $tmp_num = empty($arr[date('Y-m-d', $i)]) ? 0 : $arr[date('Y-m-d', $i)];
                $tmp_amount = empty($brr[date('Y-m-d', $i)]) ? 0 : $brr[date('Y-m-d', $i)];
                $tmp_user_num = empty($crr[date('Y-m-d', $i)]) ? 0 : $crr[date('Y-m-d', $i)];
                $tmp_sign = empty($tmp_num) ? 0 : round($tmp_amount / $tmp_num, 2);
                $order_arr[] = $tmp_num;
                $amount_arr[] = $tmp_amount;
                $sign_arr[] = $tmp_sign;
                $date = date('Y-m-d', $i);
                $list[] = array('day' => $date, 'order_num' => $tmp_num, 'amount' => $tmp_amount, 'user_num' => $tmp_user_num, 'sign' => $tmp_sign, 'end' => date('Y-m-d', $i + 24 * 60 * 60));
            }
        }

        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单数</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">销售总额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">客单价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">购买人数</td>';
        $strTable .= '</tr>';
        if (is_array($list)) {
            foreach ($list as $val) {
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['day'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['order_num'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['amount'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['sign'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['user_num'] . '</td>';
                $strTable .= '</tr>';
            }

        }
        $strTable .= '</table>';
        unset($list);
        downloadExcel($strTable, 'report');
        exit();
    }

    /**
     *导出 销售概况->销售明细列表
     */
    public function export_sale()
    {
        $order_ids = input('order_ids');
        $end_time = $this->begin + 24 * 60 * 60;
        $order_where .= "o.add_time>$this->begin and o.add_time<$end_time";
        $order_where .= $order_ids ? " and o.order_id in ($order_ids)" : '';

        $order_list = Db::name('order')->alias('o')
            ->field('o.order_id,o.order_sn,o.goods_price,o.shipping_price,o.total_amount,o.add_time,u.user_id,u.nickname')
            ->join('users u', 'u.user_id = o.user_id', 'left')
            ->where($order_where)->whereIn('order_status', '1,2,4')
            ->select();

        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">订单号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">用户名</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品总价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">物流价格</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单总价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品数量</td>';
        $strTable .= '</tr>';

        if (count($order_list)) {
            foreach ($order_list as $val) {
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:left;font-size:12px;">&nbsp;' . $val['order_sn'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['nickname'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_price'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['shipping_price'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['total_amount'] . '</td>';
                $goods_num = 0;
                $orderGoods = Db::name('order_goods')->where('order_id=' . $val['order_id'])->select();
                foreach ($orderGoods as $goods) {
                    $goods_num = $goods_num + $goods['goods_num'];
                }
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $goods_num . ' </td>';
                $strTable .= '</tr>';
            }

        }
        $strTable .= '</table>';
        unset($order_list);
        downloadExcel($strTable, 'report_sale');
        exit();

    }

    /**
     *导出销量排行列表
     */
    public function export_sale_top()
    {
        $goods_name = input('goods_name');
        $selected_goods_ids = input('selected_goods_ids');
        $where = [
            'od.pay_time' => ['Between', "$this->begin,$this->end"],
            'od.order_status' => ['notIN', '3,5'],
            'og.is_send' => 1,
        ];

        !empty($goods_name) ? $where['og.goods_name'] = ['like', "%$goods_name%"] : false;
        $selected_goods_ids ? $where['og.goods_id'] = ['in', $selected_goods_ids] : false;

        $res = Db::name('order_goods')->alias('og')
            ->field('og.goods_name,og.goods_id,og.goods_sn,sum(og.goods_num) as sale_num,sum(og.goods_num*og.goods_price) as sale_amount ')
            ->join('order od', 'og.order_id=od.order_id', 'LEFT')
            ->where($where)->group('og.goods_id')->order('sale_num DESC')
            ->select();

        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">排行</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">货号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">销售量</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">销售额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">均价</td>';
        $strTable .= '</tr>';

        if (count($res)) {
            foreach ($res as $k => $val) {
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:left;font-size:12px;">&nbsp;' . ($k + 1) . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_name'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_sn'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['sale_num'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['sale_amount'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . round($val['sale_amount'] / $val['sale_num'], 2) . ' </td>';
                $strTable .= '</tr>';
            }

        }
        $strTable .= '</table>';

        unset($res);
        downloadExcel($strTable, 'sale_top');
        exit();
    }

    /**
     *导出 销售排行->销售明细
     */
    public function export_sale_list()
    {
        $cat_id = input('cat_id', 0);
        $brand_id = input('brand_id', 0);
        $goods_id = input('goods_id', 0);
        $rec_ids = input('rec_ids');

        $where = " o.add_time>$this->begin and o.add_time<$this->end and o.order_status in(1,2,4) and og.is_send = 1 ";  //交易成功的有效订单

        if ($cat_id > 0) {
            $where .= " and (g.cat_id=$cat_id or g.extend_cat_id=$cat_id)";
            View::assign('cat_id', $cat_id);
        }
        if ($brand_id > 0) {
            $where .= " and g.brand_id=$brand_id";
            View::assign('brand_id', $brand_id);
        }
        if ($goods_id > 0) {
            $where .= " and og.goods_id=$goods_id";
        }

        $where .= $rec_ids ? " and og.rec_id in ($rec_ids)" : '';

        $res = Db::name('order_goods')->alias('og')->field('og.*,o.user_id,o.order_sn,o.shipping_name,o.pay_name,o.add_time,og.spec_key_name')
            ->join('order o', 'og.order_id=o.order_id ', 'left')
            ->join('goods g', 'og.goods_id = g.goods_id', 'left')
            ->where($where)
            ->order('o.add_time desc')
            ->select();

        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">订单ID</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品规格</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品货号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">数量</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">售价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">下单会员</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">出售日期</td>';
        $strTable .= '</tr>';

        if (count($res)) {
            foreach ($res as $k => $val) {
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:left;font-size:12px;">&nbsp;' . $val['order_id'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_name'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['spec_key_name'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_sn'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_num'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_price'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['user_id'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . date("Y-m-d H:i:s", $val['add_time']) . ' </td>';
                $strTable .= '</tr>';
            }

        }
        $strTable .= '</table>';

        unset($res);
        downloadExcel($strTable, 'sale_top');
        exit();
    }

    public function export_user_top()
    {
        $mobile = input('mobile');
        $email = input('email');
        $selected_user_ids = input('selected_user_ids');
        $order_where = [
            'o.add_time' => ['Between', "$this->begin,$this->end"],
            'o.pay_status' => 1,
            'o.order_status' => ['notIn', '3,5']
        ];
        if ($mobile) {
            $user_where['mobile'] = $mobile;
        }
        if ($email) {
            $user_where['email'] = $email;
        }
        if ($user_where) {   //有查询单个用户的条件就去找出user_id
            $user_id = Db::name('users')->where($user_where)->value('user_id');
            $order_where['o.user_id'] = $user_id;
        }
        if ($selected_user_ids) {
            $order_where['o.user_id'] = ['in', $selected_user_ids];
        }

        $res = Db::name('order')->alias('o')
            ->field('count(o.order_id) as order_num,sum(o.total_amount) as amount,o.user_id,u.mobile,u.email,u.nickname')
            ->join('users u', 'o.user_id=u.user_id', 'LEFT')
            ->where($order_where)
            ->group('o.user_id')
            ->order('amount DESC')
            ->select();   //以用户ID分组查询

        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">ID</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">排行</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员手机</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员邮箱</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单数</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">购物金额</td>';
        $strTable .= '</tr>';

        if (count($res)) {
            foreach ($res as $k => $val) {
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:left;font-size:12px;">&nbsp;' . $val['user_id'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . ($k + 1) . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['nickname'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['mobile'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['email'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['order_num'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['amount'] . ' </td>';
                $strTable .= '</tr>';
            }

        }
        $strTable .= '</table>';

        unset($res);
        downloadExcel($strTable, 'user_top');
        exit();
    }

    /**
     *导出用户订单
     */
    public function export_user_order()
    {
        $orderModel = new Order();
        $user_id = trim(input('user_id'));
        // 搜索条件
        $condition = [
            'add_time' => ['Between', "$this->begin,$this->end"],
            'pay_status' => 1,
            'user_id' => $user_id,
            'order_status' => ['notIn', '3,5'],
        ];
        $keyType = input("keytype");
        $keywords = input('keywords', '', 'trim');

        $pay_code = input('pay_code');
        $order_sn = ($keyType && $keyType == 'order_sn') ? $keywords : input('order_sn');
        $order_sn ? $condition['order_sn'] = trim($order_sn) : false;
        $pay_code != '' ? $condition['pay_code'] = $pay_code : false;   //支付方式

        $res = $orderModel
            ->where($condition)
            ->order('add_time desc')
            ->select();

        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">订单编号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货人</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">总金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">应付金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">支付方式</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">配送方式</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">下单时间</td>';
        $strTable .= '</tr>';

        if (count($res)) {
            foreach ($res as $k => $val) {
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:left;font-size:12px;">&nbsp;' . $val['order_sn'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['consignee'] . ":" . $val['mobile'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['goods_price'] . '</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['order_amount'] . '</td>';
                $tmp_pay_name = $val['pay_name'] ? $val['pay_name'] : "其他方式";
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $tmp_pay_name . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['shipping_name'] . ' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">' . date("Y-m-d H:i:s", $val['add_time']) . ' </td>';
                $strTable .= '</tr>';
            }

        }
        $strTable .= '</table>';
        unset($res);
        downloadExcel($strTable, 'user_order');
        exit();
    }

}