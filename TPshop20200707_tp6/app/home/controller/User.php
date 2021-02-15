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
namespace app\home\controller;
use think\facade\View; 
use app\common\logic\Message;
use app\common\logic\OrderLogic;
use app\common\logic\UsersLogic;
use app\common\logic\CartLogic;
use app\common\model\GoodsCollect;
use app\common\model\GoodsVisit;
use app\common\model\UserAddress;
use app\common\model\UserMessage;
use app\common\util\TpshopException;
use think\Loader;
use think\Page;
use think\facade\Session;
use think\Verify;
use think\facade\Db;
class User extends Base{

	public $user_id = 0;
	public $user = array();
	
    public function __construct() {
        //session('aaaaaaa','bbbbbbb');
        //echo session('aaaaaaa');
       // exit('aaaaa');        
        parent::__construct();
        if(session('?user'))
        {
            $session_user = session('user');
            $select_user = Db::name('users')->where("user_id", $session_user['user_id'])->find();
            $oauth_users = Db::name('oauth_users')->where(['user_id'=>$session_user['user_id']])->find();
            empty($oauth_users) && $oauth_users = [];
            empty($select_user) && $select_user = []; // 有时报第一个错
            $user =  array_merge($select_user,$oauth_users);

            $UsersLogic = new \app\common\logic\UsersLogic();
            $UsersLogic->checkUserWithdrawals($user);
            $user['cash_in'] =  Db::name('withdrawals')->where(['type'=>0, 'user_id'=>$user['user_id'],'status'=>['in',['0','1']]])->sum('money')?:'0.00'; //统计正在提现的余额


            session('user',$user);  //覆盖session 中的 user
        	$this->user = $user;
        	$this->user_id = $user['user_id'];
        	View::assign('user',$user); //存储用户信息
        	View::assign('user_id',$this->user_id);
            //获取用户信息的数量
            $messageLogic = new Message();
            $user_message_count = $messageLogic->getUserMessageNoReadCount();
            View::assign('user_message_count', $user_message_count);
        }
        $nologin = array(
                'login','pop_login','do_login','logout','verify','set_pwd','finished',
                'verifyHandle','reg','send_sms_reg_code','identity','check_validate_code',
            'forget_pwd', 'check_captcha', 'check_username', 'send_validate_code','bind_account','bind_guide','bind_reg',
        );
        if(!$this->user_id && !in_array(ACTION_NAME,$nologin)){
            //return redirect('Home/User/login');      
            $this->error('请先登录', url('/Home/user/login'), null, 1);
        }

        //用户中心面包屑导航
        $navigate_user = navigate_user();
        View::assign('navigate_user',$navigate_user);
    }

    /*
     * 用户中心首页
     */
    public function index(){       
        $logic = new UsersLogic();
        $user = $logic->get_info($this->user_id);
        $user = $user['result'];
        $level = Db::name('user_level')->select();
        $level = convert_arr_key($level,'level_id');
        $coupon = $logic ->get_coupon($this->user_id,'','','',$p=2);
        $order = new \app\common\model\Order();
        $order_list = $order->where(['user_id'=>$user['user_id'],'prom_type'=>['<',5]])->whereOr(['prom_type'=>7])->limit(1)->order('order_id desc')->select();
        View::assign('coupon',$coupon['result']);
        View::assign('level',$level);
        View::assign('user',$user);
        View::assign('order_list',$order_list);
        return View::fetch();
    }


    public function logout(){
    	setcookie('uname','',time()-3600,'/');
    	setcookie('cn','',time()-3600,'/');
    	setcookie('user_id','',time()-3600,'/');
        setcookie('PHPSESSID','',time()-3600,'/');
        session_unset();
        session(null);
        //$this->success("退出成功",url('Home/Index/index'));
        return redirect('/Home/Index/index');
        exit;
    }

    /*
     * 账户资金
     */
    public function account(){
        $user = session('user');
        $type = input('type');
        $order_sn = input('order_sn');
        $logic = new UsersLogic();
        $data = $logic->get_account_log($this->user_id,$type,$order_sn);
        $account_log = $data['result'];
        View::assign('user',$user);
        View::assign('account_log',$account_log);
        View::assign('page',$data['show']);
        View::assign('active','account');
        return View::fetch();
    }
    /*
     * 优惠券列表
     */
    public function coupon(){
        $logic = new UsersLogic();
        $data = $logic->get_coupon($this->user_id,input('type'));
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
        View::assign('coupon_list',$coupon_list);
        View::assign('page',$data['show']);
        View::assign('active','coupon');
        return View::fetch();
    }
    /**
     *  登录
     */
    public function login(){
        if($this->user_id > 0){
            return redirect('/Home/User/index');
        }
        $redirect_url = Session::get('redirect_url');
        $referurl = $redirect_url ? $redirect_url : url("Home/User/index");
        View::assign('referurl',$referurl);
        return View::fetch();
    }

    public function pop_login(){
    	if($this->user_id > 0){
            return redirect('/Home/User/index');
    	}
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url("Home/User/index");
        session('redirect_url',$referurl);
        View::assign('referurl',$referurl);
    	return View::fetch();
    }
    
