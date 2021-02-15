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
 * Date: 2015-09-09
 */
namespace app\admin\controller;
use think\facade\View;
use app\admin\logic\RefundLogic;
use app\admin\logic\KdniaoLogic;
use app\common\logic\PlaceOrder;
use app\common\logic\ShoppingCardLogic;
use app\common\model\Order as OrderModel;
use app\common\logic\Pay;
use app\common\model\OrderGoods;
use app\common\logic\OrderLogic;
use app\common\logic\MessageFactory;
use app\common\model\ReturnGoods;
use app\common\model\ShoppingCardList;
use app\common\util\TpshopException;
use think\AjaxPage;
use think\Page;
use think\facade\Db;

class Order extends Base {
    public  $order_status;
    public  $pay_status;
    public  $shipping_status;
    public  $order_from;
    /**
     * 初始化操作
     */
    public function __construct() {
        parent::__construct();
        config('TOKEN_ON',false); // 关闭表单令牌验证
        $this->order_status = config('ORDER_STATUS');
        $this->pay_status = config('PAY_STATUS');
        $this->shipping_status = config('SHIPPING_STATUS');
        $this->order_from = \app\common\model\Order::$ORDER_FROM;

        // 订单 支付 发货状态 终端
        View::assign('order_status',$this->order_status);
        View::assign('pay_status',$this->pay_status);
        View::assign('shipping_status',$this->shipping_status);
        View::assign('order_from',$this->order_from);
    }

    /**
     *订单首页
     */
    public function index(){
        return View::fetch();
    }

    /**
     * Ajax首页
     */
    public function ajaxindex(){
        $begin = $this->begin;
        $end = $this->end;
        // 搜索条件
        $condition = array('shop_id' => 0, 'deleted' => 0);
        $keyType = input("key_type");
        $keywords = input('keywords','','trim');
        $consignee =  ($keyType && $keyType == 'consignee') ? $keywords : input('consignee','','trim');
        $consignee ? $condition['consignee'] = ['like','%'.trim($consignee).'%'] : false;
        $nickname =  ($keyType && $keyType == 'nickname') ? $keywords : input('nickname','','trim');
        $nickname ? $condition['nickname'] = ['like','%'.trim($nickname).'%'] : false;

        if($begin && $end){
            $condition['add_time'] = array('between',"$begin,$end");
        }
        $condition['prom_type'] = array('in',[0,1,2,3,4,8]);
        $order_sn = ($keyType && $keyType == 'order_sn') ? $keywords : input('order_sn') ;
        $order_sn ? $condition['order_sn'] = trim($order_sn) : false;
        
        input('order_status') != '' ? $condition['order_status'] = input('order_status') : false;
        input('pay_status') != '' ? $condition['pay_status'] = input('pay_status') : false;
        //input('pay_code') != '' ? $condition['pay_code'] = input('pay_code') : false;
        if(input('pay_code')){
            switch (input('pay_code')){
                case '余额支付':
                    $condition['pay_name'] = input('pay_code');
                    break;
                case '积分兑换':
                    $condition['pay_name'] = input('pay_code');
                    break;
                case 'alipay':
                    $condition['pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $condition['pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $condition['pay_name'] = '';
                    $condition['pay_code'] = '';
                    break;
                default:
                    $condition['pay_code'] = input('pay_code');
                    break;
            }
        }
		$condition['deleted'] = 0;
        input('shipping_status') != '' ? $condition['shipping_status'] = input('shipping_status') : false;
        input('from_terminal') != '' ? $condition['from_terminal'] = input('from_terminal') : false;
        input('user_id') ? $condition['user_id'] = trim(input('user_id')) : false;
        $sort_order = input('order_by','DESC').' '.input('sort');
        $Order = new \app\common\model\Order();

        $count = $Order->alias('o')->field('order_id')->join('users u','u.user_id = o.user_id','left')->where($condition)->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $orderList = $Order->alias('o')->field('o.*,nickname')->join('users u','u.user_id = o.user_id','left')->where($condition)->limit($Page->firstRow,$Page->listRows)->order($sort_order)->select();

        View::assign('orderList',$orderList);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$Page);
        return View::fetch();
    }


