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

use app\supplier\logic\GoodsLogic;
use app\supplier\logic\StockLogic;
use app\common\model\GoodsAttr;
use app\common\model\GoodsAttribute;
use app\common\model\GoodsType;
use app\common\model\SpecGoodsPrice;
use think\Controller;
use think\facade\Db;
use think\AjaxPage;
use think\Loader;

class Goods extends Base {

	/**
	 * 商品列表页
	 */
    public function index() {
		$GoodsLogic = new GoodsLogic();        
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        View::assign('categoryList',$categoryList);
        View::assign('brandList',$brandList);
        return View::fetch();
    }
	
	/**
	 * ajax商品列表
	 */
	public function ajaxGoodsList() {
        $where = ' 1 = 1 '; // 搜索条件  
        input('brand_id') && $where = "$where and brand_id = ".input('brand_id/d') ;
        (input('is_on_sale') !== '') && $where = "$where and is_on_sale = ".input('is_on_sale/d') ;                
        $cat_id = input('cat_id');
        // 关键词搜索               
        $key_word = input('key_word') ? trim(input('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')" ;
        }
        
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
		$where = "$where and suppliers_id = " . $this->supplier['suppliers_id'] . ' and audit = 0';
        
        $count = Db::name('Goods')->where($where)->count();
      
        $Page  = new AjaxPage($count,20);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        */
        $show = $Page->show();
        $data = input('post.');
        $order_str = "{$data['orderby1']} {$data['orderby2']}";
        $goodsList = Db::name('Goods')->where($where)->order($order_str)->limit($Page->firstRow,$Page->listRows)->select();

        $catList = Db::name('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        View::assign('catList',$catList);
        View::assign('goodsList',$goodsList);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }
	
	/**
	 * 待审核商品列表页
	 */
    public function auditGoodsList() {
		$GoodsLogic = new GoodsLogic();        
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        View::assign('categoryList',$categoryList);
        View::assign('brandList',$brandList);
        return View::fetch();
    }
	
	/**
	 * ajax待审核商品列表
	 */
	public function ajaxAuditGoodsList() {
        $where = []; // 搜索条件  
        input('brand_id') && $where['g.brand_id'] = input('brand_id') ;   
        $cat_id = input('cat_id');
        if($cat_id > 0) {
            $grandson_ids = getCatGrandson($cat_id); 
            $where['g.cat_id'] = ['in', implode(',', $grandson_ids)]; // 初始化搜索条件
        }
		// 关键词搜索               
        $key_word = input('key_word') ? trim(input('key_word')) : '';
		if($key_word) {
            $where['g.goods_name|g.goods_sn'] = ['like', '%'.$key_word.'%'];
        }
		$where['g.suppliers_id'] = $this->supplier['suppliers_id'];
		$where['g.audit'] = ['in', [1,2]];
        
        $count = Db::name('Goods')->alias('g')->where($where)->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $data = input('post.');
        $order_str = 'g.' . $data['orderby1'] . ' ' . $data['orderby2'];
		$subQuery = Db::name('goods_remark')->where('type', 2)->buildSql();
        $goodsList = Db::name('Goods')
			->alias('g')
			->join($subQuery . ' gr', 'g.goods_id=gr.goods_id', 'left')
			->field('g.*,gr.content')
			->where($where)
			->order($order_str)->limit($Page->firstRow,$Page->listRows)->select();
        $catList = Db::name('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        View::assign('catList',$catList);
        View::assign('goodsList',$goodsList);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }
	/**
     * 删除商品
     */
    public function delGoods() {
        $ids = input('post.ids','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $goods_ids = rtrim($ids,",");
        // 判断此商品是否有订单
        $ordergoods_count = Db::name('OrderGoods')->whereIn('goods_id',$goods_ids)->group('goods_id')->column('goods_id');
        if($ordergoods_count)
        {
            $goods_count_ids = implode(',',$ordergoods_count);
            $this->ajaxReturn(['status' => -1,'msg' =>"ID为【{$goods_count_ids}】的商品有订单,不得删除!",'data'  =>'']);
        }
         // 商品团购
        $groupBuy_goods = Db::name('group_buy')->whereIn('goods_id',$goods_ids)->group('goods_id')->column('goods_id');
        if($groupBuy_goods)
        {
            $groupBuy_goods_ids = implode(',',$groupBuy_goods);
            $this->ajaxReturn(['status' => -1,'msg' =>"ID为【{$groupBuy_goods_ids}】的商品有团购,不得删除!",'data'  =>'']);
        }
        
        //删除用户收藏商品记录
        Db::name('GoodsCollect')->whereIn('goods_id',$goods_ids)->delete();
        
        // 删除此商品        
        Db::name("Goods")->whereIn('goods_id',$goods_ids)->delete();  //商品表
        Db::name("cart")->whereIn('goods_id',$goods_ids)->delete();  // 购物车
        Db::name("comment")->whereIn('goods_id',$goods_ids)->delete();  //商品评论
        Db::name("goods_consult")->whereIn('goods_id',$goods_ids)->delete();  //商品咨询
        Db::name("goods_images")->whereIn('goods_id',$goods_ids)->delete();  //商品相册
        Db::name("spec_goods_price")->whereIn('goods_id',$goods_ids)->delete();  //商品规格
        Db::name("spec_image")->whereIn('goods_id',$goods_ids)->delete();  //商品规格图片
        Db::name("goods_attr")->whereIn('goods_id',$goods_ids)->delete();  //商品属性
        Db::name("goods_collect")->whereIn('goods_id',$goods_ids)->delete();  //商品收藏

        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Supplier/Goods/auditGoodsList")]);
    }
	
	/**
     * 添加修改商品
     */
    public function addEditGoods()
    {
        $GoodsLogic = new GoodsLogic();
        $Goods = new \app\common\model\Goods();
        $goods_id = input('id');


        if($goods_id){
            $goods = $Goods->where('goods_id', $goods_id)->find();
            $level_cat = $GoodsLogic->find_parent_cat($goods['cat_id']); // 获取分类默认选中的下拉框
            $level_cat2 = $GoodsLogic->find_parent_cat($goods['extend_cat_id']); // 获取分类默认选中的下拉框
            $brandList = $GoodsLogic->getSortBrands($goods['cat_id']);   //获取三级分类下的全部品牌
            View::assign('goods', $goods);
            View::assign('level_cat', $level_cat);
            View::assign('level_cat2', $level_cat2);
            //View::assign('brandList', $brandList);

            // 判断活动有没有过期，过期则设置为无活动。
            $goodsPromFactory = new  \app\common\logic\GoodsPromFactory();
            if ($goods['prom_type'] && $goodsPromFactory->checkPromType($goods['prom_type'])) {
                $specGoodsPrice = SpecGoodsPrice::find(0);
                $goodsPromLogic = $goodsPromFactory->makeModule($goods, $specGoodsPrice);
                if ($goodsPromLogic && !$goodsPromLogic->checkActivityIsAble()) {
                    $goods->prom_type = 0;
                    $goods->save();
                }
            }
        }else{
            $brandList = $GoodsLogic->getSortBrands();   //获取全部品牌
        }
        $cat_list = Db::name('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
        $goodsType = Db::name("GoodsType")->select();
        $freight_template = Db::name('freight_template')->where('')->select();
        //$bespeak_template = Db::name('bespeak_template')->where(['deleted'=>1])->select();
        $goodsLabelList = Db::name('goods_label')->order('sort desc')->select();
        
        View::assign('suppliersId', $this->supplier['suppliers_id']);
		View::assign('brandList', $brandList);
        //View::assign('bespeak_template',$bespeak_template);
        View::assign('freight_template',$freight_template);
        View::assign('cat_list', $cat_list);
        View::assign('goodsType', $goodsType);
        View::assign('goodsLabelList', $goodsLabelList);
        return View::fetch('_goods');
    }
	
	/**
	 * 商品提交审核
	 */
	public function upload() {
        $data = input('post.');
        $spec_item = input('item/a', []);
        $validate = validate(\app\supplier\validate\Goods::class);
// 数据验证
        if (!$validate->batch(true)->check($data)) {
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => 0, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);
        }
		$specValidate = validate(\app\supplier\validate\SpecGoodsPrice::class);

		foreach ($spec_item as $val) {
			$val['spec_cost_price'] = $val['cost_price']; //为了防止和商品的cost_price字段冲突（返回错误时，字段一样会导致商品模型里的cost_price错误显示到通用信息里）
			if (!$specValidate->batch(true)->check($val)) {
				$error = $specValidate->getError();
				$error_msg = array_values($error);
				$return_arr = ['status' => 0, 'msg' => $error_msg[0], 'result' => $error];
				$this->ajaxReturn($return_arr);
			}
			unset($val['spec_cost_price']);
		}
        if ($data['goods_id'] > 0) {
            $goods = \app\common\model\Goods::find($data['goods_id']);
            if($data['is_virtual']==2){
                //判断预约模板是否修改
                if($goods['bespeak_template_id'] != $data['bespeak_template_id']){
                    $shopOrder_count = Db::name('shop_order')
                        ->alias('s')
                        ->join('order o','o.order_id = s.order_id','LEFT')
                        ->where(['s.is_write_off'=>0,'s.goods_id'=>$data['goods_id'],'o.pay_status'=>1,'o.order_status'=>['<>',2]])
                        ->where(['o.order_status'=>['<>',3]])
                        ->where(['o.order_status'=>['<>',4]])
                        ->where(['o.order_status'=>['<>',5]])
                        ->count();
                    if($shopOrder_count){
                        $this->ajaxReturn(['status' => 0, 'msg' => '该预约模板还有未核销的订单，不能修改','result'=>'']);
                    }
                }

            }
            $store_count_change_num = $data['store_count'] - $goods['store_count'];//库存变化量
            $cart_update_data = ['market_price'=>$data['market_price'],'goods_price'=>$data['shop_price'],'member_goods_price'=>$data['shop_price']];
            Db::name('cart')->where(['goods_id'=>$data['goods_id'],'spec_key'=>''])->save($cart_update_data);
            //编辑商品的时候需清楚缓存避免图片失效问题
            clearCache();
        }else{
            $goods = new \app\common\model\Goods();
            $store_count_change_num = $data['store_count'];
        }
        $goods->data($data, true);
        $goods->last_update = time();
        $goods->price_ladder = true;
		$goods->audit = 1;
		$goods->is_on_sale = 0;
        $goods->save();
        $GoodsLogic = new GoodsLogic();
        $GoodsLogic->afterSave($goods['goods_id']);
        $GoodsLogic->saveGoodsAttr($goods['goods_id'], $goods['goods_type']); // 处理商品 属性*/
        $return_arr = ['status' => 1, 'msg' => '操作成功'];
        $this->ajaxReturn($return_arr);
    }
	
	/**
     * 动态获取商品规格选择框 根据不同的数据返回不同的选择框
     */
    public function ajaxGetSpecSelect()
    {
        $goods_id = input('goods_id/d', 0);
        $type_id = input('type_id/d', 0);
        $specList = Db::name('Spec')->where("type_id", $type_id)->order('order desc')->select()->toArray();
        foreach ($specList as $k => $v)
            $specList[$k]['spec_item'] = Db::name('SpecItem')->where("spec_id = " . $v['id'])->order('id')->column('item','id'); // 获取规格项
        $items_id = Db::name('SpecGoodsPrice')->where('goods_id', $goods_id)->value("GROUP_CONCAT(`key` SEPARATOR '_') AS items_id");
        $items_ids = explode('_', $items_id);
        // 获取商品规格图片
        if ($goods_id) {
            $specImageList = Db::name('spec_image')->where("goods_id", $goods_id)->column('src','spec_image_id');
            View::assign('specImageList', $specImageList);
        }
        View::assign('items_ids', $items_ids);
        View::assign('specList', $specList);
        return View::fetch();
    }    
    
    /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     */    
    public function ajaxGetSpecInput(){     
         $GoodsLogic = new GoodsLogic();
         $goods_id = input('goods_id/d') ? input('goods_id/d') : 0;
         $str = $GoodsLogic->getSpecInput($goods_id ,input('post.spec_arr/a',[[]]));
         exit($str);   
    }
	
	/**
     * 动态获取商品属性输入框 根据不同的数据返回不同的输入框类型
     */
    public function ajaxGetAttrInput()
    {
        $type_id = input('type_id/d', 0);
        $goods_id = input('goods_id/d', 0);
        $GoodsAttribute = new GoodsAttribute();
        $attribute_list = $GoodsAttribute->where(['type_id' => $type_id,'attr_index'=>1])->order('order desc')->select();
        if ($attribute_list) {
            $attribute_list = $attribute_list->append(['attr_values_to_array'])->toArray();
        }
        $GoodsAttr = new GoodsAttr();
        foreach ($attribute_list as $attribute_key => $attribute_val) {
            $goods_attr = $GoodsAttr->where(['goods_id' => $goods_id, 'attr_id' => $attribute_val['attr_id']])->find();
            $attribute_list[$attribute_key]['goods_attr'] = $goods_attr;
        }
        $this->ajaxReturn($attribute_list);
    }
	
	/**
     * 删除商品相册图
     */
    public function delGoodsImages()
    {
        $path = input('filename','');
        Db::name('goods_images')->where("image_url = '$path'")->delete();
    }
	
	/**
	 * 品牌选项框
	 */
	public  function getCategoryBrandList(){
        $cart_id = input('cart_id/d',0);
        $GoodsLogic = new GoodsLogic();
        $brandList = $GoodsLogic->getSortBrands($cart_id);
        $this->ajaxReturn(['status'=>1,'result'=>$brandList]);
    }
	
	/**
     * 出入库日志
     */
    public function stockList()
    {
		$mtype = input('mtype', -1);
        $ctime = urldecode(input('ctime'));
        if($ctime){
            $gap = explode(' - ', $ctime);
            View::assign('start_time',$gap[0]);
            View::assign('end_time',$gap[1]);
            View::assign('ctime',$gap[0].' - '.$gap[1]);
        }
        $logic = new StockLogic();
		$logic->setSuppliersId($this->supplier['suppliers_id']);
        $res = $logic->getStockList();
        View::assign('pager',$res['pager']);
        View::assign('page',$res['page']);// 赋值分页输出
        View::assign('stock_list',$res['stock_list']);
        View::assign('stockChangeType', $res['stockChangeType']);
		View::assign('mtype', $mtype);
        return View::fetch();
    }
	
	/**
     * 库存预警
     */
    public function lowStockWarn()
    {
        $GoodsLogic = new GoodsLogic();
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        View::assign('categoryList',$categoryList);
        View::assign('brandList',$brandList);
        return View::fetch();
    }

    /**
     * 库存预警(获取列表）
     */
    public function ajaxLowStockWarn()
    {
        $logic = new StockLogic();
		$logic->setSuppliersId($this->supplier['suppliers_id']);
        $res = $logic->getAjaxLowStockWarn();
        View::assign('pager',$res['pager']);
        View::assign('page',$res['page']);// 赋值分页输出
        View::assign('catList',$res['catList']);
        View::assign('brand_list',$res['brand_list']);
        View::assign('goodsList',$res['goodsList']);
        return View::fetch('ajaxAlterStock');
    }

    /**
     * 库存盘点
     */
    public function alterStock()
    {
        $GoodsLogic = new GoodsLogic();
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        View::assign('categoryList',$categoryList);
        View::assign('brandList',$brandList);
        return View::fetch();
    }

    /**
     * 库存盘点（ajax获取库存列表）
     */
    public function ajaxAlterStock()
    {

        $logic = new StockLogic();
		$logic->setSuppliersId($this->supplier['suppliers_id']);
        $res = $logic->getAjaxAlterStock();
        View::assign('pager',$res['pager']);
        View::assign('page',$res['page']);// 赋值分页输出
        View::assign('catList',$res['catList']);
        View::assign('brand_list',$res['brand_list']);
        View::assign('goodsList',$res['goodsList']);
        return View::fetch();
    }

    /**
     * 库存盘点（快速修改库存）
     */
    public function changeStockVal()
    {
        $logic = new StockLogic();
		$logic->setSuppliersId($this->supplier['suppliers_id']);
        $res = $logic->doChangeStockVal(); //传入admin_id用于记录stock_log
        ajaxReturn($res);
    }
	
}