    public function do_login(){
        $username = trim(input('post.username'));
        $password = trim(input('post.password'));
    	$verify_code = input('post.verify_code');
     
        $verify = new Verify();
        if (!$verify->check($verify_code,'user_login'))
        {
             $res = array('status'=>0,'msg'=>'验证码错误');
             exit(json_encode($res));
        }
    	         
    	$logic = new UsersLogic();
    	$res = $logic->login($username,$password);

    	if($res['status'] == 1){
    		$res['url'] =  htmlspecialchars_decode(input('post.referurl'));
            if(session('?redirect_url')){
                $res['url'] =  session('redirect_url');
            }
    		session('user',$res['result']);
            setcookie('user_id',$res['result']['user_id'],null,'/');
            setcookie('token',$res['result']['token'],null,'/');
    		setcookie('is_distribut',$res['result']['is_distribut'],null,'/');
    		$nickname = empty($res['result']['nickname']) ? $username : $res['result']['nickname'];
            setcookie('uname',urlencode($nickname),null,'/');
            setcookie('cn',0,time()-3600,'/');
    		$cartLogic = new CartLogic();
            $cartLogic->setUserId($res['result']['user_id']);
            $cartLogic->doUserLoginHandle();// 用户登录后 需要对购物车 一些操作
            $orderLogic = new OrderLogic();
            $orderLogic->setUserId($res['result']['user_id']); //登录后将超时未支付订单给取消掉
            $orderLogic->abolishOrder();
    	}
    	exit(json_encode($res));
    }
    /**
     *  注册
     */
    public function reg(){
    	if($this->user_id > 0){
            return redirect('/Home/User/index');
        }
        $reg_sms_enable = tpCache('sms.regis_sms_enable');
        $reg_smtp_enable = tpCache('smtp.regis_smtp_enable');
        if(IS_POST){
            $logic = new UsersLogic();
            //验证码检验
//            $this->verifyHandle('user_reg');
            $username = input('post.username','');
            $password = input('post.password','');
            $password2 = input('post.password2','');
            $code = input('post.code','');
            $scene = input('post.scene', 1);
            $session_id = session_id();
            if(check_mobile($username)){
                if($reg_sms_enable){   //是否开启注册验证码机制
                    //手机功能没关闭
                    $check_code = $logic->check_validate_code($code, $username, 'phone', $session_id, $scene);
                    if($check_code['status'] != 1){
                        $this->ajaxReturn($check_code);
                    }
                }else{
                    if(!$this->verifyHandle('user_reg')){
                        $this->ajaxReturn(['status'=>-1,'msg'=>'图像验证码错误']);
                    };
                }
            }
            if(check_email($username)){
                if($reg_smtp_enable){        //是否开启注册邮箱验证码机制
                    //邮件功能未关闭
                    $check_code = $logic->check_validate_code($code, $username);
                    if($check_code['status'] != 1){
                        $this->ajaxReturn($check_code);
                    }
                }else{
                    if(!$this->verifyHandle('user_reg')){
                        $this->ajaxReturn(['status'=>-1,'msg'=>'图像验证码错误']);
                    };
                }
            }
            $invite = input('invite');
            if(!empty($invite)){
            	$invite = get_user_info($invite,2);//根据手机号查找邀请人
            }
            $data = $logic->reg($username,$password,$password2,0,$invite);
            if($data['status'] != 1){
                $this->ajaxReturn($data);
            }
            if(session('?redirect_url')){
                $data['url'] = session('redirect_url');
            }else{
                $data['url'] = '';
            }
            session('user',$data['result']);
    		setcookie('user_id',$data['result']['user_id'],null,'/');
    		setcookie('is_distribut',$data['result']['is_distribut'],null,'/');
            $nickname = empty($data['result']['nickname']) ? $username : $data['result']['nickname'];
            setcookie('uname',$nickname,null,'/');
            $cartLogic = new CartLogic();
            $cartLogic->setUserId($data['result']['user_id']);
            $cartLogic->doUserLoginHandle();// 用户登录后 需要对购物车 一些操作
            $this->ajaxReturn($data);
            exit;
        }
        $doc_title = Db::name('system_article')->where('doc_code', 'agreement')->value('doc_title');
        View::assign('regis_sms_enable',tpCache('sms.regis_sms_enable')); // 注册启用短信：
        View::assign('regis_smtp_enable',tpCache('smtp.regis_smtp_enable')); // 注册启用邮箱：
        $sms_time_out = tpCache('sms.sms_time_out')>0 ? tpCache('sms.sms_time_out') : 120;
        View::assign('sms_time_out', $sms_time_out); // 手机短信超时时间
        View::assign('doc_title', $doc_title);
        return View::fetch();
    }

    /*
     * 用户地址列表
     */
    public function address_list(){
        $address_lists = get_user_address_list($this->user_id);
        $region_list = get_region_list();
        View::assign('region_list',$region_list);
        View::assign('lists',$address_lists);
        View::assign('active','address_list');

        return View::fetch();
    }