    /**
     * 虚拟订单列表
     * @return mixed
     */
    public function virtual_list(){
        return View::fetch();
    }
    public  function ajaxVirtualList(){
        $pay_status = input('pay_status');
        $keyType = input("key_type");
        $keywords = input('keywords','','trim');
		$condition['deleted'] = 0;
        $condition=['add_time'=>['between',"$this->begin,$this->end"],'prom_type'=>5];
        $pay_status !='' ? $condition['pay_status'] = $pay_status : false;
        if(input('pay_code')){
            switch (input('pay_code')){
                case '余额支付':
                    $condition['pay_name'] = input('pay_code');
                    break;
                case 'alipay':
                    $condition['pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $condition['pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $condition['pay_name'] = '';
                    $condition['pay_code'] = '';
                    break;
                default:
                    $condition['pay_code'] = input('pay_code');
                    break;
            }
        }

        if(!empty($keywords)){
            $keyType == 'mobile'   ? $condition['mobile']  = $keywords : false;
            $keyType == 'order_sn' ? $condition['order_sn'] = $keywords: false;
        }
        $count = Db::name('order')->where($condition)->count();
        $Page  = new AjaxPage($count,20);
        $orderList = Db::name('order')->where($condition)->limit($Page->firstRow,$Page->listRows)->order('order_id desc')->select();
        View::assign('orderList',$orderList);
        View::assign('pager',$Page);
        View::assign('total_count',$count);
        return View::fetch();
    }

    // 虚拟订单
    public function virtual_info(){
    header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
    }

    public function virtual_cancel(){
        $order_id = input('order_id/d');
        if(IS_POST){
            $admin_note = input('admin_note');
            $order = Db::name('order')->where(array('order_id'=>$order_id))->find();
            if($order){
                $r = Db::name('order')->where(array('order_id'=>$order_id))->save(array('order_status'=>3,'admin_note'=>$admin_note));
                if($r){
                    $commonOrder = new \app\common\logic\Order();
                    $commonOrder->setOrderById($order_id);
                    $commonOrder->orderActionLog('取消订单',$admin_note,$this->admin_id);
                    $this->ajaxReturn(array('status'=>1,'msg'=>'操作成功'));
                }else{
                    $this->ajaxReturn(array('status'=>-1,'msg'=>'操作失败'));
                }
            }else{
                $this->ajaxReturn(array('status'=>-1,'msg'=>'订单不存在'));
            }
        }
        $order = Db::name('order')->where(array('order_id'=>$order_id))->find();
        View::assign('order',$order);
        return View::fetch();
    }

    public function verify_code(){
        if(IS_POST){
            $vr_code = trim(input('vr_code'));
            if (!preg_match('/^[a-zA-Z0-9]{15,18}$/',$vr_code)) {
                $this->ajaxReturn(['status'=>0,'msg' => '兑换码格式错误，请重新输入']);
            }
            $vr_code_info = Db::name('vr_order_code')->where(array('vr_code'=>$vr_code))->find();
            $order = Db::name('order')->where(['order_id'=>$vr_code_info['order_id']])->field('order_status,order_sn,user_note,user_id')->find();
            if($order['order_status'] >2 && $order['order_status'] != 4){
                $this->ajaxReturn(['status'=>0,'msg' => '兑换码对应订单状态不符合要求']);
            }
            if(empty($vr_code_info)){
                $this->ajaxReturn(['status'=>0,'msg' => '该兑换码不存在']);
            }
            if ($vr_code_info['vr_state'] == '1') {
                $this->ajaxReturn(['status'=>0,'msg' => '该兑换码已被使用']);
            }
            if ($vr_code_info['vr_indate'] < time()) {
                $this->ajaxReturn(['status'=>0,'msg'=>'该兑换码已过期，使用截止日期为： '.date('Y-m-d H:i:s',$vr_code_info['vr_indate'])]);
            }
            if ($vr_code_info['refund_lock'] > 0) {//退款锁定状态:0为正常,1为锁定(待审核),2为同意
                $this->ajaxReturn(['status'=>0,'msg'=> '该兑换码已申请退款，不能使用']);
            }
            $update['vr_state'] = 1;
            $update['vr_usetime'] = time();
            Db::name('vr_order_code')->where(array('vr_code'=>$vr_code))->save($update);
            //检查订单是否完成
            $condition = array();
            $condition['vr_state'] = 0;
            $condition['refund_lock'] = array('in',array(0,1));
            $condition['order_id'] = $vr_code_info['order_id'];
            $condition['vr_indate'] = array('>',time());
            $vr_order = Db::name('vr_order_code')->where($condition)->select()->toArray();
            if(empty($vr_order)){
                $data['order_status'] = 2;  //此处不能直接为4，不然前台不能评论
                $data['shipping_status'] = 1;  //此处不能直接为4，不然前台不能评论
                $data['confirm_time'] = time();
                Db::name('order')->where(['order_id'=>$vr_code_info['order_id']])->save($data);
                Db::name('order_goods')->where(['order_id'=>$vr_code_info['order_id']])->save(['is_send'=>1]);  //把订单状态改为已收货
            }
            $order_goods = Db::name('order_goods')->where(['order_id'=>$vr_code_info['order_id']])->find();
            if($order_goods){
                if(empty($vr_order)){
                    // 商品待评价提醒
                    $goods = Db::name('goods')->where(["goods_id" => $order_goods['goods_id']])->field('original_img')->find();
                    $send_data = [
                        'message_title' => '商品待评价',
                        'message_content' => $order_goods['goods_name'],
                        'img_uri' => $goods['original_img'],
                        'order_sn' => $order_goods['rec_id'],
                        'order_id' => $vr_code_info['order_id'],
                        'mmt_code' => 'evaluate_logistics',
                        'type' => 4,
                        'users' => [$order['user_id']],
                        'category' => 2,
                        'message_val' => []
                    ];
                    $messageFactory = new \app\common\logic\MessageFactory();
                    $messageLogic = $messageFactory->makeModule($send_data);
                    $messageLogic->sendMessage();
                }
                $result = [
                    'vr_code'=>$vr_code,
                    'order_goods'=>$order_goods,
                    'order'=>$order,
                    'goods_image'=>goods_thum_images($order_goods['goods_id'],240,240),
                ];
                $this->ajaxReturn(['status'=>1,'msg'=>'兑换成功','result'=>$result]);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'虚拟订单商品不存在']);
            }
        }
        return View::fetch();
    }
    /**
     * 虚拟订单临时支付方法，以后要删除
     */
    public function generateVirtualCode(){
        $order_id = input('order_id/d');
        // 获取操作表
        $order = Db::name('order')->where(array('order_id'=>$order_id))->find();
        update_pay_status($order['order_sn'], ['admin_id'=>session('admin_id'),'note'=>'订单付款成功']);
        $vr_order_code = Db::name('vr_order_code')->where('order_id',$order_id)->find();
        if(!empty($vr_order_code)){
            $this->success('成功生成兑换码', url('Order/virtual_info',['order_id'=>$order_id]), 1);
        }else{
            $this->error('生成兑换码失败', url('Order/virtual_info',['order_id'=>$order_id]), 1);
        }
    }

    /**
     * ajax 发货订单列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ajaxdelivery(){
        $condition = array();
        input('consignee') ? $condition['consignee'] = trim(input('consignee')) : false;
        input('nickname') ? $condition['nickname'] = trim(input('nickname')) : false;
        input('order_sn') != '' ? $condition['order_sn'] = trim(input('order_sn')) : false;
        $listRows = input('listRows') ? input('listRows') : 10;
        $shipping_status = input('shipping_status');
        $condition['shipping_status'] = empty($shipping_status) ? array('<>',1) : $shipping_status;
        $condition['order_status'] = array('in','1,2,4');
        $condition['prom_type'] = ['<>',5];
        $condition['shop_id'] = 0;
		$condition['deleted'] = 0;
        $count = Db::name('order')->alias('o')->join('users u','u.user_id = o.user_id','left')->where($condition)->count();
        $Page  = new AjaxPage($count,$listRows);
        //搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            if(!is_array($val)){
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show = $Page->show();
        if($shipping_status)
            $orderList = Db::name('order')->alias('o')->join('users u','u.user_id = o.user_id','left')->where($condition)->limit($Page->firstRow,$Page->listRows)->field("o.*,nickname,IF(shipping_name='','无需物流',shipping_name) as shipping_name")->order('add_time DESC')->select();
        else
            $orderList = Db::name('order')->alias('o')->join('users u','u.user_id = o.user_id','left')->where($condition)->field("o.*,nickname")->limit($Page->firstRow,$Page->listRows)->order('add_time DESC')->select();
        View::assign('orderList',$orderList);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$Page);
        return View::fetch();
    }
    
    public function refund_order_list(){
        input('consignee') ? $condition['consignee'] = trim(input('consignee')) : false;
        input('order_sn') != '' ? $condition['order_sn'] = trim(input('order_sn')) : false;
        input('mobile') != '' ? $condition['mobile'] = trim(input('mobile')) : false;
        $prom_type = input('prom_type');
        if($prom_type){
            $condition['prom_type'] = $prom_type;
        }
        $condition['shipping_status'] = 0;
        $condition['order_status'] = 3;
        $condition['pay_status'] = array('>',0);
	    $condition['deleted'] = 0;
        $count = Db::name('order')->where($condition)->count();
        $Page  = new Page($count,10);
        $show = $Page->show();
        $orderList = Db::name('order')->where($condition)->limit($Page->firstRow,$Page->listRows)->order('add_time DESC')->select();
        View::assign('orderList',$orderList);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$Page);
        return View::fetch();
    }
    
    public function refund_order_info($order_id){
        $orderModel = new OrderModel();
        $orderObj = $orderModel::where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
        if($order['pay_status'] == 3 && $order['orderGoods'][0]['goods']['is_virtual'] == 1){
            $this->error('查看虚拟订单详情',url("admin/Order/virtual_info",['order_id'=>$order_id]),1);
            exit;
        }
        View::assign('order',$order);
        return View::fetch();
    }

    /**
     * 取消订单退款
     * @throws \think\Exception
     */
    public function refund_order(){
        $data = input('post.');
        $orderModel = new OrderModel();
        $order = $orderModel::where(['order_id'=>$data['order_id']])->find();
        if(!$order){
            $this->error('订单不存在或参数错误');
        }
        if($data['pay_status'] == 3){
            $card_model = new ShoppingCardLogic();//购物卡退款
            $card_model->ChangeBalance($order);


            $refundLogic = new RefundLogic();
            if($data['refund_type'] == 1){
                //取消订单退款退余额
                if($refundLogic->updateRefundOrder($order,1)){
                    $this->success('成功退款到账户余额');
                }else{
                    $this->error('退款失败');
                }
            }
            if($data['refund_type']== 0){
                //取消订单支付原路退回
                $pay_code_arr = ['weixinH5','weixin'/*PC+公众号微信支付*/ , 'alipay'/*APP,PC支付宝支付*/ , 'newalipay'/*新支付宝支付*/ ,  'alipayMobile'/*手机支付宝支付*/ , 'newalipayMobile'/*新手机支付宝支付*/ , 'miniAppPay'/*小程序微信支付*/  , 'appWeixinPay'/*APP微信支付*/];
                if(in_array($order['pay_code'] , $pay_code_arr)){
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
                }else{
                    $this->error('该订单支付方式不支持在线退回');
                }
            }

        }else{
            // 已支付的订单,直接拒绝为可以发货的订单
            if($order['pay_status'] == 1){
                Db::name('order')->where(array('order_id'=>$order['order_id']))->save(['order_status'=>1]);
            }else{
                // 其它订单的状态依旧
                Db::name('order')->where(array('order_id'=>$order['order_id']))->save($data);
            }
            $this->success('拒绝退款操作成功');
        }
    }

    /**
     * 订单详情
     * @return mixed
     */
    public function detail(){
        $order_id = input('order_id', 0);
        $orderModel = new OrderModel();
        $order = $orderModel::where(['order_id'=>$order_id])->find();
        if(empty($order)){
            $this->error('订单不存在或已被删除');
        }
        if($order['invoice_desc'] == '不开发票')  $order['taxpayer'] = '';
        View::assign('order', $order);
        return View::fetch();
    }

    /**
     * 获取订单操作记录
     */
    public function getOrderAction(){
        $order_id = input('order_id/d',0);
        $order_id <= 0 && $this->ajaxReturn(['status'=>1,'msg'=>'参数错误！！']);
        $orderModel = new OrderModel();
        $orderObj = $orderModel::where(['order_id'=>$order_id])->find();
        $order = $orderObj->toArray();
        // 获取操作记录
        $action_log = Db::name('order_action')->where(['order_id'=>$order_id])->order('log_time desc')->select()->toArray();
        $admins = Db::name("admin")->column("user_name",'admin_id');
                
        $user = Db::name("users")->field('user_id,nickname')->where(['user_id'=>$order['user_id']])->find();
        //查找用户昵称
        foreach ($action_log as $k => $v){
            if ($v['group'] == 0) {
				if ($v['action_user'] == 0){
					$action_log["$k"]['action_user_name'] = '用户:'.$user['nickname'];
				}else{
					$action_log["$k"]['action_user_name'] = '管理员:'.$admins[$v['action_user']];
				}
			} else if ($v['group'] == 1) {
				$suppliers_name = Db::name("suppliers")->where(['suppliers_id'=>$v['action_user']])->value('suppliers_name');
				$action_log["$k"]['action_user_name'] = '供应商:'.$suppliers_name;
			}
            $action_log["$k"]["log_time"] = date('Y-m-d H:i:s',$v['log_time']);
            $action_log["$k"]["order_status"] = $this->order_status[$v['order_status']];
            $action_log["$k"]["pay_status"] = $this->pay_status[$v['pay_status']];
            $action_log["$k"]["shipping_status"] = $this->shipping_status[$v['shipping_status']];
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'参数错误！！','data'=>$action_log]);
    }

    /**
     * 拆分订单
     */
    public function split_order(){
        $order_id = input('order_id');
        $orderModel = new OrderModel();
        $orderObj = $orderModel::where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
        if($order['pay_status'] == 0){
            $this->error('未支付订单不允许拆分');
            exit;
        }
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
        $orderGoods = $order['orderGoods'];
        if($orderGoods){
            $orderGoods = $orderGoods->toArray();
        }
        if(IS_POST){
            //################################先处理原单剩余商品和原订单信息
            $old_goods = input('old_goods/a');
            foreach ($orderGoods as $val){
                $all_goods[$val['rec_id']] = $val;//所有商品信息
            }


            //################################新单处理
            for($i=1;$i<20;$i++){
                $temp = request()->param($i.'_old_goods/a');
                if(!empty($temp)){
                    $split_goods[] = $temp;
                }
            }

            foreach ($split_goods as $key=>$vrr){
                foreach ($vrr as $k=>$v){
                    $all_goods[$k]['goods_num'] = $v;
                    $brr[$key][] = $all_goods[$k];
                }
            }

            $user_money = $order['user_money'] / $order['total_amount'];
            $integral = $order['integral'] / $order['total_amount'];
            $order_amount = $order['order_amount'] / $order['total_amount'];
            $split_user_money = 0;// 累计
            $split_integral = 0;
            $split_order_amount = 0;

            foreach($brr as $k=>$goods){
                $newPay = new Pay();
                try{
                    $newPay->setUserId($order['user_id']);
                    $newPay->payGoodsList($goods);
                    $newPay->delivery($order['district']);
                    $newPay->orderPromotion();
                } catch (TpshopException $t) {
                    $error = $t->getErrorArr();
                    $this->error($error['msg']);
                }
                $new_order = $order;
                $new_order['order_sn'] = date('YmdHis').mt_rand(1000,9999);
                $new_order['parent_sn'] = $order['order_id']; // 放父id好
                //修改订单费用
                $new_order['goods_price']    = $newPay->getGoodsPrice(); // 商品总价
                $new_order['total_amount']   = $newPay->getTotalAmount(); // 订单总价
//                if($order['pay_name'] == '余额支付'){
                    //修改拆分订单余额展示
//                    $new_order['user_money'] = $newPay->getTotalAmount();
                    $new_order['user_money'] = floor(($user_money * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
//                }else{
//                    $new_order['order_amount']   = $newPay->getOrderAmount(); // 应付金额
                    $new_order['order_amount']   = floor(($order_amount * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
//                }
                $new_order['integral'] = floor(($integral * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
                //前面按订单总比例拆分，剩余全部给最后一个订单
                if($k == count($brr)-1){
                    $new_order['user_money'] = $order['user_money']-$split_user_money;
                    $new_order['integral'] = $order['integral']-$split_integral;
                    $new_order['order_amount'] = $order['order_amount']-$split_order_amount;
                }else{
                    $split_user_money += $new_order['user_money'];
                    $split_integral += $new_order['integral'];
                    $split_order_amount += $new_order['order_amount'];
                }
                if($order['integral'] > 0 ){
                    $new_order['integral_money'] = $new_order['integral']/($order['integral']/$order['integral_money']);
                }
                $new_order['add_time'] = time();
                unset($new_order['order_id']);
                $new_order_id = DB::name('order')->insertGetId($new_order);//插入订单表
                foreach ($goods as $vv){
                    $vv['order_id'] = $new_order_id;
                    unset($vv['rec_id']);
                    $nid = Db::name('order_goods')->insertGetId($vv);//插入订单商品表
                }
            }
            //拆分订单后软删除原父订单信息，商品信息可删除
            $orderObj->order_status = 5; // 作废
            $orderObj->deleted = 1;
            $orderObj->save();
            DB::name('order_goods')->where(['order_id'=>$order_id])->delete();

            //################################新单处理结束
            $this->success('操作成功',url('Admin/Order/index'));
            exit;
        }

        foreach ($orderGoods as $val){
            $brr[$val['rec_id']] = array('goods_num'=>$val['goods_num'],'goods_name'=>getSubstr($val['goods_name'], 0, 35).$val['spec_key_name']);
        }
        View::assign('order',$order);
        View::assign('goods_num_arr',json_encode($brr));
        View::assign('orderGoods',$orderGoods);
        return View::fetch();
    }

    /**
     * 价钱修改
     */
    public function editprice($order_id){
        $orderModel = new OrderModel();
        $orderObj = $orderModel::where(['order_id'=>$order_id])->find();
        $order = $orderObj->toArray();
        $this->editable($order);
        if(IS_POST){
            $admin_id = session('admin_id');
            if(empty($admin_id)){
                $this->error('非法操作');
                exit;
            }
            $update['discount'] = input('post.discount');
            $update['shipping_price'] = input('post.shipping_price');
            if($update['shipping_price'] < 0){
                $this->error('运费不能小于0');
                exit;
            }
            // 改为正数，往上调，负数往下调
            $update['order_amount'] = $order['goods_price']+$update['shipping_price']+$update['discount']-$order['user_money']-$order['integral_money']-$order['coupon_price']-$order['order_prom_amount'];
            //$update['discount'] *= -1;
            $row = Db::name('order')->where(array('order_id'=>$order_id))->save($update);
            if(!$row){
                $this->success('没有更新数据',url('Admin/Order/editprice',array('order_id'=>$order_id)));
            }else{
                $this->success('操作成功',url('Admin/Order/detail',array('order_id'=>$order_id)));
            }
            exit;
        }
        View::assign('order',$order);
        return View::fetch();
    }

    /**
     * 订单删除
     * @param int $id 订单id
     */
    public function delete_order(){
        $order_id = input('post.order_id/d',0);
        $order = new \app\common\logic\Order($order_id);
        $order->setOrderById($order_id);
        try{
            $order->adminDelOrder();
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 订单取消付款
     * @param $order_id
     * @return mixed
     */
    public function pay_cancel($order_id){
        if(input('remark')){
            $data = input('post.');
            $note = array('退款到用户余额','已通过其他方式退款','不处理，误操作项');
            if($data['refundType'] == 0 && $data['amount']>0){
                accountLog($data['user_id'], $data['amount'], 0,  '退款到用户余额');

                // 退款消息
                $messageFactory = new MessageFactory();
                $messageLogic = $messageFactory->makeModule(['category' => 2]);
                $messageLogic->sendRefundNotice($data['order_id'],$data['amount']);

            }
            $orderLogic = new OrderLogic();
            $orderLogic->orderProcessHandle($data['order_id'],'pay_cancel');
            $commonOrder = new \app\common\logic\Order();
            $commonOrder->setOrderById($data['order_id']);
            $d = $commonOrder->orderActionLog($data['remark'].':'.$note[$data['refundType']],'支付取消',$this->admin_id);
            if($d){
                exit("<script>window.parent.pay_callback(1);</script>");
            }else{
                exit("<script>window.parent.pay_callback(0);</script>");
            }
        }else{
            $order = Db::name('order')->where("order_id=$order_id")->find();
            View::assign('order',$order);
            return View::fetch();
        }
    }

    /**
     * 订单打印
     * @param string $id
     * @return mixed
     */
    public function order_print($id=''){
        if($id){
            $order_id = $id;
        }else{
            $order_id = input('order_id');
        }
        $orderModel = new OrderModel();
        $orderObj = $orderModel::where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address','orderGoods','delivery_method'])->toArray();
//        halt($order['orderGoods']);
        $order['province'] = getRegionName($order['province']);
        $order['city'] = getRegionName($order['city']);
        $order['district'] = getRegionName($order['district']);
        $order['full_address'] = $order['province'].' '.$order['city'].' '.$order['district'].' '. $order['address'];
        if($id){
            return $order;
        }else{
            $shop = tpCache('shop_info');
            $area_list = Db::name('region')->where('id', 'IN', [$shop['province'], $shop['city'], $shop['district']])->order('level asc')->select();
            $shop['address'] =$area_list[0]['name'].' '.$area_list[1]['name'].' '.$area_list[2]['name'].' '.$shop['address'];
            View::assign('order',$order);
            View::assign('shop',$shop);
            $template = input('template','picking');
            return View::fetch($template);
        }
    }

    /**
     *批量打印发货单
     */
    public function delivery_print(){
        $ids =input('print_ids');
        $order_ids=trim($ids,',');
        $orderModel= new OrderModel();
        $orderObj = $orderModel->whereIn('order_id',$order_ids)->select();
        if (!$orderObj->isEmpty()){
            $order = $orderObj->append(['orderGoods','full_address'])->toArray();
        }
        $shop = tpCache('shop_info');
        View::assign('order',$order);
        View::assign('shop',$shop);
        $template = input('template','print');
        return View::fetch($template);
    }

    /**
     * 快递单打印
     */
    public function shipping_print($id=''){
        $orderLogic = new OrderLogic();
        $data['order_id'] = input('order_id/d');
        $data['shipping'] = 2;
        $data['send_type'] = 2;
        $res = $orderLogic->deliveryHandle($data);
        if($res['status'] == 1){
            if($data['send_type'] == 2 && !empty($res['printhtml'])){
                View::assign('printhtml',$res['printhtml']);
                return View::fetch('print_online');
            }
        }else{
            $this->error($res['msg'],url('Admin/Order/delivery_info',array('order_id'=>$data['order_id'])));
        }

//        if($id){
//            $order_id = $id;
//        }else{
//            $order_id = input('get.order_id');
//        }
//        $orderModel = new OrderModel();
//        $orderObj = $orderModel::where(['order_id'=>$order_id])->find();
//        $order =$orderObj->append(['full_address'])->toArray();
//        //查询是否存在订单及物流
//        $shipping = Db::name('shipping')->where('shipping_code',$order['shipping_code'])->find();
//        if(!$shipping){
//          $this->error('快递公司不存在');
//        }
//      if(empty($shipping['template_html'])){
//          $this->error('请设置'.$shipping['shipping_name'].'打印模板');
//      }
//        $shop = tpCache('shop_info');//获取网站信息
//        $shop['province'] = empty($shop['province']) ? '' : getRegionName($shop['province']);
//        $shop['city'] = empty($shop['city']) ? '' : getRegionName($shop['city']);
//        $shop['district'] = empty($shop['district']) ? '' : getRegionName($shop['district']);
//
//        $order['province'] = getRegionName($order['province']);
//        $order['city'] = getRegionName($order['city']);
//        $order['district'] = getRegionName($order['district']);
//        $template_var = array("发货点-名称", "发货点-联系人", "发货点-电话", "发货点-省份", "发货点-城市",
//               "发货点-区县", "发货点-手机", "发货点-详细地址", "收件人-姓名", "收件人-手机", "收件人-电话",
//              "收件人-省份", "收件人-城市", "收件人-区县", "收件人-邮编", "收件人-详细地址", "时间-年", "时间-月",
//              "时间-日","时间-当前日期","订单-订单号", "订单-备注","订单-配送费用");
//        $content_var = array($shop['store_name'],$shop['contact'],$shop['phone'],$shop['province'],$shop['city'],
//          $shop['district'],$shop['phone'],$shop['address'],$order['consignee'],$order['mobile'],$order['phone'],
//          $order['province'],$order['city'],$order['district'],$order['zipcode'],$order['address'],date('Y'),date('M'),
//          date('d'),date('Y-m-d'),$order['order_sn'],$order['admin_note'],$order['shipping_price'],
//        );
//        $shipping['template_html_replace'] = str_replace($template_var, $content_var, $shipping['template_html']);
//        if($id){
//            return $shipping;
//        }else{
//            $shippings[0]=$shipping;
//            View::assign('shipping',$shippings);
//            return View::fetch("print_express");
//        }

    }

    /**
     *批量打印快递单
     */
    public function shipping_print_batch(){
        $ids=input('post.ids3');
        $ids=substr($ids,0,-1);
        $ids=explode(',', $ids);
        if(!is_numeric($ids[0])){
            unset($ids[0]);
        }

        $shippings=array();
        foreach ($ids as $k => $v) {
            $shippings[$k]=$this->shipping_print($v);
        }
        View::assign('shipping',$shippings);
        return View::fetch("print_express");
    }

    /**
     * 生成发货单
     */
    public function deliveryHandle(){
        $orderLogic = new OrderLogic();
        $data = input('post.');
        $res = $orderLogic->deliveryHandle($data);
        if($res['status'] == 1){
            if($data['send_type'] == 2 && !empty($res['printhtml'])){
                View::assign('printhtml',$res['printhtml']);
                return View::fetch('print_online');
            }
            $this->success('操作成功',url('Admin/Order/delivery_info',array('order_id'=>$data['order_id'])));
        }else{
            $this->error($res['msg'],url('Admin/Order/delivery_info',array('order_id'=>$data['order_id'])));
        }
    }

    /**
     * 取消电子面单
     */
    public function cancelEOrder(){
        $orderLogic = new OrderLogic();
        $data['rec_id'] = input('rec_id/a');
        $res = $orderLogic->cancelEOrder($data);
        $this->ajaxReturn($res);
    }

    public function delivery_info($id=''){
        if($id){
           $order_id=$id; 
        }else{
           $order_id = input('order_id');
        }

        $orderGoodsMdel = new OrderGoods();
        $orderModel = new OrderModel();
        $orderObj = $orderModel->where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address'])->toArray();
        $orderGoods = $orderGoodsMdel::where(['order_id'=>$order_id,'is_send'=>['<',2]])->select();
        if($id){
            if($orderGoods->isEmpty()){
                $this->error('所选订单有商品已完成退货或换货');//已经完成售后的不能再发货
            }
        }else{
            if($orderGoods->isEmpty()){
                $this->error('此订单商品已完成退货或换货');//已经完成售后的不能再发货  
            }
        }

        if($id){ 
            $order['orderGoods']=$orderGoods;
            $order['goods_num']=count($orderGoods);
            return $order;
        }else{
            $delivery_record = Db::name('delivery_doc')->alias('d')->join('admin a','a.admin_id = d.admin_id')->where('d.order_id='.$order_id)->select();
//            if($delivery_record){
//                $order['invoice_no'] = $delivery_record[0]['invoice_no'];
//            }
			$suppliers = Db::name('suppliers')->column('suppliers_name','suppliers_id');
			View::assign('suppliers', $suppliers);
            $invoice_no_arr = get_id_val($delivery_record ,'id','invoice_no');
            View::assign('invoice_no_arr',$invoice_no_arr);
            View::assign('order',$order);
            View::assign('orderGoods',$orderGoods);
            View::assign('delivery_record',$delivery_record);//发货记录
            $shipping_list = Db::name('shipping')->field('shipping_name,shipping_code')->select();
            View::assign('shipping_list',$shipping_list);
            $express_switch = tpCache('express.express_switch');
            View::assign('express_switch',$express_switch);
            return View::fetch();    
        }
    }

    /**
     * 发货单列表
     */
    public function delivery_list(){
        return View::fetch();
    }

    /**
    *批量发货
    */
    public function delivery_batch(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
    }
    /**
     * 导入批量发货订单
     */
    public function delivery_excel()
    {
        if(IS_POST){
            $CommonOrderLogic = new OrderLogic();
                
            if($_FILES['excel']['error'] != 4)
                $file = request()->file('excel');//csv文件          
            if(!$file)
        	$this->error("没有导入文件", url('Admin/import/index'));            
            $upload_max_filesize = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
             $maxsize = 30000000;

             if($file->getSize() > $maxsize)
                 $state = '上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件,且系统配置不能超过:'.$upload_max_filesize;
             $originalName = strtolower($file->getOriginalName());
             if(strstr($originalName,'.php') || strstr($originalName,'.js')) 
                $state = '上错csv文件错误';
            $extension = strtolower($file->extension());
            if(!in_array($extension,['csv','xls']))
                    $state = '仅可上传csv和xls文件';                
                
            $ids= $CommonOrderLogic->excel_import($file);
            if(0 == $ids['status']){
                $this->error($ids['msg']);
            }
            $ids = $ids['result'];
            $order=array();
            foreach ($ids as $k => $v) {
                $orderModel = new \app\common\model\Order;
                $res = $orderModel->with(['OrderGoods'])->where('order_id', $v['id'])->find();
                if ($res){
                    $order[$k] = $res;
                    $order[$k]['orderGoods'] = $res['order_goods'];
                    $order[$k]['invoice_no']=$v['invoice_no'];
                }
            }
            View::assign('order',$order);
            View::assign('num',count($order));
        }
        $shipping_list = Db::name('shipping')->field('s.shipping_name,s.shipping_code')->alias('ss')->join('shipping s','s.shipping_id = ss.shipping_id','left')->select();            
        View::assign('shipping_list',$shipping_list);
        
        return View::fetch();
    }

    /**
    *批量发货处理 
    */
    public function delivery_batch_handle(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
    }

    /**
     * 删除某个退换货申请
     */
    public function return_del(){
        $id = input('get.id');
        Db::name('return_goods')->where("id = $id")->delete();
        $this->success('成功删除!');
    }

    public function delReturnList(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $returnListIds = rtrim($ids);
        Db::name('return_goods')->whereIn('id',$returnListIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Order/return_list")]);
    }

    /**
     * 退换货操作
     */
    public function return_info()
    {
        $return_id = input('id');
        $return_goods = Db::name('return_goods')
			->alias('sg')
			->join('suppliers s', 'sg.suppliers_id=s.suppliers_id', 'left')
			->field('sg.*,s.suppliers_name')
			->where(['sg.id'=> $return_id])->find();
        !$return_goods && $this->error('非法操作!');
        $user = Db::name('users')->where(['user_id' => $return_goods['user_id']])->find();
        $order = Db::name('order')->where(array('order_id'=>$return_goods['order_id']))->find();
        $order['goods'] = Db::name('order_goods')->where(['rec_id' => $return_goods['rec_id']])->find();
        $return_goods['delivery'] = unserialize($return_goods['delivery']);  //订单的物流信息，服务类型为换货会显示
        $return_goods['seller_delivery'] = unserialize($return_goods['seller_delivery']);  //订单的物流信息，服务类型为换货会显示
        if($return_goods['imgs']) $return_goods['imgs'] = explode(',', $return_goods['imgs']);
        View::assign('id',$return_id); // 用户
        View::assign('user',$user); // 用户
        View::assign('return_goods',$return_goods);// 退换货
        View::assign('order',$order);//退货订单信息
        View::assign('return_type',config('RETURN_TYPE'));//退货订单信息
        View::assign('refund_status',config('REFUND_STATUS'));
        return View::fetch();
    }

    /**
     *修改退货状态
     */
    public function checkReturniInfo()
    {
        $orderLogic = new OrderLogic();
        $post_data = input('post.');
        $return_goods = Db::name('return_goods')->where(['id'=>$post_data['id']])->find();
        !$return_goods && $this->ajaxReturn(['status'=>-1,'msg'=>'非法操作!']);
        $type_msg = config('RETURN_TYPE');
        $status_msg = config('REFUND_STATUS');
        switch ($post_data['status']){
            case -1 :$post_data['checktime'] = time();break;
            case 1 :$post_data['checktime'] = time();break;
            case 3 :$post_data['receivetime'] = time();break;  //卖家收货时间
            default;
        }
        if($return_goods['type'] > 0  && $post_data['status'] == 4){
            $post_data['seller_delivery']['express_time'] = date('Y-m-d H:i:s');
            $post_data['seller_delivery'] = serialize($post_data['seller_delivery']); //换货的物流信息
            Db::name('order_goods')->where(['rec_id'=>$return_goods['rec_id']])->save(['is_send'=>2]);
        }
        $note ="退换货:{$type_msg[$return_goods['type']]}, 状态:{$status_msg[$post_data['status']]},处理备注：{$post_data['remark']}";
        $result = Db::name('return_goods')->where(['id'=>$post_data['id']])->save($post_data);
        if($result && $post_data['status']==1 && $return_goods['type']!=2)
        {
            //审核通过才更改订单商品状态，进行退货要改对应商品修改库存
            $order = OrderModel::find($return_goods['order_id']);
            $commonOrderLogic = new OrderLogic();
            $return_goods['type'] == 1 && $commonOrderLogic->alterReturnGoodsInventory($order,$return_goods['rec_id']); //审核通过，恢复原来库存
            if($return_goods['type'] < 2){
                $orderLogic->disposereRurnOrderCoupon($return_goods); // 是退货可能要处理优惠券
            }
        }
        $commonOrder = new \app\common\logic\Order();
        $commonOrder->setOrderById($return_goods['order_id']);
        $commonOrder->orderActionLog($note,'退换货',$this->admin_id);
        $this->ajaxReturn(['status'=>1,'msg'=>'修改成功','url'=>'']);
    }

    //售后退款原路退回
    public function refund_back(){
        $return_id = input('id');
        $refund_deposit = input('refund_deposit/d',0);
        $refund_money = input('refund_money/f',0);
        $refund_integral = input('refund_integral/d',0);
        $refundLogic = new RefundLogic();
        $refundLogic->setRefundDeposit($refund_deposit);
        $refundLogic->setRrefundMoney($refund_money);
        $refundLogic->setRrefundIntegral($refund_integral);
        $return_goods = Db::name('return_goods')->where("id= $return_id")->find();
        $rec_goods = Db::name('order_goods')->where(array('order_id'=>$return_goods['order_id'],'goods_id'=>$return_goods['goods_id']))->find();
        $order = Db::name('order')->where(array('order_id'=>$rec_goods['order_id']))->find();
        if($order['pay_code'] == 'weixinH5' || $order['pay_code'] == 'weixin' || $order['pay_code'] == 'miniAppPay'  || $order['pay_code'] == 'appWeixinPay'
            || $order['pay_code'] == 'alipay' || $order['pay_code'] == 'newalipay' || $order['pay_code'] == 'alipayMobile' || $order['pay_code'] == 'newalipayMobile'){

            $cardLogic = new ShoppingCardLogic();//购物卡退款
            $cardLogic->ChangeBalance($order);

            $orderLogic = new OrderLogic();
            $return_goods['refund_money'] = $orderLogic->getRefundGoodsMoney($return_goods);
            // 如果没有选物流发货，要退邮费。
            if(empty($order['shipping_name']) && $order['shipping_price'] > 0){
                $return_goods['refund_money'] += $order['shipping_price'];
            }
            // 判断提交的余额来退款
            if($refund_money > 0 && $refund_money < $return_goods['refund_money']){
                $return_goods['refund_money'] = $refund_money;
            }
            if($order['pay_code'] == 'weixinH5' || $order['pay_code'] == 'weixin' || $order['pay_code'] == 'miniAppPay'  || $order['pay_code'] == 'appWeixinPay'){
                include_once  PLUGIN_PATH."payment/weixin/weixin.class.php";
                $payment_obj =  new \weixin($order['pay_code']);
                // 拆分订单时，以主单号来查
                if(!empty($order['parent_sn'])){
                    $order['order_amount'] = Db::name('order')->where('order_id',$order['parent_sn'])->value('order_amount');
                }
                $data = array('transaction_id'=>$order['transaction_id'],'total_fee'=>$order['order_amount'],'refund_fee'=>$return_goods['refund_money']);
                $result = $payment_obj->payment_refund($data);
                if($result['return_code'] == 'SUCCESS'  && $result['result_code'] == 'SUCCESS'){
                    $refundLogic->updateRefundGoods($return_goods['rec_id']);//订单商品售后退款
                    $this->ajaxReturn(['status'=>1,'msg'=>'退款成功','url'=>url("Admin/Order/return_list")]);
                }else{
                    $this->ajaxReturn(['status'=>-1,'msg'=>'退款失败'.$result['return_msg']]);
                }
            }else if ($order['pay_code'] == 'newalipay' || $order['pay_code'] == 'newalipayMobile') {

                include_once PLUGIN_PATH . "payment/newalipay/newalipay.class.php";
                $payment_obj = new \newalipay();
                $refund_data = array('order_id' => $rec_goods['rec_id'], 'out_trade_no' => $order['order_sn'], 'refund_amount' => $return_goods['refund_money'], 'refund_reason' => $order['admin_note'], 'type' => 2);
				//上面的order_id传的可能是order表的order_id,或者是order_goods表的rec_id，不同情况传的参数不同
                // 部分退款需要WIDTRout_request_no参数
                if($return_goods['refund_money'] != $order['order_amount']){
                    $refund_data['WIDTRout_request_no'] = date('YmdHis').rand(1000,9999);
                }
                if ($payment_obj->payment_refund($refund_data)) {
                    $this->ajaxReturn(['status'=>1,'msg'=>'退款成功','url'=>'']); 
                } else {
                    $this->ajaxReturn(['status'=>0,'msg'=>'退款失败','url'=>'']); 
                } 
            }else{
                include_once  PLUGIN_PATH."payment/newalipay/alipay.class.php";
                $payment_obj = new \alipay();
                $detail_data = $order['transaction_id'].'^'.$return_goods['refund_money'].'^'.'用户申请订单退款';
                $data = array('batch_no'=>date('YmdHi').'r'.$rec_goods['rec_id'],'batch_num'=>1,'detail_data'=>$detail_data);
                $payment_obj->payment_refund($data);
            }
        }else{
            $this->ajaxReturn(['status'=>-1,'msg'=>'该订单支付方式不支持在线退回']);
        }
    }

    /**
     * 退款到用户余额，余额+积分支付
     * 有用三方金额支付的不走这个方法
     */
    public function refund_balance(){
        $rec_id = input('rec_id');
        $refund_deposit = input('refund_deposit/f',0);
        $refund_money = input('refund_money/f',0);
        $refund_integral = input('refund_integral/f',0);
        $return_goods = Db::name('return_goods')->where(array('rec_id'=>$rec_id))->find();
        if(empty($return_goods)) $this->ajaxReturn(['status'=>0,'msg'=>"参数有误"]);
        $refundLogic = new RefundLogic();
        $refundLogic->setRefundDeposit($refund_deposit);
        $refundLogic->setRrefundMoney($refund_money);
        $refundLogic->setRrefundIntegral($refund_integral);
        $refundLogic->updateRefundGoods($rec_id,1);//售后商品退款

        $this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>url("Admin/Order/return_list")]);
    }

    /**
     * 管理员生成申请退货单
     */
    public function add_return_goods()
   {
            $order_id = input('order_id');
            $goods_id = input('goods_id');

            $return_goods = Db::name('return_goods')->where("order_id = $order_id and goods_id = $goods_id")->find();
            if(!empty($return_goods))
            {
                $this->error('已经提交过退货申请!',url('Admin/Order/return_list'));
                exit;
            }
            $order = Db::name('order')->where("order_id = $order_id")->find();

            $data['order_id'] = $order_id;
            $data['order_sn'] = $order['order_sn'];
            $data['goods_id'] = $goods_id;
            $data['addtime'] = time();
            $data['user_id'] = $order['user_id'];
			$data['suppliers_id'] = $order['suppliers_id'];
            $data['remark'] = '管理员申请退换货'; // 问题描述
            Db::name('return_goods')->insert($data);
            $this->success('申请成功,现在去处理退货',url('Admin/Order/return_list'));
            exit;
    }

    /**
     * 订单操作
     * @param $id
     */
    public function order_action(){     
        $orderLogic = new OrderLogic();
        $action = input('get.type');
        $order_id = input('get.order_id');
        if($action && $order_id){
            if($action !=='pay'){
                $convert_action= config('CONVERT_ACTION')["$action"];
                $commonOrder = new \app\common\logic\Order();
                $commonOrder->setOrderById($order_id);
                $res =  $commonOrder->orderActionLog(input('note'),$convert_action,$this->admin_id);
            }
			$a = $orderLogic->orderProcessHandle($order_id,$action,array('note'=>input('note'),'admin_id'=>$this->admin_id));
             if($res !== false && $a !== false){
                 if ($action == 'remove') {
                     $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => url('Order/index')]);
                 } elseif ($action == 'pay') {
					 $order = Db::name('order')->where(['order_id' => $order_id])->find();
					 if ($order && $order['deleted']) {
						$this->ajaxReturn(['status' => 1,'msg' => '操作成功，此为复合订单，已拆分','url' => url('Order/index')]);
					 }
				 }
                 $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url' => url('Order/detail',array('order_id'=>$order_id))]);
             }else{
                 if ($action == 'remove') {
                     $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'url' => url('Order/index')]);
                 }
                $this->ajaxReturn(['status' => 0,'msg' => '操作失败','url' => url('Order/index')]);
             }
        }else{
            $this->ajaxReturn(['status' => 0,'msg' => '参数错误','url' => url('Order/index')]);
        }
    }
    
    public function order_log(){
        $order_sn = input('order_sn');
        $condition = array();
        $begin = $this->begin;
        $end = $this->end;
        $condition['log_time'] = array('between',"$begin,$end");
        if($order_sn){   //搜索订单号
            $order_id_arr = Db::name('order')->where(['order_sn' => $order_sn])->column('order_id');
            $order_ids =implode(',',$order_id_arr);
            $condition['order_id']=['in',$order_ids];
            View::assign('order_sn',$order_sn);
        }

        $admin_id = input('admin_id');
        if($admin_id >0 ){
            $condition['action_user'] = $admin_id;
        }
        $count = Db::name('order_action')->where($condition)->count();
        $Page = new Page($count,20);

        foreach($condition as $key=>$val) {
            $Page->parameter[$key] = urlencode($begin.'_'.$end);
        }
        $show = $Page->show();
        $list = Db::name('order_action')->where($condition)->order('action_id desc')->limit($Page->firstRow,$Page->listRows)->select()->toArray();

        //绑定订单类型是否虚拟，方便跳转对应订单详情
        $order_id_array = array_unique(array_column($list,'order_id'));
        $order_type_list = Db::name('order')->where('order_id','in',$order_id_array)->field('order_id,prom_type')->select()->toArray();
        $new_arr = [];
        foreach ($order_type_list as $k => $v){
            $new_arr[$order_type_list[$k]['order_id']] = $v['prom_type'];
        }

        $orderIds = [];
        foreach ($list as $k => $log) {
            $list[$k]['prom_type'] = $new_arr[$log['order_id']];
            if (!$log['action_user']) {
                $orderIds[] = $log['order_id'];
            }
        }
        if ($orderIds) {
            $users = Db::name("users")->alias('u')->join('order o', 'o.user_id = u.user_id')->column('u.nickname','o.order_id');
        }
        View::assign('users',$users);
        View::assign('list',$list);
        View::assign('pager',$Page);
        View::assign('page',$show);    
        $admin = Db::name('admin')->column('user_name','admin_id');
        View::assign('admin',$admin);
		$suppliers = Db::name('suppliers')->column('suppliers_name','suppliers_id');
        View::assign('suppliers',$suppliers);
        return View::fetch();
    }

    /**
     * 检测订单是否可以编辑
     * @param $order
     */
    private function editable($order){
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
        return;
    }

    /**
     * 导出订单
     */
    public function export_order()
    {

		$where['o.deleted'] = 0;
        //搜索条件
        $order_ids = input('order_ids');
		if($order_ids){
            $where['o.order_id'] = ['in', $order_ids];
        } elseif (input('is_delivery_list')) {
			//发货单页面的打印
			input('consignee') ? $where['o.consignee'] = trim(input('consignee')) : false;
			input('nickname') ? $where['o.nickname'] = trim(input('nickname')) : false;
			input('order_sn') != '' ? $where['o.order_sn'] = trim(input('order_sn')) : false;
			$shipping_status = input('shipping_status');
			$where['o.shipping_status'] = empty($shipping_status) ? array('<>',1) : $shipping_status;
			$where['o.order_status'] = array('in','1,2,4');
			$where['o.prom_type'] = ['<>',5];
			$where['o.shop_id'] = 0;
		} elseif (input('is_refund_order_list')) {
			//退款单页面的打印
			input('consignee') ? $where['o.consignee'] = trim(input('consignee')) : false;
			input('order_sn') != '' ? $where['o.order_sn'] = trim(input('order_sn')) : false;
			input('mobile') != '' ? $where['o.mobile'] = trim(input('mobile')) : false;
			$prom_type = input('export_prom_type');
			if($prom_type){
				$where['o.prom_type'] = $prom_type;
			}
			$where['o.shipping_status'] = 0;
			$where['o.order_status'] = 3;
			$where['o.pay_status'] = array('>',0);
		} else {
			$order_status = input('order_status');
			$prom_type = input('prom_type'); //订单类型
			$keyType =   input("key_type");  //查找类型
			$keywords = input('keywords','','trim');
			$where = ['o.add_time'=>['between',"$this->begin,$this->end"]];
			$isSuppliers = input('is_suppliers', 0);
			$isSuppliers ? $where['o.suppliers_id'] = ['not in', [0,-1]] : $where['o.suppliers_id'] = ['in', [0,-1]];
			if(!empty($keywords)){
				$keyType == 'mobile'   ? $where['o.mobile']  = ['like', '%'.$keywords.'%'] : false;
				$keyType == 'order_sn' ? $where['o.order_sn'] = ['like', '%'.$keywords.'%'] : false;
				$keyType == 'consignee' ? $where['o.consignee'] = ['like', '%'.$keywords.'%'] : false;
				$keyType == 'suppliers_name' ? $where['s.suppliers_name'] = ['like', '%'.$keywords.'%'] : false;
			}
			$prom_type != '' ? $where['o.prom_type'] = $prom_type : $where['o.prom_type'] = ['in',[0,1,2,3,4,7,8]];
			if(!empty($order_status) || $order_status === '0'){
				$where['o.order_status'] = $order_status;
			}
			if(input('pay_code')){
				switch (input('pay_code')){
					case '余额支付':
						$where['o.pay_name'] = input('pay_code');
						break;
					case '积分兑换':
						$where['o.pay_name'] = input('pay_code');
						break;
					case 'alipay':
						$where['o.pay_code'] = ['in',['alipay','alipayMobile']];
						break;
					case 'weixin':
						$where['o.pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
						break;
					case '其他方式':
						$where['o.pay_name'] = '';
						$where['o.pay_code'] = '';
						break;
					default:
						$where['o.pay_code'] = input('pay_code');
						break;
				}
			}
			input('pay_status') != '' ? $where['o.pay_status'] = input('pay_status') : false;
            $shipping_status = input('shipping_status');
			if($where['o.order_status'] == 3){
				$where['o.pay_status'] = ['>',0];
				$where['o.shipping_status'] = 0;
				unset($where['o.add_time']);
				unset($where['o.prom_type']);
			}elseif($shipping_status === '0' || !empty($shipping_status)){
                $where['o.shipping_status'] = $shipping_status;
            }
		}
        $orderList = Db::name('order')
			->alias('o')
			->join('suppliers s', 's.suppliers_id=o.suppliers_id', 'left')
			->field("*,FROM_UNIXTIME(o.add_time,'%Y-%m-%d') as create_time")
			->where($where)->order('o.order_id')->select();

        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:130px;">订单编号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员昵称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">日期</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货人</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货地址</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">电话</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品价格</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">实际支付</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">支付方式</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">支付状态</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单状态</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">发货状态</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品数量</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品编码</td>';
		$isSuppliers && $strTable .= '<td style="text-align:center;font-size:12px;" width="*">供应商</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品名称</td>';
        $strTable .= '</tr>';
        if($orderList->toArray()){
            $region = get_region_list();
            foreach($orderList as $k=>$val){
                $orderGoods = Db::name('order_goods')->where('order_id='.$val['order_id'])->select();
                foreach($orderGoods as $goods){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['order_sn'].'</td>';
                $user_name = Db::name('users')->where(['user_id'=>$val['user_id']])->value('nickname');
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$user_name.' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['create_time'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['consignee'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'."{$region[$val['province']]},{$region[$val['city']]},{$region[$val['district']]},{$region[$val['twon']]}{$val['address']}".' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['mobile'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods['member_goods_price'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['goods_price'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['order_amount'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['pay_name'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$this->pay_status[$val['pay_status']].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$this->order_status[$val['order_status']].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$this->shipping_status[$val['shipping_status']].'</td>';
                $strGoods="";
                $goods_num = 0;
                    $goods_num = $goods_num + $goods['goods_num'];
                    $strGoods .= " 商品名称：".$goods['goods_name'];
                    if ($goods['spec_key_name'] != '') $strGoods .= " 规格：".$goods['spec_key_name'];
//                  $strGoods .= "<br />";
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods_num.' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods['goods_sn'].' </td>';
				$isSuppliers && $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['suppliers_name'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$strGoods.' </td>';
                $strTable .= '</tr>';
                }
                unset($orderGoods);

            }

        }
        $strTable .='</table>';
        unset($orderList);
        downloadExcel($strTable,'order');
        exit();
    }
	
	/**
     * 导出发货订单数据
     */
    public function export_delivery_order()
    {
		$condition = array();
		$order_ids = input('order_ids');
		if($order_ids){
            $condition['order_id'] = ['in', $order_ids];
        } else {
			input('consignee') ? $condition['consignee'] = trim(input('consignee')) : false;
			input('nickname') ? $condition['nickname'] = trim(input('nickname')) : false;
			input('order_sn') != '' ? $condition['order_sn'] = trim(input('order_sn')) : false;
			$shipping_status = input('shipping_status');
			$condition['shipping_status'] = empty($shipping_status) ? array('<>',1) : $shipping_status;
			$condition['order_status'] = array('in','1,2,4');
			$condition['prom_type'] = ['<>',5];
			$condition['shop_id'] = 0;
			$condition['suppliers_id'] = ['>', 0];
		}
		if($shipping_status)
			$field = "o.*,nickname,IF(shipping_name='','无需物流',shipping_name) as shipping_name";
		else
			$field = "o.*,nickname";
        $orderList = Db::name('order')
			->alias('o')
			->join('users u','u.user_id = o.user_id','left')
			->where($condition)
			->field($field)
			->order('add_time DESC')->select();
			
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">订单编号</td>';
		$strTable .= '<td style="text-align:center;font-size:12px;" width="*">下单时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员昵称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货人</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货地址</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">联系电话</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">所选物流</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">物流费用</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">支付时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单总价</td>';
        $strTable .= '</tr>';
        if($orderList->toArray()){
            $region = get_region_list();
            foreach($orderList as $k=>$val){
                $orderGoods = Db::name('order_goods')->where('order_id='.$val['order_id'])->select();
                foreach($orderGoods as $goods){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['order_sn'].'</td>';
				$strTable .= '<td style="text-align:left;font-size:12px;">'.date('Y-m-d H:i', $val['add_time']).'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['nickname'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['consignee'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'."{$region[$val['province']]},{$region[$val['city']]},{$region[$val['district']]},{$region[$val['twon']]}{$val['address']}".' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['mobile'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['shipping_name'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['shipping_price'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.date('Y-m-d H:i', $val['pay_time']).'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['total_amount'].'</td>';
                $strTable .= '</tr>';
                }
                unset($orderGoods);

            }

        }
        $strTable .='</table>';
        unset($orderList);
        downloadExcel($strTable,'order');
        exit();
    }

    /**
     * 导出退换货订单
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function export_return_order()
    {
        //搜索条件
        $return_id = input('order_ids');
        $status = input('status');
        $return_type = config('RETURN_TYPE');//退货订单信息
        $refund_status = config('REFUND_STATUS');
        $returnGoods = new ReturnGoods();
        $where =[];
        if($return_id){
            $where['rg.id'] = ['in',$return_id];
        }
        if($status !=''){
            $where['eg.status'] = $status;
        }
		$isSuppliers = input('is_suppliers', 0);
		$where['rg.suppliers_id'] = ($isSuppliers ? ['not in', [0, -1]] : ['in', [0, -1]]);
        $orderList = $returnGoods
			->alias('rg')
			->join('suppliers s', 'rg.suppliers_id=s.suppliers_id', 'left')
			->where($where)->select();
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">订单编号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员昵称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">申请日期</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="200">退款详情</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">服务类型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">售后申请原因</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">售后申请描述</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">审核状态</td>';
		$isSuppliers && $strTable .= '<td style="text-align:center;font-size:12px;" width="*">供应商</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">处理备注</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品数量</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品编码</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品名称</td>';
        $strTable .= '</tr>';
        if($orderList->toArray()){
            foreach($orderList as $k=>$val){
                $orderGoods = Db::name('order_goods')->where(['rec_id'=>$val['rec_id']])->select();
                foreach($orderGoods as $goods){
                    $strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['order']['order_sn'].'</td>';
                    $user_name = Db::name('users')->where(['user_id'=>$val['order']['user_id']])->value('nickname');
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$user_name.' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.date('Y-m-d H:i:s',$val['addtime']).' </td>';
                    $str = '';
                   if($val['refund_money'] > 0){
                       $str .= '需退还金额：'.$val['refund_money'];
                   }
                    if($val['refund_deposit'] > 1){
                        $str .= '需退还余额：'.$val['refund_deposit'];
                    }
                    if($val['refund_integral'] > 2){
                        $str .= '需退还积分：'.$val['refund_integral'];
                    }
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$str.'</td>';

                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$return_type[$val['type']].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['reason'].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['describe'].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$refund_status[$val['status']].'</td>';
					$isSuppliers && $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['suppliers_name'].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['remark'].'</td>';
                    $strGoods="";
                    $strGoods .= " 商品名称：".$goods['goods_name'];
                    if ($goods['spec_key_name'] != '') $strGoods .= " 规格：".$goods['spec_key_name'];
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['goods_num'].' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods['goods_sn'].' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$strGoods.' </td>';
                    $strTable .= '</tr>';
                }
                unset($orderGoods);

            }

        }
        $strTable .='</table>';
        unset($orderList);
        downloadExcel($strTable,'order');
        exit();
    }

    /**
     * 退货单列表
     */
    public function return_list(){
        return View::fetch();
    }

    /*
     * ajax 退货订单列表
     */
    public function ajax_return_list(){
        // 搜索条件
        $order_sn =  trim(input('order_sn'));
        $order_by = input('order_by','') ? input('order_by') : 'id';
        $sort_order = input('sort_order') ? input('sort_order') : 'desc';
        $status =  input('status');
        $where = [];
        if($order_sn){
            $where['order_sn'] =['like', '%'.$order_sn.'%'];
        }
        if($status != ''){
            $where['status'] = $status;
        }
		$where['suppliers_id'] = 0;
        $ReturnGoods = new ReturnGoods();
        $count = $ReturnGoods->where($where)->count();
        $Page  = new AjaxPage($count,13);
        $show = $Page->show();
        $list = $ReturnGoods->where($where)->order("$order_by $sort_order")->limit($Page->firstRow,$Page->listRows)->select();
        $state = config('REFUND_STATUS');
        $return_type = config('RETURN_TYPE');
        View::assign('state',$state);
        View::assign('return_type',$return_type);
        View::assign('list',$list);
        View::assign('pager',$Page);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }

    /**
     * 添加订单
     */
    public function add_order()
    {
        //  获取省份
        $province = Db::name('region')->where(array('parent_id'=>0,'level'=>1))->select();
        View::assign('province',$province);
        return View::fetch();
    }

    /**
     * 提交添加订单
     */
    public function addOrder(){
            $user_id = input('user_id');// 用户id 可以为空
            $admin_note = input('admin_note'); // 管理员备注
            //收货信息
            $user  = Db::name('users')->where(['user_id'=>$user_id])->find();
            $address['consignee'] = input('consignee');// 收货人
            $address['province'] = input('province'); // 省份
            $address['city'] = input('city'); // 城市
            $address['district'] = input('district'); // 县
            $address['address'] = input('address'); // 收货地址
            $address['mobile'] = input('mobile'); // 手机
            $address['zipcode'] = input('zipcode'); // 邮编
            $address['email'] = $user['email']; // 邮编
            $invoice_title = input('invoice_title');// 发票抬头
            $taxpayer = input('taxpayer');// 纳税人识别号
            if(!empty($taxpayer)){
                $invoice_desc = "商品明细";// 发票内容
            }
            $goods_id_arr = input("goods_id/a");
            $orderLogic = new OrderLogic();
            $order_goods = $orderLogic->get_spec_goods($goods_id_arr);
            $pay = new Pay();
            try{
                $pay->setUserId($user_id);
                $pay->payGoodsList($order_goods);
                $pay->delivery($address['district']);
                $pay->orderPromotion();
            } catch (TpshopException $t) {
                $error = $t->getErrorArr();
                $this->error($error['msg']);
            }
            $placeOrder = new PlaceOrder($pay);
            $placeOrder->setUserAddress($address);
            $placeOrder->setInvoiceTitle($invoice_title);
            $placeOrder->setTaxpayer($taxpayer);
            $placeOrder->setInvoiceDesc($invoice_desc);
            $placeOrder->addNormalOrder();
            $order = $placeOrder->getOrder();
            if($order) {
                Db::name('order_action')->insert([
                    'order_id'      => $order['order_id'],
                    'action_user'   => session('admin_id'),
                    'order_status'  => 0,  //待支付
                    'shipping_status' => 0, //待确认
                    'action_note'   => $admin_note,
                    'status_desc'   => '提交订单',
                    'log_time'      => time()
                ]);
                $this->success('添加订单成功',url("Admin/Order/detail",array('order_id'=>$order['order_id'])));
            } else{
                $this->error('添加失败');
            }
    }


    /**
     * 订单编辑
     * @return mixed
     */
    public function edit_order(){
        $order_id = input('order_id');
        $orderLogic = new OrderLogic();
        $orderModel = new OrderModel();
        $orderObj = $orderModel->where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
        $orderGoods = $order['orderGoods'];
        if(IS_POST)
        {
            $order['consignee'] = input('consignee');// 收货人
            $order['province'] = input('province'); // 省份
            $order['city'] = input('city'); // 城市
            $order['district'] = input('district'); // 县
            $order['address'] = input('address'); // 收货地址
            $order['mobile'] = input('mobile'); // 手机
            $order['invoice_title'] = input('invoice_title');// 发票
            $order['taxpayer'] = input('taxpayer');// 纳税人识别号
            $order['admin_note'] = input('admin_note'); // 管理员备注
            $order['admin_note'] = input('admin_note'); //
            $order['shipping_code'] = input('shipping');// 物流方式
            $order['shipping_name'] = Db::name('shipping')->where('shipping_code',input('shipping'))->value('shipping_name');
            $order['pay_code'] = input('payment');// 支付方式
            $order['pay_name'] = Db::name('plugin')->where(array('status'=>1,'type'=>'payment','code'=>input('payment')))->value('name');
            $goods_id_arr = input("goods_id/a");
            $new_goods = $old_goods_arr = array();
            //################################订单添加商品
            if($goods_id_arr){
                $new_goods = $orderLogic->get_spec_goods($goods_id_arr);
                foreach($new_goods as $key => $val)
                {
                    $val['order_id'] = $order_id;
                    $val['final_price'] = $val['goods_price'];
                    $rec_id = Db::name('order_goods')->insertGetId($val);//订单添加商品
                    if(!$rec_id)
                        $this->error('添加失败');
                }
            }

            //################################订单修改删除商品
            $old_goods = input('old_goods/a');
            foreach ($orderGoods as $val){
                if(empty($old_goods[$val['rec_id']])){
                    Db::name('order_goods')->where("rec_id=".$val['rec_id'])->delete();//删除商品
                }else{
                    //修改商品数量
                    if($old_goods[$val['rec_id']] != $val['goods_num']){
                        $val['goods_num'] = $old_goods[$val['rec_id']];
                        Db::name('order_goods')->where("rec_id=".$val['rec_id'])->save(array('goods_num'=>$val['goods_num']));
                    }
                    $old_goods_arr[] = $val;
                }
            }

            $goodsArr = array_merge($old_goods_arr,$new_goods);
            $pay = new Pay();
            try{
                $pay->setUserId($order['user_id']);
                $pay->payOrder($goodsArr);
                $pay->delivery($order['district']);
                $pay->orderPromotion();
            } catch (TpshopException $t) {
                $error = $t->getErrorArr();
                $this->error($error['msg']);
            }
            //################################修改订单费用
            $order['goods_price']    = $pay->getGoodsPrice(); // 商品总价
            $order['shipping_price'] = $pay->getShippingPrice();//物流费
            $order['order_amount']   = $pay->getOrderAmount(); // 应付金额
            $order['total_amount']   = $pay->getTotalAmount(); // 订单总价
            $o = Db::name('order')->where('order_id='.$order_id)->save($order);
            $commonOrder = new \app\common\logic\Order();
            $commonOrder->setOrderById($order_id);
            $l = $commonOrder->orderActionLog('修改订单','修改订单',$this->admin_id);//操作日志
            if($o && $l){
                $this->success('修改成功',url('Admin/Order/editprice',array('order_id'=>$order_id)));
            }else{
                $this->success('修改失败',url('Admin/Order/detail',array('order_id'=>$order_id)));
            }
            exit;
        }
        // 获取省份
        $province = Db::name('region')->where(array('parent_id'=>0,'level'=>1))->select();
        //获取订单城市
        $city =  Db::name('region')->where(array('parent_id'=>$order['province'],'level'=>2))->select();
        //获取订单地区
        $area =  Db::name('region')->where(array('parent_id'=>$order['city'],'level'=>3))->select();
        //获取支付方式
        $payment_where = ['status'=>1,'type'=>'payment'];
        if($order['shop_id'] > 0){
            //预售订单和抢购不支持货到付款
            $payment_where['code'] = array('<>','cod');
        }
        $payment_list = Db::name('plugin')->where($payment_where)->select();
        //获取配送方式
        $shipping_list = Db::name('shipping')->field('shipping_name,shipping_code')->where('')->select();

        View::assign('order',$order);
        View::assign('province',$province);
        View::assign('city',$city);
        View::assign('area',$area);
        View::assign('orderGoods',$orderGoods);
        View::assign('shipping_list',$shipping_list);
        View::assign('payment_list',$payment_list);
        return View::fetch();
    }

    /**
     * 选择搜索商品
     */
    public function search_goods()
    {
        $brandList =  Db::name("brand")->select();
        $categoryList =  Db::name("goods_category")->select();
        View::assign('categoryList',$categoryList);
        View::assign('brandList',$brandList);
        $where = 'exchange_integral = 0 and is_on_sale = 1 and is_virtual =' . input('is_virtual/d',0);//搜索条件
        input('intro')  && $where = "$where and ".input('intro')." = 1";
        if(input('cat_id')){
            View::assign('cat_id',input('cat_id'));            
            $grandson_ids = getCatGrandson(input('cat_id')); 
            $where = " $where  and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
                
        }
        if(input('brand_id')){
            View::assign('brand_id',input('brand_id'));
            $where = "$where and brand_id = ".input('brand_id');
        }
        if(!empty($_REQUEST['keywords']))
        {
            View::assign('keywords',input('keywords'));
            $where = "$where and (goods_name like '%".input('keywords')."%' or keywords like '%".input('keywords')."%')" ;
        }
        $goods_count =Db::name('goods')->where($where)->count();
        $Page = new Page($goods_count,config('PAGESIZE'));
        
        //$goodsList = Db::name('goods')->where($where)->order('goods_id DESC')->limit($Page->firstRow,$Page->listRows)->select();
        $goodsList = \app\common\model\Goods::where($where)->order('goods_id DESC')->limit($Page->firstRow,$Page->listRows)->select();
                
        foreach($goodsList as $key => $val)
        {
            $spec_goods = Db::name('spec_goods_price')->where("goods_id = {$val['goods_id']}")->select();
            $goodsList[$key]['spec_goods'] = $spec_goods;            
        }
        if($goodsList){
            //计算商品数量
            foreach ($goodsList as $value){
                if($value['spec_goods']){
                    $count += count($value['spec_goods']);
                }else{
                    $count++;
                }
            }
            View::assign('totalSize',$count);
        }

        View::assign('page',$Page->show());
        View::assign('goodsList',$goodsList);
        return View::fetch();
    }
    
    public function ajaxOrderNotice(){
        $order_amount = Db::name('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();
        echo $order_amount;
    }

    /**
     * 删除订单日志
     */
    public function delOrderLogo(){
        $ids = input('ids');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'url'  =>'']);
        $order_ids = rtrim($ids,",");
        $res = Db::name('order_action')->whereIn('order_id',$order_ids)->delete();
        if($res !== false){
            $this->ajaxReturn(['status' => 1,'msg' =>"删除成功！",'url'  =>'']);
        }else{
            $this->ajaxReturn(['status' => -1,'msg' =>"删除失败",'url'  =>'']);
        }
    }

    /**
     * 导出发货单中包含的发货商品
     */
    public function exportDeliveryGoods()
    {
        $order_ids = input('ids4');
        if(empty($order_ids)){
            $this->error('没有选中订单', url('admin/order/delivery_list'));
        }
        $where['order_id'] = ['in', $order_ids];
        $orderList = Db::name('order')->field('order_sn,order_id,total_amount')->where($where)->order('order_id')->select();
        if(!$orderList){
            $this->error('没找到相关订单信息', url('admin/order/delivery_list'));
        }
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:125px;">订单编号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单总价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品信息</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">对应商品规格</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">对应商品数量</td>';
        $strTable .= '</tr>';
            foreach($orderList as $k=>$val){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['order_sn'].'</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['total_amount'].'</td>';
                $orderGoods = Db::name('order_goods')->where('order_id='.$val['order_id'])->select();
                $strGoods="";
                $goods_num = '';
                $spec_key_name = '';
                foreach($orderGoods as $goods){
                    $goods_num .= '&nbsp;'.$goods['goods_num'];
                    $goods_num  .= "<br />";
                    $strGoods .= "&nbsp;商品编号：".$goods['goods_sn']." 商品名称：".$goods['goods_name'];
                    $strGoods .= "<br />";
                    $spec_key_name .= "&nbsp;".($goods['spec_key_name'] ?: '无' );
                    $spec_key_name .= "<br />";
                }
                unset($orderGoods);
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$strGoods.' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$spec_key_name.' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods_num.' </td>';
                $strTable .= '</tr>';
            }

        $strTable .='</table>';
        unset($orderList);
        downloadExcel($strTable,'goods_list');
        exit();
    }
	
	/**
     * 获取所有供应商通过审核的商品订单id
     */
	public function getSupplierOrderIds(){
	    
		$orderIds = Db::name('order_goods')
			->alias('og')
			->join('goods g', 'g.goods_id = og.goods_id', 'left')
			->where(['g.suppliers_id' => $this->supplier['suppliers_id'], 'g.audit' => 0])
			->column('og.order_id','og.order_id');
		if ($orderIds) {
			$orderIds = implode(',', $orderIds);
		}
		
		return $orderIds;
	}
	
	/**
     *供应商订单列表
     */
    public function supplierOrderList(){
        return View::fetch();
    }

    /**
     * 供应商订单列表（ajax）
     */
    public function ajaxSupplierOrderList(){
        
        $begin = $this->begin;
        $end = $this->end;
        // 搜索条件
        $condition = array('o.shop_id'=>0);
		$condition['o.suppliers_id'] = ['<>', 0];
		$condition['o.deleted'] = 0;
		input('order_statis_id', '') && $condition['o.order_statis_id'] = input('order_statis_id');
        $keyType = input("key_type");
        $keywords = input('keywords','','trim');
		if ($keywords) {
			switch ($keyType) {
				case 'suppliers_name': $condition['s.'.$keyType] = ['like','%'.$keywords.'%'];break;
				case 'nickname': $condition['u.'.$keyType] = ['like','%'.$keywords.'%'];break;
				default: $condition['o.'.$keyType] = ['like','%'.$keywords.'%'];
			}
		}

        if($begin && $end && !$condition['o.order_statis_id']){ //有order_statis_id时表示是在查询订单统计下的订单，不需要时间
            $condition['o.add_time'] = array('between',"$begin,$end");
        }
        $condition['o.prom_type'] = array('in',[0,1,2,3,4,8]);
        
        input('order_status') != '' ? $condition['o.order_status'] = input('order_status') : false;
        input('pay_status') != '' ? $condition['o.pay_status'] = input('pay_status') : false;
        if(input('pay_code')){
            switch (input('pay_code')){
                case '余额支付':
                    $condition['o.pay_name'] = input('pay_code');
                    break;
                case '积分兑换':
                    $condition['o.pay_name'] = input('pay_code');
                    break;
                case 'alipay':
                    $condition['o.pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $condition['o.pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $condition['o.pay_name'] = '';
                    $condition['o.pay_code'] = '';
                    break;
                default:
                    $condition['o.pay_code'] = input('pay_code');
                    break;
            }
        }

        input('shipping_status') != '' ? $condition['o.shipping_status'] = input('shipping_status') : false;
        input('user_id') ? $condition['o.user_id'] = trim(input('user_id')) : false;
        $sort_order = input('order_by','DESC').' '.input('sort');
        $Order = new \app\common\model\Order();

        $count = $Order->alias('o')
			->field('order_id')
			->join('users u','u.user_id = o.user_id','left')
			->join('suppliers s','s.suppliers_id = o.suppliers_id','left')
			->where($condition)
			->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $orderList = $Order->alias('o')
			->field('o.*,nickname,s.suppliers_name')
			->join('users u','u.user_id = o.user_id','left')
			->join('suppliers s','s.suppliers_id = o.suppliers_id','left')
			->where($condition)
			->limit($Page->firstRow,$Page->listRows)->order($sort_order)->select();
        View::assign('orderList',$orderList);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$Page);
        
        return View::fetch();
    }
	
	/**
	 * 供应商退款单列表
	 */
	public function supplierRefundList(){
	    
		$condition['o.suppliers_id'] = ['not in', [0, -1]];
        input('consignee') ? $condition['o.consignee'] = ['like', '%'.trim(input('consignee')).'%'] : false;
        input('order_sn') != '' ? $condition['o.order_sn'] = ['like', '%'.trim(input('order_sn')).'%'] : false;
        input('mobile') != '' ? $condition['o.mobile'] = ['like', '%'.trim(input('mobile')).'%'] : false;
		input('suppliers_name') != '' ? $condition['s.suppliers_name'] = ['like', '%'.trim(input('suppliers_name')).'%'] : false;
        $prom_type = input('prom_type');
        if($prom_type){
            $condition['o.prom_type'] = $prom_type;
        }
        $condition['o.shipping_status'] = 0;
        $condition['o.order_status'] = 3;
        $condition['o.pay_status'] = array('>',0);
        $count = Db::name('order')
			->alias('o')
			->join('suppliers s', 's.suppliers_id=o.suppliers_id', 'left')
			->where($condition)
			->count();
        $Page  = new Page($count,10);
        $show = $Page->show();
        $orderList = Db::name('order')
			->alias('o')
			->join('suppliers s', 's.suppliers_id=o.suppliers_id', 'left')
			->field('o.*, s.suppliers_name')
			->where($condition)
			->limit($Page->firstRow,$Page->listRows)->order('o.add_time DESC')->select();
        View::assign('orderList',$orderList);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$Page);
        
        return View::fetch();
    }
	
	/**
     * 退货单列表
     */
    public function supplierReturnList(){
        return View::fetch();
    }

    /*
     * ajax 退货订单列表
     */
    public function ajaxSupplierReturnList(){
        
        // 搜索条件
        $order_sn =  trim(input('order_sn'));
		$suppliersName =  trim(input('suppliers_name'), '');
        $order_by = input('order_by','') ? input('order_by') : 'id';
        $sort_order = input('sort_order') ? input('sort_order') : 'desc';
        $status =  input('status');
        $where = [];
        if($order_sn){
            $where['rg.order_sn'] = ['like', '%'.$order_sn.'%'];
        }
        if($status != ''){
            $where['rg.status'] = $status;
        }
		$suppliersName && $where['s.suppliers_name'] = ['like', '%'.$suppliersName.'%'];
		$where['rg.suppliers_id'] = ['not in', [0,-1]];
        $ReturnGoods = new ReturnGoods();
        $count = $ReturnGoods
			->alias('rg')
			->join('suppliers s', 's.suppliers_id=rg.suppliers_id', 'left')
			->where($where)
			->count();
        $Page  = new AjaxPage($count,13);
        $show = $Page->show();
        $list = $ReturnGoods
			->alias('rg')
			->join('suppliers s', 's.suppliers_id=rg.suppliers_id', 'left')
			->where($where)
			->order("rg.$order_by $sort_order")->limit($Page->firstRow,$Page->listRows)->select();
        $state = config('REFUND_STATUS');
        $return_type = config('RETURN_TYPE');
        View::assign('state',$state);
        View::assign('return_type',$return_type);
        View::assign('list',$list);
        View::assign('pager',$Page);
        View::assign('page',$show);// 赋值分页输出
        
        return View::fetch();
    }

}
