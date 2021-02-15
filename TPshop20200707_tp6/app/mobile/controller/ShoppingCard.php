<?php


namespace app\mobile\controller;
use think\facade\View;
use think\facade\Db;
use app\common\logic\Order;
use app\common\logic\ShoppingCardLogic;
class ShoppingCard extends MobileBase
{
    public function __construct()
    {
        parent::__construct();
        if (session('?user')) {
            $user = session('user');
            $user = Db::name('users')->cache(true,10)->where("user_id", $user['user_id'])->find();
            session('user', $user);  //覆盖session 中的 user
            $this->user = $user;
            $this->user_id = $user['user_id'];
            View::assign('user', $user); //存储用户信息
        }else{
            $nologin = array(
                'index','card_info','ajax_get_card'
            );
            if (!$this->user_id && !in_array(ACTION_NAME, $nologin)) {
                header("location:" . url('Mobile/User/login'));
                exit;
            }
        }
    }
    /*
     * 购物卡首页
     */
    public function index()
    {
        $param = input('');
//        $param['hot']=1;
//        $param['recommend']=1;
        $param['hotOrrecommend']=1;
        $param['status']=1;
        $param['on_sell']=1;
        $logic = new ShoppingCardLogic();
        $result=$logic->getCard($param);
        View::assign('card',$result['card']);
        return View::fetch();
    }

    public function getPayCard()
    {
        $param = input('');
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $logic->getCardList($param);
    }

    /*
     * 购物卡详情
     */
    public function card_info()
    {
        $param = input('');
        $param['status']=1;
        $shopping_card_logic = new ShoppingCardLogic();
        $result=$shopping_card_logic->getCard($param);


        if($result['card']){
            View::assign('card',$result['card'][0]);
            return View::fetch();
        }else{
            $this->error('该购物卡不存在或已下架');
        }
    }

    /*
     * 获得分享图片
     */
    public function getPhoto()
    {
        $param = input('');
        $param['user_id']=$this->user_id;
        $param['type']='is_mobile';
        $shopping_card_logic = new ShoppingCardLogic();
        $result=$shopping_card_logic->getDonatePhoto($param);
        $this->ajaxReturn($result);
    }

    /*
   * 获得我的购物卡（可用或不可以）
   */
    public function ajax_my_usable_card()
    {
        $param = input('');
        $shopping_card_logic = new ShoppingCardLogic();
        $result=$shopping_card_logic->getMyCard($param);
        if($result['card']){
            $this->ajaxReturn(['card'=>$result['card'],'static'=>1]);
        }else{
            $this->ajaxReturn(['card'=>'','static'=>0]);
        }
    }

    public function ajax_my_donate_list()
    {
        $param =input('');
        $param['user_id']=$this->user_id;
        $shopping_card_logic = new ShoppingCardLogic();
        if($param['sort']==['donate']){
            $result = $shopping_card_logic->getMyDonateList($param);
        }else{
            $result = $shopping_card_logic->getMyObtainList($param);
        }
        $this->ajaxReturn($result);
    }

    /*
     * 获取购物卡
     */
    public function ajax_get_card()
    {
        $param = input('');
        $param['status']=1;
        $shopping_card_logic = new ShoppingCardLogic();
        $result = $shopping_card_logic->getCard($param);
        if($result['card']){
            $card = $result['card']->append(['discount','back_img','sell_num','id','name','residue'])->visible(['discount'])->toArray();
            $this->ajaxReturn(['status'=>1,'card'=>$card]);
        }else{
            $this->ajaxReturn(['status'=>0,'card'=>'']);
        }
    }
    /*
     * 提交订单
     */
    public function order()
    {
        $param = $_POST;
        $shopping_card_logic = new ShoppingCardLogic();
        $discount = $shopping_card_logic->getCardDiscount($param)[0];
        View::assign('discount',$discount);
        return View::fetch();
    }

    /*
     * 添加订单
     */
    public function addOrder()
    {
        $param = $_POST;
        $param['user_id'] = $this->user_id;
        $result = ShoppingCardLogic::addOrder($param);
        $this->ajaxReturn($result);
    }
    /*
     * 支付完成
     */
    public function pay_successful()
    {
        $order_logic = new Order();
        $order_logic->setOrderById(input('id',0));
        $order=$order_logic->getOrder();
        View::assign('order',$order);
        return View::fetch();
    }
    /*
     * 用户购物卡详情
     */
    public function activate()
    {
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardList($param)['list'][0];
        if($card['status']==1){
            $this->error('这张卡已经停用');
        }
        View::assign('card',$card);
        return View::fetch();
    }
    /*
     * 购物卡激活
     */
    public function ajax_activate()
    {
        $param = input('');
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $result=$logic->activateCard($param);
        $this->ajaxReturn($result);
    }
    /*
     * 充值页面
     */
    public function card_recharge()
    {
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardList($param)['list'][0];
        if($card['status']==1){
            $this->error('这张卡已经停用');
        }
        View::assign('card',$card);

        $paymentList = Db::name('Plugin')->where(['type'=>'payment' ,'code'=>['<>','cod'],'status'=>1,'scene'=> ['in','0,1']])->select();
        $paymentList = convert_arr_key($paymentList, 'code');
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            unset($paymentList['weixinH5']);
        }else{
            unset($paymentList['weixin']);
        }
        foreach ($paymentList as $key => $val) {
            $val['config_value'] = unserialize($val['config_value']);
            if ($val['config_value']['is_bank'] == 2) {
                $bankCodeList[$val['code']] = unserialize($val['bank_code']);
            }
        }
        $bank_img = include APP_PATH . 'home/bank.php'; // 银行对应图片
        View::assign('paymentList', $paymentList);