    public function address()
    {
        $address_id = input('address_id/d',0);
        $userAddress = UserAddress::where(['address_id'=>$address_id,'user_id'=> $this->user_id])->find();
        if(empty($userAddress)){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $city_list = Db::name('region')->where('parent_id',$userAddress['province'])->select();
        $district_list = Db::name('region')->where('parent_id',$userAddress['city'])->select();
        $twon_list = Db::name('region')->where('parent_id',$userAddress['district'])->select();
        $this->ajaxReturn(['status' => 1, 'msg' => '获取成功','result'=>['user_address'=>$userAddress,'city_list'=>$city_list,'district_list'=>$district_list,'twon_list'=>$twon_list]]);
    }
    /**
     * 设置默认收货地址 与多商城保持一致
     */
    public function setAddressDefault()
    {
        $id = input('id/d');
        Db::name('user_address')->where(['user_id'=>$this->user_id])->update(['is_default' => 0]);
        $row = Db::name('user_address')->where(array('user_id' => $this->user_id, 'address_id' => $id))->update(array('is_default' => 1));
        if ($row !== false){
            $this->ajaxReturn(['status'=>1,'msg'=>'设置成功','result'=>'']);
        }else{
            $this->ajaxReturn(['status'=>0,'msg'=>'设置失败','result'=>$row]);
        }
    }

    /**
     * 保存地址
     */
    public function addressSave()
    {
        $address_id = input('address_id/d',0);
        $data = input('post.');
        $data['twon'] = input('twon/d',0);
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
        $userAddress->data($data, true);
        $userAddress['longitude'] = true;
        $userAddress['latitude'] = true;
        $row = $userAddress->save();
        if ($row !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败']);
        }
    }

    /**
     * 设置默认地址
     */
    public function addressSetDefault()
    {
        $address_id = input('address_id/d', 0);
        $userAddress = UserAddress::where(['address_id'=>$address_id,'user_id'=> $this->user_id])->find();
        if(empty($userAddress)){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        Db::name('user_address')->where('user_id',$this->user_id)->save(['is_default'=>0]);
        $row = $userAddress->save(['is_default'=>1]);
        if ($row !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败']);
        }
    }

    /**
     * 地址删除
     */
    public function addressDelete()
    {
        $address_id = input('address_id/d', 0);
        $deleteAddress = Db::name('user_address')->where(['address_id'=>$address_id,'user_id'=> $this->user_id])->find();
        if(empty($deleteAddress)){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        // 如果删除的是默认收货地址 则要把第一个地址设置为默认收货地址
        if($deleteAddress['is_default'] == 1)
        {
            $addressDefault = UserAddress::where(['user_id'=> $this->user_id])->find();
            if($addressDefault){
                $addressDefault->save(['is_default'=>1]);
            }
        }
        $row = Db::name('user_address')->where('address_id',$deleteAddress['address_id'])->delete();
        if ($row !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败']);
        }
    }


    /*
     * 个人信息
     */
    public function info(){
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        if(IS_POST){
            input('post.nickname') ? $post['nickname'] = input('post.nickname') : false; //昵称
            input('post.qq') ? $post['qq'] = input('post.qq') : false;  //QQ号码
            input('post.head_pic') ? $post['head_pic'] = input('post.head_pic') : false; //头像地址
            input('post.sex') ? $post['sex'] = input('post.sex') : $post['sex'] = 0;  // 性别
            input('post.birthday') ? $post['birthday'] = strtotime(input('post.birthday')) : false;  // 生日
            input('post.province') ? $post['province'] = input('post.province') : false;  //省份
            input('post.city') ? $post['city'] = input('post.city') : false;  // 城市
            input('post.district') ? $post['district'] = input('post.district') : false;  //地区
            if(!$userLogic->update_info($this->user_id,$post))
                $this->error("保存失败");
            setcookie('uname',urlencode($post['nickname']),null,'/');
            $this->success("操作成功");
            exit;
        }
        //  获取省份
        $province = Db::name('region')->where(array('parent_id'=>0,'level'=>1))->select();
        //  获取订单城市
        $city =  Db::name('region')->where(array('parent_id'=>$user_info['province'],'level'=>2))->select();
        //获取订单地区
        $area =  Db::name('region')->where(array('parent_id'=>$user_info['city'],'level'=>3))->select();

        View::assign('province',$province);
        View::assign('city',$city);
        View::assign('area',$area);
        View::assign('user',$user_info);
        View::assign('sex',config('SEX'));
        View::assign('active','info');
        return View::fetch();
    }

    /*
     * 邮箱验证
     */
    public function email_validate(){
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        $step = input('get.step',1);
        if(IS_POST){
            $email = input('post.email');
            $old_email = input('post.old_email',''); //旧邮箱
            $code = input('post.code');
            $info = session('validate_code');
            if(!$info)
                $this->error('非法操作');
            if($info['time']<time()){
            	session('validate_code',null);
            	$this->error('验证超时，请重新验证');
            }
            //检查原邮箱是否正确
            if($user_info['email_validated'] == 1 && $old_email != $user_info['email'])
                $this->error('原邮箱匹配错误');
            //验证邮箱和验证码
            if($info['sender'] == $email && $info['code'] == $code){
                session('validate_code',null);
                if(!$userLogic->update_email_mobile($email,$this->user_id))
                    $this->error('邮箱已存在');
                $this->success('绑定成功',url('Home/User/index'));
                exit;
            }
            $this->error('邮箱验证码不匹配');
        }
        View::assign('user_info',$user_info);
        View::assign('step',$step);
        return View::fetch();
    }


    /**
     * 手机验证
     * @return mixed
     */
    public function mobile_validate()
    {
        $user_info = $this->user;
        $config = tpCache('sms');
        $sms_time_out = $config['sms_time_out'];
        View::assign('time', $sms_time_out);
        if (IS_POST) {
            $old_mobile = input('post.old_mobile');
            $code = input('post.code');
            $scene = input('post.scene', 6);
            $session_id = input('unique_id', session_id());

            $logic = new UsersLogic();
            $res = $logic->check_validate_code($code, $old_mobile, 'phone', $session_id, $scene);

            if (!$res && $res['status'] != 1) $this->error($res['msg']);

            //检查原手机是否正确
            if ($user_info['mobile_validated'] == 1 && $old_mobile != $user_info['mobile'])
                $this->error('原手机号码错误');
            //验证手机和验证码
            if ($res['status'] == 1) {
                return View::fetch('set_mobile');
            } else {
                $this->error($res['msg']);
            }
        }
        View::assign('user_info', $user_info);
        if (empty($user_info['mobile'])){
            return View::fetch('set_mobile');
        }
        return View::fetch();
    }

    /**
     * 设置新手机
     * @return mixed
     */
    public function set_mobile()
    {
        $userLogic = new UsersLogic();
        $mobile = input('post.mobile');
        $code = input('post.code');
        $scene = input('post.scene', 6);
        $session_id = input('unique_id', session_id());
        $logic = new UsersLogic();
        $res = $logic->check_validate_code($code, $mobile, 'phone', $session_id, $scene);
        //验证手机和验证码
        if ($res['status'] == 1) {
            //验证有效期
            if (!$userLogic->update_email_mobile($mobile, $this->user_id, 2)){
                $this->ajaxReturn(['status'=>-1,'msg'=>'手机已存在']);
            }else{
                $this->ajaxReturn(['status'=>1,'msg'=>'修改成功']);
            }
            exit;
        } else {
            $this->ajaxReturn(['status'=>-1,'msg'=>$res['msg']]);
        }

    }

    /*
     *商品收藏
     */
    public function goods_collect(){
        $userLogic = new UsersLogic();
        $data = $userLogic->get_goods_collect($this->user_id);
        View::assign('page',$data['show']);// 赋值分页输出
        View::assign('lists',$data['result']);
        View::assign('active','goods_collect');
        return View::fetch();
    }

    /*
     * 删除一个收藏商品
     */
    public function delGoodsCollect(){
        $ids = trim(input('get.ids',''),',');
        if(!$ids)$this->ajaxReturn(['status'=>-1,'msg'=>"请选择商品"]);
        $row = Db::name('goods_collect')->where(['user_id'=>$this->user_id,'collect_id'=>['in',$ids]])->delete();
        if(!$row)$this->ajaxReturn(['status'=>-1,'msg'=>'删除失败']);
        $this->ajaxReturn(['status'=>1,'msg'=>'删除成功','url'=>url('User/goods_collect')]);
    }

    /*
     * 密码修改
     */
    public function password(){
        //检查是否第三方登录用户
        $logic = new UsersLogic();
        $data = $logic->get_info($this->user_id);
        $user = $data['result'];
        if($user['mobile'] == ''&& $user['email'] == '')
            $this->error('请先绑定手机或邮箱',url('Home/User/info'));
        if(IS_POST){
            $userLogic = new UsersLogic();
            $data = $userLogic->password($this->user_id,input('post.old_password'),input('post.new_password'),input('post.confirm_password')); // 获取用户信息
            if($data['status'] == -1)
                $this->error($data['msg']);
            $this->success($data['msg']);
            exit;
        }
        return View::fetch();
    }

    public function forget_pwd()
    {
        if ($this->user_id > 0) {
            header("Location: " . url('Home/User/Index'));
        }
        if (IS_POST) {
            $username = input('username');
            if (!empty($username)) {
                $field = 'mobile';
                if (check_email($username)) {
                    $field = 'email';
                }
                $user = Db::name('users')->where("email", $username)->whereOr('mobile', $username)->find();
                
                if ($user) {
                    session('find_password', array('user_id' => $user['user_id'], 'username' => $username,
                        'email' => $user['email'], 'mobile' => $user['mobile'], 'type' => $field));
                    header("Location: " . url('User/identity'));
                    exit;
                } else {
                   echo "用户名不存在，请检查";
                    $this->error("用户名不存在，请检查");
                }
            }
        }
        return View::fetch();
    }
    
    public function set_pwd(){
    	if($this->user_id > 0){
            return redirect('/Home/User/Index');
    	}
    	$check = session('validate_code');
    	$logic = new UsersLogic();
    	if(empty($check)){
            return redirect('/Home/User/forget_pwd');
    	}elseif($check['is_check']==0){
    		$this->error('验证码还未验证通过',url('Home/User/forget_pwd'));
    	}    	
    	if(IS_POST){
    		$password = input('post.password');
    		$password2 = input('post.password2');
//    		if($password2 != $password){
//    			$this->error('两次密码不一致',url('Home/User/forget_pwd'));
//    		}
            $data['password'] =  input('post.password');
            $data['password2'] =  input('post.password2');
            $UserRegvalidate = validate(\app\common\validate\User::class);

            if(!$UserRegvalidate->scene('set_pwd')->batch(false)->check($data)){
                $this->error($UserRegvalidate->getError(),url('User/forget_pwd'));
            }
    		if($check['is_check']==1){
    			//$user = get_user_info($check['sender'],1);
                $user = Db::name('users')->where("mobile|email", '=', $check['sender'])->find();
    			Db::name('users')->where("user_id", $user['user_id'])->save(array('password'=>encrypt($password)));
    			session('validate_code',null);
                return redirect('/Home/User/finished');
    		}else{
    			$this->error('验证码还未验证通过',url('Home/User/forget_pwd'));
    		}
    	}
    	return View::fetch();
    }
    
    public function finished(){
    	if($this->user_id > 0){
            return redirect('/Home/User/Index');
    	}
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
    
    public function bind_guide(){
        
        $data = session('third_oauth');
        View::assign("nickname", $data['nickname']);
        View::assign("oauth", $data['oauth']);
        View::assign("head_pic", $data['head_pic']);
        View::assign('store_name',tpCache('shop_info.store_name'));
        return View::fetch();
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
        if(empty($password)){
            $this->ajaxReturn(['status' => 0, 'msg' => '请输入密码']);
        }
        $logic = new UsersLogic();
        $check_code = $logic->check_validate_code($verify_code, $mobile, 'phone', session_id(), 1);
        if($check_code['status'] != 1){
            $this->ajaxReturn(['status'=>0,'msg'=>$check_code['msg'],'result'=>'']);
        }
        $thirdUser = session('third_oauth');
        $data = $logic->reg($mobile, $password, $password, 0, [], $nickname, $thirdUser['head_pic']);
        if ($data['status'] != 1) {
            $this->ajaxReturn(['status'=>0,'msg'=>$data['msg'],'result'=>'']);
        }
        $user = new \app\common\logic\User();
        $user->setUserById($data['result']['user_id']);
        try{
            $user->checkOauthBind();
            $user->oauthBind();
            $user->refreshCookie();
            $this->ajaxReturn(['status' => 1, 'msg' => '绑定成功']);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }
    
    public function bind_auth()
    {
 
        $list = Db::name('plugin')->cache(true)->where(array('type' => 'login', 'status' => 1))->select();
        if ($list) {
            foreach ($list as $val) {
                $val['is_bind'] = 0;
                if($val['code'] == 'alipaynew'){
                    $val['code'] = 'alipay';
                }
                $where =[
                    'user_id'=>$this->user['user_id'] , 'oauth'=>$val['code']
                ];
                if($val['code'] == 'weixin'){
                    if(!isMobile()){
                        $where['oauth_child'] = 'open';
                    }else{
                        $where['oauth_child'] = 'mp';
                    }
                }

                $thridUser = Db::name('OauthUsers')->where($where)->find();
                //$thridUser = Db::name('OauthUsers')->where(array('user_id'=>$this->user['user_id'] , 'oauth'=>$val['code']))->find();
                if ($thridUser) {
                    $val['is_bind'] = 1;
                }
                $val['bind_url'] = url('LoginApi/login', array('oauth' => $val['code']));
                $val['bind_remove'] = url('User/bind_remove', array('oauth' => $val['code']));;
                $val['config_value'] = unserialize($val['config_value']);
                $lists[] = $val;
            }
        }
        View::assign('lists', $lists);
        return View::fetch();
    }

    public function bind_remove()
    {
//        $oauth = input('oauth'); // oauth_child
//        $row = Db::name('oauth_users')->where(array('user_id' => $this->user_id , 'oauth'=>$oauth))->delete();
        $row = Db::name('oauth_users')->where(array('user_id' => $this->user_id , 'oauth_child'=>'open'))->delete();

        if ($row) {
            $this->success('解除绑定成功', url('Home/User/bind_auth'));
        } else {
            $this->error('解除绑定失败', url('Home/User/bind_auth'));
        }
        
    }
    public function check_captcha(){
    	$verify = new Verify();
    	$type = input('post.type','user_login');
    	if (!$verify->check(input('post.verify_code'), $type)) {
    		exit(json_encode(0));
    	}else{
    		exit(json_encode(1));
    	}
    }
    
    public function check_username(){
    	$username = input('post.username');
    	if(!empty($username)){
    		$count = Db::name('users')->where("email", $username)->whereOr('mobile', $username)->count();
    		exit(json_encode(intval($count)));
    	}else{
    		exit(json_encode(0));
    	}  	
    }

    public function identity()
    {
        if ($this->user_id > 0) {
            header("Location: " . url('Home/User/Index'));
        }
        $user = session('find_password');
        if (empty($user)) {
            $this->error("请先验证用户名", url('User/forget_pwd'));
        }
        View::assign('userinfo', $user);
        return View::fetch();
    }
      
    /**
     * 验证码验证
     * $id 验证码标示
     */
    private function verifyHandle($id)
    {
        $verify = new Verify();
        $result = $verify->check(input('post.verify_code'), $id ? $id : 'user_login');
        if (!$result) {
            return false;
        }else{
            return true;
        }
    }

    /**
     * 验证码获取
     */
    public function verify()
    {
        //验证码类型
        $type = input('get.type') ? input('get.type') : 'user_login';
        $config = array(
            'fontSize' => 40,
            'length' => 4,
            'useCurve' => false,
            'useNoise' => false,
        );
        $Verify = new Verify($config);
        $Verify->entry($type);
		exit();
    }

    /**
     * 安全设置
     */
    public function safety_settings()
    {
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];        
        View::assign('user',$user_info);
        return View::fetch();
    }

    //添加、编辑提现账号
    public function add_card(){
        $user_id=$this->user_id;
        $data=input('post.');
        //dump($data);exit();

        if($data['type']==0){
            $info['cash_alipay']=$data['card'];
        }
        if($data['type']==1){
            $info['cash_weixinpay']=$data['card'];
        }
        $info['realname']=$data['cash_name'];
        $info['user_id']=$user_id;
        $res=Db::name('user_extend')->where('user_id='.$user_id)->count();
        if($res){
            $res2=Db::name('user_extend')->where('user_id='.$user_id)->save($info);
        }else{
            if (!isset($info['cash_unionpay'])) {
                # code...cash_unionpay 需要默认值
                $info['cash_unionpay'] = '';
            }
            $res2=Db::name('user_extend')->insertGetId($info);
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'操作成功']);
    }
    
    /**
     * 申请提现记录
     */
    public function withdrawals(){
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

            if ( $data['money'] > $this->user['user_money']) {
                $this->ajaxReturn(['status'=>0, 'msg'=>"您有提现申请待处理，本次提现余额不足"]);
            }

            if ($cash['cash_open'] == 1) {
                if ($cash['service_ratio'] >= 100) {
                    $this->ajaxReturn(['status'=>0, 'msg'=>'手续费率配置必须小于100%！']);
                }
                $taxfee =  round($data['money'] * $cash['service_ratio'] / 100, 2);
                // 限手续费
                if ($cash['max_service_money'] > 0 && $taxfee > $cash['max_service_money']) {
                    $taxfee = $cash['max_service_money'];
                }
                if ($cash['min_service_money'] > 0 && $taxfee < $cash['min_service_money']) {
                    $taxfee = $cash['min_service_money'];
                }
                if ($taxfee >= $data['money']) {
                    $this->ajaxReturn(['status'=>0, 'msg'=>'手续费超过提现额度了！']);
                }
                $data['taxfee'] = $taxfee;


                // 每次限提现额度
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
                    $total_money2 = Db::name('withdrawals')->where(array( 'type'=>0, 'user_id' => $this->user_id, 'status' => $status, 'create_time' => $create_time))->sum('money');
                    if (($total_money2 + $data['money'] > $cash['count_cash'])) {
                        $total_money = $cash['count_cash'] - $total_money2;
                        if ($total_money <= 0) {
                            $this->ajaxReturn(['status'=>0, 'msg'=>"你今天累计提现额为{$total_money2},不能再提现了."]);
                        } else {
                            $this->ajaxReturn(['status'=>0, 'msg'=>"你今天累计提现额为{$total_money2}，最多可提现{$total_money}账户余额."]);
                        }
                    }
                }
                // 今天限申请次数
                if ($cash['cash_times'] > 0) {
                    $total_times = Db::name('withdrawals')->where(array('user_id' => $this->user_id, 'status' => $status, 'create_time' => $create_time, 'type'=>0))->count();
                    if ($total_times >= $cash['cash_times']) {
                        $this->ajaxReturn(['status'=>0, 'msg'=>"今天申请提现的次数已用完."]);
                    }
                }
            }else{
                $data['taxfee'] = 0;
            }

            if (Db::name('withdrawals')->insert($data)) {
                $this->ajaxReturn(['status'=>1,'msg'=>"已提交申请",'url'=>url('User/recharge',['type'=>2])]);
            } else {
                $this->ajaxReturn(['status'=>0,'msg'=>'提交失败,联系客服!']);
            }
        }

        //获取用户绑定openId 以mp为公众号的，open 开放平台的
        $oauthUsers = Db::name("OauthUsers")->where(['user_id'=>$this->user_id, 'oauth'=>'weixin','oauth_child'=>'mp'])->find();
        $openid = $oauthUsers['openid'];

        $user_extend=Db::name('user_extend')->where('user_id='.$this->user_id)->find();

        View::assign('user_extend',$user_extend);
        View::assign('cash_config', tpCache('cash'));//提现配置项
        View::assign('user_money', $this->user['user_money']);    //用户余额
        View::assign('openid',$openid);    //用户绑定的微信openid
        return View::fetch();
    }
    
   public  function recharge(){
   		if(IS_POST){
            $card_id=input('card_id',0);
            $shopping_card_discount_id=input('shopping_card_discount_id',0);
            $data['account'] = input('account');
            if($card_id>0){
                $card = Db::name('shopping_card_list')
                    ->field('c.*')
                    ->alias('l')
                    ->join('shopping_card c','c.id=l.cid')
                    ->where(['l.id'=>$card_id])
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
                }else{
                    $this->error('这张购物卡不能充值');
                }
            }
   			$user = session('user');
   			$data['user_id'] = $this->user_id;
   			$data['nickname'] = $user['nickname'];
   			$data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
   			$data['card_list_id']=$card_id;

   			$data['ctime'] = time();
   			$order_id = Db::name('recharge')->insertGetId($data);
   			if($order_id){
   				// $url = url('Payment/getPay',array('pay_radio'=>$_REQUEST['pay_radio'],'order_id'=>$order_id));
                // 为兼容微信支付
                $url = url('Payment/getPay') . '?order_id=' . $order_id . '&pay_radio=' . urlencode($_REQUEST['pay_radio']);
                return redirect($url);
   			}else{
   				$this->error('提交失败,参数有误!');
   			}
   		}
   		
	   	$paymentList = Db::name('Plugin')->where("`type`='payment' and code!='cod' and status = 1 and  scene in(0,2)")->select();
	   	$paymentList = convert_arr_key($paymentList, 'code');	   	
	   	foreach($paymentList as $key => $val)
	   	{
	   		$val['config_value'] = unserialize($val['config_value']);
	   		if($val['config_value']['is_bank'] == 2)
	   		{
	   			$bankCodeList[$val['code']] = unserialize($val['bank_code']);
	   		}
	   	}
	   	$bank_img = include APP_PATH.'home/bank.php'; // 银行对应图片
	   	View::assign('paymentList',$paymentList);
	   	View::assign('bank_img',$bank_img);
	   	View::assign('bankCodeList',$bankCodeList);

        $type = input('type');
        $Userlogic = new UsersLogic();
        if($type == 1){
            $result = $Userlogic->get_account_log($this->user_id);  //用户资金变动记录
        }else if($type == 2){
            $status =  config('WITHDRAW_STATUS');
            $status[2] = '提现成功';
            View::assign('status', $status);
            $result=$Userlogic->get_withdrawals_log($this->user_id);  //提现记录
        }else{
            View::assign('status', config('RECHARGE_STATUS'));
            $result=$Userlogic->get_recharge_log($this->user_id);  //充值记录
        }

        // 查找最近一次充值方式
        $recharge_arr = Db::name('recharge')->field('pay_code')->where('user_id', $this->user_id)
           ->order('order_id desc')->find();
        $alipay = 'alipay'; //默认支付宝支付
        if($recharge_arr){
            foreach ($paymentList as  $key=>$item) {
                if($key == $recharge_arr['pay_code']){
                    $alipay = $recharge_arr['pay_code'];
                }
             }
        }
        View::assign('alipay', $alipay);
        View::assign('page', $result['show']);
        View::assign('lists', $result['result']);
        return View::fetch();
   }

