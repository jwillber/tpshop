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
namespace app\supplier\controller;
use think\facade\View;
use app\supplier\logic\RefundLogic;
use app\supplier\logic\KdniaoLogic;
use app\common\logic\PlaceOrder;
use app\common\model\Order as OrderModel;
use app\common\logic\Pay;
use app\common\model\OrderGoods;
use app\common\logic\OrderLogic;
use app\common\logic\MessageFactory;
use app\common\model\ReturnGoods;
use app\common\util\TpshopException;
use think\AjaxPage;
use think\Page;
use think\facade\Db;

class Order extends Base {
    public  $order_status;
    public  $pay_status;
    public  $shipping_status;
    /**
     * 初始化操作
     */
    public function __construct() {
        parent::__construct();
        config('TOKEN_ON',false); // 关闭表单令牌验证
        $this->order_status = config('ORDER_STATUS');
        $this->pay_status = config('PAY_STATUS');
        $this->shipping_status = config('SHIPPING_STATUS');
        // 订单 支付 发货状态
        View::assign('order_status',$this->order_status);
        View::assign('pay_status',$this->pay_status);
        View::assign('shipping_status',$this->shipping_status);
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
        //$condition = array('shop_id'=>0);
        $keyType = input("key_type");
        $keywords = input('keywords','','trim');
        $consignee =  ($keyType && $keyType == 'consignee') ? $keywords : input('consignee','','trim');
        $consignee ? $condition['consignee'] = ['like','%'.trim($consignee).'%'] : false;
        $nickname =  ($keyType && $keyType == 'nickname') ? $keywords : input('nickname','','trim');
        $nickname ? $condition['nickname'] = ['like','%'.trim($nickname).'%'] : false;

        if($begin && $end){
            $condition['add_time'] = array('between',"$begin,$end");
        }
//        $condition['prom_type'] = array('<',5);
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

        input('shipping_status') != '' ? $condition['shipping_status'] = input('shipping_status') : false;
        input('user_id') ? $condition['user_id'] = trim(input('user_id')) : false;
        $sort_order = input('order_by','DESC').' '.input('sort');
        $Order = new \app\common\model\Order();
		//获取该供应商下通过审核的商品id
		$condition['suppliers_id'] = $this->supplier['suppliers_id'];
        $count = $Order->alias('o')
			->field('order_id')
			->join('users u','u.user_id = o.user_id','left')
			->where($condition)
			->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $orderList = $Order->alias('o')
			->field('o.*,nickname')
			->join('users u','u.user_id = o.user_id','left')
			->where($condition)
			->limit($Page->firstRow,$Page->listRows)->order($sort_order)->select();
        View::assign('orderList',$orderList);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$Page);
        return View::fetch();
    }
	
	/**
     * 导出订单
     */
    public function export_order()
    {
        //搜索条件
        $order_status = input('order_status','');
        $order_ids = input('order_ids');
        $prom_type = input('prom_type'); //订单类型
        $keyType =   input("key_type");  //查找类型
        $keywords = input('keywords','','trim');
        $where= ['add_time'=>['between',"$this->begin,$this->end"]];
        if(!empty($keywords)){
            $keyType == 'mobile'   ? $where['mobile']  = $keywords : false;
            $keyType == 'order_sn' ? $where['order_sn'] = $keywords: false;
            $keyType == 'consignee' ? $where['consignee'] = $keywords: false;
        }
        $prom_type != '' ? $where['prom_type'] = $prom_type : $where['prom_type'] = ['in',[0,1,2,3,4,7,8]];
        if($order_status>-1 && $order_status != ''){
            $where['order_status'] = $order_status;
        }
        if($order_ids){
            $where['order_id'] = ['in', $order_ids];
        }
        if(input('pay_code')){
            switch (input('pay_code')){
                case '余额支付':
                    $where['pay_name'] = input('pay_code');
                    break;
                case '积分兑换':
                    $where['pay_name'] = input('pay_code');
                    break;
                case 'alipay':
                    $where['pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $where['pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $where['pay_name'] = '';
                    $where['pay_code'] = '';
                    break;
                default:
                    $where['pay_code'] = input('pay_code');
                    break;
            }
        }
        input('pay_status') != '' ? $where['pay_status'] = input('pay_status') : false;
        if($where['order_status'] == 3){
            $where['pay_status'] = ['>',0];
            $where['shipping_status'] = 0;
            unset($where['add_time']);
            unset($where['prom_type']);
        }
		$where['suppliers_id'] = $this->supplier['suppliers_id'];
        $orderList = Db::name('order')->field("*,FROM_UNIXTIME(add_time,'%Y-%m-%d') as create_time")->where($where)->order('order_id')->select();

        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">订单编号</td>';
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
                $strTable .= '<td style="text-align:left;font-size:12px;">'.($val['pay_name'] ? $val['pay_name'] : '其他方式').'</td>';
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
			$condition['suppliers_id'] = $this->supplier['suppliers_id'];
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
     * 导出发货单中包含的发货商品
     */
    public function exportDeliveryGoods()
    {
        $order_ids = input('ids4');
        if(empty($order_ids)){
            $this->error('没有选中订单', url('Supplier/order/deliveryList'));
        }
        $where['order_id'] = ['in', $order_ids];
        $orderList = Db::name('order')->field('order_sn,order_id,total_amount')->where($where)->order('order_id')->select();
        if(!$orderList){
            $this->error('没找到相关订单信息', url('Supplier/order/deliveryList'));
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
        $admins = Db::name("admin")->column("admin_id , user_name");
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
				$action_log["$k"]['action_user_name'] = '供应商:'.$this->supplier['suppliers_name'];
			}
            $action_log["$k"]["log_time"] = date('Y-m-d H:i:s',$v['log_time']);
            $action_log["$k"]["order_status"] = $this->order_status[$v['order_status']];
            $action_log["$k"]["pay_status"] = $this->pay_status[$v['pay_status']];
            $action_log["$k"]["shipping_status"] = $this->shipping_status[$v['shipping_status']];
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'参数错误！！','data'=>$action_log]);
    }
	
	/**
     * 订单操作
     * @param $id
     */
    public function order_action(){   
        $orderLogic = new OrderLogic();
        $action = input('get.type');
        $order_id = input('get.order_id');
        if($action && $order_id && in_array($action, ['confirm', 'cancel'])){ //供应商后台只执行confirm、cancel两种操作
            $a = $orderLogic->orderProcessHandle($order_id,$action);
            if($action !=='pay'){
                $convert_action= config('CONVERT_ACTION')["$action"];
                $commonOrder = new \app\common\logic\Order();
                $commonOrder->setOrderById($order_id);
                $res = $commonOrder->orderActionLog(input('note'),$convert_action,$this->supplier['suppliers_id'], 1);
            }
             if($res !== false && $a !== false){
                 $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url' => url('Order/detail',array('order_id'=>$order_id))]);
             }else{
                $this->ajaxReturn(['status' => 0,'msg' => '操作失败','url' => url('Order/index')]);
             }
        }else{
            $this->ajaxReturn(['status' => 0,'msg' => '参数错误','url' => url('Order/index')]);
        }
    }
	
	/**
     * ajax 获取自提订单信息
     * order_id
     */
    public function getOrderGoodsInfo()
    {
        $order_id = input("order_id/d",0);
        $Order = new OrderModel();
        $order = $Order->with(['shop','shop_order'])->where(['order_id'=>$order_id])->find();
        $order_info = $order->append(['delivery_method','shipping_status_desc'])->toArray();
        $this->ajaxReturn($order_info);
    }
	
	/**
     * 核销
     */
    public function writeOff()
    {
        $shop_order_id = input('shop_order_id/d', '');
        $bar_code = input('bar_code/d', '');
        $status = input('status/d','');

        $ShopOrderLogic = new \app\common\logic\ShopOrder();
        if($shop_order_id) $ShopOrderLogic->setShopOrderById($shop_order_id);
        if($bar_code) $ShopOrderLogic->setShopOrderByBarCode($bar_code);

        try {
            if($status == 'write_off'){
                $ShopOrderLogic->writeOff();
                $this->ajaxReturn(['status' => 1, 'msg' => '核销成功']);
            }else{
                $this->ajaxReturn(['status' => 2, 'msg' => '获取成功','result' => $ShopOrderLogic->getShopOrderModel()->append(['order'])->toArray() ]);
            }
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
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
     *批量打印快递单
     */
    public function shippingPrintBatch(){
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
     * 快递单打印
     */
    public function shipping_print($id=''){
        $orderLogic = new OrderLogic();
        $data['order_id'] = input('order_id/d');
        $data['shipping'] = 2;
        $data['send_type'] = 2;
		$data['group'] = 1;
		$data['admin_id'] = $this->supplier['suppliers_id'];
        $res = $orderLogic->deliveryHandle($data);
        if($res['status'] == 1){
            if($data['send_type'] == 2 && !empty($res['printhtml'])){
                View::assign('printhtml',$res['printhtml']);
                return View::fetch('print_online');
            }
        }else{
            $this->error($res['msg'],url('Supplier/Order/delivery_info',array('order_id'=>$data['order_id'])));
        }

    }
	
	/**
     * 生成发货单
     */
    public function deliveryHandle(){
        $orderLogic = new OrderLogic();
        $data = input('post.');
		$data['group'] = 1;
		$data['admin_id'] = $this->supplier['suppliers_id'];
        $res = $orderLogic->deliveryHandle($data);
        if($res['status'] == 1){
            if($data['send_type'] == 2 && !empty($res['printhtml'])){
                View::assign('printhtml',$res['printhtml']);
                return View::fetch('print_online');
            }
            $this->success('操作成功',url('Supplier/Order/delivery_info',array('order_id'=>$data['order_id'])));
        }else{
            $this->error($res['msg'],url('Supplier/Order/delivery_info',array('order_id'=>$data['order_id'])));
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
	
	/**
     * 发货单列表
     */
    public function deliveryList(){
        return View::fetch();
    }
	
	/**
     * ajax 发货订单列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ajaxDelivery(){
        $condition = array();
        input('consignee') ? $condition['consignee'] = ['like', '%'.trim(input('consignee')).'%'] : false;
        input('nickname') ? $condition['nickname'] = ['like', '%'.trim(input('nickname')).'%'] : false;
        input('order_sn') != '' ? $condition['order_sn'] = ['like', '%'.trim(input('order_sn')).'%'] : false;
        $shipping_status = input('shipping_status');
        $condition['shipping_status'] = empty($shipping_status) ? array('<>',1) : $shipping_status;
        $condition['order_status'] = array('in','1,2,4');
        $condition['prom_type'] = ['<>',5];
        $condition['shop_id'] = 0;
		$condition['suppliers_id'] = $this->supplier['suppliers_id'];
        $count = Db::name('order')->alias('o')->join('users u','u.user_id = o.user_id','left')->where($condition)->count();
        $Page  = new AjaxPage($count,10);
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
	
	/**
    *批量发货
    */
    public function delivery_batch(){
        /*code_18订单批量发货*/
        $ids=input('post.ids');
        $ids=substr($ids,0,-1);
        $ids=explode(',', $ids);
        if(!is_numeric($ids[0])){
            unset($ids[0]);
        }

        $orders=array();
        foreach ($ids as $k => $v) {
           $orders[$k]=$this->delivery_info($v);
        }
       
        $shipping_list = Db::name('shipping')->field('shipping_name,shipping_code')->where('')->select();
        View::assign('orders',$orders);
        View::assign('num',count($ids));
        View::assign('shipping_list',$shipping_list);
        return View::fetch();
        /*code_18订单批量发货*/
    }
	
	/**
     * 退货单列表
     */
    public function returnList(){
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
		$where['suppliers_id'] = $this->supplier['suppliers_id'];
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
            $where['id'] = ['in',$return_id];
        }
        if($status !=''){
            $where['status'] = $status;
        }
		$where['suppliers_id'] = $this->supplier['suppliers_id'];
        $orderList = $returnGoods->where($where)->select();
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
     * 退换货操作
     */
    public function return_info()
    {
        $return_id = input('id');
        $return_goods = Db::name('return_goods')->where(['id'=> $return_id])->find();
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
     *确认收货和确认发货
     */
    public function checkReturnInfo()
    {
        $orderLogic = new OrderLogic();
        $post_data = input('post.');
        $return_goods = Db::name('return_goods')->where(['id'=>$post_data['id']])->find();
        !$return_goods && $this->ajaxReturn(['status'=>-1,'msg'=>'非法操作!']);
        $type_msg = config('RETURN_TYPE');
        $status_msg = config('REFUND_STATUS');
        $post_data['status'] == 3 && $post_data['receivetime'] = time(); //卖家收货时间
        if($return_goods['type'] > 0  && $post_data['status'] == 4){
            $post_data['seller_delivery']['express_time'] = date('Y-m-d H:i:s');
            $post_data['seller_delivery'] = serialize($post_data['seller_delivery']); //换货的物流信息
            Db::name('order_goods')->where(['rec_id'=>$return_goods['rec_id']])->save(['is_send'=>2]);
        }
        $note ="退换货:{$type_msg[$return_goods['type']]}, 状态:{$status_msg[$post_data['status']]},处理备注：{$post_data['remark']}";
        Db::name('return_goods')->where(['id'=>$post_data['id']])->save($post_data);
        $commonOrder = new \app\common\logic\Order();
        $commonOrder->setOrderById($return_goods['order_id']);
        $commonOrder->orderActionLog($note,'退换货',$this->supplier['suppliers_id'],1);
        $this->ajaxReturn(['status'=>1,'msg'=>'修改成功','url'=>'']);
    }
}
