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

namespace app\mobile\controller;
use think\facade\View;

use think\facade\Db;
use app\common\util\TpshopException;

class Payment extends MobileBase
{
    public $payment; //  具体的支付类
    public $pay_code; //  具体的支付code

    /**
     * 析构流函数
     */
    public function __construct()
    {
        parent::__construct();
        if(ACTION_NAME != 'pay_success') {
            // 获取支付类型
            $pay_radio = input('pay_radio');
            if (!empty($pay_radio)) {
                $pay_radio = parse_url_param($pay_radio);
                $this->pay_code = $pay_radio['pay_code']; // 支付 code
            } else {
                $this->pay_code = input('get.pay_code');
                unset($_GET['pay_code']); // 用完之后删除, 以免进入签名判断里面去 导致错误
            }
            if (is_ios() && empty($this->pay_code)) {
                $this->pay_code = session('pay_pay_code');
            }

            // 获取通知的数据
            if (empty($this->pay_code)) {

                exit('pay_code 不能为空');
            }
            if (is_ios()) {
                $order_id = input('order_id/d');
                session('pay_order_id', $order_id);
                session('pay_pay_code', $this->pay_code);
            }
            //不是余额支付则导入插件
            if($this->pay_code != 'balance'){
                // 导入具体的支付类文件
                include_once "../plugins/payment/{$this->pay_code}/{$this->pay_code}.class.php"; // D:\wamp\www\svn_tpshop\www\plugins\payment\alipay\alipayPayment.class.php
                $code = '\\' . $this->pay_code; // \alipay
                $this->payment = new $code();
            }           
        }
    }

    /**
     * tpshop 提交支付方式
     */
    public function getCode()
    {

        if (!session('user')) {
            $this->error('请先登录', url('User/login'));
        }             
        
        // 修改订单的支付方式 苹果支付完成，再次打开本地址，不会带上order_id
        $order_id = input('order_id/d'); // 订单id
        if(is_ios()  && empty($order_id)){
            $order_id = session('pay_order_id');
        }
        $order = Db::name('order')->where("order_id", $order_id)->find();
        if ($order['pay_status'] == 1) {
            View::assign('order', $order);
            return View::fetch('success');
        }

        $payment_arr = Db::name('Plugin')->where('type', 'payment')->column('name','code');
        Db::name('order')->where("order_id", $order_id)->save(['pay_code' => $this->pay_code, 'pay_name' => $payment_arr[$this->pay_code]]);

        // 订单支付提交
        $config = parse_url_param($this->pay_code); // 类似于 pay_code=alipay&bank_code=CCB-DEBIT 参数
        $config['body'] = getPayBody($order_id);

        if ($this->pay_code == 'weixin' && $_SESSION['openid'] && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            //微信JS支付
            $code_str = $this->payment->getJSAPI($order);
            exit($code_str);
        } elseif ($this->pay_code == 'weixinH5') {
            //微信H5支付,直接返回
            $return = $this->payment->get_code($order, $config);
            ajaxReturn($return);
        } else {
            //其他支付（支付宝、银联...）
            $code_str = $this->payment->get_code($order, $config);
        }

        View::assign('code_str', $code_str);
        View::assign('order_id', $order_id);
        return View::fetch('payment');  // 分跳转 和不 跳转
    }