    /**
     *  用户消息通知
     * @author yhj
     * @time 2018-6-28
     */
    public function message_notice()
    {
        $message_logic = new Message();
        $message_logic->checkPublicMessage();

        $type = input('type', 2);
        $user_info = session('user');
        $where = array(
            'user_id' => $user_info['user_id'],
            'deleted' => 0,
            'category' => $type
        );
        $size = $type == 0 ? 4 : 3;
        $userMessage = new UserMessage();

        $count = $userMessage->where($where)->count();
        $page = new Page($count, $size);
        $show = $page->show();
        $rec_id = $userMessage->where( $where)->LIMIT($page->firstRow.','.$page->listRows)->order('rec_id desc')->column('rec_id');
        if(empty($rec_id) && empty($count)){
            $list = [];
        } else {
            // 当前分页数据删除完了，前一页还有数据
            if(empty($rec_id) && $count > 0){
                $rec_id = $userMessage->where( $where)->limit($size)->order('rec_id desc')->column('rec_id');
            }
            $list = $message_logic->sortMessageListBySendTime($rec_id, $type);
        }

        $no_read = $message_logic->getUserMessageCount();
        View::assign('no_read', $no_read);
        View::assign('page', $show);
        View::assign('list', $list);
        View::assign('count', $count);
        return View::fetch('user/message_notice');
    }

