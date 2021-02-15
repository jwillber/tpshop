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
 * 2015-11-21
 */
namespace app\mobile\controller;
use think\facade\View;

use app\common\logic\CartLogic;
use app\common\logic\Message;
use app\common\logic\UsersLogic;
use app\common\logic\OrderLogic;
use app\common\model\MenuCfg;
use app\common\model\UserAddress;
use app\common\model\Users as UserModel;
use app\common\model\UserMessage;
use app\common\util\TpshopException;
use think\facade\Cache;
use think\Page;
use think\Verify;
use think\Loader;
use think\facade\Db;
use think\Image;

class User extends MobileBase
{

    public $user_id = 0;
    public $user = array();

    /*
    * 初始化操作
    */
    public function __construct()
    {
        parent::__construct();
        if (session('?user')) {
            $User = new UserModel();
            $session_user = session('user');
            $this->user = $User->where('user_id', $session_user['user_id'])->find();
            $UsersLogic = new \app\common\logic\UsersLogic();
            $UsersLogic->checkUserWithdrawals($this->user);
            if(!empty($this->user->auth_users)){
                $session_user = array_merge($this->user->toArray(), $this->user->auth_users[0]);
                session('user', $session_user);  //覆盖session 中的 user
            }
            $this->user_id = $this->user['user_id'];
            View::assign('user', $this->user); //存储用户信息0
        }
        $nologin = array(
            'login', 'pop_login','reset_password','invite_enroll','login_mobile','setPassword','code_login','binding','checkCode','do_login', 'logout', 'verify', 'set_pwd', 'finished',
            'verifyHandle', 'reg', 'send_sms_reg_code', 'find_pwd', 'check_validate_code','userHelpList','userHelpInfo',
            'forget_pwd', 'check_captcha', 'check_username', 'send_validate_code', 'express' ,'bind_reg'
        );
        if (!$this->user_id && !in_array(ACTION_NAME, $nologin)) {
            if(IS_AJAX){
                $this->ajaxReturn(['status'=>-102,'msg'=>'登录过期或者未登录']);
            }else{
                header("location:" . url('Mobile/User/login'));
            }
            exit;
        }

        $order_status_coment = array(
            'WAITPAY' => '待付款 ', //订单查询状态 待支付
            'WAITSEND' => '待发货', //订单查询状态 待发货
            'WAITRECEIVE' => '待收货', //订单查询状态 待收货
            'WAITCCOMMENT' => '待评价', //订单查询状态 待评价
        );
        View::assign('order_status_coment', $order_status_coment);
    }

    public function index()
    {
        $MenuCfg = new MenuCfg();
        $menu_list = $MenuCfg->where('is_show', 1)->order('menu_id asc')->select();
        $user_id =$this->user_id;
        $logic = new UsersLogic();
        $user = $logic->get_info($user_id); //当前登录用户信息
        $message_logic = new Message();
        $no_read=$message_logic->getNoRead();

        View::assign('no_read',$no_read);
        View::assign('user',$user['result']);
        View::assign('menu_list', $menu_list);
        return View::fetch();
    }

    public function logout()
    {
        session_unset();
        session(null);
        Db::name('users')->where(["user_id" => cookie('user_id')])->save(['token' => '']);
        setcookie('uname','',time()-3600,'/');
        setcookie('cn','',time()-3600,'/');
        setcookie('user_id','',time()-3600,'/');
        setcookie('token','',time()-3600,'/');
        setcookie('PHPSESSID','',time()-3600,'/');
        //$this->success("退出成功",url('Mobile/Index/index'));
        header("Location:" . url('Mobile/Index/index'));
        exit();
    }