        return View::fetch();
    }
    /*
     * 购物卡赠送
     */
    public function donate()
    {
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardList($param)['list'][0];
        View::assign('card',$card);
        return View::fetch();
    }
    public function confirm_giving()
    {
        $param=input('');
        $logic = new ShoppingCardLogic();
        if($param['cid']){
            $param['id'] = $param['cid'];
            $card=$logic->getCardList($param)['list'][0];
            $log = $logic->getDonateLog($param);

            View::assign('card',$card);
            View::assign('name',$log['donate_name']);
            View::assign('text',$log['remark']);
            View::assign('now',$log['donate_time']);
            return View::fetch();
        }else{
            $card=$logic->getCardList($param)['list'][0];
            View::assign('card',$card);
            View::assign('name',$param['name']?$param['name']:$this->user['nickname']);
            View::assign('text',$param['text']);
            View::assign('now',time());
            View::assign('pic_url',url('ShoppingCard/getPhoto',array('remark'=>$param['text'],'name'=>$param['name'],'id'=>$param['id'])));
            return View::fetch();
        }
    }

    /*
     * 购买记录
     */
    public function buy_record(){
        $param=input('');
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $list=$logic->getBuyLog($param)['list'];
        View::assign('list',$list);
        if(input('is_ajax')){
            return View::fetch('ajax_buy_record');
        }
        return View::fetch();
    }
    /*
     * 赠送记录
     */
    public function giving_record()
    {
        return View::fetch();
    }
    /*
     * 领取购物卡
     */
    public function gain()
    {
        $param=input('');
        $param['user_id']=$this->user_id;
        $param['card_password']=$param['card_password'];
        $logic = new ShoppingCardLogic();
        $result=$logic->ObtainCard($param);
        $this->ajaxReturn($result);
    }

    public function gain_card(){
        $param=input('');
        $param['user_id']=$this->user_id;
        $param['card_password']=$param['card_password'];
        $logic = new ShoppingCardLogic();
        $result=$logic->ObtainCard($param);
        $this->ajaxReturn($result);
    }
    /*
     * ajax赠送记录
     */
    public function ajax_giving_record()
    {
        $param = input();
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        if($param['sort']==0){
            $result=$logic->getMyDonateList($param);
        }else{
            $result=$logic->getMyObtainList($param);
        }
        $this->ajaxReturn($result);
    }
    /*
     * 充值记录
     */
    public function recharge_list()
    {
        $param = input();
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $card=$logic->getOneList($param);
        if($card and $param['id']){
            View::assign('card',$card);
            $log = $logic->getRechargeLog($param)['log'];
            View::assign('log',$log);
            View::assign('sort',['0'=>'待支付','1'=>'充值成功','2'=>'交易关闭']);
            if(input('is_ajax')){
                return View::assign('ajax_recharge_list');
            }
            return View::fetch();
        }else{
            $this->error("请求出错");
        }
    }
    /*
     * 我的购物卡
     */
    public function my_card()
    {
        $sort=input('sort',-1);
        $data['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $data['valid']=1;
        $all_sum=$logic->getCardList($data)['count'];
        $data['sort']=0;
        $card_sum=$logic->getCardList($data)['count'];
        $data['sort']=1;
        $balance_card =$logic->getCardList($data)['count'];
        View::assign('all_sum',$all_sum);
        View::assign('card_sum',$card_sum);
        View::assign('sort',$sort);
        View::assign('balance_card',$balance_card);
        return View::fetch();
    }
    /*
     * 加载我的购物卡
     */
    public function ajax_get_my_card()
    {
        $sort=input('sort',-1);
        if($sort!=-1){
            $data['sort']=$sort;
        }
        $data['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $data['valid']=1;
        $list =$logic->getCardList($data)['list'];
        if($list){
            $this->ajaxReturn(['status'=>1,'list'=>$list]);
        }else{
            $this->ajaxReturn(['status'=>0,'list'=>[]]);
        }
    }
//
//    public function chong(){
//        $order_sn=input('id');
//        $recharge=Db::name('recharge')->where(['order_sn'=>$order_sn])->find();
//        $shopping_card_logic = new \app\common\logic\ShoppingCardLogic();
//        $shopping_card_logic->recharge($recharge);
//    }
}