    /**
     *  用户消息详情
     * @author yhj
     * @time 2018-6-28
     */    
    public function message_details()
    {

        $message_logic = new Message();
        $data['message_details'] = $message_logic->getMessageDetails(input('msg_id'), input('type', 0));
        $data['no_read'] = $message_logic->getUserMessageCount();
        View::assign($data);        
        return View::fetch('user/message_details');
    }
    /**
     * ajax用户消息删除请求
     * @author yhj
     * @time 2018-6-28
     */
    public function deletedMessage()
    {
        $message_logic = new Message();
        $res = $message_logic->deletedMessage(input('msg_id'),input('type'));
        $this->ajaxReturn($res);
    }
    /**
     * ajax设置用户消息已读
     * @author yhj
     * @time 2018-6-28
     */
    public function setMessageForRead()
    {
        $message_logic = new Message();
        $res = $message_logic->setMessageForRead(input('msg_id'));
        $this->ajaxReturn($res);
    }

    /**
     * 支付密码
     * @return mixed
     */
    public function paypwd()
    {
        //检查是否第三方登录用户
        $logic = new UsersLogic();
        $data = $logic->get_info($this->user_id);
        $user = $data['result'];
        if(strrchr($_SERVER['HTTP_REFERER'],'/') =='/cart2.html'){  //用户从提交订单页来的，后面设置完有要返回去
            session('payPriorUrl',url('Mobile/Cart/cart2'));
        }
        if ($user['mobile'] == '')
            $this->error('请先绑定手机', url('User/mobile_validate',['source'=>'paypwd']));
        $step = input('step', 1);
        if ($step > 1) {
            $check = session('validate_code');
            if (empty($check)) {
                $this->error('验证码还未验证通过', url('Home/User/paypwd'));
            }
        }
        if (IS_POST && $step == 3) {
            $userLogic = new UsersLogic();
            $data = input('post.');
            $data = $userLogic->paypwd($this->user_id, input('new_password'), input('confirm_password'));
            if ($data['status'] == -1)
                $this->error($data['msg']);
            //$this->success($data['msg']);
            return redirect(url('Home/User/paypwd', array('step' => 3)));
            exit;
        }
        View::assign('step', $step);
        return View::fetch();
    }

