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
 * Date: 2015-09-09
 */

namespace app\admin\controller;
use think\facade\View;

use app\common\logic\AdminLogic;
use app\common\logic\ModuleLogic;
use think\Page;
use think\Verify;
use think\Loader;
use think\facade\Db;
use think\facade\Session;

class Admin extends Base {

    public function index(){
    	$list = array();
    	$keywords = input('keywords/s');
    	if(empty($keywords)){
    		$res = Db::name('admin')->where('admin_id','not in','2,3')->select();
    	}else{
			$res = DB::name('admin')->where('user_name','like','%'.$keywords.'%')->where('admin_id','not in','2,3')->order('admin_id')->select();
    	}
    	$role = Db::name('admin_role')->column('role_name','role_id');
    	if($res && $role){
    		foreach ($res as $val){
    			$val['role'] =  $role[$val['role_id']];
    			$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
    			$list[] = $val;
    		}
    	}
    	View::assign('list',$list);
        return View::fetch();
    }
    
    /**
     * 修改管理员密码
     * @return \think\mixed
     */
    public function modify_pwd(){
        $admin_id = input('admin_id/d',0);
        $oldPwd = input('old_pw/s');
        $newPwd = input('new_pw/s');
        $new2Pwd = input('new_pw2/s');
       
        if($admin_id){
            $info = Db::name('admin')->where("admin_id", $admin_id)->find();
            $info['password'] =  "";
            View::assign('info',$info);
        }
        
         if(IS_POST){
            //修改密码
            $enOldPwd = encrypt($oldPwd);
            $enNewPwd = encrypt($newPwd);
            $admin = Db::name('admin')->where('admin_id' , $admin_id)->find();
            if(!$admin || $admin['password'] != $enOldPwd){
                exit(json_encode(array('status'=>-1,'msg'=>'旧密码不正确')));
            }else if($newPwd != $new2Pwd){
                exit(json_encode(array('status'=>-1,'msg'=>'两次密码不一致')));
            }else{
                $row = Db::name('admin')->where('admin_id' , $admin_id)->save(array('password' => $enNewPwd));
                if($row){
                    exit(json_encode(array('status'=>1,'msg'=>'修改成功')));
                }else{
                    exit(json_encode(array('status'=>-1,'msg'=>'修改失败')));
                }
            }
        }
        return View::fetch();
    }
    
    public function admin_info(){
    	$admin_id = input('get.admin_id/d',0);
    	if($admin_id){
    		$info = Db::name('admin')->where("admin_id", $admin_id)->find();
			$info['password'] =  "";
    		View::assign('info',$info);
    	}
    	$act = empty($admin_id) ? 'add' : 'edit';
    	View::assign('act',$act);
    	$role = Db::name('admin_role')->select();
    	View::assign('role',$role);
    	return View::fetch();
    }
    
