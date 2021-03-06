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
 * Author: lhb
 */

namespace app\common\logic;

use app\common\model\saas\AppService;
use think\facade\Db;
use think\facade\Session;

class AdminLogic
{
    public function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return ['status' => 0, 'msg' => '请填写账号密码'];
        }

        //Saas::instance()->ssoAdmin($username, $password);

        $condition['a.user_name'] = $username;
        $condition['a.password'] = encrypt($password);
        $admin = Db::name('admin')->alias('a')->join('admin_role ar', 'a.role_id=ar.role_id')->where($condition)->find();
        if (!$admin) {
            return ['status' => 0, 'msg' => '账号密码不正确'];
        }

        $this->handleLogin($admin, $admin['act_list']);

        $url = session('from_url') ? session('from_url') : url('Admin/Index/index');
        return ['status' => 1, 'url' => $url];
    }

    public function handleLogin($admin, $actList)
    {
        Db::name('admin')->where('admin_id', $admin['admin_id'])->save([
            'last_login' => time(),
            'last_ip' => request()->ip()
        ]);
        if(IS_SAAS){
            $saas_cfg = $GLOBALS['SAAS_CONFIG'];
            $app_service = AppService::find($saas_cfg['service_id']);
            session('app_service', $app_service);
        }
        session('act_list', $actList);
        session('admin_id', $admin['admin_id']);
        session('last_login_time', $admin['last_login']);
        session('last_login_ip', $admin['last_ip']);

        adminLog('后台登录');
        
    }

    public function logout($adminId)
    {
        session_unset();
        session(null);
        \think\facade\Session::clear();

        //Saas::instance()->handleLogout($adminId);
    }
}