    /**
     *  点赞
     * @author lxl
     * @time  17-4-20
     * 拷多商家Order控制器
     */
    public function ajaxZan()
    {
        $comment_id = input('post.comment_id/d');
        $user_id = $this->user_id;
        $comment_info = Db::name('comment')->where(array('comment_id' => $comment_id))->find();  //获取点赞用户ID
        $comment_user_id_array = explode(',', $comment_info['zan_userid']);
        if (in_array($user_id, $comment_user_id_array)) {  //判断用户有没点赞过
            $result['success'] = 0;
        } else {
            array_push($comment_user_id_array, $user_id);  //加入用户ID
            $comment_user_id_string = implode(',', $comment_user_id_array);
            $comment_data['zan_num'] = $comment_info['zan_num'] + 1;  //点赞数量加1
            $comment_data['zan_userid'] = $comment_user_id_string;
            Db::name('comment')->where(array('comment_id' => $comment_id))->save($comment_data);
            $result['success'] = 1;
        }
        exit(json_encode($result));
    }

    /**
     * 删除足迹
     * @author lxl
     * @time  17-4-20
     * 拷多商家User控制器
     */
    public function del_visit_log(){

        $visit_id = input('visit_id/d' , 0);
        $row = Db::name('goods_visit')->where(['visit_id'=>$visit_id])->delete();
        if($row>0){
            $this->ajaxReturn(['status'=>1 , 'msg'=> '删除成功']);
        }else{
            $this->ajaxReturn(['status'=>-1 , 'msg'=> '删除失败']);
        }
    }

