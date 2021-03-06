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
namespace app\admin\controller;
use think\facade\View;
use app\admin\logic\GoodsLogic;
use app\admin\logic\SearchWordLogic;
use app\common\model\GoodsAttr;
use app\common\model\GoodsAttribute;
use app\common\model\GoodsType;
use app\common\model\Spec;
use app\common\model\SpecGoodsPrice;
use app\common\model\SpecItem;
use app\common\model\GoodsCategory;
use app\common\util\TpshopException;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Reader_CSV;
use PHPExcel_Reader_Excel5;
use think\AjaxPage;
use think\facade\Cache;
use think\Loader;
use think\Page;
use think\facade\Db;

use app\admin\logic\StockLogic;

class Goods extends Base {

    /**
     *  商品分类列表
     */
    public function categoryList(){                
        $GoodsLogic = new GoodsLogic();               
        $cat_list = $GoodsLogic->goods_cat_list();
        View::assign('cat_list',$cat_list);        
        return View::fetch();
    }
    
    /**
     * 添加修改商品分类
     * 手动拷贝分类正则 ([\u4e00-\u9fa5/\w]+)  ('393','$1'), 
     * select * from tp_goods_category where id = 393
        select * from tp_goods_category where parent_id = 393
        update tp_goods_category  set parent_id_path = concat_ws('_','0_76_393',id),`level` = 3 where parent_id = 393
        insert into `tp_goods_category` (`parent_id`,`name`) values 
        ('393','时尚饰品'),
     */
    public function addEditCategory(){
        
            $GoodsLogic = new GoodsLogic();        
            if(IS_GET)
            {
                $goods_category_info = Db::name('GoodsCategory')->where('id='.input('get.id',0))->find();
                View::assign('goods_category_info',$goods_category_info);
                
                $all_type = Db::name('goods_category')->where("level < 3")->column('id,name,parent_id','id');//上级分类数据集，限制3级分类，那么只拿前两级作为上级选择
                if(!empty($all_type)){
                	$parent_id = empty($goods_category_info) ? input('parent_id',0) : $goods_category_info['parent_id'];
                	$all_type = $GoodsLogic->getCatTree($all_type);
                	$cat_select = $GoodsLogic->exportTree($all_type,0,$parent_id);
                	View::assign('cat_select',$cat_select);
                }
                
                //$cat_list = Db::name('goods_category')->where("parent_id = 0")->select(); 
                //View::assign('cat_list',$cat_list);         
                return View::fetch('_category');
                exit;
            }

            $GoodsCategory = new GoodsCategory(); // Db::name('GoodsCategory'); //

            $type = input('id') > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新                        
            //ajax提交验证
            if(input('is_ajax') == 1)
            {
                // 数据验证            
                $validate = validate(\app\admin\validate\GoodsCategory::class);

                if(!$validate->batch(true)->check(input('post.')))
                {                          
                    $error = $validate->getError();
                    $error_msg = array_values($error);
                    $return_arr = array(
                        'status' => -1,
                        'msg' => $error_msg[0],
                        'data' => $error,
                    );
                    $this->ajaxReturn($return_arr);
                } else {
                    $GoodsCategory->data(input('post.'),true); // 收集数据
                    $GoodsCategory->parent_id = input('parent_id');
                    
                    //查找同级分类是否有重复分类
                    $par_id = ($GoodsCategory->parent_id > 0) ? $GoodsCategory->parent_id : 0;
                    $sameCateWhere = ['parent_id'=>$par_id , 'name'=>$GoodsCategory['name']];
                    $GoodsCategory->id && $sameCateWhere['id'] = array('<>' , $GoodsCategory->id);
                    $same_cate = Db::name('GoodsCategory')->where($sameCateWhere)->find();
                    if($same_cate){
                        $return_arr = array('status' => 0,'msg' => '同级已有相同分类存在','data' => '');
                        $this->ajaxReturn($return_arr);
                    }
                    
                    if ($GoodsCategory->id > 0 && $GoodsCategory->parent_id == $GoodsCategory->id) {
                        //  编辑
                        $return_arr = array('status' => 0,'msg' => '上级分类不能为自己','data' => '',);
                        $this->ajaxReturn($return_arr);
                    }

                    //判断不能为自己的子类
                    if ($GoodsCategory->id > 0) {
                        $category_id_list = Db::name('goods_category')->where('parent_id',$GoodsCategory->id)->field('id')->select()->toArray();                    
                        $category_id_list = array_column($category_id_list,'id');
                        if (in_array($GoodsCategory->parent_id,$category_id_list)) {
                            $return_arr = array('status' => 0,'msg' => '上级分类不能为自己的子类','data' => '');
                            $this->ajaxReturn($return_arr);
                        }
                    }


                    if($GoodsCategory->commission_rate > 100)
                    {
                        //  编辑
                        $return_arr = array('status' => -1,'msg'   => '分佣比例不得超过100%','data'  => '');
                        $this->ajaxReturn($return_arr);                        
                    }   
                   
                    if ($type == 2)
                    {
                        //$GoodsCategory->isUpdate(true)->save(); // 写入数据到数据库
						$GoodsCategory::update($GoodsCategory->getData()); 
                        $GoodsLogic->refresh_cat(input('id'));
                    }
                    else
                    {
                        $GoodsCategory->save(); // 写入数据到数据库
                        //$insert_id = $GoodsCategory->getLastInsIDb::name();
						$insert_id = $GoodsCategory->id;
                        $GoodsLogic->refresh_cat($insert_id);
                    }
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',
                        'data'  => array('url'=>url('Admin/Goods/categoryList')),
                    );
                    $this->ajaxReturn($return_arr);

                }  
            }

    }

    /**
     * 删除分类
     */
    public function delGoodsCategory(){
        $ids = input('post.ids','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        // 判断子分类
        $count = Db::name("goods_category")->where("parent_id = {$ids}")->count("id");
        $count > 0 && $this->ajaxReturn(['status' => -1,'msg' =>'该分类下还有分类不得删除!']);
        // 判断是否存在商品
        $goods_count = Db::name('Goods')->where("cat_id = {$ids}")->count('*');
        $goods_count > 0 && $this->ajaxReturn(['status' => -1,'msg' =>'该分类下有商品不得删除!']);
        // 删除分类
        DB::name('goods_category')->where('id',$ids)->delete();
        $this->ajaxReturn(['status' => 1,'msg' =>'操作成功','url'=>url('Admin/Goods/categoryList')]);
    }

    /**
     * 商品标签
     */
    public function goods_label(){

        return View::fetch();
    }
    public function ajaxGoodsLabel(){
        $where = [];
        $count = Db::name('goods_label')->where($where)->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $goodsList = Db::name('goods_label')->where($where)->order('label_id desc')->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('goodsList',$goodsList);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }
    /**
     * 添加修改商品标签
     */
    public function addEditGoodsLabel()
    {

        $data = input('post.');
        if($data){
            if(empty($data['label_id'])){
                $data['create_time'] = time();
                Db::name('goods_label')->insert($data);
            }else{
                Db::name('goods_label')->update($data);
            }
            $return_arr = array(
                'status' => 1,
                'msg'   => '操作成功',
                'data'  => array('url'=>url('Admin/Goods/goods_label')),
            );
            $this->ajaxReturn($return_arr);
        }
        $label_id = input('label_id');
        if($label_id){
            $arr = Db::name('goods_label')->find($label_id);
            View::assign('data', $arr);
        }
        return View::fetch('_goodsLabel');
    }

    /**
     * 删除商品标签
     */
    public function delGoodsLabel()
    {
        $ids = input('post.ids','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $goods_ids = rtrim($ids,",");
        Db::name('goods_label')->whereIn('label_id',$goods_ids)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/goods/goods_label")]);
    }

    /**
     *  商品列表
     */
    public function goodsList(){      
        $GoodsLogic = new GoodsLogic();        
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        View::assign('categoryList',$categoryList);
        View::assign('brandList',$brandList);
		//供应商列表
		$supplierList = Db::name('suppliers')->column('suppliers_name','suppliers_id');
		View::assign('supplier_list', $supplierList);
        return View::fetch();
    }
    
    /**
     *  商品列表
     */
    public function ajaxGoodsList(){            
        
        $where = ' 1 = 1 '; // 搜索条件                
        input('intro')    && $where = "$where and ".input('intro')." = 1" ;        
        input('brand_id') && $where = "$where and brand_id = ".input('brand_id') ;
        (input('is_on_sale') !== '') && $where = "$where and is_on_sale = ".input('is_on_sale') ;                
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
        $where .= ' and audit = 0';
		$suppliersId = input('suppliers_id');
		if ($suppliersId >= 0) {
			$where .= " and suppliers_id = $suppliersId";
		}
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
		
		//供应商列表
		$supplierList = Db::name('suppliers')->column('suppliers_name','suppliers_id');
		View::assign('supplier_list', $supplierList);

        $catList = Db::name('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        View::assign('catList',$catList);
        View::assign('goodsList',$goodsList);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }


    /**
     * 出入库日志
     */
    public function stockList()
    {
        $ctime = urldecode(input('ctime'));
        if($ctime){
            $gap = explode(' - ', $ctime);
            View::assign('start_time',$gap[0]);
            View::assign('end_time',$gap[1]);
            View::assign('ctime',$gap[0].' - '.$gap[1]);
        }
        $logic = new StockLogic();
        $res = $logic->getStockList();
        View::assign('pager',$res['pager']);
        View::assign('page',$res['page']);// 赋值分页输出
        View::assign('stock_list',$res['stock_list']);
        View::assign('stockChangeType', $res['stockChangeType']);
        return View::fetch('stock_list');
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
        $res = $logic->doChangeStockVal($this->admin_id); //传入admin_id用于记录stock_log
        ajaxReturn($res);

    }

    /**
     * 添加修改商品
     */
    public function addEditGoods()
    {
        $GoodsLogic = new GoodsLogic();
        $Goods = new \app\common\model\Goods();
        $goods_id = input('id');
        $goods_edit_type = input('type');


        if($goods_id){
            $goods = $Goods->where('goods_id', $goods_id)->find();
            $level_cat = $GoodsLogic->find_parent_cat($goods['cat_id']); // 获取分类默认选中的下拉框
            $level_cat2 = $GoodsLogic->find_parent_cat($goods['extend_cat_id']); // 获取分类默认选中的下拉框
            //$brandList = $GoodsLogic->getSortBrands($goods['cat_id']);   //获取三级分类下的全部品牌
            if($goods_edit_type == 'copy') {
                $goods['goods_id'] = '';
            }
            View::assign('goods', $goods);
            View::assign('level_cat', $level_cat);
            View::assign('level_cat2', $level_cat2);

            // 判断活动有没有过期，过期则设置为无活动。
            $goodsPromFactory = new  \app\common\logic\GoodsPromFactory();
            if ($goods['prom_type'] && $goods['prom_type']!= 5 && $goodsPromFactory->checkPromType($goods['prom_type'] && $goods_edit_type != 'copy')) {
                $specGoodsPrice = SpecGoodsPrice::find(0);
                $goodsPromLogic = $goodsPromFactory->makeModule($goods, $specGoodsPrice);
                if ($goodsPromLogic && !$goodsPromLogic->checkActivityIsAble()) {
                    $goods->prom_type = 0;
                    $goods->save();
                }
            }
        }
        $cat_list = Db::name('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
        $goodsType = Db::name("GoodsType")->select();
        $suppliersList = Db::name("suppliers")->where(['is_check'=>1])->column('suppliers_name','suppliers_id');
        $freight_template = Db::name('freight_template')->where('')->column('template_name','template_id');
        $bespeak_template = Db::name('bespeak_template')->where(['deleted'=>1])->select();
        $goodsLabelList = Db::name('goods_label')->order('sort desc')->select();
        $brandList = $GoodsLogic->getSortBrands();   //获取全部品牌
        View::assign('brandList', $brandList);
        View::assign('bespeak_template',$bespeak_template);
        View::assign('freight_template',$freight_template);
        View::assign('suppliersList', $suppliersList);
        View::assign('cat_list', $cat_list);
        View::assign('goodsType', $goodsType);
        View::assign('goodsLabelList', $goodsLabelList);
        return View::fetch('_goods');
    }

    //商品保存
    public function save(){
        $data = input('post.');
        $spec_item = input('item/a', []);
        $validate = validate(\app\admin\validate\Goods::class);
// 数据验证
        if (!$validate->batch(true)->check($data)) {
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => 0, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);
        }
		$specValidate = validate(\app\admin\validate\SpecGoodsPrice::class);

		foreach ($spec_item as $val) {
			if (!$specValidate->batch(true)->check($val)) {
				$error = $specValidate->getError();
				$error_msg = array_values($error);
				$return_arr = ['status' => 0, 'msg' => $error_msg[0], 'result' => $error];
				$this->ajaxReturn($return_arr);
			}
		}
		if ($data['is_virtual'] != 0) { //只有实物商品才有供应商
			$data['suppliers_id'] = 0;
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
            
        }else{
            $goods = new \app\common\model\Goods();
            $store_count_change_num = $data['store_count'];
        }
        $goods->data($data, true);
        $goods->last_update = time();
        $goods->price_ladder = true;
		if (!empty($data['behavior']) && $data['behavior'] == 'audit') {
			$goods->audit = 0;
			$goods->is_on_sale = 1;
		}
        $goods->save();
        if(empty($spec_item)){
            update_stock_log(session('admin_id'), $store_count_change_num, ['goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name']]);//库存日志
        }
        $GoodsLogic = new GoodsLogic();
        $GoodsLogic->afterSave($goods['goods_id']);
        $GoodsLogic->saveGoodsAttr($goods['goods_id'], $goods['goods_type']); // 处理商品 属性
        $return_arr = ['status' => 1, 'msg' => '操作成功'];
        //编辑商品的时候需清楚缓存避免图片失效问题
        if($goods['goods_id']){
            delFile(UPLOAD_PATH . "goods/thumb/" . $goods['goods_id']); // 删除缩略图
            Cache::clear('original_img_cache');
        }
        clearCache();
        $this->ajaxReturn($return_arr);
    }

    public  function getCategoryBrandList(){
        $cart_id = input('cart_id/d',0);
        $GoodsLogic = new GoodsLogic();
        $brandList = $GoodsLogic->getSortBrands($cart_id);
        $this->ajaxReturn(['status'=>1,'result'=>$brandList]);
    }
    /**
     * 商品类型  用于设置商品的属性
     */
    public function type_list()
    {
        $name = input('name/s');
        $where = [];
        if ($name) {
            $where['name'] = ['like','%'.$name.'%'];
        }
        $GoodsType = new GoodsType();
        $count = $GoodsType->where($where)->count();
        $page = new Page($count, 14);
        $goods_type_list = $GoodsType->where($where)->order("id desc")->limit($page->firstRow,$page->listRows)->select();
        View::assign('page', $page);
        View::assign('goods_type_list', $goods_type_list);
        return View::fetch();
    }

    //商品模型编辑页
    public function type()
    {
        $id = input('id/d');
        if($id){
            $goods_type = GoodsType::where(['id'=>$id])->find();
            View::assign('goods_type', $goods_type);
        }
        return View::fetch();
    }

    //商品模型保存
    public function saveType()
    {
        $data = input('post.');
        $validate = validate(\app\admin\validate\GoodsType::class);
// 数据验证
        if (!$validate->batch(true)->check($data)) {
            $error = $validate->getError();
            $error_msg = array_values($error);
            $this->ajaxReturn(['status' => 0, 'msg' => $error_msg[0], 'result' => $error]);
        }
        if ($data['id'] > 0) {
            $goodsType = GoodsType::find($data['id']);
        }else{
            $goodsType = new GoodsType();
        }
        try{
            $goodsType->data($data, true)->save();
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功','type_id'=>$goodsType->id]);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn(['status' => 1, 'msg' => $error[0]]);
        }
    }

    //删除商品模型
    public function deleteType()
    {
        $id = input('id/d');
        if(empty($id)){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $GoodsType = new GoodsType();
        $goods_type = $GoodsType->where(['id'=>$id])->find();
        try {
            $goods_type->delete();
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
    }

    public function delTypes(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $typesIds = rtrim($ids);
        Db::name('GoodsType')->whereIn('id',$typesIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Goods/type_list")]);
    }

    //删除规格项
    public function deleteSpe()
    {
        $id = input('id/d');
        if (empty($id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $Spec = new Spec();
        $spec = $Spec->where('id', $id)->find();
        try {
            $spec->delete();
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    //删除规格值
    public function deleteSpeItem()
    {
        $id = input('id/d');
        if (empty($id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $SpeItem = new SpecItem();
        $spec_item = $SpeItem->where('id', $id)->find();
        try{
            $spec_item->delete();
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    //删除属性
    public function deleteAttribute()
    {
        $attr_id = input('attr_id/d');
        if (empty($attr_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $GoodsAttribute = new GoodsAttribute();
        $goods_attribute = $GoodsAttribute->where('attr_id', $attr_id)->find();
        try {
            $goods_attribute->delete();
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 更改指定表的指定字段
     */
    public function updateField(){
        $data = input('post.');
        $primary = array(
                'goods' => 'goods_id',
                'goods_category' => 'id',
                'brand' => 'id',            
                'goods_attribute' => 'attr_id',
        		'ad' =>'ad_id',            
        );        
        $model = Db::name($data['table']);
        $model->$primary[$data['table']] = $data['id'];
        $model->$data['field'] = $data['value'];
        $model->save();   
        $return_arr = array(
            'status' => 1,
            'msg'   => '操作成功',                        
        );
        $this->ajaxReturn($return_arr);
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
     * 删除商品
     */
    public function delGoods()
    {
        $ids = input('ids','');
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

        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/goods/goodsList")]);
    }
    /**
     * 品牌列表
     */
    public function brandList(){
        $keyword = input('keyword');
        $where = $keyword ? " name like '%$keyword%' " : "";
        $count = Db::name("Brand")->where($where)->count();
        $Page = $pager = new Page($count,10);        
        $brandList = Db::name("Brand")->where($where)->order("sort desc")->limit($Page->firstRow,$Page->listRows)->select();
        $show  = $Page->show(); 
        $cat_list = Db::name('goods_category')->where("parent_id = 0")->column('name','id'); // 已经改成联动菜单
        View::assign('cat_list',$cat_list);       
        View::assign('pager',$pager);
        View::assign('show',$show);
        View::assign('brandList',$brandList);
        return View::fetch('brandList');
    }

    /**
     * 添加修改编辑  商品品牌
     */
    public function addEditBrand()
    {
        $id = input('id');
        if (IS_POST) {
            $data = input('post.');
            $brandVilidate = validate(\app\admin\validate\Brand::class);

            if (!$brandVilidate->batch(true)->check($data)) {
                $return = ['status' => 0, 'msg' => '操作失败', 'result' => $brandVilidate->getError()];
                $this->ajaxReturn($return);
            }
            if ($id) {
                Db::name("Brand")->update($data);
            } else {
                Db::name("Brand")->insert($data);
            }
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'result' => '']);
        }
        $cat_list = Db::name('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
        View::assign('cat_list', $cat_list);
        $brand = Db::name("Brand")->find($id);
        View::assign('brand', $brand);
        return View::fetch('_brand');
    }    
    
    /**
     * 删除品牌
     */
    public function delBrand()
    {
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' => '非法操作！']);
        $brind_ids = rtrim($ids,",");
        // 判断此品牌是否有商品在使用
        $goods_count = Db::name('Goods')->whereIn("brand_id",$brind_ids)->group('brand_id')->column('brand_id');
        $use_brind_ids = implode(',',$goods_count);
        if($goods_count)
        {
            $this->ajaxReturn(['status' => -1,'msg' => 'ID为【'.$use_brind_ids.'】的品牌有商品在用不得删除!','data'  =>'']);
        }
        $res=Db::name('Brand')->whereIn('id',$brind_ids)->delete();
        if($res){
            $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/goods/brandList")]);
        }
        $this->ajaxReturn(['status' => -1,'msg' => '操作失败','data'  =>'']);
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
        $items_id = Db::name('SpecGoodsPrice')->where('goods_id', $goods_id)->value('GROUP_CONCAT(`key` SEPARATOR "_") AS items_id');
        $items_ids = explode('_', $items_id);
        // 获取商品规格图片
        if ($goods_id) {
            $specImageList = Db::name('spec_image')->where("goods_id", $goods_id)->column('src','spec_image_id');
            View::assign('specImageList', $specImageList);
        }
        View::assign('items_ids', $items_ids);
        View::assign('specList', $specList);
        return View::fetch('ajax_spec_select');
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
     * 删除商品相册图
     */
    public function del_goods_images()
    {
        $path = input('filename','');
        Db::name('goods_images')->where("image_url = '$path'")->delete();
    }

    /**
     * 初始化商品关键词搜索
     */
    public function initGoodsSearchWord(){
        $searchWordLogic = new SearchWordLogic();
        $successNum = $searchWordLogic->initGoodsSearchWord();
        $this->success('成功初始化'.$successNum.'个搜索关键词');
    }

    /**
     * 初始化地址json文件
     */
    public function initLocationJsonJs()
    {
        $goodsLogic = new GoodsLogic();
        $region_list = $goodsLogic->getRegionList();//获取配送地址列表
        $area_list = $goodsLogic->getAreaList();
        $data = "var locationJsonInfoDyr = ".json_encode($region_list, JSON_UNESCAPED_UNICODE).';'."var areaListDyr = ".json_encode($area_list, JSON_UNESCAPED_UNICODE).';';
        file_put_contents(ROOT_PATH."public/public/js/locationJson.js", $data);
        $this->success('初始化地区json.js成功。文件位置为'.ROOT_PATH."public/public/js/locationJson.js");
    }
    public function getTypeById(){
        $id = input('id/d');
        return GoodsType::where(['id'=>$id])->find();
    }
    /**
     * 获取商品模型下拉列表
     */
    public function ajaxGetGoodsTypeList($type_id)
    {
        $GoodsType = new GoodsType();
        $goods_type = $GoodsType->select();

        $html = '<option value="0">选择商品模型';
        foreach ($goods_type as $v){
            $html .= "<option value='{$v['id']}'";
            if($type_id == $v['id']){
                $html .= ' selected="selected" ';
            }
            $html .= ">{$v['name']}</option>";
        }
        ajaxReturn($html);
    }

    /**
     * 添加虚拟评论
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_comment(){
        $goods = Db::name('goods')->find(input('id'));
        View::assign('order_goods',$goods);
        if (IS_POST) {
           // 晒图片
            if($_FILES['comment_img_file']['error'] != 4 && $_FILES['comment_img_file']['error'][0] != 4)
               $files = request()->file('comment_img_file');            
                        
            $save_url = 'comment/' . date('Y', time()) . '/' . date('m-d', time());
            if($files) {
                foreach ($files as $file) {                    
                            $originalName = strtolower($file->getOriginalName());
                        if(strstr($originalName,'.php') || strstr($originalName,'.js')) 
                                $this->ajaxReturn(['status' => 0,'msg' =>'上传图片格式有错误']);
                         // 上传图片
                        try {                        
                                \think\facade\Filesystem::disk('public')->putFileAs($save_url, $file,$originalName);  
                                $comment_img[] = '/' .UPLOAD_PATH.$save_url . '/' . $originalName;
                        } catch (think\exception\ValidateException $e) {
                                $upload_error = $e->getMessage();
                                $this->ajaxReturn(['status' =>-1,'msg' =>$upload_error]);                       
                        }
                }
            }
            if (!empty($comment_img)) {
                $add['img'] = serialize($comment_img);
            }

            $add['rec_id'] = 0;
            $add['goods_id'] = input('goods_id/d');
            $add['email'] = '';
            $hide_username = input('hide_username');
            $add['username'] = $hide_username?$hide_username:'匿名用户';
            $add['is_anonymous'] = $hide_username?0:1;  //是否匿名评价:0不是\1是
            $add['order_id'] = 0;
            $add['service_rank'] = input('service_rank');
            $add['deliver_rank'] = input('deliver_rank');
            $add['goods_rank'] = input('goods_rank');
            $add['is_show'] = 1; //默认显示
            $add['content'] = input('content');
            $add['item_id'] = 0;
            $add['add_time'] = time();
            $add['ip_address'] = request()->ip();
            $add['user_id'] = 0;
            //添加评论
            $row = $row = Db::name('comment')->insertGetId($add);
            if ($row) {
                Db::name('goods')->where(['goods_id'=>$add['goods_id']])->inc('comment_count')->update();
                $this->ajaxReturn(['status' => 1,'msg' =>'评论成功']);
            } else {
                $this->ajaxReturn(['status' => 0,'msg' =>'评论失败']);
            }
        }
        return View::fetch();
    }


    //excel导入处理
    public function excel_import()
    {
        if($_FILES['excel']['error'] != 4)
             $file = request()->file('excel');
        if(!$file)
            $this->error("导入excel文件失败");
        
        $goods_id = input('goods_id');
        $path = 'public/upload/excel/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        $originalName = strtolower($file->getOriginalName());
        if(strstr($originalName,'.php') || strstr($originalName,'.js')) 
                $state = '上错文件格式错误';
        $extension = strtolower($file->extension());
        if(!in_array($extension,['xlsx']))
               $this->error("仅可上传xlsx文件");

        // 上传 cvs
        try {          
                $excel = $savename = md5(mt_rand()).'.'.$file->extension();
                \think\facade\Filesystem::disk('public')->putFileAs($path, $file,$savename);  
        } catch (think\exception\ValidateException $e) {
                $upload_error = $e->getMessage();
                 $this->error($upload_error);
        }          
   
        //导入的excel数据处理开始
        $excel = 'public/upload/'.$path . $excel;         
        $arr = $this->importExcel($excel);//

        //excel模板头数组
        $excel_model = array('会员昵称', '评价内容', '商品星级', '服务星级', '发货星级', '评论时间');
        $excel_title = $arr[1];//excel头部标题部分
        if ($excel_title !== $excel_model) {
            $this->error('excel数据格式错误,请下载并参照excel模板');
        }
        unset($arr[1]);

        foreach ($arr as $k=>$v)
        {
            $data[$k]['goods_id']  = $goods_id;
            $data[$k]['rec_id'] = 0;
            $data[$k]['goods_id'] = input('goods_id/d');
            $data[$k]['email'] = '';
            $data[$k]['username'] = $v[0]?$v[0]:'匿名用户';
            $data[$k]['is_anonymous'] = $v[0]?0:1;  //是否匿名评价:0不是\1是
            $data[$k]['order_id'] = 0;
            $data[$k]['service_rank'] = (int)$v[3];
            $data[$k]['deliver_rank'] = (int)$v[4];
            $data[$k]['goods_rank'] = (int)$v[2];
            $data[$k]['is_show'] = 1; //默认显示
            $data[$k]['content'] = $v[1];
            $data[$k]['item_id'] = 0;
            $data[$k]['add_time'] = strtotime($v[5])?strtotime($v[5]):time();
            $data[$k]['ip_address'] = request()->ip();
            $data[$k]['user_id'] = 0;
        }
        $Comment = new \app\common\model\Comment();
        $saveAll = $Comment->saveAll($data);
        if($saveAll){
            Db::name('goods')->where(['goods_id'=>$goods_id])->inc('comment_count',count($arr));
            $this->success("评价excel导入成功");
        }else{
            $this->error("评价excel导入失败");
        }
    }

    /**
     * @param $file
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importExcel($file){
        require_once '../vendor/PHPExcel/PHPExcel.php';
        require_once '../vendor/PHPExcel/PHPExcel/IOFactory.php';
        require_once '../vendor/PHPExcel/PHPExcel/Reader/Excel5.php';
        $extension = strtolower( pathinfo($file, PATHINFO_EXTENSION) );
        if ($extension =='xlsx') {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');//use excel2007 for 2007 format
        } else if ($extension =='xls') {
//            $objReader = new PHPExcel_Reader_Excel5();//use excel2007 for 2007 format
            $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
        } else if ($extension=='csv') {
            //还没有测试过
            $objReader = new PHPExcel_Reader_CSV();
            //默认输入字符集
            $objReader->setInputEncoding('GBK');
            //默认的分隔符
            $objReader->setDelimiter(',');
        }

        //载入文件
        $objPHPExcel = $objReader->load($file);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
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
        input('brand_id') && $where['g.brand_id'] = input('brand_id');
        (input('is_on_sale') !== '') && $where['g.is_on_sale'] = input('is_on_sale') ;                
        $cat_id = input('cat_id');
        // 关键词搜索               
        $key_word = input('key_word') ? trim(input('key_word')) : '';
        if($key_word)
        {
            $where['goods_name|goods_sn'] = ['like', '%'.$key_word.'%'];
        }
        
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where['g.cat_id'] = ['in', implode(',', $grandson_ids)]; // 初始化搜索条件
        }
		$where['g.audit'] = 1;
        
        $count = Db::name('Goods')->alias('g')->where($where)->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $data = input('post.');
        $order_str = "{$data['orderby1']} {$data['orderby2']}";
        $goodsList = Db::name('Goods')
			->alias('g')
			->join('suppliers s', 's.suppliers_id=g.suppliers_id', 'left')
			->field('g.*,s.suppliers_name')
			->where($where)
			->order($data['orderby1'],$data['orderby2'])->limit($Page->firstRow,$Page->listRows)->select();

        $catList = Db::name('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        View::assign('catList',$catList);
        View::assign('goodsList',$goodsList);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }
	
	/**
	 * 审核商品
	 */
    public function auditGoods() {
		$goodsId = input('goods_id');
		$content = input('content', '');
		$audit = input('audit');
		if (!$goodsId) {
			$this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
		}
		//拒绝供应商商品
		if ($audit == 2) {
			if ($content == '') {
				$this->ajaxReturn(['status' => 0, 'msg' => '必须填写拒绝理由']);
			} else {
				Db::name('goods')->where('goods_id', $goodsId)->save(['audit' => $audit]);
				$goodsRemark = Db::name('goods_remark')->where('goods_id', $goodsId)->find();
				if ($goodsRemark) {
					Db::name('goods_remark')->where('goods_id', $goodsId)->save(['content' => $content, 'type' => $audit]);
				} else {
					Db::name('goods_remark')->insert(['goods_id' => $goodsId, 'content' => $content, 'type' => $audit]);
				}
				$this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
			}
		}
    }
	
	/**
	 * 供应商商品列表页
	 */
    public function supplierGoodsList() {
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
	public function ajaxSupplierGoodsList() {
        $where = ' 1 = 1 '; // 搜索条件  
        input('brand_id') && $where = "$where and brand_id = ".input('brand_id') ;
        (input('is_on_sale') !== '') && $where = "$where and is_on_sale = ".input('is_on_sale') ;
		$suppliersId = input('suppliers_id','');  //>0对应供应商id商品，-1所有供应商商品
		$suppliersId > 0 ?$where = "$where and suppliers_id = $suppliersId" : $where = "$where and suppliers_id != 0";
        $cat_id = input('cat_id');
		// 关键词搜索
		$key_type = input('key_type');
        $key_word = input('key_word') ? trim(input('key_word')) : '';
        if($key_word)
        {
			if ($key_type == 'goods_name') {
				$where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')";
			} elseif ($key_type == 'suppliers_name') {
				$suppliersIds = Db::name('suppliers')->where('suppliers_name', 'like', '%' . $key_word . '%')->column('suppliers_id','suppliers_id');
				if ($suppliersIds) {
					$suppliersIds = implode(',', $suppliersIds);
					$where = "$where and suppliers_id in (" . $suppliersIds . ')';
				}
			}
        }
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
		$where = "$where and audit = 0";
        
        $count = Db::name('Goods')->where($where)->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $data = input('post.');
        $order_str = "{$data['orderby1']} {$data['orderby2']}";
        $goodsList = Db::name('Goods')->where($where)->order($data['orderby1'], $data['orderby2'])->limit($Page->firstRow,$Page->listRows)->select();
        
		$suppliersList = Db::name('suppliers')->column('suppliers_name','suppliers_id');

        $catList = Db::name('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        View::assign('catList',$catList);
		View::assign('suppliersList',$suppliersList);
        View::assign('goodsList',$goodsList);
        View::assign('page',$show);// 赋值分页输出
        return View::fetch();
    }
}