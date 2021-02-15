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
 * Date: 2015-12-11
 */
namespace app\admin\controller;
use think\facade\View;
use app\common\logic\MessageTemplateLogic;
use app\common\logic\MessageFactory;
use app\common\logic\wechat\WechatUtil;
use think\AjaxPage;
use think\Page;
use think\facade\Db;
use think\Loader;

class Coupon extends Base {
    /**----------------------------------------------*/
     /*                优惠券控制器                  */
    /**----------------------------------------------*/
    /*
     * 优惠券类型列表
     */
    public function index(){
        //获取优惠券列表
    	$count =  Db::name('coupon')->count();
    	$Page = new Page($count,10);
        $show = $Page->show();
        $lists = Db::name('coupon')->order('add_time desc')->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('lists',$lists);
        View::assign('pager',$Page);// 赋值分页输出
        View::assign('page',$show);// 赋值分页输出   
        View::assign('coupons',config('COUPON_TYPE'));
        return View::fetch();
    }

    /*
     * 添加编辑一个优惠券类型
     */
    public function coupon_info(){
        $cid = input('get.id/d');
        if ($cid) {
            $coupon = Db::name('coupon')->where(array('id' => $cid))->find();
            if (empty($coupon)) {
                $this->error('代金券不存在');
            }else{
                if($coupon['use_type'] == 2){
                    $goods_coupon = Db::name('goods_coupon')->where('coupon_id',$cid)->find();
                    $cat_info = Db::name('goods_category')->where(array('id'=>$goods_coupon['goods_category_id']))->find();
                    $cat_path = explode('_', $cat_info['parent_id_path']);
                    $coupon['cat_id1'] = $cat_path[1];
                    $coupon['cat_id2'] = $cat_path[2];
                    $coupon['cat_id3'] = $goods_coupon['goods_category_id'];
                }
                if($coupon['use_type'] == 1){
                    $coupon_goods_ids = Db::name('goods_coupon')->where('coupon_id',$cid)->column('goods_id');
                    $enable_goods = Db::name('goods')->where("goods_id", "in", $coupon_goods_ids)->select();
                    View::assign('enable_goods',$enable_goods);
                }
            }
            View::assign('coupon', $coupon);
        } else {
            $def['send_start_time'] = strtotime("+1 day");
            $def['send_end_time'] = strtotime("+1 month");
            $def['use_start_time'] = strtotime("+1 day");
            $def['use_end_time'] = strtotime("+2 month");
            View::assign('coupon', $def);
        }
        $cat_list = Db::name('goods_category')->where(['parent_id' => 0])->select();//自营店已绑定所有分类
        View::assign('cat_list',$cat_list);
        return View::fetch();
    }

