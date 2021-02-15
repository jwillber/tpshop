<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * 采用最新Thinkphp6
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-10-09
 */

namespace app\supplier\controller;
use think\facade\View;

use think\facade\Db;
use think\Loader;
use think\Page;
use app\common\model\FreightTemplate;

class System extends Base{
	
	/**
	 * 供应商基本信息
	 */
	public function index()
	{
		$info = DB::name('suppliers')
			->alias('s')
			->join('users u', 's.user_id=u.user_id', 'LEFT')
			->where(array('s.suppliers_id' => $this->supplier['suppliers_id']))
			->find();
		$city_list = Db::name('region')->where(['parent_id'=>$info['province_id'],'level'=> 2])->select();
		$district_list = Db::name('region')->where(['parent_id'=>$info['city_id']])->select();
		View::assign('city_list', $city_list);
		View::assign('district_list', $district_list);
		View::assign('info', $info);
		$province_list = Db::name('region')->where(['parent_id'=>0,'level'=> 1])->cache(true)->select();
        View::assign('province_list', $province_list);
		return View::fetch();
	}
	
	/**
	 * 编辑供应商
	 */
	public function editSupplier() {
        $data = input('post.');
        $supplierValidate = validate(\app\supplier\validate\Supplier::class);

        if (!$supplierValidate->scene('edit')->batch(true)->check($data)) {
            $error = $supplierValidate->getError();
            $error_msg = array_values($error);
            $this->ajaxReturn(['status' => 0, 'msg' => $error_msg[0], 'result' => $error]);
        }
        //编辑
		unset($data['supplier_account']);
		unset($data['supplier_name']);
		unset($data['user_name']);
        $row = Db::name('suppliers')->update($data);
        if($row !== false){
            $this->ajaxReturn(['status' => 1, 'msg' => '编辑成功', 'result' => '']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
        }
	}
	
	/**
	 * 密码管理
	 */
	public function password() {
		View::assign('suppliers_id', $this->supplier['suppliers_id']);
		return View::fetch();
	}
	
	/**
	 * 修改密码
	 */
	public function changePassword() {
        $data = input('post.');
        $supplierValidate = validate(\app\supplier\validate\Supplier::class);

        if (!$supplierValidate->scene('change_password')->batch(true)->check($data)) {
            $error = $supplierValidate->getError();
            $error_msg = array_values($error);
            $this->ajaxReturn(['status' => 0, 'msg' => $error_msg[0], 'result' => $error]);
        }
		$supplier = Db::name('suppliers')->where('suppliers_id', $data['suppliers_id'])->find();
		$password = encrypt($data['new_password']);
        $row = Db::name('users')->where('user_id', $supplier['user_id'])->update(['password' => $password]);
        if($row !== false){
            $this->ajaxReturn(['status' => 1, 'msg' => '编辑成功', 'result' => '']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
        }
	}
	
	/**
	 * 运费模板
	 */
	public function freight()
    {
        $FreightTemplate = new FreightTemplate();
        $count = $FreightTemplate->where('')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $template_list = $FreightTemplate->append(['type_desc'])->with(['freightConfig'])->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('page', $show);
        View::assign('template_list', $template_list);
        return View::fetch();
    }
}