    /**
     * 我的足迹
     * @author lxl
     * @time  17-4-20
     * 拷多商家User控制器
     * */
    public function visit_log()
    {
        $cat_id = input('cat_id', 0);
        $map['user_id'] = $this->user_id;
        if ($cat_id > 0) $map['a.cat_id'] = $cat_id;
        $count_all = Db::name('goods_visit')->where(['user_id' => $this->user_id])->count();
        $count = Db::name('goods_visit')->where($map)->count();
        $Page = new Page($count, 20);
        $visit_list = Db::name('goods_visit')->alias('a')->field("a.*,g.goods_name,g.shop_price")
            ->join('goods g', 'a.goods_id = g.goods_id', 'LEFT')
            ->where($map)
            ->limit($Page->firstRow,$Page->listRows)
            ->order('a.visittime desc')
            ->select();
        $visit_log = $cates = array();
        $visit_total = 0;
        if ($visit_list) {
            $now = time();
            $endLastweek = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y'));
            $weekarray = array("日", "一", "二", "三", "四", "五", "六");
            foreach ($visit_list as $k => $val) {
                if ($now - $val['visittime'] < 3600 * 24 * 7) {
                    if (date('Y-m-d') == date('Y-m-d', $val['visittime'])) {
                        $val['date'] = '今天';
                    } else {
                        if ($val['visittime'] < $endLastweek) {
                            $val['date'] = "上周" . $weekarray[date("w", $val['visittime'])];
                        } else {
                            $val['date'] = "周" . $weekarray[date("w", $val['visittime'])];
                        }
                    }
                } else {
                    $val['date'] = '更早以前';
                }
                $visit_log[$val['date']][] = $val;
            }
            $cates = Db::name('goods_visit')->alias('a')->field('cat_id,COUNT(cat_id) as csum')->where($map)->group('cat_id')->select()->toArray();
            $cat_ids = get_arr_column($cates,'cat_id');
            $cateArr = Db::name('goods_category')->whereIN('id', array_unique($cat_ids))->column('name','id'); //收藏商品对应分类名称
            foreach ($cates as $k => $v) {
                if (isset($cateArr[$v['cat_id']])) $cates[$k]['name'] = $cateArr[$v['cat_id']];
                $visit_total += $v['csum'];
            }
        }
        View::assign('visit_total', $visit_total);
        View::assign('count', $count_all);
        View::assign('catids', $cates);
        View::assign('page', $Page->show());
        View::assign('visit_log', $visit_log); //浏览记录
        return View::fetch();
    }