    public function getPay()
    {
        //手机端在线充值
        //config('TOKEN_ON',false); // 关闭 TOKEN_ON 
        header("Content-type:text/html;charset=utf-8");
        $order_id = input('order_id/d'); //订单id
        $card_id = input('card_id/d');
        $shopping_card_discount_id = input('shopping_card_discount_id',0);
        if(is_ios()  && empty($order_id)){
            $order_id = session('pay_order_id');
        }
        $user = session('user');
        $data['account'] = input('account');
        if ($order_id > 0) {
            Db::name('recharge')->where(array('order_id' => $order_id, 'user_id' => $user['user_id']))->save($data);
        }else if($card_id >0){
            $card = Db::name('shopping_card_list')
                ->alias('l')
                ->join('shopping_card c','c.id=l.cid')
                ->where(['l.id'=>$card_id,'c.sort'=>1])
                ->find();

            if($card){
                if($shopping_card_discount_id>0){
                    $discount=Db::name('shopping_card_discount')
                        ->where(['id'=>$shopping_card_discount_id,'cid'=>$card['id']])->find();
                    if($discount){
                        if($card['give']==1){//充值打折
                            $data['account']=($discount['targer_money']*$discount['give_num'])/100;
                            $data['shopping_card_discount_id']=$shopping_card_discount_id;
                        }else if($card['give']==0){//充值赠送
                            $data['account']=$discount['targer_money'];
                            $data['shopping_card_discount_id']=$shopping_card_discount_id;
                        }
                    }else{
                        $this->error('充值失败');
                    }
                }
                $body = '充值到余额卡';

                $data['card_list_id'] = $card_id;
                $data['user_id'] = $user['user_id'];
                $data['nickname'] = $user['nickname'];
                $data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
                $data['ctime'] = time();
                $order_id = Db::name('recharge')->insertGetId($data);
            }else{
                $this->error('这张购物卡不能充值或者不存在');
            }
        }
        else {
            $body = '充值到余额';
        	$data['buy_vip'] = input('buy_vip',0);
        	if($data['buy_vip'] == 1){
        		$map['user_id'] = $user['user_id'];
        		$map['buy_vip'] = 1;
        		$map['pay_status'] = 1;
        		$info = Db::name('recharge')->where($map)->order('order_id desc')->find();
        		if (($info['pay_time'] + 86400 * 365) > time() && $user['is_vip'] == 1) {
        			$this->error('您已是VIP且未过期，无需重复充值办理该业务！');
        		}
                $body = 'VIP充值';
        	}

        	$data['user_id'] = $user['user_id'];
        	$data['nickname'] = $user['nickname'];
        	$data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
        	$data['ctime'] = time();
        	$order_id = Db::name('recharge')->insertGetId($data);
        }
        if ($order_id) {
            $order = Db::name('recharge')->where("order_id", $order_id)->find();
            if (is_array($order) && $order['pay_status'] == 0) {
                $order['order_amount'] = $order['account'];
                $pay_radio = $_REQUEST['pay_radio'];
                $config_value = parse_url_param($pay_radio); // 类似于 pay_code=alipay&bank_code=CCB-DEBIT 参数
                $config_value['body'] = $body; // 加上body 微信需要
                $payment_arr = Db::name('Plugin')->where("`type` = 'payment'")->column('name','code');
                Db::name('recharge')->where("order_id", $order_id)->save(array('pay_code' => $this->pay_code, 'pay_name' => $payment_arr[$this->pay_code]));
                //微信JS支付
                if ($this->pay_code == 'weixin' && $_SESSION['openid'] && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                    $code_str = $this->payment->getJSAPI($order);
                    exit($code_str);
                } elseif ($this->pay_code == 'weixinH5') {
                    //微信H5支付
                    $return = $this->payment->get_code($order, $config_value);
                    if ($return['status'] != 1) {
                        $this->error($return['msg']);
                    }
                    View::assign('deeplink', $return['result']);
                } else {
                    $code_str = $this->payment->get_code($order, $config_value);
                }
            } else {
                $this->error('此充值订单，已完成支付!');
            }
        } else {
            $this->error('提交失败,参数有误!');
        }
        View::assign('code_str', $code_str);
        View::assign('order_id', $order_id);
        return View::fetch('recharge'); //分跳转 和不 跳转
    }

    // 服务器点对点 // http://www.tp-shop.cn/index.php/Home/Payment/notifyUrl
    public function notifyUrl()
    {
        $this->payment->response();
        exit();
    }

    // 页面跳转 // http://www.tp-shop.cn/index.php/Home/Payment/returnUrl
    public function returnUrl()
    {
        $result = $this->payment->respond2(); // $result['order_sn'] = '201512241425288593';
        if (stripos($result['order_sn'], 'recharge') !== false) {
            $order = Db::name('recharge')->where("order_sn", $result['order_sn'])->find();
            View::assign('order', $order);
            if ($result['status'] == 1)
                return View::fetch('recharge_success');
            else
                return View::fetch('recharge_error');
        }
        $order = Db::name('order')->where("order_sn", $result['order_sn'])->find();
        View::assign('order', $order);
        if ($result['status'] == 1)
            return View::fetch('success');
        else
            return View::fetch('error');
    }
    public function pay_success(){
        $order_id = input('order_id/d');
        $order = Db::name('order')->where("order_id", $order_id)->find();
        if ($order['pay_status'] == 1) {
            View::assign('order', $order);
            return View::fetch('success');
        }else {
            return View::fetch('error');
        }
    }
	
