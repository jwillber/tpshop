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
 * Author: IT宇宙人
 * Date: 2015-09-09
 */

namespace app\common\logic;

use app\common\logic\wechat\MiniAppUtil;
use app\common\model\UserAddress;
use app\common\util\TpshopException;
use think\facade\Cache;
use think\Loader;
use think\Model;
use think\Page;
use think\facade\Db;
use think\Image;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class UsersLogic
{
    protected $user_id=0;

    /**
     * 设置用户ID
     * @param $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    /*
    * 登陆
    */
    public function login($username,$password)
    {
        if (!$username || !$password) {
            return array('status' => 0, 'msg' => '请填写账号或密码');
        }

        $user = Db::name('users')->where("mobile", $username)->whereOr('email', $username)->find();
        if (!$user) {
            $result = array('status' => -1, 'msg' => '账号不存在!');
        } elseif (encrypt($password) != $user['password']) {
            //登录密码错误的次数
            $password_error_num = Cache::get('password_error_num'.$username)+1;
            Cache::set('password_error_num'.$username,$password_error_num,1200);
            $result = array('status' => -2, 'msg' => '密码错误!','password_error_num'=>$password_error_num);
        } elseif ($user['is_lock'] == 1) {
            $result = array('status' => -3, 'msg' => '账号异常已被锁定！！！');
        } else {
            //是否清空积分
            $this->isEmptyingIntegral($user);
            //查询用户信息之后, 查询用户的登记昵称
            $levelId = $user['level'];
            $levelName = Db::name("user_level")->where("level_id", $levelId)->value('level_name');
            $user['level_name'] = $levelName;
            // 万一登录无token??
            if(empty($user['token'])){
                $save_data['token'] = md5(time().mt_rand(1,999999999));
                $user['token'] = $save_data['token'];
            }
            $save_data['last_login'] = time();
            Db::name('users')->where("user_id", $user['user_id'])->save($save_data);
            user_login($user['user_id']);//登录日志
            $result = array('status' => 1, 'msg' => '登陆成功', 'result' => $user);
        }
        return $result;
    }

    /*
     * app端登陆
     */
    public function app_login($username, $password, $capache, $push_id=0)
    {
    	$result = array();
        if(!$username || !$password)
           $result= array('status'=>0,'msg'=>'请填写账号或密码');
        $user = Db::name('users')->where("mobile|email","=",$username)->find();
        if(!$user){
           $result = array('status'=>-1,'msg'=>'账号不存在!');
        }elseif($password != $user['password']){
           $result = array('status'=>-2,'msg'=>'密码错误!');
        }elseif($user['is_lock'] == 1){
           $result = array('status'=>-3,'msg'=>'账号异常已被锁定！！！');
        }else{
            //是否清空积分           zengmm          2018/06/11
            $this->isEmptyingIntegral($user);
            //查询用户信息之后, 查询用户的登记昵称
            $levelId = $user['level'];
            $levelName = Db::name("user_level")->where("level_id", $levelId)->value('level_name');
            $user['level_name'] = $levelName;            
            $user['token'] = md5(time().mt_rand(1,999999999));
            $data = ['token' => $user['token'], 'last_login' => time()];
            $push_id && $data['push_id'] = $push_id;
            Db::name('users')->where("user_id", $user['user_id'])->save($data);
            $result = array('status'=>1,'msg'=>'登陆成功','result'=>$user);
        }
        return $result;
    }

    /*
     * app端登出
     */
    public function app_logout($token = '')
    {
        if (empty($token)){
            ajaxReturn(['status'=>-100, 'msg'=>'已经退出账户']);
        }

        $user = Db::name('users')->where("token", $token)->find();
        if (empty($user)) {
            ajaxReturn(['status'=>-101, 'msg'=>'用户不在登录状态']);
        }

        Db::name('users')->where(["user_id" => $user['user_id']])->save(['token' => '']);
        session(null);

        return ['status'=>1, 'msg'=>'退出账户成功'];;
    }

    //绑定账号
    public function oauth_bind($data = array())
    {
        if (!empty($data['openid'])) {
            return false;
        }

        $user = session('user');
        if (empty($data['oauth_child'])) {
            $data['oauth_child'] = '';
        }

        if (empty($data['unionid'])) {
            $column = 'openid';
            $open_or_unionid = $data['openid'];
        } else {
            $column = 'unionid';
            $open_or_unionid = $data['unionid'];
        }

        $where = [$column => $open_or_unionid];
        if ($column == 'openid') {
            $where['oauth'] = $data['oauth']; //unionid不需要加这个限制
        }

        $ouser = Db::name('Users')->alias('u')->field('u.user_id,o.tu_id')->join('OauthUsers o', 'u.user_id = o.user_id')->where($where)->find();
        if ($ouser) {
            //删除原来绑定
            Db::name('OauthUsers')->where('tu_id', $ouser['tu_id'])->delete();
        }
        //绑定账号
        return Db::name('OauthUsers')->save(array('oauth' => $data['oauth'], 'openid' => $data['openid'], 'user_id' => $user['user_id'], 'unionid' => $data['unionid'], 'oauth_child' => $data['oauth_child']));
    }
    
    //绑定账号
    public function oauth_bind_new($user = array())
    {
        $thirdOauth = session('third_oauth');
        
        $thirdName = ['weixin'=>'微信' , 'qq'=>'QQ' , 'alipay'=>'支付宝', 'miniapp' => '微信小程序'];
        
        //1.检查账号密码是否正确
        $ruser = Db::name('Users')->where(array('mobile'=>$user['mobile']))->find();
        if(empty($ruser)){
            return array('status'=>-1,'msg'=>'账号不存在','result'=>'');
        }
        
        if($ruser['password'] != $user['password']){
            return array('status'=>-1,'msg'=>'账号或密码错误','result'=>'');
        }
    
        //2.检查第三方信息是否完整
        $openid = $thirdOauth['openid'];   //第三方返回唯一标识
        $unionid = $thirdOauth['unionid'];   //第三方返回唯一标识
        $oauth = $thirdOauth['oauth'];      //来源
        $oauthCN = $platform = $thirdName[$oauth];
        if((empty($unionid) || empty($openid)) && empty($oauth)){
            return array('status'=>-1,'msg'=>'第三方平台参数有误[openid:'.$openid.' , unionid:'.$unionid.', oauth:'.$oauth.']','result'=>'');
        }
    
        //3.检查当前当前账号是否绑定过开放平台账号
        //1.判断一个账号绑定多个QQ
        //2.判断一个QQ绑定多个账号
        if($unionid){ 
            //如果有 unionid
            
            //1.1此oauth是否已经绑定过其他账号
            $thirdUser = Db::name('OauthUsers')->where(['unionid'=>$unionid, 'oauth'=> $oauth])->find();
            if($thirdUser && $ruser['user_id'] != $thirdUser['user_id'] ){ 
                return array('status'=>-1,'msg'=>'此'.$oauthCN.'已绑定其它账号','result'=>'');
            } 
            
            //1.2此账号是否已经绑定过其他oauth
            $thirdUser = Db::name('OauthUsers')->where(['user_id'=>$ruser['user_id'], 'oauth'=> $oauth])->find();
            if($thirdUser && $thirdUser['unionid'] != $unionid){         
                return array('status'=>-1,'msg'=>'此'.$oauthCN.'已绑定其它账号','result'=>'');
            }
         
        }else{
            //如果没有unionid
            
            //2.1此oauth是否已经绑定过其他账号
            $thirdUser = Db::name('OauthUsers')->where(['openid'=>$openid, 'oauth'=> $oauth])->find();
            if($thirdUser){ 
                return array('status'=>-1,'msg'=>'此'.$oauthCN.'已绑定其它账号','result'=>'');
            }
            
            //2.2此账号是否已经绑定过其他oauth
            $thirdUser = Db::name('OauthUsers')->where(['user_id'=>$ruser['user_id'], 'oauth'=> $oauth])->find();
            if($thirdUser){
                return array('status'=>-1,'msg'=>'此账号已绑定其它'.$oauthCN.'账号','result'=>'');
            } 
        }
       
        if(!isset($thirdOauth['oauth_child'])){
            $thirdOauth['oauth_child'] = '';
        }
        //4.账号绑定
        Db::name('OauthUsers')->save(array('oauth'=>$oauth , 'openid'=>$openid ,'user_id'=>$ruser['user_id'] , 'unionid'=>$unionid, 'oauth_child'=>$thirdOauth['oauth_child']));
        $ruser['token'] = md5(time().mt_rand(1,999999999));
        $ruser['last_login'] = time();
        
        Db::name('Users')->where('user_id' , $ruser['user_id'])->save(array('token'=>$ruser['token'] , 'last_login'=>$ruser['last_login']));
        
        return array('status'=>1,'msg'=>'绑定成功','result'=>$ruser);
       
         
    }

    /**
     * 获取第三方登录的用户
     * @param $openid
     * @param $unionid
     * @param $oauth
     * @param $oauth_child
     * @return array
     */
    private function getThirdUser($data,$user_id=0)
    {
        $user = [];
        $thirdUser = Db::name('oauth_users')->where(['openid' => $data['openid'], 'oauth' => $data['oauth']])->find();
        if (!$thirdUser) {
            if ($data['unionid']) {
                $thirdUser = Db::name('oauth_users')->where(['unionid' => $data['unionid']])->find();
                if ($thirdUser) {
                    $data['user_id'] = $thirdUser['user_id'];
                    Db::name('oauth_users')->insert($data);//补充其他第三方登录方式
                }elseif($user_id > 0){//直接绑定用户信息
                    $data['user_id'] = $user_id;
                    Db::name('oauth_users')->insert($data);
                    //更新用户信息
                    $updateData['nickname'] = $data['nickname'];
                    $updateData['head_pic'] = $data['head_pic'];
                    $updateData['unionid'] = $data['unionid'];
                    $updateData['oauth'] = $data['oauth'];
                    $updateData['openid'] = $data['openid'];
                    Db::name('users')->where(['user_id'=>$user_id])->update($updateData);
                    $user = Db::name('users')->where(['user_id'=>$user_id])->find();
                }
            }
        }
        
        if ($thirdUser) {
            $user = Db::name('users')->where('user_id', $thirdUser['user_id'])->find();
            if (!$user) {
                Db::name('oauth_users')->where(['openid' => $data['openid'], 'oauth' => $data['oauth']])->delete();//删除残留数据
            }
        }
        return $user;
    }

    /*
     * 第三方登录: (第一种方式:第三方账号直接创建账号, 不需要额外绑定账号)
     */
    public function thirdLogin($data = array())
    {
        if (!$data['openid'] || !$data['oauth']) {
            return array('status' => -1, 'msg' => '参数有误openid或oauth丢失', 'result' => 'aaa');
        }
        $user2 = session('user');
        if (!empty($user2)) {
            $r = $this->oauth_bind($data);//绑定账号
            if ($r) {
                return array('status' => 1, 'msg' => '绑定成功', 'result' => $user2);
            } else {
                return array('status' => -1, 'msg' => '您的' . $data['oauth'] . '账号已经绑定过账号', 'bind_status' => 0);
            }
        }

        $data['push_id'] && $map['push_id'] = $data['push_id'];
        $map['token'] = md5(time() . mt_rand(1, 999999999));
        $map['last_login'] = time();

        $user = $this->getThirdUser($data);
        if(!$user){
            //账户不存在 注册一个
            $map['password'] = '';
            $map['openid'] = $data['openid'];
            $map['nickname'] = $data['nickname'];
            $map['reg_time'] = time();
            $map['oauth'] = $data['oauth'];
            $map['head_pic'] = !empty($data['head_pic']) ? $data['head_pic'] : '/public/images/icon_goods_thumb_empty_300.png';
            $map['sex'] = $data['sex'] === null ? 0 :  $data['sex'];
            $map['first_leader'] = cookie('first_leader'); // 推荐人id
            if(!empty(input('first_leader')) && input('first_leader') > 0)
                $map['first_leader'] = input('first_leader'); // 微信授权登录返回时 get 带着参数的

            // 如果找到他老爸还要找他爷爷他祖父等
            if ($map['first_leader']) {
                $first_leader = Db::name('users')->where("user_id = {$map['first_leader']}")->find();
                if($first_leader){
                    $map['second_leader'] = $first_leader['first_leader']; //  第一级推荐人
                    $map['third_leader'] = $first_leader['second_leader']; // 第二级推荐人
                    //他上线分销的下线人数要加1
                    Db::name('users')->where(array('user_id' => $map['first_leader']))->inc('underling_number')->update();
                    Db::name('users')->where(array('user_id' => $map['second_leader']))->inc('underling_number')->update();
                    Db::name('users')->where(array('user_id' => $map['third_leader']))->inc('underling_number')->update();
                    //需要给推荐人送积分
                    $integral = tpCache('integral');
                    $invite_integral =$integral['invite_integral'];
                    if($invite_integral > 0 && $integral['invite']){
                        accountLog($map['first_leader'], 0,$invite_integral, '邀请会员注册赠送积分'); // 记录日志流水
                    }
                }

            } else {
                $map['first_leader'] = 0;
            }
            // 成为分销商条件
            $distribut_condition = tpCache('distribut.condition');
            if($distribut_condition == 0){    // 直接成为分销商, 每个人都可以做分销
                $map['is_distribut']  = 1;
            } 
            $row_id = Db::name('users')->insertGetId($map);
            if($distribut_condition == 0 && file_exists(APP_PATH.'common/logic/DistributLogic.php')){
                //无条件成为分销商，默认开启店铺
                $setS = new DistributLogic();
                $setS->setStore(['nickname'=>$data['nickname'],'user_id'=>$row_id,'mobile'=>'']);
            }

            // 会员注册赠送积分
            $isRegIntegral = tpCache('integral.is_reg_integral');
            if($isRegIntegral==1){
                $pay_points = tpCache('integral.reg_integral');
            }else{
                $pay_points = 0;
            }
            if($pay_points > 0){
                accountLog($row_id, 0,$pay_points, '会员注册赠送积分'); // 记录日志流水
            }
            if($integral['invitee_integral'] > 0 && $first_leader){
                accountLog($row_id, 0,$integral['invitee_integral'], '被邀请会员注册成功，获得积分'); // 记录日志流水
            }

            $user = Db::name('users')->where(array('user_id'=>$row_id))->find();
            
            if (!isset($data['oauth_child'])) {
                $data['oauth_child'] = '';
            }
            
            //不存在则创建个第三方账号
            $data['user_id'] = $user['user_id'];
            $user_level =Db::name('user_level')->where('amount = 0')->find(); //折扣
            $data['discount'] = !empty($user_level) ? $user_level['discount']/100 : 1;  //新注册的会员都不打折
            Db::name('OauthUsers')->save($data);

            //生成小程序专属二维码
            if ($data['oauth'] == 'miniapp') {
                $qrcode = $this->checkUserQrcode($row_id);
                if(!$user['xcx_qrcode'])
                    $user['xcx_qrcode'] = $qrcode;
            }
            
        } else {
            Db::name('users')->where('user_id', $user['user_id'])->save($map);
            $user['token'] = $map['token'];
            $user['last_login'] = $map['last_login'];
        }

        return array('status'=>1,'msg'=>'登陆成功','result'=>$user);
    }
    
    /*
     * 第三方登录(第二种方式:第三方账号登录必须绑定账号)
     */
    public function thirdLogin_new($data = array(),$user_id=0)
    {
        if((empty($data['openid']) && empty($data['unionid'])) || empty($data['oauth'])){
            return ['status' => -1, 'msg' => '参数错误, openid,unionid或oauth为空','result'=>''];
        }

        $user = $this->getThirdUser($data,$user_id);
        if( ! $user){
            return ['status' => -1, 'msg' => '请绑定账号' , 'result'=>'100'];
        }

        //兼容以前登录的小程序用户没有获取到openid
        if(!$user['openid']){
            $map['openid'] = $data['openid'];
        }

        $data['push_id'] && $map['push_id'] = $data['push_id'];
        $map['token'] = md5(time() . mt_rand(1, 999999999));
        $map['last_login'] = time();

        Db::name('users')->where(array('user_id' => $user['user_id']))->save($map);
        //重新加载一次用户信息
        $user = Db::name('users')->where(array('user_id' => $user['user_id']))->find();

        return array('status' => 1, 'msg' => '登陆成功', 'result' => $user);
    }

    /**
     * 注册
     * @param $username  邮箱或手机
     * @param $password  密码
     * @param $password2 确认密码
     * @param int $push_id
     * @param array $invite
     * @param string $nickname
     * @param string $head_pic
     * @return array
     */
    public function reg($username,$password,$password2,$push_id = 0,$invite=array(),$nickname="",$head_pic=""){
        $is_validated = 0 ;
        if(check_email($username)){
            $is_validated = 1;
            $map['email_validated'] = 1;
            $map['email'] = $username; //邮箱注册
        }

        if(check_mobile($username)){
            $is_validated = 1;
            $map['mobile_validated'] = 1;
            $map['mobile'] = $username; //手机注册
            $user = Db::name('users')->where('mobile',$username)->find();
            if($user){
                //绑定已存在的账号，并修改头像
               /* empty($nickname)? $update['nickname'] = $nickname:false;
                empty($head_pic)? $update['head_pic'] = $head_pic:false;
                if($update){
                    Db::name('users')->where('mobile',$username)->update($update);
                }
                if($nickname) $user['nickname'] = $nickname;
                if($head_pic) $user['head_pic'] = $head_pic;*/
                return array('status'=>1,'msg'=>'注册成功','result'=>$user);
            }
        }
        if($is_validated != 1)
            return array('status'=>-1,'msg'=>'请用手机号或邮箱注册','result'=>'');
        $map['nickname'] = $nickname ? $nickname : $username;
        $map['nickname'] = replaceSpecialStr($map['nickname']); // 去掉特殊字符
        if(!empty($head_pic)){
            $map['head_pic'] = $head_pic;
        }else{
            $map['head_pic']='/public/images/icon_goods_thumb_empty_300.png';
        }

        $data=[
            'nickname' =>$map['nickname'],
            'password' =>$password,
            'password2'=>$password2,
        ];
       $UserRegValidate = validate(\app\common\validate\User::class);
        if(!$UserRegValidate->scene('reg')->batch(false)->check($data)){
            return array('status'=>-1,'msg'=>$UserRegValidate->getError(),'result'=>'');
        }
        if(I('oauth') == 'miniapp'){
             $map['password'] = md5(config('AUTH_CODE').$password);
        }else{
            $map['password'] = empty($password)?'':$password;
        }
        $map['reg_time'] = time();
        $map['first_leader'] = cookie('first_leader')?: cache(input('unique_id').'first_leader');  //推荐人id
        //获取小程序保存的上下级关系
        $third_oauth = session('third_oauth');
        if(empty($third_oauth)) $third_oauth = Cache::get(input('unique_id'));
        cache(input('unique_id').'first_leader',null);
        $createRecomRelationship = $this->createRecomRelationship($third_oauth);
        if($createRecomRelationship['first_leader']>0 && $map['first_leader'] == 0){
            $map['first_leader'] = $createRecomRelationship['first_leader'];
        }
        $third_oauth['openid']? $map['openid'] = $third_oauth['openid']:false;
        // 获取族谱关系并增加相应的下级人数
        if($map['first_leader'])
        {
            $first_leader = Db::name('users')->where("user_id = {$map['first_leader']}")->find();
            $map['second_leader'] = $first_leader['first_leader'];
            $map['third_leader'] = $first_leader['second_leader'];
            //他上线分销的下线人数要加1
            Db::name('users')->where(array('user_id' => $map['first_leader']))->inc('underling_number');
            Db::name('users')->where(array('user_id' => $map['second_leader']))->inc('underling_number');
            Db::name('users')->where(array('user_id' => $map['third_leader']))->inc('underling_number');
        }else{
            $map['first_leader'] = 0;
        }
        if(is_array($invite) && !empty($invite)){
            $map['first_leader'] = $invite['user_id'];
            $map['second_leader'] = $invite['first_leader'];
            $map['third_leader'] = $invite['second_leader'];
            //需要给推荐人送积分
            $integral = tpCache('integral');
            $invite_integral =$integral['invite_integral'];
            if($invite_integral > 0 && $integral['invite']){
                accountLog($invite['user_id'], 0,$invite_integral, '邀请会员注册赠送积分'); // 记录日志流水
            }
        }
        $switch = tpCache('distribut.switch');
        // 成为分销商条件
        $distribut_condition = tpCache('distribut.condition');
        if($switch==1 && $distribut_condition == 0 && file_exists(APP_PATH.'common/logic/DistributLogic.php'))  // 直接成为分销商, 每个人都可以做分销
            $map['is_distribut']  = 1;

        $map['push_id'] = $push_id; //推送id
        $map['token'] = md5(time().mt_rand(1,999999999));
        $map['last_login'] = time();
        $user_level =Db::name('user_level')->where('amount = 0')->find(); //折扣
        $map['discount'] = !empty($user_level) ? $user_level['discount']/100 : 1;  //新注册的会员都不打折
        $user_id = Db::name('users')->insertGetId($map);
        user_login($user_id); // 注册时也增加一次登录
        if($switch==1 && $distribut_condition == 0 && file_exists(APP_PATH.'common/logic/DistributLogic.php')){
            //无条件成为分销商，默认开启店铺
            $setS = new DistributLogic();
            $setS->setStore(['nickname'=>$map['nickname'],'user_id'=>$user_id,'mobile'=>'']);
        }

        if($user_id === false)
            return array('status'=>-1,'msg'=>'注册失败');
        // 会员注册赠送积分
        $isRegIntegral = tpCache('integral.is_reg_integral');
        if($isRegIntegral==1){
            $pay_points = tpCache('integral.reg_integral');
        }else{
            $pay_points = 0;
        }
        //注册可获得积分
        if(is_array($invite) && !empty($invite)){
            if($integral['invitee_integral'] > 0){
                accountLog($user_id, 0,$integral['invitee_integral'], '被邀请会员注册成功，获得积分'); // 记录日志流水
            }
        }
        if($pay_points > 0){
            accountLog($user_id, 0,$pay_points, '会员注册赠送积分'); // 记录日志流水
        }
        $user = Db::name('users')->where("user_id", $user_id)->find();
        return array('status'=>1,'msg'=>'注册成功','result'=>$user);
    }

    /*
     * 获取当前登录用户信息
     */
    public function get_info($user_id)
    {
        if (!$user_id) {
            return array('status'=>-1, 'msg'=>'缺少参数');
        }
            $user = (new \app\common\model\Users())->find($user_id);
        if (!$user) {
            return false;
        }
        //账户资金需实时更新
        $UsersLogic = new \app\common\logic\UsersLogic();
        $UsersLogic->checkUserWithdrawals($user);
        $user['cash_in'] =  Db::name('withdrawals')->where(['user_id'=>$user['user_id'],'type'=>0,['status','in',['0','1']]])->sum('money')?:'0.00'; //统计正在提现的余额


        $activityLogic = new \app\common\logic\ActivityLogic;             //获取能使用优惠券个数
        $user['coupon_count'] = $activityLogic->getUserCouponNum($user_id, 0);
        $user['collect_count'] = Db::name('goods_collect')->where('user_id', $user_id)->count(); //获取商品收藏数量
        $user['return_count'] = Db::name('return_goods')->where(['user_id'=>$user_id,['status','in', '0,1,2,3']])->count();   //退换货数量
        //不统计虚拟的
        $user['waitPay'] = Db::name('order')->where("prom_type < 5 and user_id = $user_id " . config('WAITPAY'))->count(); //待付款数量
        $user['waitSend'] = Db::name('order')->where("prom_type < 5 and user_id = $user_id " . config('WAITSEND'))->count(); //待发货数量
        $user['waitReceive'] = Db::name('order')->where("prom_type < 5 and user_id = $user_id " . config('WAITRECEIVE'))->count(); //待收货数量
        $user['order_count'] = $user['waitPay'] + $user['waitSend'] + $user['waitReceive'];

        $commentLogic = new CommentLogic;
        $user['uncomment_count'] = $commentLogic->getCommentNum($user_id, 0); //待评论数
        $user['comment_count'] = $commentLogic->getCommentNum($user_id, 1); //已评论数

        return ['status' => 1, 'msg' => '获取成功', 'result' => $user];
    }

    /*
      * 获取当前登录用户信息
      */
    public function getApiUserInfo($user_id)
    {
        if (!$user_id) {
            return array('status'=>-1, 'msg'=>'账户未登陆');
        }

        $user = Db::name('users')->where('user_id', $user_id)->find();
        if (!$user) {
            return false;
        }
        $describe = Db::name('user_level')->where('level_id',$user['level'])->value('level_name');
        $activityLogic = new \app\common\logic\ActivityLogic;             //获取能使用优惠券个数
        $user['coupon_count'] = $activityLogic->getUserCouponNum($user_id, 0);
        $user['describe'] = $describe?$describe:'注册会员';
        $user['collect_count'] = Db::name('goods_collect')->where('user_id', $user_id)->count();//获取收藏数量
        $user['visit_count']   = Db::name('goods_visit')->where('user_id', $user_id)->count();   //商品访问记录数
        $user['return_count'] = Db::name('return_goods')->where("user_id=$user_id and status < 2")->count();   //退换货数量
        $order_where = "deleted=0 AND order_status <> 5 AND prom_type < 5 AND user_id=$user_id ";
        $user['waitPay']     = Db::name('order')->where($order_where.config('WAITPAY'))->count(); //待付款数量
        $user['waitSend']    = Db::name('order')->where($order_where.config('WAITSEND'))->count(); //待发货数量
        $user['waitReceive'] = Db::name('order')->where($order_where.config('WAITRECEIVE'))->count(); //待收货数量
        $user['order_count'] = $user['waitPay'] + $user['waitSend'] + $user['waitReceive'];
        $user['cash_in'] =  Db::name('withdrawals')->where(['type'=>0, 'user_id'=>$user_id,['status','in',['0','1']]])->sum('money')?:'0.00'; //统计正在提现的余额
        $user['user_money'] = round($user['user_money'] -  $user['cash_in'],2);   //刷新可用余额，可提现 = 余额 - 已提现

        $messageLogic = new \app\common\logic\Message();
        $user['message_count'] = $messageLogic->getUserMessageNoReadCount();
        
        $commentLogic = new CommentLogic;
        $user['uncomment_count'] = $commentLogic->getCommentNum($user_id, 0);; //待评论数
        $user['comment_count'] = $commentLogic->getCommentNum($user_id, 1); //已评论数
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($user_id);
        $user['cart_goods_num'] = $cartLogic->getUserCartGoodsNum();

         return ['status' => 1, 'msg' => '获取成功', 'result' => $user];
     }
     
    /*
     * 获取最近一笔订单
     */
    public function get_last_order($user_id){
        $last_order = Db::name('order')->where("user_id", $user_id)->order('order_id DESC')->find();
        return $last_order;
    }

    /**
     * 检查用户账户是否存在正在提现的订单,存在则可用余额 = 可用余额 - 提现总金额
     * status提现状态为 0表示为已发起提现申请 1审核成功待转账的
     * @param array $user
     */
    public function checkUserWithdrawals(&$user)
    {
        $check = DB::name('withdrawals')->fetchSql(false)->where(['type'=>0,'user_id'=>$user['user_id'],['status','in',['0','1']]])->sum('money');
        if($check > 0){
            $user['user_money'] = $user['user_money'] - $check;
        }
    }


    /*
     * 获取订单商品
     */
    public function get_order_goods($order_id){
        $sql = "SELECT og.*,g.commission FROM __PREFIX__order_goods og LEFT JOIN __PREFIX__goods g ON g.goods_id = og.goods_id WHERE order_id = :order_id";
        $bind['order_id'] = $order_id;
        $goods_list = DB::query($sql,$bind);

        $return['status'] = 1;
        $return['msg'] = '';
        $return['result'] = $goods_list;
        return $return;
    }

    /**
     * 获取账户资金记录
     * @param $user_id|用户id
     * @param int $account_type|收入：1,支出:2 所有：0
     * @param null $order_sn
     * @return array
     */
    public function get_account_log($user_id, $account_type = 0, $order_sn = null){
        $account_log_where = ['user_id'=>$user_id];
        if($account_type == 1){
            $account_log_where['user_money|pay_points'] = ['>',0];
        }elseif($account_type == 2){
            $account_log_where['user_money|pay_points'] = ['<',0];
        }
        $order_sn && $account_log_where['order_sn'] = $order_sn;
        $count = Db::name('account_log')->where($account_log_where)->count();
        $Page = new Page($count,15);
        $account_log = Db::name('account_log')->where($account_log_where)
            ->order('change_time desc')
            ->limit($Page->firstRow,$Page->listRows)
            ->select();
        $return = [
            'status'    =>1,
            'msg'       =>'',
            'result'    =>$account_log,
            'show'      =>$Page->show()
        ];
        return $return;
    }

    /**
     * 提现记录
     * @author lxl 2017-4-26
     * @param $user_id
     * @param int $withdrawals_status 提现状态 0:申请中 1:申请成功 2:申请失败
     * @return mixed
     */
    public function get_withdrawals_log($user_id,$withdrawals_status=''){
        $withdrawals_log_where = ['user_id'=>$user_id ,'type'=>0];
        if($withdrawals_status){
            $withdrawals_log_where['status']=$withdrawals_status;
        }
        $count = Db::name('withdrawals')->where($withdrawals_log_where)->count();
        $Page = new Page($count, 15);
        $withdrawals_log = Db::name('withdrawals')->where($withdrawals_log_where)
            ->order('id desc')
            ->limit($Page->firstRow,$Page->listRows)
            ->select();
        $return = [
            'status'    =>1,
            'msg'       =>'',
            'result'    =>$withdrawals_log,
            'show'      =>$Page->show()
        ];
        return $return;
    }

    /**
     * 用户充值记录
     * $author lxl 2017-4-26
     * @param $user_id 用户ID
     * @param int $pay_status 充值状态0:待支付 1:充值成功 2:交易关闭
     * @return mixed
     */
    public function get_recharge_log($user_id,$pay_status=0){
        $recharge_log_where = ['user_id'=>$user_id,'level_id'=>0]; //限制充值记录不可查看购买加盟分销等级记录，购买分销操作是消费记录
        if($pay_status){
            $pay_status['status']=$pay_status;
        }
        $count = Db::name('recharge')->where($recharge_log_where)->count();
        $Page = new Page($count, 15);
        $recharge_log = Db::name('recharge')->where($recharge_log_where)
            ->order('order_id desc')
            ->limit($Page->firstRow,$Page->listRows)
            ->select();
        $return = [
            'status'    =>1,
            'msg'       =>'',
            'result'    =>$recharge_log,
            'show'      =>$Page->show()
        ];
        return $return;
    }
    /*
     * 获取优惠券
     */
    public function get_coupon($user_id, $type =0, $orderBy = null,$order_money = 0,$p=12)
    {
        $activityLogic = new \app\common\logic\ActivityLogic;
        $count = $activityLogic->getUserCouponNum($user_id, $type, $orderBy,$order_money );
        
        $page = new Page($count, $p);
        $list = $activityLogic->getUserCouponList($page->firstRow, $page->listRows, $user_id, $type, $orderBy,$order_money);

        $return['status'] = 1;
        $return['msg'] = '获取成功';
        $return['result'] = $list;
        $return['show'] = $page->show();
        return $return;
    }

    /**
     * 获取商品收藏列表
     * @param $user_id
     * @return mixed
     */
    public function get_goods_collect($user_id){
        $count = Db::name('goods_collect')->alias('c')
            ->field('c.collect_id,c.add_time,g.goods_id,g.goods_name,g.shop_price,g.is_on_sale,g.store_count,g.cat_id,g.is_virtual')
            ->join('goods g','g.goods_id = c.goods_id','INNER')
            ->where("c.user_id = $user_id")
            ->count();
        $page = new Page($count,10);
        $show = $page->show();
        //获取我的收藏列表
            $result = Db::name('goods_collect')->alias('c')
            ->field('c.collect_id,c.add_time,g.goods_id,g.goods_name,g.shop_price,g.is_on_sale,g.store_count,g.cat_id,g.is_virtual')
            ->join('goods g','g.goods_id = c.goods_id','INNER')
            ->where("c.user_id = $user_id")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $return['status'] = 1;
        $return['msg'] = '获取成功';
        $return['result'] = $result;
        $return['show'] = $show;        
        return $return;
    }

    /**
     * 获取评论列表
     * @param $user_id 用户id
     * @param $status  状态 0 未评论 1 已评论 2全部
     * @return mixed
     */
    public function get_comment($user_id,$status=2){
        if($status == 1){
            //已评论
            $commented_count = Db::name('comment')
                ->alias('c')
                ->join('order_goods g','c.goods_id = g.goods_id and c.order_id = g.order_id', 'inner')
                ->where('c.user_id',$user_id)
                ->count();
            $page = new Page($commented_count,10);
            $comment_list = Db::name('comment')
                ->alias('c')
                ->field('c.*,g.*,(select order_sn from  __PREFIX__order where order_id = c.order_id ) as order_sn')
                ->join('order_goods g','c.goods_id = g.goods_id and c.order_id = g.order_id', 'inner')
                ->where('c.user_id',$user_id)
                ->order('c.add_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select();
        }else{
            $comment_where = ['o.user_id'=>$user_id,'og.is_send'=>1,'o.order_status'=>['in',[2,4]]];
            if($status == 0){
                $comment_where['og.is_comment'] = 0;
                $comment_where['o.order_status'] = 2;
            }
            $comment_count = Db::name('order_goods')->alias('og')->join('order o','o.order_id = og.order_id','left')->where($comment_where)->count();
            $page = new Page($comment_count,10);
            $comment_list = Db::name('order_goods')
                ->alias('og')
                ->join('order o','o.order_id = og.order_id','left')
                ->where($comment_where)
                ->order('o.order_id desc')
                ->limit($page->firstRow,$page->listRows)
                ->select();
        }
        $show = $page->show();
        if($comment_list){
        	$return['result'] = $comment_list;
        	$return['show'] = $show; //分页
        	return $return;
        }else{
        	return array();
        }
    }

    /**
     * 添加评论
     * @param $add
     * @return array
     */
    public function add_comment($add){
        if(!$add['order_id'] || !$add['goods_id']) 
            return array('status'=>-1,'msg'=>'非法操作','result'=>'');
        
        //检查订单是否已完成
        $order = Db::name('order')->field('order_status')->where("order_id", $add['order_id'])->where('user_id', $add['user_id'])->find();
        if($order['order_status'] != 2)
            return array('status'=>-1,'msg'=>'该笔订单还未确认收货','result'=>'');

        //检查是否已评论过
        $goods = Db::name('comment')->where(['rec_id'=>$add['rec_id']])->find();
        if($goods)return array('status'=>-1,'msg'=>'您已经评论过该商品','result'=>'');
        if($add['goods_rank']<1 || $add['service_rank']<1){
            return array('status'=>-1,'msg'=>'请给商品服务评分','result'=>'');
        }
        $row = Db::name('comment')->insertGetId($add);
        if($row)
        {
            //更新订单商品表状态
            if($add['item_id'] > 0){
                Db::name('order_goods')->where(array('rec_id'=>$add['rec_id'],'order_id'=>$add['order_id'],'item_id'=>$add['item_id']))->save(array('is_comment'=>1));
            }else{
                Db::name('order_goods')->where(array('rec_id'=>$add['rec_id'],'order_id'=>$add['order_id']))->save(array('is_comment'=>1));
            }

            Db::name('goods')->where(array('goods_id'=>$add['goods_id']))->inc('comment_count',1)->update(); // 评论数加一
            // 查看这个订单是否全部已经评论,如果全部评论了 修改整个订单评论状态   ,排除退款的，已经退款了就不能评价了
//            $comment_count   = Db::name('order_goods')->where("order_id", $add['order_id'])->where('is_comment', 0)->count();
            $comment_count   = Db::name('order_goods')->where(['order_id'=>$add['order_id'],'is_comment'=>0,['is_send','<',3]])->count();
            if($comment_count == 0) // 如果所有的商品都已经评价了 订单状态改成已评价
            {
                $rs = Db::name('order')->where("order_id",$add['order_id'])->save(array('order_status'=>4));
                //已完成状态的订单处理判断是否可赠送积分
                if($rs) $this->receiveGoodsGiftIntegral($add);
            }
            return array('status'=>1,'msg'=>'评论成功','result'=>'');
        }
        return array('status'=>-1,'msg'=>'评论失败','result'=>'');
    }

    /**
     * 邮箱或手机绑定
     * @param $email_mobile  邮箱或者手机
     * @param int $type  1 为更新邮箱模式  2 手机
     * @param int $user_id  用户id
     * @return bool
     */
    public function update_email_mobile($email_mobile,$user_id,$type=1){
        //检查是否存在邮件
        if($type == 1)
            $field = 'email';
        if($type == 2)
            $field = 'mobile';
        $condition['user_id'] = array('<>',$user_id);
        $condition[$field] = $email_mobile;

        $is_exist = Db::name('users')->where($condition)->find();
        if($is_exist)
            return false;
        unset($condition[$field]);
        $condition['user_id'] = $user_id;
        $validate = $field.'_validated';
        Db::name('users')->where($condition)->save(array($field=>$email_mobile,$validate=>1));
        return true;
    }

    /**
     * 更新用户信息
     * @param $user_id
     * @param $post  要更新的信息
     * @return bool
     */
    public function update_info($user_id,$post=array()){
        $model = Db::name('users')->where("user_id", $user_id);
        $row = $model->update($post);
        if($row === false)
           return false;
        return true;
    }

    /**
     * 地址添加/编辑
     * @param $user_id 用户id
     * @param $user_id 地址id(编辑时需传入)
     * @return array
     */
    public function add_address($user_id,$address_id=0,$data){
        $post = $data;
        if($address_id == 0)
        {
            $c = Db::name('UserAddress')->where("user_id", $user_id)->count();
            if($c >= 20)
                return array('status'=>-1,'msg'=>'最多只能添加20个收货地址','result'=>'');
        }
        //检查手机格式
        if($post['consignee'] == '')
            return array('status'=>-1,'msg'=>'收货人不能为空','result'=>'');
        if (!($post['province'] >= 0)|| !($post['city']>=0) || !($post['district']>=0))
            return array('status'=>-1,'msg'=>'所在地区不能为空','result'=>'');
        if(!$post['address'])
            return array('status'=>-1,'msg'=>'地址不能为空','result'=>'');
        if(!check_mobile($post['mobile']) && !check_telephone($post['mobile']))
            return array('status'=>-1,'msg'=>'手机号码格式有误','result'=>'');

        //编辑模式
        if($address_id > 0){

            $address = Db::name('user_address')->where(array('address_id'=>$address_id,'user_id'=> $user_id))->find();
            if($post['is_default'] == 1 && $address['is_default'] != 1)
                Db::name('user_address')->where(array('user_id'=>$user_id))->save(array('is_default'=>0));
            $row = Db::name('user_address')->where(array('address_id'=>$address_id,'user_id'=> $user_id))->save($post);
            if($row !== false){
                return array('status'=>1,'msg'=>'编辑成功','result'=>$address_id);
            }else{
                return array('status'=>-1,'msg'=>'操作完成','result'=>$address_id);
            }

        }
        //添加模式
        $post['user_id'] = $user_id;
        
        // 如果目前只有一个收货地址则改为默认收货地址
        $c = Db::name('user_address')->where("user_id", $post['user_id'])->count();
        if($c == 0)  $post['is_default'] = 1;

        //获取微信地址对应的region_id，city，district，province，twon为0
        $data = $this->getAddressRegionId($post);

        $insert_id = $address_id = Db::name('user_address')->insertGetId($data);
        //如果设为默认地址
       
        $map['user_id'] = $user_id;
        $map['address_id'] = array('<>',$insert_id);
               
        if($post['is_default'] == 1)
            Db::name('user_address')->where($map)->save(array('is_default'=>0));
        if(!$address_id)
            return array('status'=>-1,'msg'=>'添加失败','result'=>'');
        
        
        return array('status'=>1,'msg'=>'添加成功','result'=>$address_id);
    }

    /**
     * 转换微信地址获取对应的region_id
     * @param $data
     * @return mixed
     */
    private function getAddressRegionId($data){
        $province = 0;
        $city = 0;
        $district = 0;
        if(0 == $data['province'] && !empty($data['province_name'])){ //先获取省级
            $province = Db::name('region')->where(['level'=>1,'name' => $data['province_name']])->value('id');
        }
        if(0 == $data['city'] && !empty($data['city_name']) &&  $province != 0){ //获取市级
            $city = Db::name('region')->where(['level'=>2,'parent_id'=>$province,'name' => $data['city_name']])->value('id');
        }
        if(0 == $data['district'] && !empty($data['district_name']) &&  $city != 0){
            $district = Db::name('region')->where(['level'=>3,'parent_id'=>$city,'name' => $data['district_name']])->value('id');
            if(!$district){
                $district = Db::name('region')->insertGetId([
                    'name'=>$data['district_name'],
                    'level'=>3,
                    'parent_id'=>$city
                ]);
            }
        }

        $data['province'] = $province ?: $data['province'];
        $data['city'] = $city ?: $data['city'];
        $data['district'] = $district ?: $data['district'];
        return $data;
    }


    /**
     * 设置默认收货地址
     * @param $user_id
     * @param $address_id
     */
    public function set_default($user_id,$address_id){
        Db::name('user_address')->where(array('user_id'=>$user_id))->save(array('is_default'=>0)); //改变以前的默认地址地址状态
        $row = Db::name('user_address')->where(array('user_id'=>$user_id,'address_id'=>$address_id))->save(array('is_default'=>1));
        if(!$row)
            return false;
        return true;
    }

    /**
     * 修改密码
     * @param $user_id  用户id
     * @param $old_password  旧密码
     * @param $new_password  新密码
     * @param $confirm_password 确认新 密码
     * @param bool|true $is_update
     * @return array
     */
    public function password($user_id,$old_password,$new_password,$confirm_password,$is_update=true){
        $user = Db::name('users')->where('user_id', $user_id)->find();
        if ($new_password != $confirm_password)
            return ['status'=>-1,'msg'=>'请输入相同的新密码'];
        if ($old_password == $new_password)
            return ['status'=>-1,'msg'=>'新密码不能和旧密码相同'];
        if (strlen($new_password) < 6 || strlen($new_password) >18 )
            return ['status'=>-1,'msg'=>'请输入新密码长度为6~18'];
        $data=[
          'password' => $new_password,
          'password2' => $confirm_password,
        ];

        $UserRegvalidate = validate(\app\common\validate\User::class);
		
        if(!$UserRegvalidate->scene('set_pwd')->batch(false)->check($data)){
            return array('status'=>-1,'msg'=>$UserRegvalidate->getError(),'result'=>'');
        }
        //验证原密码
        if($is_update && ($user['password'] != '' && encrypt($old_password) != $user['password']))
            return array('status'=>-1,'msg'=>'原密码验证失败','result'=>'');
        $row = Db::name('users')->where("user_id", $user_id)->save(array('password'=>encrypt($new_password)));
        if(!$row)
            return array('status'=>-1,'msg'=>'修改失败','result'=>'');
        return array('status'=>1,'msg'=>'修改成功','result'=>'');
    }

    /**
     *  针对 APP 修改密码的方法
     * @param $user_id  用户id
     * @param $old_password  旧密码
     * @param $new_password  新密码
     * @param bool $is_update
     * @return array
     */
    public function passwordForApp($user_id,$old_password,$new_password,$is_update=true){
        $user = Db::name('users')->where('user_id', $user_id)->find();
        $data=[
            'password' => $new_password,
            'password2' => $new_password,
        ];
        $UserRegvalidate = validate(\app\common\validate\User::class);

        if(!$UserRegvalidate->scene('set_pwd')->batch(false)->check($data)){
            return array('status'=>-1,'msg'=>$UserRegvalidate->getError(),'result'=>'');
        }
        //验证原密码
        if($is_update && ($user['password'] != '' && $old_password != $user['password'])){
            return array('status'=>-1,'msg'=>'旧密码错误','result'=>'');
        }

        $row = Db::name('users')->where("user_id='{$user_id}'")->update(array('password'=>$new_password));
        if(!$row){
            return array('status'=>-1,'msg'=>'密码修改失败','result'=>'');
        }
        return array('status'=>1,'msg'=>'密码修改成功','result'=>'');
    }

    /**
     * 订单确认页面设置支付密码回调页面
     * @param $url  回调页面
     * @param string $order_id 拼团订单id
     * @param string $goods_id 商品id
     * @param string $goods_num 商品数量
     * @param string $item_id
     * @param string $action 操作方式
     */
    public function orderPageRedirectUrl($url,$order_id='',$goods_id='',$goods_num='',$item_id='',$action=''){
        $redirect_url = $url.'?goods_id='.$goods_id.'&goods_num='.$goods_num.'&item_id='.$item_id.'&action='.$action.'&order_id='.$order_id;
        session('payPriorUrl',$redirect_url);
    }


    /**
     * 设置初始密码 只能调用一次
     * @param users object 用户信息
     * @param password string 原始密码
     * @return data object   返回结果
     */
    public function initialPassword($users,$password){
        if($users['password']){
            throw new TpshopException('不能再次设置初始密码！', 0, ['status' => 0, 'msg' => '不能再次设置初始密码！', 'result' => '']);
        }
        $password = trim($password);//去掉空白符
        if(strlen($password) < 6){
            throw new TpshopException('密码不能少于6位！', 0, ['status' => 0, 'msg' => '密码不能少于6位！', 'result' => '']);
        }
        $result = Db::name('users')->where(['user_id'=>$users['user_id']])->update(['password'=>encrypt($password)]);
        if(!$result){
            throw new TpshopException('初始密码设置失败！', 0, ['status' => 0, 'msg' => '初始密码设置失败！', 'result' => '']);
        }
        return true;
    }


    /**
     * 设置支付密码
     * @param $user_id
     * @param $new_password  新密码
     * @param $confirm_password  确认密码
     * @return array
     */
    public function paypwd($user_id,$new_password,$confirm_password){
        $data=[
            'password' => $new_password,
            'password2' =>$confirm_password,
        ];
        $UserRegvalidate = validate(\app\common\validate\User::class);

        if(!$UserRegvalidate->scene('set_pwd')->batch(false)->check($data)){
            return array('status'=>-1,'msg'=>$UserRegvalidate->getError(),'result'=>'');
        }
        $row = Db::name('users')->where("user_id",$user_id)->update(array('paypwd'=>encrypt($new_password)));
        if(!$row){
            return array('status'=>-1,'msg'=>'修改失败','result'=>'');
        }
        $url = session('payPriorUrl') ? session('payPriorUrl'): url('User/userinfo');
        session('payPriorUrl',null);
    	return array('status'=>1,'msg'=>'修改成功','url'=>$url);
    }

    /**
     *  针对 APP 修改支付密码的方法
     * @param $user_id  用户id
     * @param $new_password  新密码
     * @return array
     */
    public function payPwdForApp($user_id, $new_password)
    {
        if (strlen($new_password) < 6) {
            return array('status' => -1, 'msg' => '密码不能低于6位字符', 'result' => '');
        }

        $row = Db::name('users')->where(['user_id'=>$user_id])->update(array('paypwd' => $new_password));
        if (!$row) {
            return array('status' => -1, 'msg' => '密码修改失败', 'result' => '');
        }
        return array('status' => 1, 'msg' => '密码修改成功', 'result' => '');
    }
    /**
     * 发送验证码: 该方法只用来发送邮件验证码, 短信验证码不再走该方法
     * @param $sender 接收人
     * @param $type 发送类型
     * @return json
     */
    public function send_email_code($sender){
    	$sms_time_out = tpCache('sms.sms_time_out');
    	$sms_time_out = $sms_time_out ? $sms_time_out : 180;
    	//获取上一次的发送时间
    	$send = session('validate_code');
    	if(!empty($send) && $send['time'] > time() && $send['sender'] == $sender){
    		//在有效期范围内 相同号码不再发送
    		$res = array('status'=>-1,'msg'=>'规定时间内,不要重复发送验证码');
            return $res;
    	}
    	$code =  mt_rand(1000,9999);
		//检查是否邮箱格式
		if(!check_email($sender)){
			$res = array('status'=>-1,'msg'=>'邮箱码格式有误');
            return $res;
		}
//		$send = send_email($sender,tpCache('shop_info.store_name'),'您好，你的验证码是：'.$code); // 阿里云邮箱，需要说明用途
        $send = send_email($sender,tpCache('shop_info.store_name'),'您的验证码是：'.$code.'，您正进行身份验证，打死不告诉别人！');
        if($send['status'] == 1){
    		$info['code'] = $code;
    		$info['sender'] = $sender;
    		$info['is_check'] = 0;
    		$info['time'] = time() + $sms_time_out; //有效验证时间
    		session('validate_code',$info);
    		$res = array('status'=>1,'msg'=>'验证码已发送，请注意查收');
    	}else{
    		$res = $send;
    	}
    	return $res;
    }

    /**
     * 检查短信/邮件验证码验证码
     * @param $code
     * @param $sender
     * @param string $type
     * @param int $session_id
     * @param int $scene
     * @return array
     */
    public function check_validate_code($code, $sender, $type ='email', $session_id=0 ,$scene = -1){
    	
        $timeOut = time();
        $inValid = true;  //验证码失效
        if($type == 'email'){
            //邮件证码是否开启
            $reg_smtp_enable = tpCache('smtp.regis_smtp_enable');
            if(!$reg_smtp_enable){//发生邮件功能关闭
                $validate_code = session('validate_code');
                $validate_code['sender'] = $sender;
                $validate_code['is_check'] = 1;//标示验证通过
                session('validate_code',$validate_code);
                return array('status'=>1,'msg'=>'邮件验证码功能关闭, 无需校验验证码');
            }
            if(!$code)return array('status'=>-1,'msg'=>'请输入邮件验证码');
            //邮件
            $data = session('validate_code');
            $timeOut = $data['time'];
            if($data['code'] != $code || $data['sender']!=$sender){
                $inValid = false;
            }
        }else{
            //短信发送否开启
            //-1:用户没有发送短信
            //空:发送验证码关闭
            $sms_status = checkEnableSendSms($scene);
            if($scene == -1){
                return array('status'=>-1,'msg'=>'参数错误, 请传递合理的scene参数');
            }elseif($sms_status['status'] == 0){
                $data['sender'] = $sender;
                $data['is_check'] = 1; //标示验证通过
                session('validate_code',$data);
                return array('status'=>1,'msg'=>'短信验证码功能关闭, 无需校验验证码');
            }

            if(!$code)return array('status'=>-1,'msg'=>'请输入短信验证码');
            //短信
            $sms_time_out = tpCache('sms.sms_time_out');
            $sms_time_out = $sms_time_out ? $sms_time_out : 180;
            $data = Db::name('sms_log')->where(array('mobile'=>$sender,'session_id'=>$session_id , 'status'=>1))->order('id DESC')->find();
            if(is_array($data) && $data['code'] == $code){
            	$data['sender'] = $sender;
            	$timeOut = $data['add_time']+ $sms_time_out;
            }else{
            	$inValid = false;
            }           
        }
        
       if(empty($data)){
           $res = array('status'=>-1,'msg'=>'请先获取验证码');
       }elseif($timeOut < time()){
           $res = array('status'=>-1,'msg'=>'验证码已超时失效');
       }elseif(!$inValid)
       {
           $res = array('status'=>-1,'msg'=>'验证失败,验证码有误');
       }else{
            $data['is_check'] = 1; //标示验证通过
            session('validate_code',$data);
            $res = array('status'=>1,'msg'=>'验证成功');
        }
        return $res;
    }
     
    
    /**
     * @time 2016/09/01
     * 设置用户系统消息已读
     */
    public function setSysMessageForRead()
    {
        $user_info = session('user');
        if (!empty($user_info['user_id'])) {
            $data['status'] = 1;
            Db::name('user_message')->where(array('user_id' => $user_info['user_id'], 'category' => 0))->save($data);
        }
    }

    /**
     * 设置用户消息已读
     * @param int $category 0:系统消息|1：活动消息
     * @param $msg_id
     * @return array
     * @throws \think\Exception
     */
    public function setMessageForRead($category = 0,$msg_id)
    {
        $user_info = session('user');
        if (!empty($user_info['user_id'])) {
            $data['status'] = 1;
            $set_where['user_id'] = $user_info['user_id'];
            $set_where['category'] = $category;
            if($msg_id){
                $set_where['message_id'] = $msg_id;
            }
            $updat_meg_res = Db::name('user_message')->where($set_where)->update($data);
            if ($updat_meg_res){
                return ['status'=>1,'msg'=>'操作成功'];
            }
        }
        return ['status'=>-1,'msg'=>'操做失败'];
    }

    /**
     * 获取访问记录
     * @param type $user_id
     * @param type $p
     * @return type
     */
    public function getVisitLog($user_id, $p = 1)
    {
        $visit = Db::name('goods_visit')->alias('v')
            ->field('v.visit_id, v.goods_id, v.visittime, g.goods_name, g.shop_price, g.cat_id')
            ->join('goods g', 'v.goods_id=g.goods_id')
            ->where('v.user_id', $user_id)
            ->order('v.visittime desc')
            ->page($p, 20)
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

        return $visit_list;
    }
    
    /**
     * 上传头像
     */
    public function upload_headpic($must_upload = true)
    {
        if ($_FILES['head_pic']['tmp_name']) {
            $file = request()->file('head_pic');
 
            
           $parentDir = date('Ymd');
            $dir = "head_pic/$parentDir/";
            $dir2 = UPLOAD_PATH.$dir;            
            $originalName = strtolower($file->getOriginalName());
             if(strstr($originalName,'.php') || strstr($originalName,'.js'))   
                 return ['status' => -1, 'msg' => '上错图片格式错误', 'result' => []];
                 $upload_max_filesize = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
                 $maxsize = 30000000;
                 if($file->getSize() > $maxsize)
                     return ['status' => -1, 'msg' =>'上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件,且系统配置不能超过:'.$upload_max_filesize, 'result' => []];                        
                 $extension = strtolower($file->extension());
                 if(!in_array($extension,['jpg','jpeg','gif','png']))
                         return ['status' => -1, 'msg' => '仅可上传jpg,jpeg,gif,png文件', 'result' => []];                            
                 $savename = md5(mt_rand()).'.'.$file->extension();
                 \think\facade\Filesystem::disk('public')->putFileAs($dir, $file,$savename);         
                 $pic_path = '/'.$dir2.$savename;
            
        } elseif ($must_upload) {
            return ['status' => -1, 'msg' => "图片不存在！"];
        }
        return ['status' => 1, 'msg' => '上传成功', 'result' => $pic_path];
    }

    /**
     * 账户明细
     */
    public function account($user_id, $type='all'){
    	if($type == 'all'){
    		$count = Db::name('account_log')->where("user_money!=0 and user_id=" . $user_id)->count();
    		$page = new Page($count, 16);
    		$account_log = Db::name('account_log')->field("*,from_unixtime(change_time,'%Y-%m-%d %H:%i:%s') AS change_data")->where("user_money!=0 and user_id=" . $user_id)
                ->order('log_id desc')->limit($page->firstRow,$page->listRows)->select();
    	}else{
    		$where = $type=='plus' ? " and user_money > 0 " : " and user_money < 0 ";
    		$count = Db::name('account_log')->where("user_id=" . $user_id.$where)->count();
    		$page = new Page($count, 16);
    		$account_log = Db::name('account_log')->field("*,from_unixtime(change_time,'%Y-%m-%d %H:%i:%s') AS change_data")->where("user_id=" . $user_id.$where)
                ->order('log_id desc')->limit($page->firstRow,$page->listRows)->select();
    	}
    	$result['account_log'] = $account_log;
    	$result['page'] = $page;
    	return $result;
    }

    /**
     * 积分明细
     */
    public function points($user_id, $type='all')
    {
 		 if($type == 'all'){
    		$count = Db::name('account_log')->where("user_id=" . $user_id ." and pay_points!=0 ")->count();
    		$page = new Page($count, 16);
    		$account_log = Db::name('account_log')->where("user_id=" . $user_id." and pay_points!=0 ")->order('log_id desc')->limit($page->firstRow,$page->listRows)->select();
    	}else{
    		$where = $type=='plus' ? " and pay_points > 0 " : " and pay_points < 0 ";
    		$count = Db::name('account_log')->where("user_id=" . $user_id.$where)->count();
    		$page = new Page($count, 16);
    		$account_log = Db::name('account_log')->where("user_id=" . $user_id.$where)->order('log_id desc')->limit($page->firstRow,$page->listRows)->select();
    	}

        $result['account_log'] = $account_log;
        $result['page'] = $page;
        return $result;
    }

    /**
     * 添加用户签到信息
     * @param $user_id int 用户id
     * @param $date date 日期
     * @return array
     */
    public function addUserSign($user_id, $date)
    {
        $config = tpCache('sign');
        $data['user_id'] = $user_id;
        $data['sign_total'] = 1;
        $data['sign_last'] = $date;
        $data['cumtrapz'] = $config['sign_integral'];
        $data['sign_time'] = "$date";
        $data['sign_count'] = 1;
        $data['this_month'] = $config['sign_integral'];
        $result['status'] = false;
        $result['msg'] = '签到失败!';
        if (Db::name('user_sign')->insert($data)) {
            $result['status'] = true;
            $result['msg'] = '签到旅程开始啦,积分奖励!';
            accountLog($user_id, 0, $config['sign_integral'], '第一次签到赠送' . $config['sign_integral'] . '积分');
        }
        return $result;
    }

    /**
     * 累计用户签到信息
     * @param $userInfo  array   用户信息
     * @param $date      date    日期
     * @return array
     */
    public function updateUserSign($userInfo, $date)
    {
        $config = tpCache('sign');
        $update_data = array(
            'sign_total' =>Db::raw('sign_total+' . 1),                                     //累计签到天数
            'sign_last'  =>Db::raw("'$date'"),                                             //最后签到时间
            'cumtrapz'   =>Db::raw('cumtrapz+' . $config['sign_integral']),                //累计签到获取积分
            'sign_time'  =>Db::raw("CONCAT_WS(',',sign_time ,'$date')"),                   //历史签到记录
            'sign_count' =>Db::raw('sign_count+' . 1),                                     //连续签到天数
            'this_month' =>Db::raw('this_month+' . $config['sign_integral']),              //本月累计积分
        );
        $daya = $userInfo['sign_last'];
        $dayb = date("Y-n-j", strtotime($date) - 86400);
        if ($daya != $dayb) {                                                               //不是连续签
            $update_data['sign_count'] =  1;
        }
        $mb = date("m", strtotime($date));
        if (intval($mb) != intval(date("m", strtotime($daya)))) {                            //不是本月签到
            $update_data['sign_count'] =  1;
            $update_data['sign_time']  = $date;
            $update_data['this_month'] = $config['sign_integral'];
        }
        $update = Db::name('user_sign')->where(['user_id' => $userInfo['user_id']])->update($update_data);
        $result['status'] = false;
        $result['msg'] = '签到失败!';
        if ($update>0) {
            accountLog($userInfo['user_id'], 0, $config['sign_integral'], '签到赠送' . $config['sign_integral'] . '积分');
            $result['status'] = true;
            $result['msg']    = '签到成功!奖励' . $config['sign_integral'] . '积分';
            $userFind = Db::name('user_sign')->where(['user_id' => $userInfo['user_id']])->find();
            //满足额外奖励
            if ($userFind['sign_count'] >= $config['sign_signcount']) {
                $result['msg']    = '哇，你已经连续签到' . $userFind['sign_count'] . '天,得到额外奖励！';
                $this->extraAward($userInfo, $config);
            }
        }
        return $result;
    }

    /**
     * 累计签到额外奖励
     * @param $userSingInfo array 用户信息
     */
    public function extraAward($userSingInfo)
    {
        $config = tpCache('sign');
        //满足额外奖励
        Db::name('user_sign')->where(['user_id' => $userSingInfo['user_id']])->update([
            'cumtrapz' => Db::raw('cumtrapz+' . $config['sign_award']),
            'this_month' => Db::raw('this_month+' . $config['sign_award'])
        ]);
        $msg = '连续签到奖励' . $config['sign_award'] . '积分';
        accountLog($userSingInfo['user_id'], 0, $config['sign_award'], $msg);
    }

    /**
     * 标识用户签到信息
     * @param $user_id int 用户id
     * @return      array
     */
    public function idenUserSign($user_id)
    {
        $config = tpCache('sign');
        $map['us.user_id'] = $user_id;
        $field = [
            'u.user_id as user_id',
            'u.nickname',
            'u.mobile',
            'us.*',
        ];
        //$join = [['users u', 'u.user_id=us.user_id', 'left']];
        $info = Db::name('user_sign')->alias('us')->field($field)->join('users u', 'u.user_id=us.user_id', 'left')->where($map)->find();        
        ($info['sign_last'] != date("Y-n-j", time())) && $tab = "1";
        $signTime = explode(",", $info['sign_time']);
        $str = "";
        //是否标识历史签到
        if (date("m", strtotime($info['sign_last'])) == date("m", time())) {
            foreach ($signTime as $val) {
                $str .= date("j", strtotime($val)) . ',';
            }
        } else {
            $info['sign_count'] = 0; //不是本月清除连续签到
        }
        if( $info['sign_count']>=$config['sign_signcount'])
            $display_sign=  $config['sign_award']+ $config['sign_integral'];
        else
            $display_sign=  $config['sign_integral'];
        $jiFen = ($config['sign_signcount'] * $config['sign_integral']) + $config['sign_award'];
        return ['info' => $info, 'str' => $str, 'jifen' => $jiFen, 'config' => $config, 'tab' => $tab,'display_sign'=>$display_sign];
    }

    /**
     * 判断登录成功后是否需要清空积分（积分是否过期）
     * @param $user str 用户信息
     */
    protected function isEmptyingIntegral($user)
    {
        $integralExpiredInfo = Db::name("config")->where("name='is_integral_expired' and inc_type='integral'")->find();
        if($integralExpiredInfo['value'] == 2) {
            $configInfo = Db::name("config")->where("name='expired_time' and inc_type='integral'")->find();
            $expiredTime = explode(",", $configInfo['value']);
            $newExpiredTime = strtotime(date("Y")."-".$expiredTime[0]."-".$expiredTime[1]);
            if($user["last_login"] < $newExpiredTime && time() >= $newExpiredTime){
                accountLog($user['user_id'], 0, -$user['pay_points'], '积分过期清空');
            }
        }
    }
    
    /**
     * 判断是虚拟商品可获取赠送的积分
     * 
     */
    public function receiveGoodsGiftIntegral($order_goods){ 
       $check =  Db::name('order')->where(array('order_id'=>$order_goods['order_id']))->find();
       //虚拟订单
       if($check['prom_type'] == 5){
           $integral = Db::name('order_goods')->where(array('rec_id'=>$order_goods['rec_id'],'order_id'=>$order_goods['order_id']))->find();
           $msg = '购买虚拟商品赠送' . $integral['give_integral'] . '积分';
           accountLog($check['user_id'], 0, $integral['give_integral'], $msg);
       }
      
    }

    /**
     * 检查该用户是否存在小程序专属二维码
     * @param $user 用户id
     * @return array|mixed|string
     */

    public function checkUserQrcode($user){
        $qrcode = Db::name('users')->where('user_id',$user)->value('xcx_qrcode');
        if(!$qrcode || !file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$qrcode)){
            $path="/pages/index/index/index?first_leader=".$user;
            $post_data = json_encode(["path" => $path,"width" => 430]);
            $minapp = new \app\common\logic\wechat\MiniAppUtil();
            $assecc_token = $minapp->getMinAppAccessToken();
            if($assecc_token == false){
                return ['status'=>0,'msg'=>$minapp->getError()];
            }
            $result = $minapp->getWecatCreateQrcode($assecc_token,$post_data);
            if($result == false){
                return ['status'=>0,'msg'=>$minapp->getError()];
            }

            $dir = 'public/upload/user_qroce';
            !is_dir($dir) && mkdir($dir, 0777, true);   // 如果文件夹不存在，将以递归方式创建该文件夹
            $newFilePath = $dir . '/xcx_qrcode_'.$user.'_'.date("YmdHis").'.jpg';
            $newFile = fopen($newFilePath,"w");//打开文件准备写入
            fwrite($newFile,$result);//写入二进制流到文件
            fclose($newFile);//关闭文件

            $rs = Db::name("users")->where(array('user_id'=>$user))->update(["xcx_qrcode"=>'/'.$newFilePath]);
            if($rs)
                $qrcode  = '/'.$newFilePath;

        }
        return $qrcode;
    }


    /**
     * gd流合成用户专属推广海报
     */
    public function createUserQrcodePoster($user_id = ''){
        define('IMGROOT_PATH', str_replace("\\","/",realpath(dirname(dirname(__FILE__)).'/../../'))); //图片根目录（绝对路径）
        $project_path = '/public/images/poster/'.input('_saas_app','all');
        $file_path = IMGROOT_PATH.'/public'.$project_path;

        if(!is_dir($file_path)){
            mkdir($file_path,777,true);
        }
        $poster = DB::name('poster')->where(['enabled'=>1])->find();
        if(!$poster['back_url']){
           return ['status'=>1,'msg'=>'请先上传海报背景图','result'=>-1];
        }

       $background_img = IMGROOT_PATH.'/public'.$poster['back_url'];    //海报背景图
        if(  !@fopen( $background_img, 'r' ) ){
            return ['status'=>1,'msg'=>'海报背景图丢失或不存在，请重新上传','result'=>-1];
        }

        $qrcode = IMGROOT_PATH.'/public'.$this->checkUserQrcode($user_id);
        if(  !@fopen( $qrcode, 'r' ) ){
            return ['status'=>1,'msg'=>'微信用户二维码丢失或不存在','result'=>-1];
        }

        //计算canvas画布宽高在上传图片后的宽高的绽放比例
        //处理海报背景图
        $canvas_maxWidth = $poster['canvas_width'];
        $canvas_maxHeight = $poster['canvas_height'];
        $info = getimagesize($background_img);                                                     //取得一个图片信息的数组
        $im = checkPosterImagesType($info,$background_img);                                        //根据图片的格式对应的不同的函数
        $rate_poster_width = $canvas_maxWidth/$info[0];                                            //计算绽放比例
        $rate_poster_height = $canvas_maxHeight/$info[1];
        $maxWidth =  floor($info[0]*$rate_poster_width);
        $maxHeight = floor($info[1]*$rate_poster_height);                                          //计算出缩放后的高度
        $des_im = imagecreatetruecolor($maxWidth,$maxHeight);                                      //创建一个缩放的画布
        imagecopyresized($des_im,$im,0,0,0,0,$maxWidth,$maxHeight,$info[0],$info[1]);              //缩放
        $news_poster = $file_path.'/'.createImagesName() . ".png";                                 //获得缩小后新的二维码路径
        inputPosterImages($info,$des_im,$news_poster);                                             //输出到png即为一个缩放后的文件

        //处理二维码
        $maxWidth = 100;
        $info2 = getimagesize($qrcode);                                                            //取得一个图片信息的数组
        $im2 = checkPosterImagesType($info2,$qrcode);                                              //根据图片的格式对应的不同的函数
        $qrcode_rate = $maxWidth/$info2[0];                                                        //计算绽放比例
        $qrcode_maxHeight = floor($info2[1]*$qrcode_rate);                                         //计算出缩放后的高度
        $des_im2 = imagecreatetruecolor($maxWidth,$qrcode_maxHeight);                              //创建一个缩放的画布
        imagecopyresized($des_im2,$im2,0,0,0,0,$maxWidth,$qrcode_maxHeight,$info2[0],$info2[1]);   //缩放
        $news_qrcode = $file_path.'/'.createImagesName() . ".png";                                 //获得缩小后新的二维码路径
        inputPosterImages($info2,$des_im2,$news_qrcode);                                           //输出到png即为一个缩放后的文件

        $QR = imagecreatefromstring ( file_get_contents ( $news_qrcode ) );
        $background_img = imagecreatefromstring ( file_get_contents ( $news_poster ) );
        imagecopyresampled ( $background_img, $QR,$poster['canvas_x'],$poster['canvas_y'],0,0,100,103,100, 96 );      //合成图片
        $result_png = '/poster_'.$user_id. ".png";
        unlink($news_poster);
        unlink($news_qrcode);
        $file = $file_path . $result_png;
        imagepng ( $background_img, $file );                                                      //输出最终图片
        return $project_path.$result_png.'?t='.time();
    }

    /**
     * 获取用户发票信息
     */
    public function getUserDefaultInvoice(){
        $map = [];
        $map['user_id']=  $this->user_id;
        $field=[
            'invoice_title',
            'taxpayer',
            'invoice_desc',
        ];
        $info = Db::name('user_extend')->field($field)->where($map)->find();
        return !empty($info) ? $info : '';
    }

    /**
     * 记录上级关系
     * @param $map
     * @return array|bool|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function createRecomRelationship($map)
    {
        $add = 0;
        if(!$map['openid']){
            return false;
        }
        $recom_relationship = Db::name('recom_relationship')->where(['openid'=>$map['openid']])->find();
        if($recom_relationship){
            //如果已记录关系表，就判断有没有绑定上级，如果已经绑定不做处理，要是原来没有绑定，现在扫码进来有上级就更新最新的上级
            if(0 == $recom_relationship['first_leader'] && $map['first_leader'] >0){
                Db::name('recom_relationship')->where(['openid'=>$map['openid']])->save(['first_leader'=>$map['first_leader'],'up_time'=>time()]);
            }
        }else{
            $data['openid']  = $map['openid'];
            $data['unionid'] = empty($map['unionid'])?'':$map['unionid'];
            $data['first_leader'] = $map['first_leader']?$map['first_leader']:0;
            $data['is_entry'] = 1;
            $data['content'] = json_encode($map);
            $data['add_time'] = time();
            $data['up_time'] = time();
            Db::name('recom_relationship')->save($data);
        }
        $recom_relationship = Db::name('recom_relationship')->where(['openid'=>$map['openid']])->find();
        return $recom_relationship;
    }


    /**
     * 授权手机号码,
     *  用户未注册手机号，添加手机号，是空号
     * @param $data
     * @param array $user
     * @return array
     * @throws \think\Exception
     */
    public function addMobile($data,$user =[]){
        $updateData['mobile'] = $data['phoneNumber'];
        $userInfo = Db::name('users')->where(['mobile'=>$data['phoneNumber']])->find();
        if($userInfo['user_id'] == $user['user_id'])   return array('status'=>1,'msg'=>'授权成功','result'=>$user);
        //根据电话号码 判断之前是否有已绑定的账号  若存在，只需要绑定之前的授权表的用户ID 若不存在 则修改电话号即可
        if($userInfo){
            $oauth_users = Db::name('oauth_users')->where(['user_id'=>$user['user_id']])->value('tu_id');
            Db::name('oauth_users')->where(['tu_id'=>$oauth_users])->update(['user_id'=>$userInfo['user_id']]);
            //将之前的登录token更新
            Db::name('users')->where(['user_id'=>$userInfo['user_id']])->update(['last_login'=>time(),'token'=>$user['token']]);
            Db::name('users')->where(['user_id'=>$user['user_id']])->delete();
            //恢复注册时增加的上级数据，若出现数据问题那么在于绑定功能是否完善
            //他上线分销的下线人数要减1
            Db::name('users')->where(array('user_id' => $userInfo['first_leader']))->setDec('underling_number');
            Db::name('users')->where(array('user_id' => $userInfo['second_leader']))->setDec('underling_number');
            Db::name('users')->where(array('user_id' => $userInfo['third_leader']))->setDec('underling_number');
        }else{
            Db::name('users')->where(['user_id'=>$user['user_id']])->update(['mobile'=>$data['phoneNumber']]);
        }
        $data = Db::name('users')->where(['mobile'=>$data['phoneNumber']])->find();
        return array('status'=>1,'msg'=>'授权成功','result'=>$data);
    }


    /**
     * 登录需要处理的逻辑
     * @param user_id  int 用户ID
     * @param unique_id [string]  唯一标识（使用之前的）
     */
    public function LoginOperation($user_id,$unique_id=''){
        if(empty($user_id) || empty($unique_id)) return false ;
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($user_id);
        $cartLogic->setUniqueId($unique_id);
        $cartLogic->doUserLoginHandle();// 用户登录后 需要对购物车 一些操作
        $orderLogic = new OrderLogic();
        $orderLogic->setUserId($user_id);//登录后将超时未支付订单给取消掉
        $orderLogic->abolishOrder();
    }
}