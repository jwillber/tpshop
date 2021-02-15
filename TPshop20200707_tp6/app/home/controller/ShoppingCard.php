<?php


namespace app\home\controller;
use think\facade\View;

use app\common\logic\ShoppingCardLogic;
use app\common\model\ShoppingCardGoods;
use app\common\model\ShoppingCardList;
use app\common\logic\GoodsLogic;
use think\facade\Db;
use think\Page;
class ShoppingCard extends Base
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
                'index'
            );
            if (!$this->user_id && !in_array(ACTION_NAME, $nologin)) {
                header("location:" . url('Home/User/login'));
                exit;
            }
        }
    }

    public function index()
    {
        $logic = new ShoppingCardLogic();
        $hot_data=[];
        $hot_data['hot']=1;
        $hot_data['on_sell']=1;
        $hot_data['status']=1;
        $hot_card = $logic->getCard($hot_data)['card'];
        View::assign('hot_card',$hot_card);

        $max_money=Db::name('shopping_card')->alias('c')->join('shopping_card_discount d','c.id=d.cid')->where(['hot'=>1,'status'=>1,'give'=>0])->where("(repertory=0 OR residue > 0)")->max('give_num');
        View::assign('max_money',$max_money);

        $recommend_data=[];
        $recommend_data['recommend']=1;
        $recommend_data['on_sell']=1;
        $recommend_data['status']=1;
        $recommend_data['sort']=1;
        $recommend_card=$logic->getCard($recommend_data)['card'];
        View::assign('recommend_card',$recommend_card);
        return View::fetch();
    }

    /*
     * 用户下单
     */
    public function oder_des()
    {
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardDiscount($param)[0];
        View::assign('card',$card);
        return View::fetch();
    }
    /*
     * 添加订单
     */
    public function addOrder()
    {
        $param = $_POST;
        $param['user_id'] = $this->user_id;
        $logic = new ShoppingCardLogic();
        $result = ShoppingCardLogic::addOrder($param);
        $this->ajaxReturn($result);
    }
    /*
     * 激活购物卡
     */
    public function activate()
    {
        $param=input('');
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $result=$logic->activateCard($param);
        $this->ajaxReturn($result);
    }

    /*
     * 成功下单创建用户购物卡
     */
    public function order()
    {
        $param=input('');
        $order = Db::name('order')->where(['order_id'=>$param['id']])->find();
        $param['order']=$order;
        $cardlogic = new ShoppingCardLogic();
        $result=$cardlogic->addShoppingCardList($param);
        $this->ajaxReturn($result);
    }

    /*
     * 我的购物卡
     */
    public function MyCard()
    {
        $sort=input('sort',-1);
        $data['user_id']=$this->user_id;
        View::assign('sort',$sort);
        if($sort!=-1){
            $data['sort']=$sort;
        }
        $data['user_id']=$this->user_id;
        $data['valid']=1;
        $logic = new ShoppingCardLogic();
        $result =$logic->getCardList($data);
        View::assign('list',$result['list']);
        View::assign('page',$result['page']);
        View::assign('sort',$sort);
        return View::fetch();
    }

    public function getPhoto()
    {
        $param = input('');
        $param['user']=$this->user;
        $param['user_id']=$this->user_id;
        $cardLogic = new ShoppingCardLogic();
        $result=$cardLogic->getDonatePhoto($param);
        $this->ajaxReturn($result);
    }
    /*
     * 购买记录
     */
    public function buy_record()
    {
        $param=input('');
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $result=$logic->getBuyLog($param);
        View::assign('list',$result['list']);
        View::assign('page',$result['page']);
        return View::fetch();
    }

    /*
     * 充值记录
     */
    public function recharge_record()
    {
        $param = input();
        $param['user_id']=$this->user_id;
        $logic = new ShoppingCardLogic();
        $card=$logic->getOneList($param);
        if($card and $param['id']){
            View::assign('card',$card);
            $result = $logic->getRechargeLog($param);
            View::assign('log',$result['log']);
            View::assign('page',$result['page']);
            View::assign('sort',['0'=>'待支付','1'=>'充值成功','2'=>'交易关闭']);
            return View::fetch();
        }else{
            $this->error("请求出错");
        }
    }

    public function card_info()
    {
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardList($param)['list'][0];
        if($card['status']==1){
            $this->error('这张卡已经停用');
        }
        View::assign('card',$card);
        View::assign('sort',$logic->getSort());
        return View::fetch();
    }
    /*
     * 充值
     */
    public function balance(){
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

    public function card_goodsInfo(){
        $param=input('');
        $param['on_sell']=1;
        $logic = new ShoppingCardLogic();
        $card_info = $logic->getCard($param)['card'][0];

        $max_money=Db::name('shopping_card')->where(['hot'=>1,'status'=>1])->where("(repertory=0 OR residue > 0)")->max('face_value');
        View::assign('max_money',$max_money);

        $hot_data['hot']=1;
        $hot_data['on_sell']=1;
        $hot_card = $logic->getCard($hot_data)['card'];

        $recommend_data['recommend']=1;
        $recommend_data['on_sell']=1;
        $recommend_card = $logic->getCard($recommend_data)['card'];
        View::assign('recommend_card',$recommend_card);

        if($card_info){
            $sort=$logic->getSort();
            View::assign("sort",$sort);
            View::assign('card_info',$card_info);
            View::assign('hot_card',$hot_card);
        }else{
            $this->error('该购物卡已经售完或停售');
        }


//        $goodsLogic = new GoodsLogic();
//        $goods_id = input("get.id/d");
//        $Goods = new \app\common\model\Goods();
//        $goods = $Goods->where('goods_id',$goods_id)->find();
//        View::assign('goods', $goods);
        return View::fetch();
    }

    public function create_code(){
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardList($param)['list'][0];
        View::assign('card',$card);
        View::assign('sort',$logic->getSort());
        View::assign('name',$param['name']?$param['name']:$this->user['nickname']);
        View::assign('now',time());
        return View::fetch();
    }
    public function give_friends(){
        $param=input('');
        $logic = new ShoppingCardLogic();
        $card=$logic->getCardList($param)['list'][0];
        View::assign('card',$card);
        View::assign('sort',$logic->getSort());
        return View::fetch();
    }

    public function exchange(){
        if($_POST){
            $param=$_POST;
            $param['user_id']=$this->user_id;
            $param['card_password']=$param['card_password'];
            $logic = new ShoppingCardLogic();
            $result=$logic->ObtainCard($param);
            $this->ajaxReturn($result);
        }
        return View::fetch();
    }
    public function move(){
        $logic = new ShoppingCardLogic();
        $data=[];
        $data['on_sell']=1;
        $data['status']=1;
        if(!(input('sort')===null)){
            $data['sort']=input('sort');
        }
        $result = $logic->getCard($data,16);
        $card=$result['card'];
        $page=$result['page'];
        View::assign('card',$card);
        View::assign('page',$page);
        return View::fetch();
    }
}