    /**
     * 添加编辑优惠券
     */
    public function addEditCoupon()
    {
        $data = input('post.');
        $data['send_start_time'] = strtotime($data['send_start_time']);
        $data['send_end_time'] = strtotime($data['send_end_time']);
        $data['use_end_time'] = strtotime($data['use_end_time']);
        $data['use_start_time'] = strtotime($data['use_start_time']);
        $couponValidate = validate(\app\admin\validate\Coupon::class);

        if (!$couponValidate->batch(true)->check($data)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => $couponValidate->getError()]);
        }
        if(empty($data['goods_id']) && $data['use_type']==1)$this->ajaxReturn(['status' => -1, 'msg' => '请选择活动商品', 'result' => '']);
        if (empty($data['id'])) {
            $data['add_time'] = time();
            $row = Db::name('coupon')->insertGetId($data);
            //指定商品
            if ($data['use_type'] == 1) {
                foreach ($data['goods_id'] as $v) {
                    Db::name('goods_coupon')->insert(['coupon_id' => $row, 'goods_id' => $v,'goods_category_id'=>0]);
                }
            }
            //指定商品分类id
            if ($data['use_type'] == 2) {
                $cat_id =0;
                if($data['cat_id1']){
                    $cat_id = $data['cat_id1'];
                }
                if($data['cat_id2']){
                    $cat_id = $data['cat_id2'];
                }
                if($data['cat_id3']){
                    $cat_id = $data['cat_id3'];
                }
                Db::name('goods_coupon')->insert(['coupon_id' => $row, 'goods_category_id' => $cat_id]);
            }
        } else {
            $row = Db::name('coupon')->where(array('id' => $data['id']))->save($data);
            Db::name('goods_coupon')->where(['coupon_id'=>$data['id']])->delete();//先删除后添加
            //指定商品
            if ($data['use_type'] == 1) {
                foreach ($data['goods_id'] as $value) {
                    Db::name('goods_coupon')->insert(['coupon_id' => $data['id'], 'goods_id' => $value]);
                }
            }
            //指定商品分类id
            if ($data['use_type'] == 2) {
                $cat_id =0;
                if($data['cat_id1']){
                    $cat_id = $data['cat_id1'];
                }
                if($data['cat_id2']){
                    $cat_id = $data['cat_id2'];
                }
                if($data['cat_id3']){
                    $cat_id = $data['cat_id3'];
                }
                Db::name('goods_coupon')->insert(['coupon_id' => $data['id'], 'goods_category_id' => $cat_id]);
            }
        }
        if ($row !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '编辑代金券成功', 'result' => '']);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '编辑代金券失败', 'result' => '']);
        }
    }

    /*
    * 优惠券发放
    */
    public function make_coupon(){
        //获取优惠券ID
        $cid = input('get.id/d');
        $type = input('get.type');
        //查询是否存在优惠券
        $data = Db::name('coupon')->where(array('id'=>$cid))->find();
        $remain = $data['createnum'] - $data['send_num'];//剩余派发量
    	if($remain<=0 && $data['createnum']>0) $this->error($data['name'].'已经发放完了');
        if(!$data) $this->error("优惠券类型不存在");
        if($type != 3) $this->error("该优惠券类型不支持发放");
        if(IS_POST){
            $num  = input('post.num/d');
            if($num>$remain && $data['createnum']>0) $this->error($data['name'].'发放量不够了');
            if(!$num > 0) $this->error("发放数量不能小于0");
            if($data['status'] == 2) $this->error("优惠券已设置为失效");
            $add['cid'] = $cid;
            $add['type'] = $type;
            $add['send_time'] = time();
            for($i=0;$i<$num; $i++){
                do{
                    $code = get_rand_str(8,0,1);//获取随机8位字符串
                    $check_exist = Db::name('coupon_list')->where(array('code'=>$code))->find();
                }while($check_exist);
                $add['code'] = $code;
                Db::name('coupon_list')->insert($add);
            }
            Db::name('coupon')->where("id",$cid)->inc('send_num',$num)->update();
            adminLog("发放".$num.'张'.$data['name']);
            $this->success("发放成功",url('Admin/Coupon/index'));
            exit;
        }
        View::assign('coupon',$data);
        return View::fetch();
    }
    
    public function ajax_get_user(){
    	//搜索条件
    	$condition = array();
    	input('mobile') ? $condition['mobile'] = input('mobile') : false;
    	input('email') ? $condition['email'] = input('email') : false;
    	input('level_id') ? $condition['level'] = input('level_id') : false;
        $cid = input('cid');
    	$nickname = input('nickname');
    	if(!empty($nickname)){
    		$condition['nickname'] = array('like',"%$nickname%");
    	}
        $issued_uids = Db::name('coupon_list')->where(['cid'=>$cid])->column('uid'); //已经发放的用户ID
    	$count = Db::name('users')->whereNotIn('user_id',$issued_uids)->where($condition)->count();
    	$Page  = new AjaxPage($count,10);
    	/*foreach($condition as $key=>$val) {
    		$Page->parameter[$key] = urlencode($val);
    	}*/
    	$show = $Page->show();
    	$userList = Db::name('users')->whereNotIn('user_id',$issued_uids)->where($condition)->order("user_id desc")->limit($Page->firstRow,$Page->listRows)->select();

        $user_level = Db::name('user_level')->column('level_name','level_id');
        View::assign('user_level',$user_level);
    	View::assign('userList',$userList);
    	View::assign('page',$show);
        View::assign('pager',$Page);
    	return View::fetch();
    }
    
    public function send_coupon(){
    	$cid = input('cid/d');
    	if(IS_POST){
    		$level_id = input('level_id');
    		$user_id = input('user_id/a');

    		$insert = [];
    		$coupon = Db::name('coupon')->where("id",$cid)->find();
    		if($coupon['createnum']>0){
    			$remain = $coupon['createnum'] - $coupon['send_num'];//剩余派发量
    			if($remain<=0) $this->error($coupon['name'].'已经发放完了');
    		}
    		if(empty($user_id) && $level_id>=0){
    			if($level_id==0){
    				$user = Db::name('users')->where("is_lock",0)->select();
    			}else{
    				$user = Db::name('users')->where("is_lock",0)->where('level', $level_id)->select();
    			}
    			if($user){
    				$able = count($user);//本次发送量
    				if($coupon['createnum']>0 && $remain<$able){
    					$this->error($coupon['name'].'派发量只剩'.$remain.'张');
    				}
    				foreach ($user as $k=>$val){
    					$time = time();
                        $insert[] = ['cid' => $cid, 'type' => 1, 'uid' => $val['user_id'], 'send_time' => $time];
                        $user_id[] = $val['user_id']; //用于通知消息
    				}
    			}
    		}else{
    			$able = count($user_id);//本次发送量
    			if($coupon['createnum']>0 && $remain<$able){
    				$this->error($coupon['name'].'派发量只剩'.$remain.'张');
    			}
    			foreach ($user_id as $k=>$v){
    				$time = time();
                    $insert[] = ['cid' => $cid, 'type' => 1, 'uid' => $v, 'send_time' => $time];
    			}
    		}
			DB::name('coupon_list')->insertAll($insert);
            // 通知消息
            $messageFactory = new MessageFactory();
            $messageLogic = $messageFactory->makeModule(['category' => 0]);
            $messageLogic->getCouponNotice($cid, $user_id);

            // 如果有微信公众号 则推送一条消息到微信.微信浏览器才发消息，否则下单超时。by清华
//            if(is_weixin()){
//                $user_id = ['3661','3645'];
                $user = Db::name('OauthUsers')->where(['user_id'=>['in',$user_id] , 'oauth'=>'weixin' , 'oauth_child'=>'mp'])->select();
                if ($user) {
                    $wx_content = "您刚刚收到了优惠券，请注意查收";
                    $wechat = new WechatUtil();
                    foreach ($user as $v)
                    {
                        $wechat->sendMsg($v['openid'], 'text', $wx_content);
                    }
                }
//            }
			Db::name('coupon')->where("id",$cid)->inc('send_num',$able)->update();
			adminLog("发放".$able.'张'.$coupon['name']);
			$this->success("发放成功");
			exit;
    	}
    	$level = Db::name('user_level')->select();
    	View::assign('level',$level);
    	View::assign('cid',$cid);
    	return View::fetch();
    }
    
    public function send_list(){
		$begin = $this->begin;
        $end = $this->end;
		$condition = array();
		if($begin && $end){
            $condition['send_time'] = array('between',"$begin,$end");
        }
		input('type') != '' ? $condition['type'] = input('type') : false;
        input('status') != '' ? $condition['status'] = input('status') : false;
		input('uid') != '' ? $condition['uid'] = input('uid') : false;
		$count =  Db::name('coupon_list')->where($condition)->count();
    	$Page = new Page($count,20);
        $show = $Page->show();
        $lists = Db::name('coupon_list')->where($condition)->order('send_time desc')->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('lists',$lists);
        View::assign('pager',$Page);// 赋值分页输出
        View::assign('page',$show);// 赋值分页输出   
        View::assign('coupons',config('COUPON_TYPE'));
		if(!$lists->isEmpty()){
			$user_id_arr = get_arr_column($lists, 'uid');
			$users = Db::name('users')->where('user_id in('.implode(',', $user_id_arr).')')->column('nickname','user_id');
			$coupon_id_arr = get_arr_column($lists, 'cid');
			$coupon = Db::name('coupon')->where('id in('.implode(',', $coupon_id_arr).')')->column('id,name,money,use_end_time','id');
			View::assign('users',$users);
			View::assign('coupon',$coupon);
		}
    	return View::fetch();
    }

    /*
     * 删除优惠券类型
     */
    public function del_coupon(){
        //获取优惠券ID
        $cid = input('get.id/d');
        //查询是否存在优惠券
        $row = Db::name('coupon')->where(array('id'=>$cid))->delete();
        if (!$row) {
            $this->ajaxReturn(['status' => 0, 'msg' => '优惠券不存在，删除失败']);
        }
        // 删除优惠券通知消息
        $messageFactory = new MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 0]);
        $messageLogic->deletedMessage($cid, 2);
        $messageLogic->deletedMessage($cid, 4);

        //删除此类型下的优惠券
        Db::name('coupon_list')->where(array('cid'=>$cid))->delete();
        $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
    }


    /*
     * 优惠券详细查看
     */
    public function coupon_list(){
        //获取优惠券ID
        $cid = input('get.id/d');
        //查询是否存在优惠券
        $check_coupon = Db::name('coupon')->field('id,type')->where(array('id'=>$cid))->find();
        if(!$check_coupon['id'] > 0)
            $this->error('不存在该类型优惠券');
       
        //查询该优惠券的列表的数量
        $sql = "SELECT count(1) as c FROM __PREFIX__coupon_list  l ".
                "LEFT JOIN __PREFIX__coupon c ON c.id = l.cid ". //联合优惠券表查询名称
                "LEFT JOIN __PREFIX__order o ON o.order_id = l.order_id ".     //联合订单表查询订单编号
                "LEFT JOIN __PREFIX__users u ON u.user_id = l.uid WHERE l.cid = :cid";    //联合用户表去查询用户名
        
        $count = DB::query($sql,['cid' => $cid]);
        $count = $count[0]['c'];
    	$Page = new Page($count,10);
    	$show = $Page->show();
        
        //查询该优惠券的列表
        $sql = "SELECT l.*,c.name,o.order_sn,u.nickname FROM __PREFIX__coupon_list  l ".
                "LEFT JOIN __PREFIX__coupon c ON c.id = l.cid ". //联合优惠券表查询名称
                "LEFT JOIN __PREFIX__order o ON o.order_id = l.order_id ".     //联合订单表查询订单编号
                "LEFT JOIN __PREFIX__users u ON u.user_id = l.uid WHERE l.cid = :cid ".    //联合用户表去查询用户名
                " ORDER BY l.id asc".
                " limit {$Page->firstRow} , {$Page->listRows}";
        $coupon_list = DB::query($sql,['cid' => $cid]);
        View::assign('coupon_type',config('COUPON_TYPE'));
        View::assign('type',$check_coupon['type']);       
        View::assign('lists',$coupon_list);            	
    	View::assign('page',$show);
        View::assign('pager',$Page);
        return View::fetch();
    }
    
    /*
     * 删除一张优惠券
     */
    public function coupon_list_del(){
        //获取优惠券ID
        $cid = input('get.id');
        if(!$cid)
            $this->error("缺少参数值");
        //查询是否存在优惠券
         $row = Db::name('coupon_list')->where(array('id'=>$cid))->delete();
        if(!$row)
            $this->error('删除失败');
        $this->success('删除成功');
    }


    /**
     * 导出线下优惠卷
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function export_coupon()
    {
        $list_ids = input('list_ids','');
        $cid = input('cid',0);
        if($list_ids){
            $where['cl.id'] = ['in', $list_ids];
        }
        $where['cl.cid'] = $cid;
        $where['cl.type'] = 3;
        $join = [
          ['__COUPON__ c','c.id = cl.cid','left'],
          ['__USERS__ u','u.user_id = cl.uid','left'],
          ['__ORDER__ o','o.order_id = cl.order_id','left'],
        ];
        $couponList = Db::name('coupon_list')
            ->alias('cl')
            ->field('cl.*,c.name,u.nickname,o.order_sn')
            ->join($join)
            ->where($where)
            ->order('cl.id')
            ->select();
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:*">优惠券名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="120px;">发放类型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="120px;">订单号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="120px;">所属用户</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="120px;">使用时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">优惠券码</td>';
        $strTable .= '</tr>';
        if($couponList->toArray()){
            foreach($couponList as $k=>$val){
                    $strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['name'].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">线下发放</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['order_sn'].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['nickname'].'</td>';
                    $use_time = $val['use_time']?date('Y-m-d H:i:s',$val['use_time']):"未使用";
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$use_time.'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['code'].'</td>';
                    $strTable .= '</tr>';
                }
            }
        $strTable .='</table>';
        unset($orderList);
        downloadExcel($strTable,'coupon');
        exit();
    }



}