    public function buy_upgrade(){
        header("Content-type:text/html;charset=utf-8");
        $user = session('user');
        $level_id = input('level_id');
        $order = Db::name('recharge')->where(array('level_id'=>$level_id,'user_id'=>$user['user_id']))->find();
        if(empty($order)){
            $level_info = Db::name('distribut_level')->where('level_id',$level_id)->find();
            $data['user_id'] = $user['user_id'];
            $data['nickname'] = $user['nickname'];
            $data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
            $data['ctime'] = time();
            $data['level_id'] = $level_id;
            $data['account'] = $level_info['join_price'];
            $order_id = Db::name('recharge')->insertGetId($data);
        }else{
            $order_id = $order['order_id'];
        }
        if (0 == $order['pay_status']) {
            $order['order_amount'] = $order['account'];
            $pay_radio = empty($_REQUEST['pay_radio']) ? 'weixin' : $_REQUEST['pay_radio'];
            $config_value = parse_url_param($pay_radio); 
            //$config_value['body'] = $body;
            $payment_arr = Db::name('Plugin')->where("`type` = 'payment'")->column('name','code');
            Db::name('recharge')->where("order_id", $order_id)->save(array('pay_code' => $this->pay_code, 'pay_name' => $payment_arr[$this->pay_code]));
            //微信JS支付
            if ($this->pay_code == 'weixin' && $_SESSION['openid'] && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                $code_str = $this->payment->getJSAPI($order);
                exit($code_str);
            } elseif ($this->pay_code == 'weixinH5') {
                //微信H5支付
                $return = $this->payment->get_code($order, $config_value);
                if ($return['status'] != 1) {
                    $this->error($return['msg']);
                }
                View::assign('deeplink', $return['result']);
            } else {
                $code_str = $this->payment->get_code($order, $config_value);
            }
        } else {
            $this->error('请勿重复支付!');
        }
		
		View::assign('code_str', $code_str);
        View::assign('order_id', $order_id);
        return View::fetch('recharge'); //分跳转 和不 跳转
    }
    
    /**
     * 普通订单余额支付
     */
    public function balancePay(){
        $order_id = input('order_id',''); // 订单id
        $password = input('password',''); // 支付密码
        $user = session('user');
        if (!$user) {
            $this->ajaxReturn(['status' => -1, 'msg' => '请先登录' ]);
        }
        
        $user = Db::name('users')->where('user_id',$user['user_id'])->find();
        $orderModel = new \app\common\model\Order();
        $order = $orderModel->where(['order_id'=>$order_id])->find();
  
        if(!$order){
            $this->ajaxReturn(['status' => 0, 'msg' => '订单不存在！' ]);
        }   
        
        if($order['pay_status'] == 1){
            $this->ajaxReturn(['status' => 3, 'msg' => '订单已支付，不可重复支付' , 'result'=>$order['order_id']]);
        }      
        
        if($user['user_money'] - $order['order_amount'] < 0){
            $this->ajaxReturn(['status' => 0, 'msg' => '余额不足，请充值' ]);
        }
      
        if (empty($user['paypwd'])) {
            $url = url('Cart/cart4').'?order_sn='.$order['order_sn'];
            session('payPriorUrl',$url);
            $this->ajaxReturn(['status' => 2, 'msg' => '请先设置支付密码' ,'result'=>$order['order_sn']]);
        }            

        $pay = new \app\common\logic\Pay();     
        try {
            $pay->setUserId($user['user_id'])->setOrderAmount($order['order_amount'])->useUserMoney($order['order_amount']);
            $placeOrder = new \app\common\logic\PlaceOrder($pay);
            $placeOrder->setPayPsw($password);
            //下单前检查
            $placeOrder->check();
            //扣除用户余额，并产生消费记录
            $placeOrder->changUserPointMoney($order);            
            //设订单余额支付的金额，使用了余额支付，那线上支付设置为0，线上支付和余额支付不能共存
            $order->user_money = $order['order_amount'];
            $order->order_amount = 0;
            $order->save();
            //使用余额全额付款，修改状态订单
            update_pay_status($order['order_sn']); 
            //支付成功跳转页面
            $this->ajaxReturn(['status' => 1, 'msg' => '支付成功' ,'result'=>$order['order_id'] ]);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn(['status' => 0, 'msg' => $error['msg'] ]);        
        }  
    }
}
