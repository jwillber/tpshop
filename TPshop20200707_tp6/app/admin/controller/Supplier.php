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

//use app\common\logic\AdminLogic;
//use app\common\logic\ModuleLogic;
use think\Page;
use think\Verify;
use think\Loader;
use think\facade\Db;
//use think\facade\Session;

class Supplier extends Base {
	/**
	 * 供应商列表
	 */
	public function index()
	{
	    
		input('is_check', '') !== '' ? $map['s.is_check'] = input('is_check') : false;
		$key_type = input('key_type');
		$key_word = input('key_word');
		if ($key_word) {
			switch ($key_type) {
				case 'suppliers_name': $key_type = 's.suppliers_name';break;
				case 'nickname': $key_type = 'u.nickname';break;
			}
			$map[$key_type] = ['like', '%'.$key_word.'%'];
		}
		$map['s.deleted'] = 0;
		$supplier_count = DB::name('suppliers')
			->alias('s')
			->join('users u','s.user_id = u.user_id','LEFT')
			->where($map)
			->count();
		$page = new Page($supplier_count, 10);
		$subQuery = Db::name('goods')
			->field('count(*) as count,suppliers_id')
			->where('is_on_sale = 1')
			->group('suppliers_id')
			->buildSql();
		$supplier_list = DB::name('suppliers')
			->field('s.*,u.*,c.count')
			->alias('s')
			->join('users u','s.user_id = u.user_id','LEFT')
			->join($subQuery . ' c', 's.suppliers_id = c.suppliers_id', 'LEFT')
			->where($map)
			->limit($page->firstRow, $page->listRows)
			->select();
		View::assign('list', $supplier_list);
		View::assign('pager', $page);
		
		return View::fetch();
	}
	
	/**
	 * 供应商编辑页
	 */
	public function supplier_info()
	{
	    
		$suppliers_id = input('get.suppliers_id/d', 0);
		if ($suppliers_id) {
			$info = DB::name('suppliers')
				->alias('s')
				->join('users u', 's.user_id=u.user_id', 'LEFT')
				->where(array('s.suppliers_id' => $suppliers_id))
				->find();
			$city_list = Db::name('region')->where(['parent_id'=>$info['province_id'],'level'=> 2])->select();
            $district_list = Db::name('region')->where(['parent_id'=>$info['city_id']])->select();
            View::assign('city_list', $city_list);
            View::assign('district_list', $district_list);
			View::assign('info', $info);
		}
		$province_list = Db::name('region')->where(['parent_id'=>0,'level'=> 1])->cache(true)->select();
        View::assign('province_list', $province_list);
        
		return View::fetch();
	}
	
