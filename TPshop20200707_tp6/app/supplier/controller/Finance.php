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
 * Author: IT宇宙人
 *
 * Date: 2016-03-09
 */

namespace app\supplier\controller;
use think\facade\View;

use think\facade\Db;
use think\Page;
use app\supplier\logic\FinanceLogic;

class Finance extends Base
{
    /**
     *  供应商订单结算记录
     */
    public function index()
    {
        $order_statis_where = array(
            'suppliers_id' => $this->supplier['suppliers_id'],
            'create_date' => ['between', [$this->begin, $this->end]]
        );
        $count = Db::name('order_statis')->where($order_statis_where)->count();
        $Page = new Page($count, 16);
        $list = Db::name('order_statis')->where($order_statis_where)->order("id desc")->limit($Page->firstRow,$Page->listRows)->select();

        $show = $Page->show();
        View::assign('pager', $Page);
        View::assign('list', $list);
        config('TOKEN_ON', false);
        return View::fetch();
    }

    /**
     * 订单列表
     */
    public function orderList()
    {
        $orderStatisId = input('order_statis_id', 0);
		
		$map['order_statis_id'] = $orderStatisId;
		$map['suppliers_id'] = $this->supplier['suppliers_id'];
		
        $count = Db::name("order")->where($map)->count();
        $Page = new Page($count, 16);
        $orderList = Db::name("order")
			->alias('o')
			->join('users u', 'u.user_id=o.user_id', 'left')->where($map)->order("order_id desc")->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('show', $show);
        View::assign('orderList', $orderList);
		View::assign('pager', $Page);
		
        // 订单 支付 发货状态
        View::assign('order_status',config('ORDER_STATUS'));
        View::assign('pay_status',config('PAY_STATUS'));
        View::assign('shipping_status',config('SHIPPING_STATUS'));
		
        config('TOKEN_ON', false);
        return View::fetch();
    }
	
	/**
     * 提现申请记录
     */
    public function withdrawalsList()
    {
		$supplier_money = Db::name('suppliers')->where('suppliers_id', $this->supplier['suppliers_id'])->value('supplier_money');
		View::assign('supplier_money', $supplier_money);
		
        $status = input('status');
        $bank_card = input('bank_card');
        $realname = input('realname');
        $begin =  $this->begin;
        $end   =  $this->end;
        if ($begin && $end) {
            $map = [
                'suppliers_id' => $this->supplier['suppliers_id'],
                'create_time' => ['between', [$begin,$end]]
            ];
        }
        if ($status != '') {
            $map['status'] = $status;
        } else {
			$map['status'] = ['<>', 2];
		}
        if ($bank_card) {
            $map['bank_card'] = ['like', '%' . $bank_card . '%'];
        }
        if ($realname) {
            $map['realname'] = ['like', '%' . $realname . '%'];
        }
        $count = Db::name("supplier_withdrawals")->where($map)->count();
        $Page = new Page($count, 16);
        $list = Db::name("supplier_withdrawals")->where($map)->order("id desc")->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('pager', $Page);
        View::assign('list', $list);
        config('TOKEN_ON', false);
        return View::fetch();
    }

    /**
     * 添加提现申请
     */
    public function addEditWithdrawals()
    {
        $id = input('id/d', 0);
        $withdrawals = Db::name('supplier_withdrawals')->where(array('id' => $id, 'suppliers_id' => $this->supplier['suppliers_id']))->find();

        if (IS_POST) {
            if ($withdrawals['status'] == 1) {
                $this->error('申请成功的不能再修改');
            }
            $data = input('post.');
            if ($data['money'] <= 1) {
                $this->error('提现金额不得小于1');
            }
            if ($data['id']) {
                Db::name('supplier_withdrawals')->update($data);
            } else {
                $withdrawal = Db::name('supplier_withdrawals')->where(array('suppliers_id' => $this->supplier['suppliers_id'], 'status' => 0))->sum('money');
                $supplier = Db::name('suppliers')->where("suppliers_id", $this->supplier['suppliers_id'])->find();
                if ($supplier['supplier_money'] < ($withdrawal + $data['money'])) {
                    $this->error('您有提现申请待处理，本次提现余额不足');
                }
                $data['suppliers_id'] = $this->supplier['suppliers_id'];
                $data['create_time'] = time();
                Db::name('supplier_withdrawals')->insert($data);
            }
            $this->success('保存完成', url('withdrawalsList'));
            exit();
        }
        $withdrawals_max = Db::name('suppliers')->where(array('suppliers_id' => $this->supplier['suppliers_id']))->value('supplier_money');
        $withdrawals_min = tpCache('cash.min_cash');
        View::assign('withdrawals_max', $withdrawals_max);
        View::assign('withdrawals_min', $withdrawals_min);
        View::assign('withdrawals', $withdrawals);
        return View::fetch('_withdrawals');
    }

    /**
     * 删除申请记录
     */
    public function delWithdrawals()
    {
        $id = input('del_id/d');
        $where = array(
            'id' => $id,
            'suppliers_id' => $this->supplier['suppliers_id']
        );
        Db::name('supplier_withdrawals')->where($where)->delete();
        $return_arr = array('status' => 1, 'msg' => '操作成功', 'data' => '',);
        $this->ajaxReturn($return_arr);
    }

    /**
     *  转账汇款记录
     */
    public function remittance()
    {
        $bank_card = input('bank_card');
        $realname = input('realname');
        $create_time = input('create_time');
        $create_time = str_replace("+", " ", $create_time);
        $create_time = $create_time ? $create_time : date('Y-m-d', strtotime('-1 year')) . ' - ' . date('Y-m-d', strtotime('+1 day'));
        $create_time2 = explode(' - ', $create_time);
        View::assign('start_time', $create_time2[0]);
        View::assign('end_time', $create_time2[1]);
        $map = array(
            'suppliers_id' => $this->supplier['suppliers_id'],
            'create_time' => ['between', [strtotime($create_time2[0]), strtotime($create_time2[1])]]
        );
		$map['status'] = input('status', 1);
        if ($bank_card) {
            $map['bank_card'] = ['like', '%' . $bank_card . '%'];
        }
        if ($realname) {
            $map['realname'] = ['like', '%' . $realname . '%'];
        }
		$export = input('export');
        if($export == 1){
			$ids = input('selected_ids', '');
			if ($ids) {
				$map['id'] = ['in', $ids];
			}
            $this->exportSupplierWithdrawals($map);  //打印
        }
        $count = Db::name('supplier_withdrawals')->where($map)->count();
        $Page = new Page($count, 20);
        $list = Db::name('supplier_withdrawals')->where($map)->order("id desc")->limit($Page->firstRow,$Page->listRows)->select();

		View::assign('status', $map['status']);
        View::assign('create_time', $create_time);
        View::assign('pager', $Page);
        View::assign('list', $list);
        config('TOKEN_ON', false);
        return View::fetch();
    }
	
	/**
	 * 打印转款记录
	 */
	public function  exportSupplierWithdrawals($where){
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">ID</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">提现金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行账号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">开户人姓名</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">申请时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">提现备注</td>';
        $strTable .= '</tr>';
        $remittanceList = Db::name('supplier_withdrawals')
			->where($where)
			->order("id desc")->select();
        if(is_array($remittanceList)){
            foreach($remittanceList as $k=>$val){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">'.$val['id'].'</td>';
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
}