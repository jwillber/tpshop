<?php
namespace app\mobile\controller;
use think\facade\View;
use app\common\model\Order as OrderModel;
/**
 * 客服IM控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 17:29
 */
class Supplier extends MobileBase{

    //客服im界面
    public function index()
    {
        $user = array();
        $goods_id = input('get.goods_id');
        if(session("?user")){
            $user = session('user');
            if(!empty($user['head_pic'])){
                if(strpos($user['head_pic'], 'http')===0){
                }else{
                    $user['head_pic'] = SITE_URL . $user['head_pic'];
                }
            }else{
                $user['head_pic'] = '';
            }
        }
        $user['goods_id'] = $goods_id;
        View::assign('user',$user);
        $order_id = input('order_id');
        if($order_id){
            $Order = new OrderModel();
            $order = $Order::where(['order_id' => $order_id])->find();
            View::assign('order', $order);
        }
        return View::fetch();
    }

    //app 客服交互页面
    public function appServiceContact()
    {
        $user = [
            'goods_id' => input('get.goods_id') ? : '',
            'user_id' => input('get.user_id') ? : '',
            'nickname' => input('get.nickname') ? : '',
            'head_pic' => input('get.head_pic') ? : '',
            'order_id' =>input('order_id'),
        ];
        if(!empty($user['head_pic'])){
            if(strpos($user['head_pic'], 'http')===0){
            }else{
                $user['head_pic'] = SITE_URL . $user['head_pic'];
            }
        }else{
            $user['head_pic'] = '';
        }
        $order_id = input('order_id');
        if($order_id){
            $Order = new OrderModel();
            $order = $Order::where(['order_id' => $order_id])->find();
            View::assign('order', $order);
            $user['order_id'] = $order_id;
        }
        View::assign('user',$user);
        return View::fetch('app');
    }
}