<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * 采用最新Thinkphp6
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */

namespace app\admin\logic;
use app\common\logic\OrderLogic;
use think\Model;
use think\facade\Db;
use app\common\logic\OrderLogic as adminOrderLogic;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class RefundLogic 
{
    protected  $refund_deposit=0;  //要退的余额
    protected  $refund_money=0;     //要退的金额（三方支付的）
    protected  $refund_integral=0;  //要退的积分

    public function setRefundDeposit($refund_deposit)
    {
        $this->refund_deposit = $refund_deposit;
    }

    public function setRrefundMoney($refund_money)
    {
        $this->refund_money = $refund_money;
    }

    public function setRrefundIntegral($refund_integral)
    {
        $this->refund_integral = $refund_integral;
    }

    //订单商品售后退款
    function updateRefundGoods($rec_id,$refund_type=0){

        $order_goods = Db::name('order_goods')->where(array('rec_id'=>$rec_id))->find();
        $return_goods = Db::name('return_goods')->where(array('rec_id'=>$rec_id))->find();
        $up_data = [
            'refund_deposit'=>$this->refund_deposit,
            'refund_integral'=>$this->refund_integral,
            'refund_type'=>$refund_type,
            'refund_time'=>time(),
            'status'=>5
        ];
        if($return_goods['type'] == 2){
            return; //换货的话，不用退任何东西,否则要退相应的。
        }
        //使用积分或者余额抵扣部分原路退还
        if(($this->refund_deposit >0 || $this->refund_integral>0)){
            accountLog($return_goods['user_id'],$this->refund_deposit,$this->refund_integral,'用户申请商品退款',0,$return_goods['order_id'],$return_goods['order_sn']);
        }
        //在线支付金额退到余额去
        if($refund_type==1 && $this->refund_money>0){
            accountLog($return_goods['user_id'],$this->refund_money,0,'用户申请商品退款',0,$return_goods['order_id'],$return_goods['order_sn']);
        }
        Db::name('return_goods')->where(['rec_id'=>$rec_id])->save($up_data);//更新退款申请状态
        Db::name('order_goods')->where(['rec_id'=>$rec_id])->save(['is_send'=>3]);//修改订单商品状态
        $return_goods_count = Db::name('order_goods')->where(['order_id'=>$return_goods['order_id'],'is_send'=>3])->count();//获取该订单商品退款个数
        $order_goods_count = Db::name('order_goods')->where(['order_id'=>$return_goods['order_id']])->count();//查询该订单所有商品个数
        //该订单全部商品退款完才能订单作废
        if($return_goods_count == $order_goods_count){
            Db::name('order')->where(['order_id'=>$return_goods['order_id']])->save(['order_status'=>5]);//修改订单状态为作废，以后给6也行，不然统计销售额的时候会统计进去
        }
        $order = Db::name('order')->field('parent_sn')->where(['order_id'=>$return_goods['order_id']])->find();
        $oid = $this->is_split_all($order);
        if($oid){
            $order_id = $oid;
        }else{
            $order_id = $return_goods['order_id'];
        }
        // 只要不是换货，就需要追回
        //if($return_goods['is_receive'] == 1){
            //赠送积分追回
            if($order_goods['give_integral']>0){
                $user = get_user_info($return_goods['user_id']);
                if($order_goods['give_integral']<$user['pay_points']){
                    accountLog($return_goods['user_id'],0,-$order_goods['give_integral']*$return_goods['goods_num'],'退货积分追回',0,$return_goods['order_id'],$return_goods['order_sn']);
                }
            }

            // 订单活动赠送积分 追回
            $account_log_list = Db::name('account_log')->where('order_id',$return_goods['order_id'])->where('desc','订单活动赠送积分')->select();
            foreach ($account_log_list as $k => $v){
                accountLog($return_goods['user_id'],0,-$v['pay_points'],'订单活动赠送积分追回',0,$return_goods['order_id'],$return_goods['order_sn']);
            }

            //追回订单商品赠送的优惠券
            $coupon_info = Db::name('coupon_list')->where(array('uid'=>$return_goods['user_id'],'get_order_id'=>$order_id))->find();
            if(!empty($coupon_info)){
                if($coupon_info['status'] == 1) { //如果优惠券被使用,那么从退款里扣
                    $coupon = Db::name('coupon')->where(array('id' => $coupon_info['cid']))->find();
                    if ($this->refund_money > $coupon['money']) {
                        //退款金额大于优惠券金额直接扣
                        $this->refund_money = $this->refund_money - $coupon['money'];//更新实际退款金额
                        Db::name('return_goods')->where(['id' => $return_goods['id']])->save(['refund_money' => $this->refund_money]);
                    }else{
                        //否则从退还余额里扣
                        $this->refund_deposit = $this->refund_deposit - $coupon['money'];//更新实际退还余额
                        Db::name('return_goods')->where(['id' => $return_goods['id']])->save(['refund_deposit' => $this->refund_deposit]);
                    }
                }else {
                    Db::name('coupon_list')->where(array('id' => $coupon_info['id']))->delete();
                    Db::name('coupon')->where(array('id' => $coupon_info['cid']))->dec('send_num')->update();//优惠券追回
                }
            }
        //}
        if($oid){
            $coupon_list = Db::name('coupon_list')->where(['uid'=>$return_goods['user_id'],'order_id'=>$oid])->find();
            if(!empty($coupon_list)){
                $update_coupon_data = ['status'=>0,'use_time'=>0,'order_id'=>0];
                Db::name('coupon_list')->where(['id'=>$coupon_list['id'],'status'=>1])->save($update_coupon_data);//符合条件的，优惠券就退给他
            }
        }else{
            //退还使用的优惠券
            $order_goods_count =  Db::name('order_goods')->where(array('order_id'=>$return_goods['order_id']))->sum('goods_num');
            $return_goods_count = Db::name('return_goods')->where(array('order_id'=>$return_goods['order_id']))->sum('goods_num');
            if($order_goods_count == $return_goods_count){
                $coupon_list = Db::name('coupon_list')->where(['uid'=>$return_goods['user_id'],'order_id'=>$return_goods['order_id']])->find();
                if(!empty($coupon_list)){
                    $update_coupon_data = ['status'=>0,'use_time'=>0,'order_id'=>0];
                    Db::name('coupon_list')->where(['id'=>$coupon_list['id'],'status'=>1])->save($update_coupon_data);//符合条件的，优惠券就退给他
                }
            }
        }

        $expense_data = array(
            'money'=>$this->refund_money+$this->refund_deposit,
            'integral'=>$this->refund_integral,
            'log_type_id'=>$rec_id,
            'type'=>3,
            'user_id'=>$return_goods['user_id']
        );

        expenseLog($expense_data);//退款记录日志
        // 退款提醒
        $messageFactory = new \app\common\logic\MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 2]);
        // 发退款消息
        $messageLogic->sendRefundNotice($return_goods['order_id'], $return_goods['refund_money']);

        $commonOrderLogic = new OrderLogic();
        $commonOrderLogic->alterReturnGoodsInventory($order);//取消订单后改变库存
        $User =new \app\common\logic\User();
        $User->setUserById($order['user_id']);
        $User->updateUserLevel();


        //如果是分销商品, 从佣金记录表中扣除此商品产生的佣金: 该笔订单有三条记录(分别对应一二三级佣金)
        $rebateLogs = Db::name('rebate_log')->where(['order_id'=>$order_goods['order_id']])->select();
        if(!$rebateLogs)return ;
        foreach ($rebateLogs as $k => $v){
            if(!$v['detail'])continue;
            $rebate_detail_arr = unserialize($v['detail']);
            $money = 0;
            //找出此商品的佣金
            foreach ($rebate_detail_arr as $m => $item){
                if($item['rec_id'] ==$rec_id && $item['isReturn'] == 0 ){
                    $money +=  $item['money'];
                    $rebate_detail_arr[$m]['isReturn'] = 1;
                }
            }
            //扣除退款商品的佣金, 将明细中的此商品佣金标记为已退回
            $v['detail'] = serialize($rebate_detail_arr);
            $real_money = $v['money'] - $money;
            $v['money'] = ($real_money > 0) ? $real_money : 0 ;
            Db::name('rebate_log')->where(['id'=>$v['id']])->update($v);
        }
        if($rebateLogs[0]['status'] < 3 ){
            // 等分成时，退货，直接设置为取消=4
            Db::name('rebate_log')->where(['order_id'=>$order_goods['order_id']])->save(['status'=>4]);
        }
    }


    /**
     * 取消订单退还余额，优惠券等
     * @param $order
     * @param int $type
     * @return bool
     */
    function updateRefundOrder($order,$type=0){

        //要么退到余额，要么用下面的方法退，
        if($order['order_amount']>0 && $type == 1){
            //改方法已经是退回余额, 不需要判断原路退回还是退还到余额
            accountLog($order['user_id'],$order['order_amount'],$order['integral'],'用户取消订单退款',0,$order['order_id'],$order['order_sn']);
        }else{
            //使用积分或者余额抵扣部分
            if ($order['user_money'] > 0 || $order['integral'] > 0) {
                $update_money_res = accountLog($order['user_id'], $order['user_money'], $order['integral'], '用户申请订单退款', 0, $order['order_id'], $order['order_sn']);
                if(!$update_money_res){
                    return false;
                }
            }
        }
        // 拆分订单时
        $order_id = $this->is_split_all($order);
        if($order_id){
            $coupon_list = Db::name('coupon_list')->where(['uid'=>$order['user_id'],'order_id'=>$order_id])->find();
            if(!empty($coupon_list)){
                $update_coupon_data = ['status'=>0,'use_time'=>0,'order_id'=>0];
                Db::name('coupon_list')->where(['id'=>$coupon_list['id'],'status'=>1])->save($update_coupon_data);
            }
        }else{
            //符合条件的，该笔订单使用的优惠券就退还
            $coupon_list = Db::name('coupon_list')->where(['uid'=>$order['user_id'],'order_id'=>$order['order_id']])->find();
            if(!empty($coupon_list)){
                $update_coupon_data = ['status'=>0,'use_time'=>0,'order_id'=>0];
                Db::name('coupon_list')->where(['id'=>$coupon_list['id'],'status'=>1])->save($update_coupon_data);
            }
        }

        Db::name('order')->where(array('order_id'=>$order['order_id']))->save(array('pay_status'=>3)); //更改订单状态
        $messageFactory = new \app\common\logic\MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 2]);
        $messageLogic->sendRefundNotice($order['order_id'],$order['order_amount']); // 发退款消息通知

        $orderLogic = new adminOrderLogic();
        $orderLogic->alterReturnGoodsInventory($order);//取消订单后改变库存
        $User =new \app\common\logic\User();
        $User->setUserById($order['user_id']);
        $User->updateUserLevel();
        $expense_data = [
            'money'         => $order['user_money'],
            'integral'         => $order['integral'],
            'log_type_id'   => $order['order_id'],
            'type'          => 2,
            'user_id'       => $order['user_id'],
        ];
        $this->expenseLog($expense_data);//平台支出记录
        return true;
    }
	
	/**
     * 判断拆分订单全部退货了没
	 */
    function is_split_all($order){
        if(empty($order['parent_sn'])) return false;
        $order_id_arr = Db::name('order')->where('parent_sn',$order['parent_sn'])->column('order_id');
        $order_goods = Db::name('order_goods')->where('order_id','in',$order_id_arr)->column('is_send');
        foreach ($order_goods as $key => $value) {
            if($value != 3) return false;
        }
        return $order['parent_sn'];
    }
    /**
     * 平台支出记录(从command.php公用办法移出来)
     * 为了兼容砍价自动退款调用,否则砍价调用会报错不存在该办法
     * @param $data
     */
    function expenseLog($data){
        $data['addtime'] = time();
        $data['admin_id'] = session('admin_id');
        Db::name('expense_log')->insert($data);
    }


}