    public function myCollect()
    {
        $item = input('item', 12);
        $goodsCollectModel = new GoodsCollect();
        $user_id = $this->user_id;
        $goodsList = $goodsCollectModel->with(['goods'])->where('user_id', $user_id)->limit($item)->order('collect_id', 'desc')->select();
        foreach($goodsList as $key=>$goods){
            $goodsList[$key]['url'] = $goods->url;
            $goodsList[$key]['imgUrl'] = goods_thum_images($goods['goods_id'], 160, 160);
        }
        if ($goodsList) {
            $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $goodsList]);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '没有记录', 'result' => '']);
        }
    }

    /**
     * 历史记录
     */
    public function historyLog(){
        $item = input('item', 12);
        $goodsCollectModel = new GoodsVisit();
        $user_id = $this->user_id;
        $goodsList = $goodsCollectModel->with(['goods'])->where('user_id', $user_id)->limit($item)->order('visit_id', 'desc')->select();
        foreach($goodsList as $key=>$goods){
            $goodsList[$key]['url'] = $goods->url;
            $goodsList[$key]['imgUrl'] = goods_thum_images($goods['goods_id'], 160, 160);
        }
        if ($goodsList) {
            $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $goodsList]);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '没有记录', 'result' => '']);
        }
    }

    /**
     * vip充值
     */
    public function rechargevip(){
        if (IS_POST) {
            $user = session('user');
            $map['user_id'] = $user['user_id'];
            $map['buy_vip'] = 1;
            $map['pay_status'] = 1;
            $info = Db::name('recharge')->where($map)->order('order_id desc')->find();
            if (($info['pay_time'] + 86400 * 365) > time() && $user['is_vip'] == 1) {
            	$this->error('您已是VIP且未过期，无需重复充值办理该业务！');
            }

            $data['user_id']    = $this->user_id;
            $data['nickname']   = $user['nickname'];
            $data['account']    = input('account');
            $data['order_sn']   = 'recharge' . get_rand_str(10, 0, 1);
            $data['buy_vip']    = 1;
            $data['ctime']  = time();
            $order_id = Db::name('recharge')->insertGetId($data);
            if ($order_id) {
                $url = url('Home/Payment/getPay', array('pay_radio' => $_REQUEST['pay_radio'], 'order_id' => $order_id));
                return redirect($url);
            } else {
                $this->error('提交失败,参数有误!');
            }
        }
        $paymentList = Db::name('Plugin')->cache(true)->where("`type`='payment' and code!='cod' and status = 1 and scene in(0,2)")->select();
        $paymentList = convert_arr_key($paymentList, 'code');
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
        return View::fetch();
    }

    public function check_mobile(){
        $mobile = input('mobile/s');
        if(strlen($mobile)<11){
            $this->ajaxReturn(['status'=>0,'msg'=>'长度不够']);
        }else if(strlen($mobile)>11)
        {
            $this->ajaxReturn(['status'=>0,'msg'=>'号码过长']);
        }else{
            if(check_mobile($mobile)){
                $mobile=Db::name('users')->where(['mobile'=>$mobile,'user_id'=>['<>',$this->user_id]])->find();
                if($mobile){
                    $this->ajaxReturn(['status'=>0,'msg'=>'这个手机号已被使用']);
                }else{
                    $this->ajaxReturn(['status'=>1,'msg'=>'可以使用']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'输入有误']);
            }
        }
    }
}