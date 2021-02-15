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
 * Date: 2016-03-19
 */
namespace app\common\logic;

use app\common\model\Users;
use app\common\util\TpshopException;
use think\Model;
use think\facade\Db;

/**
 * 订单类
 * Class CatsLogic
 * @package Home\Logic
 */
class Order
{

    private $order;
    private $user_id = 0;

    public function __construct()
    {
        $this->order = new \app\common\model\Order();
    }

    public function setOrderById($order_id)
    {
        $this->order = \app\common\model\Order::find($order_id);
    }

    public function setOrderModel($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * 订单收货确认
     */
    public function deliveryConfirm()
    {
        if(empty($this->order)){
            throw new TpshopException('订单确认收货', 0, ['status' => 0, 'msg' => '订单不存在']);
        }
        if($this->order['order_status'] == 0){
            throw new TpshopException("自提订单核销",0,['status' => 0, 'msg' => '系统没有确认该订单']);
        }
        if($this->order['order_status'] == 2){
            throw new TpshopException("自提订单核销",0,['status' => 0, 'msg' => '该订单已收货']);
        }
        if($this->order['order_status'] == 3){
            throw new TpshopException("自提订单核销",0,['status' => 0, 'msg' => '该订单已取消']);
        }
        if($this->order['order_status'] == 4){
            throw new TpshopException("自提订单核销",0,['status' => 0, 'msg' => '该订单已完成']);
        }
        if($this->order['order_status'] == 5){
            throw new TpshopException("自提订单核销",0,['status' => 0, 'msg' => '该订单已作废']);
        }
        if(empty($this->order['pay_time']) || $this->order['pay_status'] != 1){
            throw new TpshopException('订单确认收货', 0, ['status' => 0, 'msg' => '商家未确定付款，该订单暂不能确定收货']);
        }
        $data['order_status'] = 2; // 已收货
        $data['shipping_status'] = 1; // 已发货
        $data['pay_status'] = 1; // 已付款
        $data['confirm_time'] = time(); // 收货确认时间
        if($this->order['pay_code'] == 'cod'){
            $data['pay_time'] = time();
        }
        $this->order->save($data);
        order_give($this->order);// 调用送礼物方法, 给下单这个人赠送相应的礼物

        // 自提点发货，改为待评价提醒 如果发了两次消息，那是因为order_give也发了次消息
        /*$order_arr = Db::name('order_goods')->where("order_id", $this->order['order_id'])->find();
        $goods_original_img = Db::name('goods')->where("goods_id", $order_arr['goods_id'])->value('original_img');
        $send_data = [
            'message_title' => '商品待评价',
            'message_content' => $order_arr['goods_name'],
            'img_uri' => $goods_original_img,
            'order_sn' => $this->order['order_sn'],
            'order_id' => $this->order['order_id'],
            'mmt_code' => 'evaluate_logistics',
            'type' => 4,
            'users' => [$this->order['user_id']],
            'category' => 2,
            'message_val' => []
        ];
        $messageFactory = new MessageFactory();
        $messageLogic = $messageFactory->makeModule($send_data);
        $messageLogic->sendMessage();*/

        //分销设置
        Db::name('rebate_log')->where("order_id", $this->order['order_id'])->save(['status'=>2,'confirm'=>time()]);
    }

    /**
     * 用户删除订单
     * @return array
     * @throws TpshopException
     */
    public function userDelOrder()
    {
        $validate = validate('order');
        $order_id = $this->order['order_id'];
        if (!$validate->scene('del')->batch(false)->check(['order_id' => $order_id])) {
            throw new TpshopException('用户删除订单', 0, ['status' => 0, 'msg' => $validate->getError()]);
        }
        if (empty($this->user_id)) {
            throw new TpshopException('用户删除订单', 0, ['status' => 0, 'msg' => '非法操作']);
        }
        $row = Db::name('order')->where(['user_id' => $this->user_id, 'order_id' => $order_id])->update(['deleted' => 1]);
        if (!$row) {
            Db::name('order_goods')->where(['order_id' => $order_id])->update(['deleted' => 1]);
            throw new TpshopException('用户删除订单', 0, ['status' => 0, 'msg' => '删除失败']);
        }
    }

    /**
     * 管理员删除订单
     * @return array
     * @throws \think\Exception
     */
    public function adminDelOrder()
    {
        Db::name('order_goods')->where('order_id',$this->order['order_id'])->delete();
        $this->order->delete();
    }

    /**
     * 订单操作记录
     * @param $action_note|备注
     * @param $status_desc|状态描述
     * @param $action_user
     * @return mixed
     */
    public function orderActionLog($action_note, $status_desc, $action_user = 0, $group = 0)
    {
        $data = [
            'order_id' => $this->order['order_id'],
            'action_user' => $action_user,
            'action_note' => $action_note,
            'order_status' => $this->order['order_status'],
            'pay_status' => $this->order['pay_status'],
            'log_time' => time(),
            'status_desc' => $status_desc,
            'shipping_status' => $this->order['shipping_status'],
			'group' => $group,
        ];
        //虚拟服务类商品支付,记录虚拟订单默认确认订单
        if($this->order['prom_type'] == 5 && $this->order['pay_status'] == 1){
            $data['order_status'] = 1;
        }
        return Db::name('order_action')->insert($data);//订单操作记录
    }


    /**
     * 未支付订单催单
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function urgentOrder(){
        //查询催付配置
        $config = tpCache('shopping');
        if (!$config['urgent_pay_comfirm']){
            return false;
        }
        //查询未付款订单
        $unpay_order = (new \app\common\model\Order())->where(['order_status'=>0,'pay_status'=>0])->select();
        foreach($unpay_order as $key => $val){
//            var_dump('666','</br>');
            $send_info_all = cache('urgent_pay');
            if (!$send_info_all){
                cache('urgent_pay',[['666'=>['time'=>6,'last_time'=>6666]]],TEMP_PATH);
                $send_info_all = cache('urgent_pay');;
            }
            $delay = $config['urgent_delay'];
            $speed = $config['urgent_speed'];
            $limit = $config['urgent_limit'];
//            var_dump('$send_info_all',$send_info_all,'</br>');
//            var_dump($speed,$limit,$delay,'</br>');
            if ($send_info_all){
                $send_info = $send_info_all[$val['order_sn']];
            }else{
                $send_info = [];
            }
            $now = time();
//            var_dump('$send_info',$send_info,'</br>');
//            var_dump(
//                'condiction</br>',
//                '还在延时',
//                (ceil(($now - $val['add_time'])/60)),
//                (ceil(($now - $val['add_time'])/60)<$delay),
//                '</br>非设定间隔',
//                (ceil(($now-$send_info['last_time'])/60)),
//                ((ceil(($now-$send_info['last_time'])/60))!=$speed),
//                '</br>超出次数',
//                ($send_info['time']>=$limit),
//                '</br>'
//            );
            //是否满足催付延时
            if (ceil(($now - $val['add_time'])/60)<$delay){
                continue;
            }
//            echo '1</br>';
            //是否满足催付频率
            if ($send_info['last_time']){
                if (ceil(($now-$send_info['last_time'])/60)<$speed){
                    continue;
                }
            }
//            echo '2</br>';
            //是否满足催付限制次数
            if ($send_info['send_num']>=$limit){
                continue;
            }
//            echo 'init</br>';
            //发送站内信
            $send_data = [
                'message_title' => '未付款订单提醒',
                'message_content' => '您的订单'.$val['order_sn'].'还未支付，请尽快支付！',
                'mmt_code' => 'urgent_pay',
                'users' => [$val['user_id']],
                'category' => 0,
            ];
            $messageFactory = new MessageFactory();
            $messageLogic = $messageFactory->makeModule($send_data);
            $messageLogic->sendMessage();

            //发送短信
            $params = array('order_sn'=>$val['order_sn']);
            $mobile = (new Users())->where(['user_id'=>$val['user_id']])->value('mobile');
            sendSms(10, $mobile, $params,'');
            //储存已发送催付订单信息至cache
            if (!$send_info){
                $send_info['send_num']+=1;
                $send_info['last_time']=$now;
                $send_info_all[$val['order_sn']] = $send_info;
            }else{
                $send_info['send_num']+=1;
                $send_info['last_time']=$now;
                $send_info_all[$val['order_sn']] = $send_info;
            }
//            var_dump('$send_info_all',$send_info_all,'</br>');
            cache('urgent_pay',$send_info_all);
//            $urgent_pay = cache('urgent_pay');
//            var_dump('$urgent_pay',$urgent_pay);
//            var_dump('$send_info',$send_info);
        }
    }
    public function urgentOrderInit($init){
        cache('urgent_pay',[['666'=>['time'=>6,'last_time'=>6666]]],TEMP_PATH);
        if ($init){
            $urgent_pay = cache('urgent_pay');
            var_dump($urgent_pay);
        }else{
            //delFile(RUNTIME_PATH);
            \think\facade\Cache::clear();
        }

    }

}