	/**
	 * 添加供应商
	 */
	public function add() {
	    
        $data = input('post.');
        $supplierValidate = validate(\app\admin\validate\Supplier::class);

        if (!$supplierValidate->scene('add')->batch(true)->check($data)) {
            $error = $supplierValidate->getError();
            $error_msg = array_values($error);
            $this->ajaxReturn(['status' => 0, 'msg' => $error_msg[0], 'result' => $error]);
        }
        //添加
        $user_id = Db::name('users')->where(['email|mobile'=>$data['user_name']])->value('user_id');
        if(empty($user_id)){
            if(check_email($data['user_name'])){
                $user_data['email'] = $data['user_name'];
            }else{
                $user_data['mobile'] = $data['user_name'];
            };
            $user_data['password'] = $data['password'];
            $user_obj = new \app\admin\logic\UsersLogic();
            $add_user_res = $user_obj->addUser($user_data);
            if($add_user_res['status'] !=1) {
                $this->ajaxReturn($add_user_res);
            }
            $user_id = $add_user_res['user_id'];
        }
		unset($data['user_name']);
		unset($data['password']);
		$data['user_id'] = $user_id;
        $data['add_time'] = time();
        $supplier=Db::name('suppliers')->where(['user_id' => $user_id,'deleted'=>1])->find();
        if($supplier){
            $res=Db::name('suppliers')->where(['user_id' => $user_id,'deleted'=>1])->delete();
            if($res){
                $supplier['suppliers_id']=$supplier['suppliers_id'];
            }else{
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
            }
        }
        $row = Db::name('suppliers')->insertGetId($data);
        if($row !== false){
            $this->ajaxReturn(['status' => 1, 'msg' => '添加成功', 'result' => '']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
        }
        
	}
	
	/**
	 * 编辑供应商
	 */
	public function edit() {
	    
        $data = input('post.');
        $supplierValidate = validate(\app\admin\validate\Supplier::class);

        if (!$supplierValidate->scene('edit')->batch(true)->check($data)) {
            $error = $supplierValidate->getError();
            $error_msg = array_values($error);
            $this->ajaxReturn(['status' => 0, 'msg' => $error_msg[0], 'result' => $error]);
        }
        //编辑
		unset($data['supplier_account']);
        $row = Db::name('suppliers')->update($data);
        if($row !== false){
            $this->ajaxReturn(['status' => 1, 'msg' => '编辑成功', 'result' => '']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
        }
        
	}
	
	/**
     * 删除供应商
     */
    public function del()
    {
        
        $suppliers_id = input('id', 0);
        if(empty($suppliers_id)){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $supplier = Db::name('suppliers')->where('suppliers_id', $suppliers_id)->find();
        if(!$supplier) {
            $this->ajaxReturn(['status' => 0, 'msg' => '非法操作', 'result' => '']);
        }
		
		$supplierGoodsCount = Db::name('goods')->where("suppliers_id", $suppliers_id)->count();
		if ($supplierGoodsCount > 0) {
			$this->ajaxReturn(['status' => 0, 'msg' => '该供应商有发布商品，不得删除', 'result' => '']);
		}else{
			$row = Db::name('suppliers')->whereIn('suppliers_id', $suppliers_id)->update(['deleted'=>1]);
			if($row){
                // 同时需要删除商品关联的供应商
                Db::name('goods')->where('suppliers_id',$suppliers_id)->update(['suppliers_id'=>0]);
				adminLog("删除供应商".$supplier['suppliers_name']);
				$this->ajaxReturn(['status' => 1, 'msg' => '删除供应商“'.$supplier['suppliers_name'].'”成功', 'url' => url("Admin/Supplier/index"), 'result' => '']);
			}else{
				$this->ajaxReturn(['status' => 0, 'msg' => '删除失败', 'result' => '']);
			}
		}
		
    }
	
	/**
     * 修改供应商状态
     */
    public function changeSupplierIsCheck()
    {
        
        $suppliers_id = input('suppliers_id', 0);
        $supplier = Db::name('suppliers')->where('suppliers_id', $suppliers_id)->find();
		if ($supplier) {
			$is_check = $supplier['is_check'] ? 0 : 1;
			Db::name('suppliers')->where('suppliers_id', $suppliers_id)->update(['is_check' => $is_check]);
			Db::name('goods')->where(['suppliers_id' => $suppliers_id, 'audit' => 0])->update(['is_on_sale' => $is_check]);
			$supplierGoodsCount = Db::name('goods')->where(['suppliers_id' => $suppliers_id, 'is_on_sale' => 1])->count();
			$this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'result' => ['suppliers_id' => $suppliers_id, 'goods_count' => $supplierGoodsCount]]);
		} else {
			$this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
		}
		
    }
	
	/**
     * 供应商提现申请记录
     */
    public function withdrawalsList()
    {
        
		$this->get_supplier_withdrawals(null);
		
        return View::fetch();
    }
	
    /**
     *  供应商转账汇款记录
     */
    public function remittance(){
        
    	$status = input('status', 1);
    	View::assign('status',$status);
		$this->get_supplier_withdrawals($status);
		
        return View::fetch();
    }
	
	public function get_supplier_withdrawals($status){
	    
    	$suppliers_id = input('suppliers_id');
    	$realname = input('realname');
    	$bank_card = input('bank_card');
    	$create_time = input('create_time');
    	
    	$create_time = str_replace("+"," ",$create_time);
    	$create_time2 = $create_time  ? $create_time  : date('Y-m-d',strtotime('-1 year')).' - '.date('Y-m-d',strtotime('+1 day'));
    	$create_time3 = explode(' - ',$create_time2);
    	View::assign('start_time', $create_time3[0]);
    	View::assign('end_time', $create_time3[1]);
    	$where['sw.create_time'] =  array(array('>', strtotime($create_time3[0])), array('<', strtotime($create_time3[1])));
    	$suppliers_id && $where['sw.suppliers_id'] = $suppliers_id;
        $status = empty($status) ? input('status') : $status;
//    	if($status === '0' || $status > 0) {
    		$where['sw.status'] = $status;
//    	}
    	$bank_card && $where['sw.bank_card'] = array('like','%'.$bank_card.'%');
    	$realname && $where['sw.realname'] = array('like','%'.$realname.'%');
		$export = input('export');
        if($export == 1){
            $this->exportSupplierWithdrawals($where);  //打印
        }
    	$count = Db::name('supplier_withdrawals')
			->alias('sw')
			->join('suppliers s','s.suppliers_id = sw.suppliers_id', 'INNER')
			->where($where)->count();
    	$Page  = new Page($count,20);
    	$list = Db::name('supplier_withdrawals')
			->alias('sw')
			->field('sw.*,s.suppliers_name')
			->join('suppliers s','s.suppliers_id = sw.suppliers_id', 'INNER')
			->where($where)
			->order("id desc")->limit($Page->firstRow,$Page->listRows)->select();

    	View::assign('create_time',$create_time2);
    	$show  = $Page->show();
    	View::assign('list',$list);
    	View::assign('pager',$Page);
    	config('TOKEN_ON',false);
    	
    }
	
	/**
	 * 打印转款记录
	 */
	public function  exportSupplierWithdrawals($where){
		$ids = input('remittance_ids');
		if ($ids) {
			$where = ['id' => ['in', $ids]];
		}
	    
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">供应商</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">提现金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行账号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">开户人姓名</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">申请时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">提现备注</td>';
        $strTable .= '</tr>';
        $remittanceList = Db::name('supplier_withdrawals')
			->alias('sw')
			->field('sw.*,s.suppliers_name')
			->join('suppliers s','s.suppliers_id = sw.suppliers_id', 'INNER')
			->where($where)
			->order("sw.id desc")->select();
        if($remittanceList->toArray()){
            foreach($remittanceList as $k=>$val){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">'.$val['suppliers_name'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['money'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['bank_name'].'</td>';
                $strTable .= '<td style="vnd.ms-excel.numberformat:@">'.$val['bank_card'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['realname'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.date('Y-m-d H:i:s',$val['create_time']).'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['remark'].'</td>';
                $strTable .= '</tr>';
            }
        }
        $strTable .='</table>';
        unset($remittanceList);
        downloadExcel($strTable,'remittance');
        exit();
        
    }

	/**
     *  供应商结算记录
     */
    public function orderStatis(){
        
        $suppliers_id = input('suppliers_id/d',0);
        $create_date = input('create_date');
        $create_date = str_replace("+"," ",$create_date);
        $create_date2 = $create_date  ? $create_date  : date('Y-m-d',strtotime('-1 month')).' - '.date('Y-m-d',strtotime('+1 month'));
        $create_date3 = explode(' - ',$create_date2);
        $where = " os.create_date >= '".strtotime($create_date3[0])."' and os.create_date <= '".strtotime($create_date3[1])."' ";
        View::assign('start_time',$create_date3[0]);
        View::assign('end_time',$create_date3[1]);
        $suppliers_id && $where .= " and os.suppliers_id = $suppliers_id ";

        $count = Db::name('order_statis')->alias('os')->where($where)->count();
        $Page  = new Page($count,20);
        $list = Db::name('order_statis')
            ->alias('os')->join('suppliers s','s.suppliers_id = os.suppliers_id')
            ->where($where)->order("id desc")->limit($Page->firstRow,$Page->listRows)->select();
        
        View::assign('create_date',$create_date2);
        View::assign('pager',$Page);
        View::assign('list',$list);
        config('TOKEN_ON',false);
        
        return View::fetch();
        
    }
	
	/**
     *  处理供应商提现申请  供应商提现
     */
    public function withdrawalsUpdate(){
        
    	$id = input('id/a');
        $data['status']= $status = input('status');
        if($status == 1) $data['check_time'] = time();
        if($status != 1) $data['refuse_time'] = time();
        $data['remark'] = input('remark');
        $r = Db::name('supplier_withdrawals')->where('id in ('.implode(',', $id).')')->save($data);
    	if($r){
    		$this->ajaxReturn(array('status'=>1,'msg'=>"操作成功"),'JSON');
    	}else{
    		$this->ajaxReturn(array('status'=>0,'msg'=>"操作失败"),'JSON');
    	}
    	
    }
	
	/**
	 * 修改编辑供应商 申请提现
	 */
	public function supplierWithdrawalsInfo()
	{
	    
		$id = input('id');
		$withdrawals = Db::name('supplier_withdrawals')->where('id', $id)->find();
		$supplier = Db::name('suppliers')->where("suppliers_id", $withdrawals['suppliers_id'])->find();
		View::assign('supplier', $supplier);
		View::assign('data', $withdrawals);
		
		return View::fetch();
	}
	
	/**
	 * 供应商提现
	 */
	public function supplierTransfer()
    {
        
        $id = input('selected/a');
        if (empty($id)) $this->error('请至少选择一条记录');
        $atype = input('atype');
        if (is_array($id)) {
            $withdrawals = Db::name('supplier_withdrawals')->where('id in (' . implode(',', $id) . ')')->select();
        } else {
            $withdrawals = Db::name('supplier_withdrawals')->where(array('id' => $id))->select();
        }

        $messageFactory = new \app\common\logic\MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 0]);

        $alipay['batch_num'] = 0;
        $alipay['batch_fee'] = 0;
        foreach ($withdrawals as $val) {
            $supplier = Db::name('suppliers')->where(array('suppliers_id' => $val['suppliers_id']))->find();
            if($supplier['supplier_money'] < $val['money'])
    		{
    			$data['status'] = -2;
    			$data['remark'] = '账户余额不足';
    			Db::name('supplier_withdrawals')->where(array('id'=>$val['id']))->save($data);
    			$this->error('账户余额不足');
    		}else{
                $rdata = array('type' => 7, 'money' => $val['money'], 'log_type_id' => $val['id'], 'suppliers_id' => $val['suppliers_id']);
                if ($atype == 'online') {
                    header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
                } else {
                    supplier_account_log($val['suppliers_id'], ($val['money'] * -1), "管理员处理供应商提现申请");//手动转账，默认视为已通过线下转方式处理了该笔提现申请
                    $r = Db::name('supplier_withdrawals')->where(array('id' => $val['id']))->save(array('status' => 2, 'pay_time' => time()));
                    expenseLog($rdata);//支出记录日志
                    // 提现通知
                    $messageLogic->supplierWithdrawalsNotice($val['id'], $supplier['user_id'], $val['money'] - $val['taxfee'], $supplier['suppliers_name']);
                }
            }
        }
        if ($alipay['batch_num'] > 0) {
            //支付宝在线批量付款
            include_once PLUGIN_PATH . "payment/newalipay/newalipay.class.php";
            $alipay_obj = new \newalipay();
            $alipay_obj->transfer($alipay);
        }
        
        $this->success("操作成功!", url('remittance'), 3);
    }
}