    public function adminHandle(){
    	$data = input('post.');
		$adminValidate = validate(\app\admin\validate\Admin::class);

		if(!$adminValidate->scene($data['act'])->batch(true)->check($data)){
			$this->ajaxReturn(['status'=>-1,'msg'=>'操作失败','result'=>$adminValidate->getError()]);
		}
		if(empty($data['password'])){
			unset($data['password']);
		}else{
			$data['password'] =encrypt($data['password']);
		}
    	if($data['act'] == 'add'){
    		unset($data['admin_id']);    		
    		$data['add_time'] = time();
			$r = Db::name('admin')->insertGetId($data);
    	}
    	
    	if($data['act'] == 'edit'){
    		$r = Db::name('admin')->where('admin_id', $data['admin_id'])->save($data);
    	}
        if($data['act'] == 'del' && $data['admin_id']>1){
    		$r = Db::name('admin')->where('admin_id', $data['admin_id'])->delete();
    	}
    	
    	if($r){
			$this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>url('Admin/Admin/index')]);

		}else{
			$this->ajaxReturn(['status'=>-1,'msg'=>'操作失败']);
    	}
    }


	public function delAdmins(){
		$ids = input('post.ids','');
		empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
		$shippingIds = rtrim($ids);
		Db::name('shipping')->whereIn('shipping_id',$shippingIds)->delete();
		$this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Shipping/index")]);
	}
    
    
    /**
     * 管理员登陆
     */
    public function login()
    {
        if (IS_POST) {
            $code = input('post.vertify');
            $username = input('post.username/s');
            $password = input('post.password/s');

            $verify = new Verify();
            if (!$verify->check($code, "admin_login")) {
                $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
            }

            $adminLogic = new AdminLogic;
            $return = $adminLogic->login($username, $password);
            $this->ajaxReturn($return);
        }

        if (session('?admin_id') && session('admin_id') > 0) {
            $this->error("您已登录", url('Admin/Index/index'));
        }

        return View::fetch();
    }
    
    /**
     * 退出登陆
     */
    public function logout()
    {
        $adminLogic = new AdminLogic;
        $adminLogic->logout(session('admin_id'));

        $this->success("退出成功",url('Admin/Admin/login'));
    }
    
    /**
     * 验证码获取
     */
    public function vertify()
    {
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => false,
            'useNoise' => false,
        	'reset' => false
        );    
        $Verify = new Verify($config);
        $Verify->entry("admin_login");
        exit();
    }
    
    public function role(){
    	$list = Db::name('admin_role')->order('role_id desc')->select();
    	View::assign('list',$list);
    	return View::fetch();
    }
    
    public function role_info(){
    	$role_id = input('get.role_id/d');
    	$detail = array();
    	if($role_id){
    		$detail = Db::name('admin_role')->where("role_id",$role_id)->find();
    		$detail['act_list'] = explode(',', $detail['act_list']);
    		View::assign('detail',$detail);
    	}
		$right = Db::name('system_menu')->order('id')->select();
		foreach ($right as $val){
			if(!empty($detail)){
				$val['enable'] = in_array($val['id'], $detail['act_list']);
			}
			$modules[$val['group']][] = $val;
		}
		//admin权限组
        $group = (new ModuleLogic)->getPrivilege(0);
		View::assign('group',$group);
		View::assign('modules',$modules);
    	return View::fetch();
    }
    
    public function roleSave(){
    	$data = input('post.');
    	$res = $data['data'];
    	$res['act_list'] = is_array($data['right']) ? implode(',', $data['right']) : '';
        if(empty($res['act_list']))
            $this->error("请选择权限!");        
    	if(empty($data['role_id'])){
			$admin_role = Db::name('admin_role')->where(['role_name'=>$res['role_name']])->find();
			if($admin_role){
				$this->error("已存在相同的角色名称!");
			}else{
				$r = Db::name('admin_role')->insertGetId($res);
			}
    	}else{
			$admin_role = Db::name('admin_role')->where(['role_name'=>$res['role_name'],'role_id'=>['<>',$data['role_id']]])->find();
			if($admin_role){
				$this->error("已存在相同的角色名称!");
			}else{
				$r = Db::name('admin_role')->where('role_id', $data['role_id'])->save($res);
			}
    	}
		if($r){
			adminLog('管理角色');
			$this->success("操作成功!",url('Admin/Admin/role_info',array('role_id'=>$data['role_id'])));
		}else{
			$this->error("操作失败!",url('Admin/Admin/role'));
		}
    }
    
    public function roleDel(){
    	$role_id = input('post.role_id/d');
    	$admin = Db::name('admin')->where('role_id',$role_id)->find();
    	if($admin){
    		exit(json_encode("请先清空所属该角色的管理员"));
    	}else{
    		$d = Db::name('admin_role')->where("role_id", $role_id)->delete();
    		if($d){
    			exit(json_encode(1));
    		}else{
    			exit(json_encode("删除失败"));
    		}
    	}
    }

	public function delRoles(){
		$ids = input('post.ids','');
		empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
		$rolesIds = rtrim($ids);
		$admin = Db::name('admin')->whereIn('role_id',$rolesIds)->find();
		if ($admin) {
			$this->ajaxReturn(['status' => -1,'msg' => '请先清空所属该角色的管理员','url'=>url("Admin/Admin/role")]);
		} else {
			Db::name('admin_role')->whereIn('role_id',$rolesIds)->delete();
			$this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Admin/role")]);
		}
	}
    
    public function log(){
    	$p = input('p/d',1);
    	$logs = DB::name('admin_log')->alias('l')->join('admin a','a.admin_id =l.admin_id')->order('log_time DESC')->page($p.',20')->select();
    	View::assign('list',$logs);
    	$count = DB::name('admin_log')->count();
    	$Page = new Page($count,20);
    	$show = $Page->show();
		View::assign('pager',$Page);
		View::assign('page',$show);
    	return View::fetch();
    }
}