    /*
     * 账户资金
     */
    public function account()
    {
        //账户资金需实时更新
        $user = (new \app\common\model\Users())->find($this->user_id);
        $UsersLogic = new \app\common\logic\UsersLogic();
        $UsersLogic->checkUserWithdrawals($user);
        $user['cash_in'] =  Db::name('withdrawals')->where(['type'=>0, 'user_id'=>$user['user_id'],'status'=>['in',['0','1']]])->sum('money')?:'0.00'; //统计正在提现的余额

        //获取账户资金记录
        $logic = new UsersLogic();
        $data = $logic->get_account_log($this->user_id, input('get.type'));
        $account_log = $data['result'];

        View::assign('user', $user);
        View::assign('account_log', $account_log);
        View::assign('page', $data['show']);
        View::assign('info',input('info',0));
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_account_list');
            exit;
        }
        return View::fetch();
    }

    public function account_list()
    {
    	$type = input('type','all');
    	$usersLogic = new UsersLogic;
    	$result = $usersLogic->account($this->user_id, $type);
        $this->ajaxReturn(array('status'=>1,'msg'=>'获取成功','result'=>['type'=>$type,'account_log'=>$result['account_log']]));
//    	View::assign('type', $type);
//    	View::assign('account_log', $result['account_log']);
//    	if ($_GET['is_ajax']) {
//    		return View::fetch('ajax_account_list');
//    	}
//    	return View::fetch();
    }

    public function account_detail(){
        $log_id = input('log_id/d',0);
        $detail = Db::name('account_log')->where(['log_id'=>$log_id])->find();
        View::assign('detail',$detail);
        return View::fetch();
    }
    
    /**
     * 优惠券
     */
    public function coupon()
    {
        $logic = new UsersLogic();
        $data = $logic->get_coupon($this->user_id, input('type'));
        foreach($data['result'] as $k =>$v){
            $user_type = $v['use_type'];
            $data['result'][$k]['use_scope'] = config('COUPON_USER_TYPE')["$user_type"];
            if($user_type==1){ //指定商品
                $data['result'][$k]['goods_id'] = Db::name('goods_coupon')->field('goods_id')->where(['coupon_id'=>$v['cid']])->value('goods_id');
            }
            if($user_type==2){ //指定分类
                $data['result'][$k]['category_id'] = Db::name('goods_coupon')->where(['coupon_id'=>$v['cid']])->value('goods_category_id');
            }
        }
        $coupon_list = $data['result'];
        View::assign('coupon_list', $coupon_list);
        View::assign('page', $data['show']);
        if (input('is_ajax')) {
            return View::fetch('ajax_coupon_list');
            exit;
        }
        return View::fetch();
    }

    /**
     *  登录
     */
    public function login()
    {
        if ($this->user_id > 0) {
             return redirect('/Mobile/User/index');
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url("Mobile/User/index");
        session('redirect_url',urlencode($referurl));//将来源链接储存cookie 在登录成功之后在接口返回
        // 新版支付宝跳转链接
        View::assign('alipay_url', urlencode(SITE_URL.url("Mobile/LoginApi/login",['oauth'=>'alipaynew'])));
        return View::fetch();
    }

    /**
     * 登录
     */
    public function do_login()
    {
        $username = trim(input('username'));
        $password = trim(input('password'));
        $password_error_num = Cache::get('password_error_num'.$username);
        if($password_error_num>=10){
            exit(json_encode(array('status' => -2, 'msg' => '密码错误!','password_error_num'=>$password_error_num)));
        }
        $logic = new UsersLogic();
        $res = $logic->login($username, $password);
        if ($res['status'] == 1) {
            $res['url'] = htmlspecialchars_decode(input('referurl'));
            if(session('?redirect_url')){
                $res['url'] =  urldecode(session('redirect_url'));
            }
            session('user', $res['result']);
            setcookie('user_id', $res['result']['user_id'], null, '/');
            setcookie('is_distribut', $res['result']['is_distribut'], null, '/');
            $nickname = empty($res['result']['nickname']) ? $username : $res['result']['nickname'];
            setcookie('uname', urlencode($nickname), null, '/');
            setcookie('cn', 0, time() - 3600, '/');
            setcookie('head_pic', $res['result']['head_pic'], null, '/');
            $token = md5(time().mt_rand(1,999999999));
            Db::name('users')->where(["user_id"=>$res['result']['user_id']])->save(['token' => $token, 'last_login' => time()]);
            setcookie('token',$token, null, '/');
            $cartLogic = new CartLogic();
            $cartLogic->setUserId($res['result']['user_id']);
            $cartLogic->doUserLoginHandle();// 用户登录后 需要对购物车 一些操作
            $orderLogic = new OrderLogic();
            $orderLogic->setUserId($res['result']['user_id']);//登录后将超时未支付订单给取消掉
            $orderLogic->abolishOrder();
        }
        exit(json_encode($res));
    }

    /**
     *  注册
     */
    public function reg()
    {

        if($this->user_id > 0) {
            return redirect(url('Mobile/User/index'));
        }
        $reg_sms_enable = tpCache('sms.regis_sms_enable');
        $reg_smtp_enable = tpCache('sms.regis_smtp_enable');

        if (IS_POST) {
            $logic = new UsersLogic();
            //验证码检验
            //$this->verifyHandle('user_reg');
            $nickname = input('post.nickname', '');
            $username = input('post.username', '');
            $password = input('post.password', '');
            $password2 = input('post.password2', '');
            $is_bind_account = tpCache('basic.is_bind_account');
            //是否开启注册验证码机制
            $code = input('post.mobile_code', '');
            $scene = input('post.scene', 1);
            
            $session_id = session_id();

            //是否开启注册验证码机制
            if(check_mobile($username)){
                if($reg_sms_enable){
                    //手机功能没关闭
                    $check_code = $logic->check_validate_code($code, $username, 'phone', $session_id, $scene);
                    if($check_code['status'] != 1){
                        $this->ajaxReturn($check_code);
                    }
                }
            }
            //是否开启注册邮箱验证码机制
            if(check_email($username)){
                if($reg_smtp_enable){
                    //邮件功能未关闭
                    $check_code = $logic->check_validate_code($code, $username);
                    if($check_code['status'] != 1){
                        $this->ajaxReturn($check_code);
                    }
                }
            }
            
            $invite = input('invite');
            if(!empty($invite)){
                $invite = get_user_info($invite,2);//根据手机号查找邀请人
                if(empty($invite)){
                    $this->ajaxReturn(['status'=>-1,'msg'=>'推荐人不存在','result'=>'']);
                }
            }else{
                $invite = array();
            }
            if($is_bind_account && session("third_oauth")){ //绑定第三方账号
                $thirdUser = session("third_oauth");
                $head_pic = $thirdUser['head_pic'];
                $data = $logic->reg($username, $password, $password2, 0, $invite ,$nickname , $head_pic);
                //用户注册成功后, 绑定第三方账号
                $userLogic = new UsersLogic();
                $data = $userLogic->oauth_bind_new($data['result']);
            }else{
                $data = $logic->reg($username, $password, $password2,0,$invite);
            }
             
            
            if ($data['status'] != 1) $this->ajaxReturn($data);
            
            //获取公众号openid,并保持到session的user中
            $oauth_users = Db::name('OauthUsers')->where(['user_id'=>$data['result']['user_id'] , 'oauth'=>'weixin' , 'oauth_child'=>'mp'])->find();
            $oauth_users && $data['result']['open_id'] = $oauth_users['open_id'];
            if(session('?redirect_url')){
                $data['url'] =  session('redirect_url');
            }else{
                $data['url'] =  '';
            }
            session('user', $data['result']);
            setcookie('user_id', $data['result']['user_id'], null, '/');
            setcookie('token', $data['result']['token'], null, '/');
            setcookie('is_distribut', $data['result']['is_distribut'], null, '/');
            $cartLogic = new CartLogic();
            $cartLogic->setUserId($data['result']['user_id']);
            $cartLogic->doUserLoginHandle();// 用户登录后 需要对购物车 一些操作
            $this->ajaxReturn($data);
            exit;
        }
        View::assign('regis_sms_enable',$reg_sms_enable); // 注册启用短信：
        View::assign('regis_smtp_enable',$reg_smtp_enable); // 注册启用邮箱：
        $sms_time_out = tpCache('sms.sms_time_out')>0 ? tpCache('sms.sms_time_out') : 120;
        View::assign('sms_time_out', $sms_time_out); // 手机短信超时时间
        return View::fetch();
    }

    public function bind_guide(){
        $data = session('third_oauth');
        //没有第三方登录的话就跳到登录页
        if(empty($data)){
            return redirect('/User/login');
        }
        $first_leader = Cache::get($data['openid']);
        if($first_leader){
            //拿关注传时候过来来的上级id
            setcookie('first_leader',$first_leader);
        }
        View::assign("nickname", $data['nickname']);
        View::assign("oauth", $data['oauth']);
        View::assign("head_pic", $data['head_pic']);
        View::assign('store_name',tpCache('shop_info.store_name'));
        return View::fetch();
    }

    /**
     * 绑定已有账号
     * @return \think\mixed
     */
    public function bind_account()
    {
        $mobile = input('mobile/s');
        $verify_code = input('verify_code/s');
        //发送短信验证码
        $logic = new UsersLogic();
        $check_code = $logic->check_validate_code($verify_code, $mobile, 'phone', session_id(), 1);
        if($check_code['status'] != 1){
            $this->ajaxReturn(['status'=>0,'msg'=>$check_code['msg'],'result'=>'']);
        }
        if(empty($mobile) || !check_mobile($mobile)){
            $this->ajaxReturn(['status' => 0, 'msg' => '手机格式错误']);
        }
        $users = Db::name('users')->where('mobile',$mobile)->find();
        if (empty($users)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '账号不存在']);
        }
        $user = new \app\common\logic\User();
        $user->setUserById($users['user_id']);
        $cartLogic = new CartLogic();
        try{
            $user->checkOauthBind();
            $user->oauthBind();
            $user->doLeader();
            $user->refreshCookie();
            $cartLogic->setUserId($users['user_id']);
            $cartLogic->doUserLoginHandle();
            $orderLogic = new OrderLogic();//登录后将超时未支付订单给取消掉
            $orderLogic->setUserId($users['user_id']);
            $orderLogic->abolishOrder();
            $this->ajaxReturn(['status' => 1, 'msg' => '绑定成功']);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }
    /**
     * 先注册再绑定账号
     * @return \think\mixed
     */
    public function bind_reg()
    {
        $mobile = input('mobile/s');
        $verify_code = input('verify_code/s');
        $password = input('password/s');
        $nickname = input('nickname/s', '');
        if(empty($mobile) || !check_mobile($mobile)){
            $this->ajaxReturn(['status' => 0, 'msg' => '手机格式错误']);
        }
        /*if(empty($password)){
            $this->ajaxReturn(['status' => 0, 'msg' => '请输入密码']);
        }*/
        $logic = new UsersLogic();
        $check_code = $logic->check_validate_code($verify_code, $mobile, 'phone', session_id(), 1);
        if($check_code['status'] != 1){
            $this->ajaxReturn(['status'=>0,'msg'=>$check_code['msg'],'result'=>'']);
        }
        $thirdUser = session('third_oauth');
        $data = $logic->reg($mobile, $password, $password, 0, [], $thirdUser['nickname'], $thirdUser['head_pic']);
        if ($data['status'] != 1) {
            $this->ajaxReturn(['status'=>0,'msg'=>$data['msg'],'result'=>'']);
        }
        $redirect_url = 'Admin/User/index';
        if(session('?redirect_url')){
            $redirect_url =  urldecode(session('redirect_url'));
        }
        $data['redirect_url'] = $redirect_url;
        $user = new \app\common\logic\User();
        $user->setUserById($data['result']['user_id']);
        try{
            $user->checkOauthBind();
            $user->oauthBind();
            $user->refreshCookie();
            $this->ajaxReturn($data);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    public function ajaxAddressList()
    {
        $UserAddress = new UserAddress();
        $address_list = $UserAddress->where('user_id', $this->user_id)->order('is_default desc')->select();
        if(!$address_list->isEmpty()){
            $address_list = $address_list->append(['address_area'])->toArray();
        }else{
            $address_list = [];
        }
        $this->ajaxReturn($address_list);
    }

    /**
     * 用户地址列表
     */
    public function address_list()
    {
        $address_lists =  Db::name('user_address')->where('user_id', $this->user_id)->select();
        $region_list = Db::name('region')->cache(true)->column('name','id');
        View::assign('region_list', $region_list);
        View::assign('lists', $address_lists);
        return View::fetch();
    }

    /**
     * 保存地址
     */
    public function addressSave()
    {
        $address_id = input('address_id/d',0);
        $data = input('post.');
        $userAddressValidate = validate(\app\common\validate\UserAddress::class);

        if (!$userAddressValidate->batch(true)->check($data)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => $userAddressValidate->getError()]);
        }
        if (!empty($address_id)) {
            //编辑
            $userAddress = UserAddress::where(['address_id'=>$address_id,'user_id'=> $this->user_id])->find();
            if(empty($userAddress)){
                $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
            }
        } else {
            //新增
            $userAddress = new UserAddress();
            $user_address_count = Db::name('user_address')->where("user_id", $this->user_id)->count();
            if ($user_address_count >= 20) {
                $this->ajaxReturn(['status' => 0, 'msg' => '最多只能添加20个收货地址']);
            }
            $data['user_id'] = $this->user_id;
        }
        $userAddress->data($data);
        $userAddress['longitude'] = true;
        $userAddress['latitude'] = true;
        $row = $userAddress->save();
        if ($row !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'result'=>['address_id'=>$userAddress->address_id]]);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败']);
        }
    }
    /*
         * 添加地址
         */
    public function add_address()
    {
        $source = input('source');
        if (IS_POST) {
            $post_data = input('post.');
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id, 0, $post_data);
            if ($data['status'] != 1){
                $this->ajaxReturn($data);
            } else {
                $data['url']= url('/Mobile/User/address_list');
                $this->ajaxReturn($data);
            }
        }
        $p = Db::name('region')->where(array('parent_id' => 0, 'level' => 1))->select();
        View::assign('province', $p);
        View::assign('source', $source);
        return View::fetch();

    }

    /*
     * 地址编辑
     */
    public function edit_address()
    {
        $id = input('id/d');
        $address = Db::name('user_address')->where(array('address_id' => $id, 'user_id' => $this->user_id))->find();
        if (IS_POST) {
            $post_data = input('post.');
            $source = $post_data['source'];
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id, $id, $post_data);
            if ($source == 'cart2') {
                $data['url']=url('/Mobile/Cart/cart2', array('address_id' => $data['result'],'goods_id'=>$post_data['goods_id'],'goods_num'=>$post_data['goods_num'],'item_id'=>$post_data['item_id'],'action'=>$post_data['action']));
                $this->ajaxReturn($data);
            } elseif ($source == 'integral') {
                $data['url'] = url('/Mobile/Cart/integral', array('address_id' => $data['result'],'goods_id'=>$post_data['goods_id'],'goods_num'=>$post_data['goods_num'],'item_id'=>$post_data['item_id']));
                $this->ajaxReturn($data);
            } elseif($source == 'pre_sell_cart'){
                $data['url'] = url('/Mobile/Cart/pre_sell_cart', array('address_id' => $data['result'],'act_id'=>$post_data['act_id'],'goods_num'=>$post_data['goods_num']));
                $this->ajaxReturn($data);
            } elseif($source == 'team'){
                $data['url']= url('/Mobile/Team/order', array('address_id' => $data['result'],'order_id'=>$post_data['order_id']));
                $this->ajaxReturn($data);
            } elseif ($_POST['source'] == 'pre_sell') {
                $prom_id = input('prom_id/d');
                $data['url'] = url('/Mobile/Cart/pre_sell', array('address_id' => $data['result'],'goods_num' => $goods_num,'prom_id' => $prom_id));
                $this->ajaxReturn($data);
            } else {
                $data['url']= url('/Mobile/User/address_list');
                $this->ajaxReturn($data);
            }
        }
        //获取省份
        $p = Db::name('region')->where(array('parent_id' => 0, 'level' => 1))->select();
        $c = Db::name('region')->where(array('parent_id' => $address['province'], 'level' => 2))->select();
        $d = Db::name('region')->where(array('parent_id' => $address['city'], 'level' => 3))->select();
        if ($address['twon']) {
            $e = Db::name('region')->where(array('parent_id' => $address['district'], 'level' => 4))->select();
            View::assign('twon', $e);
        }
        View::assign('province', $p);
        View::assign('city', $c);
        View::assign('district', $d);
        View::assign('address', $address);
        return View::fetch();
    }

    /*
     * 设置默认收货地址
     */
    public function set_default()
    {
        $id = input('get.id/d');
        $source = input('get.source');
        Db::name('user_address')->where(array('user_id' => $this->user_id))->save(array('is_default' => 0));
        $row = Db::name('user_address')->where(array('user_id' => $this->user_id, 'address_id' => $id))->save(array('is_default' => 1));
        if ($source == 'cart2') {
            header("Location:" . url('Mobile/Cart/cart2'));
            exit;
        } else {
            header("Location:" . url('Mobile/User/address_list'));
        }
    }

    /*
     * 地址删除
     */
    public function del_address()
    {
        $id = input('get.id/d');

        $address = Db::name('user_address')->where("address_id", $id)->find();
        $row = Db::name('user_address')->where(array('user_id' => $this->user_id, 'address_id' => $id))->delete();
        // 如果删除的是默认收货地址 则要把第一个地址设置为默认收货地址
        if ($address['is_default'] == 1) {
            $address2 = Db::name('user_address')->where("user_id", $this->user_id)->find();
            $address2 && Db::name('user_address')->where("address_id", $address2['address_id'])->save(array('is_default' => 1));
        }
        if (!$row)
            $this->error('操作失败', url('User/address_list'));
        else
            $this->success("操作成功", url('User/address_list'));
    }


    /*
     * 个人信息
     */
    public function userinfo()
    {
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        if (IS_POST) {
        	if ($_FILES['head_pic']['tmp_name']) {
        		$file = request()->file('head_pic');
                        
                        $parentDir = date('Ymd');
                        $dir = "head_pic/$parentDir/";
        		$dir2 = UPLOAD_PATH.$dir;
        		 
                        $originalName = strtolower($file->getOriginalName());
                        if(strstr($originalName,'.php') || strstr($originalName,'.js'))   
                                $this->error('上错图片格式错误');                     
                            $upload_max_filesize = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
                            $maxsize = 30000000;
                            if($file->getSize() > $maxsize)
                                $this->error('上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件,且系统配置不能超过:'.$upload_max_filesize);
                            $extension = strtolower($file->extension());
                            if(!in_array($extension,['jpg','jpeg','gif','png']))
                                    $this->error('仅可上传jpg,jpeg,gif,png文件');                                    
                            $savename = md5(mt_rand()).'.'.$file->extension();
                            \think\facade\Filesystem::disk('public')->putFileAs($dir, $file,$savename);                                                    
        		    $post['head_pic'] = '/'.$dir2.$savename;        	 
        	}
            input('post.nickname') ? $post['nickname'] = input('post.nickname') : false; //昵称
            input('post.qq') ? $post['qq'] = input('post.qq') : false;  //QQ号码
            input('post.head_pic') ? $post['head_pic'] = input('post.head_pic') : false; //头像地址
            input('post.sex') ? $post['sex'] = input('post.sex') : false;  // 性别
            input('post.birthday') ? $post['birthday'] = strtotime(input('post.birthday')) : false;  // 生日
            input('post.province') ? $post['province'] = input('post.province') : false;  //省份
            input('post.city') ? $post['city'] = input('post.city') : false;  // 城市
            input('post.district') ? $post['district'] = input('post.district') : false;  //地区
            input('post.email') ? $post['email'] = input('post.email') : false; //邮箱
            input('post.mobile') ? $post['mobile'] = input('post.mobile') : false; //手机

            $email = input('post.email');
            $mobile = input('post.mobile');
            $code = input('post.mobile_code', '');
            $scene = input('post.scene', 6);

            if (!empty($email)) {
                $c = Db::name('users')->where(['email' => input('post.email'), 'user_id' => ['<>', $this->user_id]])->count();
                $c && $this->error("邮箱已被使用");
            }
            if (!empty($mobile)) {
                $c = Db::name('users')->where(['mobile' => input('post.mobile'), 'user_id' => ['<>', $this->user_id]])->count();
                $c && $this->error("手机已被使用");
                if (!$code)
                    $this->error('请输入验证码');
                $check_code = $userLogic->check_validate_code($code, $mobile, 'phone', $this->session_id, $scene);
                if ($check_code['status'] != 1)
                    $this->error($check_code['msg']);
            }

            if (!$userLogic->update_info($this->user_id, $post))
                $this->error("保存失败",url('User/userinfo'));
            setcookie('uname',urlencode($post['nickname']),null,'/');
            $this->success("操作成功",url('User/userinfo'));
            exit;
        }
        //  获取省份
        $province = Db::name('region')->where(array('parent_id' => 0, 'level' => 1))->select();
        //  获取订单城市
        $city = Db::name('region')->where(array('parent_id' => $user_info['province'], 'level' => 2))->select();
        //  获取订单地区
        $area = Db::name('region')->where(array('parent_id' => $user_info['city'], 'level' => 3))->select();
        View::assign('province', $province);
        View::assign('city', $city);
        View::assign('area', $area);
        View::assign('user', $user_info);
        View::assign('sex', config('SEX'));
        //从哪个修改用户信息页面进来，
        $dispaly = input('action');
        if ($dispaly != '') {
            return View::fetch("$dispaly");
        }
        return View::fetch();
    }

    /**
     * 修改绑定手机
     * @return mixed
     */
    public function setMobile(){
        $userLogic = new UsersLogic();
        if (IS_POST) {
            $mobile = input('mobile');
            $mobile_code = input('mobile_code');
            $scene = input('post.scene', 6);
            $validate = input('validate',0);
            $status = input('status',0);
            $c = Db::name('users')->where(['mobile' => $mobile, 'user_id' => ['<>', $this->user_id]])->count();
            $c && $this->error('手机已被使用');
            if (!$mobile_code)
                $this->error('请输入验证码');
            $check_code = $userLogic->check_validate_code($mobile_code, $mobile, 'phone', $this->session_id, $scene);
            if($check_code['status'] !=1){
                $this->error($check_code['msg']);
            }

            if($validate == 1 && $status == 0){
                $res = Db::name('users')->where(['user_id' => $this->user_id])->update(['mobile'=>$mobile,'mobile_validated'=>1]);

                if($res!==false){
                    $source = input('source');
                    !empty($source) && $this->success('绑定成功', url("User/$source"));
                    $this->success('修改成功',url('User/userinfo'));
                }
                $this->error('修改失败');
            }
        }
        View::assign('status',$status);
        View::assign('sms',tpCache('sms'));
        return View::fetch();
    }

    /*
     * 邮箱验证
     */
    public function email_validate()
    {
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        $step = input('get.step', 1);
        //验证是否未绑定过
        if ($user_info['email_validated'] == 0)
            $step = 2;
        //原邮箱验证是否通过
        if ($user_info['email_validated'] == 1 && session('email_step1') == 1)
            $step = 2;
        if ($user_info['email_validated'] == 1 && session('email_step1') != 1)
            $step = 1;
        if (IS_POST) {
            $email = input('post.email');
            $code = input('post.code');
            $info = session('email_code');
            if (!$info)
                $this->error('非法操作');
            if ($info['email'] == $email || $info['code'] == $code) {
                if ($user_info['email_validated'] == 0 || session('email_step1') == 1) {
                    session('email_code', null);
                    session('email_step1', null);
                    if (!$userLogic->update_email_mobile($email, $this->user_id))
                        $this->error('邮箱已存在');
                    $this->success('绑定成功', url('Home/User/index'));
                } else {
                    session('email_code', null);
                    session('email_step1', 1);
                    redirect(url('Home/User/email_validate', array('step' => 2)));
                }
                exit;
            }
            $this->error('验证码邮箱不匹配');
        }
        View::assign('step', $step);
        return View::fetch();
    }

    /*
    * 手机验证
    */
    public function mobile_validate()
    {
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        $step = input('get.step', 1);
        //验证是否未绑定过
        if ($user_info['mobile_validated'] == 0)
            $step = 2;
        //原手机验证是否通过
        if ($user_info['mobile_validated'] == 1 && session('mobile_step1') == 1)
            $step = 2;
        if ($user_info['mobile_validated'] == 1 && session('mobile_step1') != 1)
            $step = 1;
        if (IS_POST) {
            $mobile = input('post.mobile');
            $code = input('post.code');
            $info = session('mobile_code');
            if (!$info)
                $this->error('非法操作');
            if ($info['email'] == $mobile || $info['code'] == $code) {
                if ($user_info['email_validated'] == 0 || session('email_step1') == 1) {
                    session('mobile_code', null);
                    session('mobile_step1', null);
                    if (!$userLogic->update_email_mobile($mobile, $this->user_id, 2))
                        $this->error('手机已存在');
                    $this->success('绑定成功', url('Home/User/index'));
                } else {
                    session('mobile_code', null);
                    session('email_step1', 1);
                    redirect(url('Home/User/mobile_validate', array('step' => 2)));
                }
                exit;
            }
            $this->error('验证码手机不匹配');
        }
        View::assign('step', $step);
        return View::fetch();
    }

    /**
     * 用户收藏列表
     */
    public function collect_list()
    {
        $userLogic = new UsersLogic();
        $data = $userLogic->get_goods_collect($this->user_id);
        View::assign('page', $data['show']);// 赋值分页输出
        View::assign('goods_list', $data['result']);
        if (IS_AJAX) {      //ajax加载更多
            return View::fetch('ajax_collect_list');
            exit;
        }
        return View::fetch();
    }

    /*
     *取消收藏
     */
    public function cancel_collect()
    {
        $collect_id = input('collect_id/d');
        $user_id = $this->user_id;
        if (Db::name('goods_collect')->where(['collect_id' => $collect_id, 'user_id' => $user_id])->delete()) {
            $this->success("已取消收藏", url('User/collect_list'));
        } else {
            $this->error("未取消收藏", url('User/collect_list'));
        }
    }

    /**
     * 我的留言
     */
    public function message_list()
    {
        config('TOKEN_ON', true);
        if (IS_POST) {
            if(!$this->verifyHandle('message')){
                $this->error('验证码错误', url('User/message_list'));
            };

            $data = input('post.');
            $data['user_id'] = $this->user_id;
            $user = session('user');
            $data['user_name'] = $user['nickname'];
            $data['msg_time'] = time();
            if (Db::name('feedback')->insert($data)) {
                $this->success("留言成功", url('User/message_list'));
                exit;
            } else {
                $this->error('留言失败', url('User/message_list'));
                exit;
            }
        }
        $msg_type = array(0 => '留言', 1 => '投诉', 2 => '询问', 3 => '售后', 4 => '求购');
        $count = Db::name('feedback')->where("user_id", $this->user_id)->count();
        $Page = new Page($count, 100);
        $Page->rollPage = 2;
        $message = Db::name('feedback')->where("user_id", $this->user_id)->limit($Page->firstRow,$Page->listRows)->select();
        $showpage = $Page->show();
        header("Content-type:text/html;charset=utf-8");
        View::assign('page', $showpage);
        View::assign('message', $message);
        View::assign('msg_type', $msg_type);
        return View::fetch();
    }

    /**账户明细*/
    public function points()
    {
        $type = input('type', 'all');    //获取类型
        View::assign('type', $type);
        if ($type == 'recharge') {
            //充值明细
            $count = Db::name('recharge')->where("user_id", $this->user_id)->count();
            $Page = new Page($count, 16);
            $account_log = Db::name('recharge')->where("user_id", $this->user_id)->order('order_id desc')->limit($Page->firstRow,$Page->listRows)->select();
        } else if ($type == 'points') {
            //积分记录明细
            $count = Db::name('account_log')->where(['user_id' => $this->user_id, 'pay_points' => ['<>', 0]])->count();
            $Page = new Page($count, 16);
            $account_log = Db::name('account_log')->where(['user_id' => $this->user_id, 'pay_points' => ['<>', 0]])->order('log_id desc')->limit($Page->firstRow,$Page->listRows)->select();
        } else {
            //全部
            $count = Db::name('account_log')->where(['user_id' => $this->user_id])->count();
            $Page = new Page($count, 16);
            $account_log = Db::name('account_log')->where(['user_id' => $this->user_id])->order('log_id desc')->limit($Page->firstRow,$Page->listRows)->select();
        }
        $show = $Page->show();
        View::assign('account_log', $account_log);
        View::assign('page', $show);
        View::assign('listRows', $Page->listRows);
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_points');
            exit;
        }
        return View::fetch();
    }

    
    public function points_list()
    {
    	$type = input('type','all');
    	$usersLogic = new UsersLogic;
    	$result = $usersLogic->points($this->user_id, $type);
    
    	View::assign('type', $type);
    	$showpage = $result['page']->show();
    	View::assign('account_log', $result['account_log']);
    	View::assign('page', $showpage);
    	if ($_GET['is_ajax']) {
    		 return View::fetch('ajax_points');
    	}
    	return View::fetch();
    }
    
    
    /*
     * 密码修改
     */
    public function password()
    {
        if (IS_POST) {
            $logic = new UsersLogic();
            $data = $logic->get_info($this->user_id);
            $user = $data['result'];
            if ($user['mobile'] == '' && $user['email'] == '')
                $this->ajaxReturn(['status'=>-1,'msg'=>'请先绑定手机或邮箱','url'=>url('/Mobile/User/index')]);
            $userLogic = new UsersLogic();
            $data = $userLogic->password($this->user_id, input('post.old_password'), input('post.new_password'), input('post.confirm_password'));
            if ($data['status'] == -1)
                $this->ajaxReturn(['status'=>-1,'msg'=>$data['msg']]);
            $this->ajaxReturn(['status'=>1,'msg'=>$data['msg'],'url'=>url('/Mobile/User/index')]);
            exit;
        }
        return View::fetch();
    }

    /*
     * 第一次设置密码
     *
     */
    public function set_password()
    {
        $password = input('old_password');
        if($this->user['password']){
            $this->ajaxReturn(['status'=>0,'msg'=>'初始密码已设置！','url'=>url('/Mobile/User/index')]);
        }
        $userLogic = new UsersLogic();
        $data = $userLogic->password($this->user_id, input('post.old_password'), input('post.new_password'), input('post.confirm_password'));
        if ($data['status'] == -1)
            $this->ajaxReturn(['status'=>-1,'msg'=>$data['msg']]);
        $this->ajaxReturn(['status'=>1,'msg'=>$data['msg'],'url'=>url('/Mobile/User/index')]);
    }

    /*
     * 验证码登录
     */
    public function code_login()
    {
        View::assign('mobile',I('mobile'));
        return View::fetch();
    }
    /*
     * 重置密码
     */
    public function reset_password()
    {
        View::assign('redirect_url',urldecode(session('redirect_url')));
        return View::fetch();
    }
    /*
     * 邀请码注册
     */
    public function invite_enroll()
    {
        return $this->fetch();
    }
    /*
     * 绑定电话页面
     */
    public function binding()
    {
        if($this->user){
            return redirect('User/index');
        }
        $data = session('third_oauth');
        //没有第三方登录的话就跳到登录页
        if(empty($data) && strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){
            $first_leader = input('first_leader');
            return redirect(url('Mobile/User/binding',['first_leader'=>$first_leader]));
        }elseif(empty($data)){
            return redirect('User/login');
        }
        $first_leader = Cache::get($data['openid']);
        if($first_leader){
            //拿关注传时候过来来的上级id
            setcookie('first_leader',$first_leader);
        }
        return View::fetch();
    }

    function forget_pwd()
    {
        if ($this->user_id > 0) {
            return redirect("User/index");
        }
        $username = input('username');
        if (IS_POST) {
            if (!empty($username)) {
                if(!$this->verifyHandle('forget')){
                    $this->ajaxReturn(['status'=>-1,'msg'=>"验证码错误"]);
                };
                $field = 'mobile';
                if (check_email($username)) {
                    $field = 'email';
                }
                $user = Db::name('users')->where("email", $username)->whereOr('mobile', $username)->find();
                if ($user) {
                    $sms_status = checkEnableSendSms(2);
                    session('find_password', array('user_id' => $user['user_id'], 'username' => $username,
                        'email' => $user['email'], 'mobile' => $user['mobile'], 'type' => $field,'sms_status'=>$sms_status['status']));
                    $regis_smtp_enable = $this->tpshop_config['smtp_regis_smtp_enable'];
                    if(($field=='mobile' && $this->tpshop_config['sms_forget_pwd_sms_enable']==1)){
                        $this->ajaxReturn(['status'=>1,'msg'=>"用户验证成功",'url'=>url('User/find_pwd')]);
                    }

                    if(($field=='email' && $regis_smtp_enable==0) || ($field=='mobile' && $sms_status['status']<1)){
                        $this->ajaxReturn(['status'=>1,'msg'=>"用户验证成功",'url'=>url('User/set_pwd')]);
                    }
                    exit;
                } else {
                    $this->ajaxReturn(['status'=>-1,'msg'=>"用户名不存在，请检查"]);
                }
            }
        }
        return View::fetch();
    }

    function find_pwd()
    {
        if ($this->user_id > 0) {
            header("Location: " . url('User/index'));
        }
        $user = session('find_password');
        if (empty($user)) {
            $this->error("请先验证用户名", url('User/forget_pwd'));
        }
        View::assign('user', $user);
        return View::fetch();
    }


    public function set_pwd()
    {
        if ($this->user_id > 0) {
            return redirect('/Mobile/User/index');
        }
        $check = session('validate_code');
        $find_password = session('find_password');
        $field = $find_password['field'];
        $sms_status = session('find_password')['sms_status'];
        $regis_smtp_enable = $this->tpshop_config['smtp_regis_smtp_enable'];
        $is_check_code=false;
        //需要验证邮箱或者手机
        if($field=='email' && $regis_smtp_enable==1)$is_check_code = true;
        if($field=='mobile' && $sms_status['status']==1)$is_check_code = true;
        if ((empty($check) || $check['is_check'] == 0) && $is_check_code) {
            $this->error('验证码还未验证通过',url('User/forget_pwd'));
        }
        if (IS_POST) {
            $data['password'] = $password = input('post.password');
            $data['password2'] = $password2 = input('post.password2');
            $UserRegvalidate = validate(\app\common\validate\User::class);

            if(!$UserRegvalidate->scene('set_pwd')->batch(false)->check($data)){
                $this->error($UserRegvalidate->getError(),url('User/forget_pwd'));
            }
            Db::name('users')->where("user_id", $find_password['user_id'])->save(array('password' => encrypt($password)));
            session('validate_code', null);
            return View::fetch('reset_pwd_sucess');
        }
        $is_set = input('is_set', 0);
        View::assign('is_set', $is_set);
        return View::fetch();
    }

    /**
     * 验证码验证
     * $id 验证码标示
     */
    private function verifyHandle($id)
    {
        $verify = new Verify();
        if (!$verify->check(input('post.verify_code'), $id ? $id : 'user_login')) {
            return false;
        }
        return true;
    }

    /**
     * 验证码获取
     */
    public function verify()
    {
        //验证码类型
        $type = input('get.type') ? input('get.type') : 'user_login';
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'imageH' =>  60,
            'imageW' =>  300,
            'fontttf' => '5.ttf',
            'useCurve' => false,
            'useNoise' => false,
        );
        $Verify = new Verify($config);
        $Verify->entry($type);
		exit();
    }

    /**
     * 账户管理
     */
    public function accountManage()
    {
        return View::fetch();
    }

    public function recharge()
    {
        $order_id = input('order_id/d');
        $paymentList = Db::name('Plugin')->where(['type'=>'payment' ,'code'=>['<>','cod'],'status'=>1,'scene'=> ['in','0,1']])->select();
        $paymentList = convert_arr_key($paymentList, 'code');
        //微信浏览器
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
        View::assign('bank_img', $bank_img);
        View::assign('bankCodeList', $bankCodeList);

        // 查找最近一次充值方式
        $recharge_arr = Db::name('Recharge')->field('pay_code')->where('user_id', $this->user_id)
            ->order('order_id desc')->find();
        $alipay = 'alipayMobile'; //默认支付宝支付
        if($recharge_arr){
            foreach ($paymentList as  $key=>$item) {
                if($key == $recharge_arr['pay_code']){
                    $alipay = $recharge_arr['pay_code'];
                }
            }
        }
        View::assign('alipay', $alipay);

        if ($order_id > 0) {
            $order = Db::name('recharge')->where("order_id", $order_id)->find();
            View::assign('order', $order);
        }
        return View::fetch();
    }
    
    public function recharge_list(){
    	$usersLogic = new UsersLogic;
    	$result= $usersLogic->get_recharge_log($this->user_id);  //充值记录
    	View::assign('page', $result['show']);
    	View::assign('lists', $result['result']);
    	if (input('is_ajax')) {
    		return View::fetch('ajax_recharge_list');
    	}
    	return View::fetch();
    }

    //添加、编辑提现支付宝账号
    public function add_card(){
        $user_id=$this->user_id;
        $data=input('post.');
        if($data['type']==0){
            $info['cash_alipay']=$data['card'];
            $info['realname']=$data['cash_name'];
            $info['user_id']=$user_id;
            $res=DB::name('user_extend')->where('user_id='.$user_id)->count();
            if($res){
                $res2=Db::name('user_extend')->where('user_id='.$user_id)->save($info);
            }else{
                $res2=Db::name('user_extend')->insertGetId($info);
            }
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功']);
        }else{
            //防止非支付宝类型的表单提交
            $this->ajaxReturn(['status'=>0,'msg'=>'不支持的提现方式']);
        }

    }



    /**
     * 申请提现记录
     */
    public function withdrawals()
    {
        config('TOKEN_ON', true);
        $cash_open=tpCache('cash.cash_open');
        if($cash_open!=1){
            $this->error('提现功能已关闭,请联系商家');
        }
        if (IS_POST) {
            $cash_open=tpCache('cash.cash_open');
            if($cash_open!=1){
                $this->ajaxReturn(['status'=>0, 'msg'=>'提现功能已关闭,请联系商家']);
            }

            $data = input('post.');
            $data['user_id'] = $this->user_id;
            $data['create_time'] = time();
            $cash = tpCache('cash');

            if(encrypt($data['paypwd']) != $this->user['paypwd']){
                $this->ajaxReturn(['status'=>0, 'msg'=>'支付密码错误']);
            }
            if ($data['money'] > $this->user['user_money']) {
                $this->ajaxReturn(['status'=>0, 'msg'=>"本次提现余额不足"]);
            } 
            if ($data['money'] <= 0) {
                $this->ajaxReturn(['status'=>0, 'msg'=>'提现额度必须大于0']);
            }
            
            if ($data['money'] > $this->user['user_money']) {
                $this->ajaxReturn(['status'=>0, 'msg'=>"您有提现申请待处理，本次提现余额不足"]);
            }

            if ($cash['cash_open'] == 1) {
                $taxfee =  round($data['money'] * $cash['service_ratio'] / 100, 2);
                // 限手续费
                if ($cash['max_service_money'] > 0 && $taxfee > $cash['max_service_money']) {
                    $taxfee = $cash['max_service_money'];
                }
                if ($cash['min_service_money'] > 0 && $taxfee < $cash['min_service_money']) {
                    $taxfee = $cash['min_service_money'];
                }
                if ($taxfee >= $data['money']) {
                    $this->ajaxReturn(['status'=>0, 'msg'=>'提现额度必须大于手续费！']);
                }
                $data['taxfee'] = $taxfee;

                // 每次限最多提现额度
                if ($cash['min_cash'] > 0 && $data['money'] < $cash['min_cash']) {
                    $this->ajaxReturn(['status'=>0, 'msg'=>'每次最少提现额度' . $cash['min_cash']]);
                }
                if ($cash['max_cash'] > 0 && $data['money'] > $cash['max_cash']) {
                    $this->ajaxReturn(['status'=>0, 'msg'=>'每次最多提现额度' . $cash['max_cash']]);
                }

                $status = ['in','0,1,2,3'];
                $create_time = ['>',strtotime(date("Y-m-d"))];
                // 今天限总额度
                if ($cash['count_cash'] > 0) {
                    $total_money2 = Db::name('withdrawals')->where(array('user_id' => $this->user_id, 'status' => $status, 'create_time' => $create_time,'type'=>0))->sum('money');
                    if (($total_money2 + $data['money'] > $cash['count_cash'])) {
                        $total_money = $cash['count_cash'] - $total_money2;
                        if ($total_money <= 0) {
                            $this->ajaxReturn(['status'=>0, 'msg'=>"你今天累计提现额为{$total_money2},金额已超过可提现金额."]);
                        } else {
                            $this->ajaxReturn(['status'=>0, 'msg'=>"你今天累计提现额为{$total_money2}，最多可提现{$total_money}账户余额."]);
                        }
                    }
                }
                // 今天限申请次数
                if ($cash['cash_times'] > 0) {
                    $total_times = Db::name('withdrawals')->where(array('user_id' => $this->user_id, 'status' => $status, 'create_time' => $create_time ,'type'=>0))->count();
                    if ($total_times >= $cash['cash_times']) {
                        $this->ajaxReturn(['status'=>0, 'msg'=>"今天申请提现的次数已用完."]);
                    }
                }
            }else{
                $data['taxfee'] = 0;
            }

            if (Db::name('withdrawals')->insert($data)) {
                $this->ajaxReturn(['status'=>1,'msg'=>"已提交申请",'url'=>url('User/account',['type'=>2])]);
            } else {
                $this->ajaxReturn(['status'=>0,'msg'=>'提交失败,联系客服!']);
            }
        }
        $user_extend=Db::name('user_extend')->where('user_id='.$this->user_id)->find();

        //获取用户绑定openId 以mp为公众号的，open 开放平台的
        $oauthUsers = Db::name("OauthUsers")->where(['user_id'=>$this->user_id, 'oauth'=>'weixin','oauth_child'=>'mp'])->find();
        $openid = $oauthUsers['openid'];

        View::assign('user_extend',$user_extend);
        View::assign('cash_config', tpCache('cash'));//提现配置项
        View::assign('user_money', $this->user['user_money']);    //用户余额
        View::assign('openid',$openid);    //用户绑定的微信openid
        return View::fetch();
    }

    //手机端是通过扫码PC端来绑定微信,需要ajax获取一下openID
    public function get_openid(){
        //halt($this->user_id); 22 以mp为公众号的，open 开放平台的
        $oauthUsers = Db::name("OauthUsers")->where(['user_id'=>$this->user_id, 'oauth'=>'weixin','oauth_child'=>'mp'])->find();
        $openid = $oauthUsers['openid'];
        if(empty($oauthUsers)){
            $openid = Db::name('oauth_users')->where(['user_id'=>$this->user_id, 'oauth'=>'wx'])->value('openid');
        }
        if($openid){
            $this->ajaxReturn(['status'=>1,'result'=>$openid]);
        }else{
            $this->ajaxReturn(['status'=>0,'result'=>'']);   
        }
    }

    /**
     * 申请记录列表
     * @param $type 提现类型 ： 0 = 余额提现 ， 1 = 佣金提现
     */
    public function withdrawals_list()
    {
        $type = input('type',0);
        $withdrawals_where['user_id'] = $this->user_id;
        $withdrawals_where['type'] = $type;
        $count = Db::name('withdrawals')->where($withdrawals_where)->count();
        // $pagesize = config('PAGESIZE'); //10条数据，不显示滚动效果
        // $page = new Page($count, $pagesize);
        $page = new Page($count, 15);
        $list = Db::name('withdrawals')->where($withdrawals_where)->order("id desc")->limit($page->firstRow,$page->listRows)->select();

        View::assign('page', $page->show());// 赋值分页输出
        View::assign('list', $list); // 下线
        if (input('is_ajax')) {
            return View::fetch('ajax_withdrawals_list');
        }
        View::assign('type',$type);
        return View::fetch();
    }

    /**
     * 我的关注
     * @author lxl
     * @time   2017/1
     */
    public function myfocus()
    {
        return View::fetch();
    }

    /**
     *  用户消息通知
     * @author yhj
     * @time 2018/07/10
     */
    public function message_notice()
    {
        $message_logic = new Message();
        $message_logic->checkPublicMessage();
        $where = array(
            'user_id' => $this->user_id,
            'deleted' => 0,
            'category' => 0
        );
        $userMessage = new UserMessage();
        $data['message_notice'] = $userMessage->where($where)->LIMIT(1)->order('rec_id desc')->find();

        $where['category'] = 1;
        $data['message_activity'] = $userMessage->where($where)->LIMIT(1)->order('rec_id desc')->find();

        $where['category'] = 2;
        $data['message_logistics'] = $userMessage->where($where)->LIMIT(1)->order('rec_id desc')->find();

        //$where['category'] = 3;
        //$data['message_private'] = $userMessage->where($where)->LIMIT(1)->order('rec_id desc')->find();

        $data['no_read'] = $message_logic->getUserMessageCount();

        // 最近消息，日期，内容
        View::assign($data);        
        return View::fetch();
    }


    /**
     * 查看通知消息详情
     */
    public function message_notice_detail()
    {

        $type = input('type', 0);
        // $type==3私信，暂时没有

        $message_logic = new Message();
        $message_logic->checkPublicMessage();

        $where = array(
            'user_id' => $this->user_id,
            'deleted' => 0,
            'category' => $type
        );
        $userMessage = new UserMessage();
        $count = $userMessage->where($where)->count();
        $page = new Page($count, 10);
        //$lists = $userMessage->where($where)->order("rec_id DESC")->limit($page->firstRow . ',' . $page->listRows)->select();

        $rec_id = $userMessage->where( $where)->LIMIT($page->firstRow.','.$page->listRows)->order('rec_id desc')->column('rec_id');
        $lists = $message_logic->sortMessageListBySendTime($rec_id, $type);

        View::assign('lists', $lists);
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_message_detail');
        }
        if (empty($lists)) {
            return View::fetch('user/message_none');
        }
        return View::fetch();
    }

    /**
     * 通知消息详情
     */
    public function message_notice_info(){
        $message_logic = new Message();
        $message_details = $message_logic->getMessageDetails(input('msg_id'), input('type', 0));
        View::assign('message_details', $message_details);  
        return View::fetch();
    }

    /**
     * 浏览记录
     */
    public function visit_log()
    {
        $count = Db::name('goods_visit')->where('user_id', $this->user_id)->count();
        $Page = new Page($count, 20);
        $visit = Db::name('goods_visit')->alias('v')
            ->field('v.visit_id, v.goods_id, v.visittime, g.goods_name, g.shop_price, g.cat_id')
            ->join('goods g', 'v.goods_id=g.goods_id')
            ->where('v.user_id', $this->user_id)
            ->order('v.visittime desc')
            ->limit($Page->firstRow, $Page->listRows)
            ->select();

        /* 浏览记录按日期分组 */
        $curyear = date('Y');
        $visit_list = [];
        foreach ($visit as $v) {
            if ($curyear == date('Y', $v['visittime'])) {
                $date = date('m月d日', $v['visittime']);
            } else {
                $date = date('Y年m月d日', $v['visittime']);
            }
            $visit_list[$date][] = $v;
        }

        View::assign('visit_list', $visit_list);
        if (input('get.is_ajax', 0)) {
            return View::fetch('ajax_visit_log');
        }
        return View::fetch();
    }

    /**
     * 删除浏览记录
     */
    public function del_visit_log()
    {
        $visit_ids = input('get.visit_ids', 0);
        $row = Db::name('goods_visit')->where('visit_id','IN', $visit_ids)->delete();

        if(!$row) {
            $this->error('操作失败',url('User/visit_log'));
        } else {
            $this->success("操作成功",url('User/visit_log'));
        }
    }

    /**
     * 清空浏览记录
     */
    public function clear_visit_log()
    {
        $row = Db::name('goods_visit')->where('user_id', $this->user_id)->delete();

        if(!$row) {
            $this->error('操作失败',url('User/visit_log'));
        } else {
            $this->success("操作成功",url('User/visit_log'));
        }
    }

    /**
     * 支付密码
     * @return mixed
     */
    public function paypwd()
    {
        //检查是否第三方登录用户
        $user = Db::name('users')->where('user_id', $this->user_id)->find();
        if ($user['mobile'] == '')
            $this->error('请先绑定手机号',url('User/setMobile',['source'=>'paypwd']));
        $step = input('step', 1);
        if ($step > 1) {
            $check = session('validate_code');
            if (empty($check)) {
                $this->error('验证码还未验证通过', url('mobile/User/paypwd'));
            }
        }
        if (IS_POST && $step == 2) {
            $new_password = trim(input('new_password'));
            $confirm_password = trim(input('confirm_password'));
            $oldpaypwd = trim(input('old_password'));
            //以前设置过就得验证原来密码
            if(!empty($user['paypwd']) && ($user['paypwd'] != encrypt($oldpaypwd))){
                //$this->ajaxReturn(['status'=>-1,'msg'=>'原密码验证错误！','result'=>'']);
            }
            $userLogic = new UsersLogic();
            $data = $userLogic->paypwd($this->user_id, $new_password, $confirm_password);
            $this->ajaxReturn($data);
            exit;
        }
        View::assign('sms',tpCache('sms'));
        View::assign('step', $step);
        return View::fetch();
    }


    /**
     * 会员签到积分奖励
     * 2017/9/28
     */
    public function sign()
    {
        $userLogic = new UsersLogic();
        $user_id = $this->user_id;
        $info = $userLogic->idenUserSign($user_id);//标识签到
        View::assign('info', $info);
        return View::fetch();
    }

    /**
     * Ajax会员签到
     * 2017/11/19
     */
    public function user_sign()
    {
        $userLogic = new UsersLogic();
        $user_id   = $this->user_id;
        $config    = tpCache('sign');
        $date      = input('date'); //2017-9-29
        //是否正确请求
        (date("Y-n-j", time()) != $date) && $this->ajaxReturn(['status' => false, 'msg' => '签到失败！', 'result' => '']);
        //签到开关
        if ($config['sign_on_off'] > 0) {
            $map['sign_last'] = $date;
            $map['user_id']   = $user_id;
            $userSingInfo     = Db::name('user_sign')->where($map)->find();
            //今天是否已签
            $userSingInfo && $this->ajaxReturn(['status' => false, 'msg' => '您今天已经签过啦！', 'result' => '']);
            //是否有过签到记录
            $checkSign = Db::name('user_sign')->where(['user_id' => $user_id])->find();
            if (!$checkSign) {
                $result = $userLogic->addUserSign($user_id, $date);            //第一次签到
            } else {
                $result = $userLogic->updateUserSign($checkSign, $date);       //累计签到
            }
            $return = ['status' => $result['status'], 'msg' => $result['msg'], 'result' => ''];
        } else {
            $return = ['status' => false, 'msg' => '该功能未开启！', 'result' => ''];
        }
        $this->ajaxReturn($return);
    }


    /**
     * vip充值
     */
    public function rechargevip(){
        $paymentList = Db::name('Plugin')->where("`type`='payment' and code!='cod' and status = 1 and  scene in(0,1)")->select();
        //微信浏览器
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            $paymentList = Db::name('Plugin')->where("`type`='payment' and status = 1 and code='weixin'")->select();
        }
        $paymentList = convert_arr_key($paymentList, 'code');

        foreach ($paymentList as $key => $val) {
            $val['config_value'] = unserialize($val['config_value']);
            if ($val['config_value']['is_bank'] == 2) {
                $bankCodeList[$val['code']] = unserialize($val['bank_code']);
            }
        }
        $bank_img = include APP_PATH . 'home/bank.php'; // 银行对应图片
        $payment = Db::name('Plugin')->where("`type`='payment' and status = 1")->select();
        View::assign('paymentList', $paymentList);
        View::assign('bank_img', $bank_img);
        View::assign('bankCodeList', $bankCodeList);
        return View::fetch();
    }


    /**
     * 个人海报推广二维码 （我的名片）
     */
    public function qr_code()
    {
        $user_id = $this->user['user_id'];
        if (!$user_id) {
            return View::fetch();
        }
        //判断是否是分销商
          $user = Db::name('users')->where('user_id', $user_id)->find();
//        if (!$user && $user['is_distribut'] != 1) {
//            return View::fetch();
//        }

        //判断是否存在海报背景图
        if(!DB::name('poster')->where(['enabled'=>1])->find()){
            $this->error('平台为设置海报封面',url('mobile/user/index'));
        }

            //分享数据来源
            $shareLink = urlencode("http://{$_SERVER['HTTP_HOST']}/index.php?m=Mobile&c=Index&a=index&first_leader={$user['user_id']}");

        $head_pic = $user['head_pic'] ?: '';
        if ($head_pic && strpos($head_pic, 'http') !== 0) {
            $head_pic = '.'.$head_pic;
            if(!file_exists($head_pic)){
                $head_pic = '';
            }
        }
        // 图片带有&的参数处理法
        if(strpos($head_pic,'&')){
            $head_pic = urlencode($head_pic);
        }
        View::assign('user',  $user);
        View::assign('head_pic', $head_pic);
        View::assign('ShareLink', $shareLink);
        return View::fetch();
        //$this->poster_qrcode($shareLink,$head_pic);
    }

    // 用户海报二维码
    public function poster_qrcode()
    {
        ob_end_clean();
        include_once '../vendor/topthink/think-image/src/Image.php';
        include_once '../vendor/phpqrcode/phpqrcode.php';

        error_reporting(E_ALL);
        $url = isset($_GET['data']) ? $_GET['data'] : '';
        $url = urldecode($url);

        $poster = DB::name('poster')->where(['enabled'=>1])->find();
        define('IMGROOT_PATH', str_replace("\\","/",realpath(dirname(dirname(__FILE__)).'/../../'))); //图片根目录（绝对路径）
        $project_path = '/public/upload/poster/'.input('_saas_app','all');  // 原是 /public/images，linux可能无权限
        $file_path = IMGROOT_PATH.'/public/public'.$project_path;

        if(!is_dir($file_path)){
            mkdir($file_path,777,true);
        }

        $head_pic = input('get.head_pic', '');                   //个人头像
        $head_pic = urldecode($head_pic);
        $head_pic = str_replace('&amp;', '&', $head_pic);        //图片带有&的参数处理法
        $back_img = IMGROOT_PATH.'/public/public'.$poster['back_url'];            //海报背景
        $valid_date = input('get.valid_date', 0);                //有效时间

        $qr_code_path = UPLOAD_PATH.'qr_code/';
        if (!file_exists($qr_code_path)) {
            mkdir($qr_code_path,777,true);
        }

        /* 生成二维码 */
        $qr_code_file = $qr_code_path.time().rand(1, 10000).'.png';
        $size = floor($poster['qrcode_size']/37*100)/100 + 0.01;
        \QRcode::png($url, $qr_code_file, QR_ECLEVEL_M,$size,2);
		//将二维码大小从px转到函数需要的数值,考虑到生成的二维码还有空白的外边距，这里生成的二维码比实际设置的要大，所以要在下面的imagecopyresampled函数进行缩放

        /* 二维码叠加水印 */
        $QR = \think\image\Image::open($qr_code_file);
        $QR_width = $QR->width();
        $QR_height = $QR->height();

        /* 添加头像 */
        if ($head_pic) {
            //如果是网络头像
            if (strpos($head_pic, 'http') === 0) {
                //下载头像
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $head_pic);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $file_content = curl_exec($ch);
                curl_close($ch);
                //保存头像
                if ($file_content) {
                    $head_pic_path = $qr_code_path.time().rand(1, 10000).'.png';
                    file_put_contents($head_pic_path, $file_content);
                    $head_pic = $head_pic_path;
                }
            }
            //如果是本地头像
            if (file_exists($head_pic)) {
                $logo = \think\image\Image::open($head_pic);
                $logo_width = $logo->height();
                $logo_height = $logo->width();
                $logo_qr_width = $QR_width / 4;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $logo_file = $qr_code_path.time().rand(1, 10000);
                $logo->thumb($logo_qr_width, $logo_qr_height)->save($logo_file, null, 100);
                $QR = $QR->thumb($QR_width, $QR_height)->water($logo_file, \think\image\Image::WATER_CENTER);
                $logo_file && unlink($logo_file);
                if(isset($head_pic_path)) unlink($head_pic_path); // 如果是网络头像的
            }
        }

        if ($valid_date && strpos($url, 'weixin.qq.com') !== false) {
            $QR = $QR->text('有效时间 '.$valid_date, "../vendor/topthink/think-captcha/assets/zhttfs/1.ttf", 7, '#00000000', Image::WATER_SOUTH);
        }
        $QR->save($qr_code_file, null, 100);

        $canvas_maxWidth = $poster['canvas_width'];
        $canvas_maxHeight = $poster['canvas_height'];
        $info = getimagesize($back_img);                                                           //取得一个图片信息的数组
        $im = checkPosterImagesType($info,$back_img);                                              //根据图片的格式对应的不同的函数
        $rate_poster_width = $canvas_maxWidth/$info[0];                                            //计算绽放比例
        $rate_poster_height = $canvas_maxHeight/$info[1];
        $maxWidth =  floor($info[0]*$rate_poster_width);
        $maxHeight = floor($info[1]*$rate_poster_height);                                          //计算出缩放后的高度
        $des_im = imagecreatetruecolor($maxWidth,$maxHeight);                                      //创建一个缩放的画布
        imagecopyresized($des_im,$im,0,0,0,0,$maxWidth,$maxHeight,$info[0],$info[1]);              //缩放
        $news_poster = $file_path.'/'.createImagesName() . ".png";                                 //获得缩小后新的二维码路径
        inputPosterImages($info,$des_im,$news_poster);                                             //输出到png即为一个缩放后的文件
        $QR = imagecreatefromstring(file_get_contents($qr_code_file));
        $background_img = imagecreatefromstring ( file_get_contents ( $news_poster ) );

        imagecopyresampled ( $background_img, $QR,$poster['canvas_x'],$poster['canvas_y'],0,0,$poster['qrcode_size'],$poster['qrcode_size'],$QR_width, $QR_height);      //合成图片
        $result_png = '/'.createImagesName(). ".png";
        $file = $file_path . $result_png;
        imagepng ($background_img, $file);                                                          //输出合成海报图片
        $final_poster = imagecreatefromstring ( file_get_contents (  $file ) );                     //获得该图片资源显示图片
        header("Content-type: image/png");
        imagepng ( $final_poster);
        imagedestroy( $final_poster);
        $news_poster && unlink($news_poster);
        $qr_code_file && unlink($qr_code_file);
        $file && unlink($file);
        exit;
    }

    /**
     * 用户帮助
     * @return mixed
     */
    public function userHelpList()
    {
        $cat_id = input('cat_id');
        if($cat_id){
            $where['cat_id'] = $cat_id;
        }
        $where['is_open'] = 1;
        $list = Db::name('article')->where($where)->order(['cat_id'=>'asc','article_id'=>'desc'])->column('article_id,cat_id,title');
        if($list){
            $list = array_values($list);
        }
//                dump($list);exit;
        View::assign('list', $list);
        return View::fetch();
    }

    public function userHelpInfo()
    {
        $article_id = input('article_id');
        if($article_id){
            $where['article_id'] = $article_id;
        }
        $article = Db::name('article')->where($where)->find();
        View::assign('article', $article);
        return View::fetch();
    }

    /**
     * 短信验证码快捷登录(账号不存在就注册一个账号，并绑定上下级关系)
     * @param mobile  int 手机号码
     * @param code string  短信验证码
     * @return result object  返回账号信息
     */
    public function login_mobile(){
        $mobile = I('mobile/s','');
        $code   = I('code/s','');
        $session_id = I('unique_id', session_id());
        if(!check_mobile($mobile)){
            $this->ajaxReturn(['status'=>-1,'msg'=>'手机号码有误！','result'=>[]]);
        }
        $userLogic = new UsersLogic();
        $res = $userLogic->check_validate_code($code, $mobile  , 'mobile' , $session_id , 6);
        if($res['status'] != 1) exit(json_encode($res));
        //先判断是否是已存在的账号（登录）
        $userInfo = Db::name('users')->where(['mobile'=>$mobile])->find();
        if($userInfo){//登录
            //修改token 可以不修改，
            $updateUser['token'] = md5(time().mt_rand(1,999999999));
            Db::name('users')->where(['user_id'=>$userInfo['user_id']])->update($updateUser);
            $userInfo['token'] = $updateUser['token'];//最新的用户信息
        }else{//注册
            $result = $userLogic->reg($mobile,'','','',[],$nickname="",$head_pic="");
            if($result['status'] != 1){//注册失败
                $this->ajaxReturn($result);
            }
            $userInfo = $result['result'];//最新的用户信息
        }
        $redirect_url = 'Admin/User/index';
        if(session('?redirect_url')){
            $redirect_url =  session('redirect_url');
        }
        session('user', $userInfo);
        setcookie('user_id', $userInfo['user_id'], null, '/');
        setcookie('is_distribut', $userInfo['is_distribut'], null, '/');
        $nickname = empty($userInfo['nickname']) ? $mobile : $userInfo['nickname'];
        setcookie('uname', urlencode($nickname), null, '/');
        setcookie('cn', 0, time() - 3600, '/');
        setcookie('head_pic', $userInfo['head_pic'], null, '/');
        setcookie('token',$userInfo['token'], null, '/');
        $userLogic->LoginOperation($userInfo['user_id'],$session_id);
        $this->ajaxReturn(['status'=>1,'msg'=>'登录成功！','result'=>$userInfo,'redirect_url'=>urldecode($redirect_url)]);
    }


    /**
     * 设置初始密码
     * @param  password string 密码
     * @return data obj 返回结果
     */
    public function initialPassword(){
        $password = I('post.password','');
        $userLogic = new UsersLogic();
        try{
            $userLogic->initialPassword($this->user,$password);
            $this->ajaxReturn(['status'=>1,'msg'=>'设置成功！','redirect_url'=>urldecode(session('redirect_url'))]);
        }catch (TpshopException $e){
            $this->ajaxReturn($e->getErrorArr());
        }
    }

    /**
     * 重置密码密码
     * @param  password string 密码
     * @return data obj 返回结果
     */
    public function setPassword(){
        $username = I('post.username/s','');
        $password = I('post.password/s','');
        $session_id = I('unique_id', session_id());
        $data['password'] = $password;
        $data['password2'] = $password;
        $UserRegvalidate = Loader::validate('User');
        if(!$UserRegvalidate->scene('set_pwd')->check($data)){
            $this->ajaxReturn(['status'=>0,'msg'=>$UserRegvalidate->getError()]);
        }
        $userInfo = Db::name('users')->where(['mobile|email'=>$username])->find();
        if(!$userInfo){
            $this->ajaxReturn(['status'=>0,'msg'=>'会员账号不存在！']);
        }
        Db::name('users')->where("user_id", $userInfo['user_id'])->save(array('password' => encrypt($password)));
        session('validate_code', null);
        $redirect_url = '/Admin/User/index';
        if(session('?redirect_url')){
            $redirect_url =  urldecode(session('redirect_url'));
        }
        //重置成功默认登录 不需要登录的情况 注释下面代码
        $userLogic = new UsersLogic();
        session('user', $userInfo);
        setcookie('user_id', $userInfo['user_id'], null, '/');
        setcookie('is_distribut', $userInfo['is_distribut'], null, '/');
        $nickname = empty($userInfo['nickname']) ? $username : $userInfo['nickname'];
        setcookie('uname', urlencode($nickname), null, '/');
        setcookie('cn', 0, time() - 3600, '/');
        setcookie('head_pic', $userInfo['head_pic'], null, '/');
        setcookie('token',$userInfo['token'], null, '/');
        $userLogic->LoginOperation($userInfo['user_id'],$session_id);

        $this->ajaxReturn(['status'=>1,'msg'=>'密码重置成功！','redirect_url'=>$redirect_url ]);
    }

    /**
     * 检测短信验证码或者邮箱码是否正确
     * @param  username string 账号名称
     * @param  code string 验证码
     * @return data obj 返回结果  是否通过验证
     */
    public function checkCode(){
        $username = I('post.username','');
        $code     = I('post.code/d','');
        $session_id = I('unique_id', session_id());
        $userLogic = new UsersLogic();
        if(check_mobile($username)){//校验是否是电话
            $res = $userLogic->check_validate_code($code, $username  , 'mobile' , $session_id , 6);
        }elseif(check_email($username)){
            $res = $userLogic->check_validate_code($code, $username  , 'email' , $session_id );
        }else{
            $this->ajaxReturn(['status'=>0,'msg'=>'账号格式错误！']);
        }
        $this->ajaxReturn($res);

    }

}
