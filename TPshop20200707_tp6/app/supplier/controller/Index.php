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
use think\Controller;
use think\facade\Db;
class Index extends Base {

    public function index(){
        //$order_amount = Db::name('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();
        //View::assign('order_amount',$order_amount);
		View::assign('menu',getMenuArr());
        return View::fetch();
    }
   
    public function welcome(){
    	$today = strtotime(date("Y-m-d"));
    	$count['handle_order'] = Db::name('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->where('suppliers_id', $this->supplier['suppliers_id'])->count();//待处理订单
    	$count['new_order'] = Db::name('order')->where("add_time >= $today")->where('suppliers_id', $this->supplier['suppliers_id'])->count();//今天新增订单
    	$count['goods'] =  Db::name('goods')->where('suppliers_id', $this->supplier['suppliers_id'])->count();//商品总数
		$warningStorage = tpCache('basic.warning_storage') ?: 10;
		$goodsId = Db::name('spec_goods_price')
			->alias('sgp')
			->join('goods g', 'sgp.goods_id=g.goods_id', 'left')
			->where("sgp.store_count<$warningStorage")
			->where(['g.suppliers_id' => $this->supplier['suppliers_id'], 'g.audit' => 0])->column('sgp.goods_id','sgp.goods_id');
		$count['stock'] = Db::name('goods')->where("store_count < $warningStorage")
			->where(['suppliers_id' => $this->supplier['suppliers_id'], 'goods_id' => ['not in', $goodsId], 'audit' => 0])->count();
		$count['stock'] += count($goodsId);
		View::assign('supplier', session('supplier'));
    	View::assign('count',$count);
        return View::fetch();
    }

    /**
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal(){  
            $table = input('table'); // 表名
            $id_name = input('id_name'); // 表主键id名
            $id_value = input('id_value'); // 表主键id值
            $field  = input('field'); // 修改哪个字段
            $value  = input('value'); // 修改字段值                        
            Db::name($table)->where([$id_name => $id_value])->save(array($field=>$value)); // 根据条件保存修改的数据
    }	    

    public function about(){
    	return View::fetch();
    }
}