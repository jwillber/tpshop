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

namespace app\supplier\controller;
use think\facade\View;

use app\common\logic\Supplier;
use app\common\util\TpshopException;
use think\facade\Session;
use think\Verify;
use think\facade\Db;

class Admin extends Base {

	/**
	 * 管理员登录页
	 * @return mixed
	 */
	public function login(){
		if (session('?suppliers_id') && session('suppliers_id') > 0) {
			$this->error("您已登录", url('Supplier/Index/index'));
		}
		return View::fetch();
	}

	/**
	 * 管理员登录
	 */
	public function logon()
	{
		$code = input('post.verify');
		$username = input('post.username/s');
		$password = input('post.password/s');
		$verify = new Verify();
		if (!$verify->check($code, "supplier_login")) {
			$this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
		}
		$password = encrypt($password);
		$supplier = new Supplier();
		$supplier->setSupplierAccount($username);
		try{
			$supplier->login($password);//这里面登录成功后自动结算订单
			$url = session('from_url') ? session('from_url') : url('Supplier/Index/index');
			$this->ajaxReturn(['status' => 1, 'msg' => '成功登陆','url'=>$url]);
		}catch (TpshopException $t){
			$this->ajaxReturn($t->getErrorArr());
		}
	}

    /**
     * 退出登陆
     */
    public function logout()
    {
		session_unset();
		session(null);
		\think\facade\Session::clear();
        $this->success("退出成功",url('Supplier/Admin/login'));
    }
    
    /**
     * 验证码获取
     */
    public function verify()
    {
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => false,
            'useNoise' => false,
        	'reset' => false
        );    
        $Verify = new Verify($config);
        $Verify->entry("supplier_login");
        exit();
    }


}