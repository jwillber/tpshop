<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */

namespace app\mobile\controller;
use think\facade\View;

use app\common\logic\ActivityLogic;
use app\common\logic\GoodsLogic;
use app\common\logic\GoodsPromFactory;
use app\common\model\PromOrder;
use app\common\model\Combination;
use app\common\model\SpecGoodsPrice;
use app\common\util\TpshopException;
use think\AjaxPage;
use think\Page;
use think\facade\Db;
use app\common\model\ShoppingCard;
use app\common\model\Coupon;
class Goods extends MobileBase
{
    public function index()
    {
        return View::fetch();
    }

    /**
     * 分类列表显示
     */
    public function categoryList()
    {
        $config = tpCache('category'); // 系统配置
        $category = input('category/d'); // 以传参为主
        if($category > 0 && $category < 4){
            $config['category_switch'] = $category;
        }
        if(isset($config['category_switch']) && $config['category_switch'] != 1){
            $html = 'categoryList' . $config['category_switch'];
            return View::fetch($html);
        }
        return View::fetch();
    }

    /**
     * 分类详情 ajax返回
     */
    public function ajax_category(){
        $category = input('category/d',2);
        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类
        $data = $goodsLogic->ajax_category($category);
        $this->ajaxReturn($data);
    }

    /**
     * 商品列表页
     */
    public function goodsList()
    {
        $filter_param = array(); // 帅选数组
        $id = input('id/d', 1); // 当前分类id
        $brand_id = input('brand_id/d', 0);
        $spec = input('spec', 0); // 规格
        $attr = input('attr', ''); // 属性
        $sort = input('sort', 'sort'); // 排序
        $sort_asc = input('sort_asc', 'desc'); // 排序
        $price = input('price', ''); // 价钱
        $start_price = trim(input('start_price', '0')); // 输入框价钱
        $end_price = trim(input('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $spec && ($filter_param['spec'] = $spec); //加入帅选条件中
        $attr && ($filter_param['attr'] = $attr); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中

        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类
        // 分类菜单显示
        $goodsCate = Db::name('GoodsCategory')->where("id", $id)->find();// 当前分类
        //($goodsCate['level'] == 1) && header('Location:'.url('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        $cateArr = $goodsLogic->get_goods_cate($goodsCate);

        // 帅选 品牌 规格 属性 价格
        $cat_id_arr = getCatGrandson($id);
        $goods_where = ['is_on_sale' => 1, 'exchange_integral' => 0, 'cat_id' => ['in', $cat_id_arr]];
        $filter_goods_id = Db::name('goods')->where($goods_where)->cache(true)->column("goods_id");

        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if ($spec)// 规格
        {
            $goods_id_2 = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if ($attr)// 属性
        {
            $goods_id_3 = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_3); // 获取多个帅选条件的结果 的交集
        }

        //筛选网站自营,入驻商家,货到付款,仅看有货,促销商品
        $sel = input('sel');
        if ($sel) {
            $goods_id_4 = $goodsLogic->getFilterSelected($sel, $cat_id_arr);
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_4);
        }

        $filter_menu = $goodsLogic->get_filter_menu($filter_param, 'goodsList'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'goodsList'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'goodsList'); // 获取指定分类下的帅选品牌
        $filter_spec = $goodsLogic->get_filter_spec($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选规格
        $filter_attr = $goodsLogic->get_filter_attr($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选属性

        $count = count($filter_goods_id);
        $page = new Page($count, config('PAGESIZE'));
        if ($count > 0) {
            $sort_asc = $sort_asc == 'asc' ? 'desc' : 'asc'; // 防注入
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort'; // 防注入

            $goods_list =(new \app\common\model\Goods())->where("goods_id", "in", implode(',', $filter_goods_id))->order([$sort => $sort_asc])->limit($page->firstRow,$page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if ($filter_goods_id2)
                $goods_images = Db::name('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->cache(true)->select();
            set_goods_label_name($goods_list);
        }
        $goods_category = Db::name('goods_category')->where('is_show=1')->cache(true)->column('id,name,parent_id,level','id'); // 键值分类数组
        View::assign('goods_list', $goods_list);
        View::assign('goods_category', $goods_category);
        View::assign('goods_images', $goods_images);  // 相册图片
        View::assign('filter_menu', $filter_menu);  // 帅选菜单
        View::assign('filter_spec', $filter_spec);  // 帅选规格
        View::assign('filter_attr', $filter_attr);  // 帅选属性
        View::assign('filter_brand', $filter_brand);// 列表页帅选属性 - 商品品牌
        View::assign('filter_price', $filter_price);// 帅选的价格期间
        View::assign('goodsCate', $goodsCate);
        View::assign('cateArr', $cateArr);
        View::assign('filter_param', $filter_param); // 帅选条件
        View::assign('cat_id', $id);
        View::assign('page', $page);// 赋值分页输出
        View::assign('sort_asc', $sort_asc == 'asc' ? 'desc' : 'asc');
        config('TOKEN_ON', false);
        if (input('is_ajax'))
            return View::fetch('ajaxGoodsList');
        else
            return View::fetch();
    }

    /**
     * 商品列表页 ajax 翻页请求 搜索
     */
    public function ajaxGoodsList()
    {
        $where = '';

        $cat_id = input("id/d", 0); // 所选择的商品分类id
        if ($cat_id > 0) {
            $grandson_ids = getCatGrandson($cat_id);
            $where .= " WHERE cat_id in(" . implode(',', $grandson_ids) . ") "; // 初始化搜索条件
        }

        $result = DB::query("select count(1) as count from __PREFIX__goods $where ");
        $count = $result[0]['count'];
        $page = new AjaxPage($count, 10);

        $order = " order by sort desc"; // 排序
        $limit = " limit " . $page->firstRow . ',' . $page->listRows;
        $list = DB::query("select *  from __PREFIX__goods $where $order $limit");

        View::assign('lists', $list);
        $html = View::fetch('ajaxGoodsList'); //return View::fetch('ajax_goods_list');
        exit($html);
    }

    /**
     * 领取优惠券
     */
    public function couponList(){
        $p = input('p', 1);
        $cat_id = input('cat_id', 0);
        $goods_id = input('goods_id', 0);
        $user = session('user');

        $activityLogic = new ActivityLogic();
        $result = $activityLogic->getCouponCenterList($cat_id, $user['user_id'], $p,$goods_id);
        $return = array(
            'status' => 1,
            'msg' => '获取成功',
            'result' => $result ,
        );
        $this->ajaxReturn($return);
    }

    public function checkGoodsPromotionType(){
        $where['goods_id'] =input("get.goods_id/d");
        $where['item_id'] = input("get.item_id/d",0);
        $prom_id = DB::name('prom_goods_item')->where($where)->value('prom_id');

        $return = array(
            'status' => -1,
            'msg' => '暂无参与促销活动'
        );

        if($prom_id){
            $check = DB::name('prom_goods')->where(['id'=>$prom_id,'is_end'=>0])->find();
            if($check){
                $return = array(
                    'status' => 1,
                    'msg' => '正在参与促销活动',
                    'result' => $check['type']
                );
            }
        }
        return json($return);
    }

    /**
     * 商品详情页
     */
    public function goodsInfo()
    {
        config('TOKEN_ON', true);
        $goodsLogic = new GoodsLogic();
        $goods_id = input("get.id/d");
        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::find($goods_id);
        if (empty($goods) || ($goods['is_on_sale'] == 0)) {
            $this->error('此商品不存在或者已下架');
        }
        if(($goods['is_virtual'] == 1 && $goods['virtual_indate'] <= time())){
            $goods->save(['is_on_sale' => 0]);
            $this->error('此商品不存在或者已下架');
        }
        $goods->click_count += 1;
        $goods->save();
        $user_id = cookie('user_id');
        if ($user_id) {
            $goodsLogic->add_visit_log($user_id, $goods);
            $collect = Db::name('goods_collect')->where(array("goods_id" => $goods_id, "user_id" => $user_id))->count(); //当前用户收藏
            View::assign('collect', $collect);
        }else{
            session('redirect_url',url('Mobile/Goods/goodsInfo',['id'=>$goods_id]));
        }
        $PromOrder = new PromOrder();
        $prom_order_list = $PromOrder->where(['start_time' => ['<=', time()], 'end_time' => ['>', time()], 'is_close' => 0])->limit(3)->order('id desc')->select();
        $prom_order = [];
        if(!$prom_order_list->isEmpty()){
            $prom_order = $prom_order_list->append(['prom_detail'])->toArray();
        }
        $recommend_goods = Db::name('goods')->where("is_recommend=1 and is_on_sale=1 and cat_id = {$goods['cat_id']}")->cache(7200)->limit(9)->field("goods_id, goods_name, shop_price")->select();
        View::assign('recommend_goods', $recommend_goods);
        View::assign('goods', $goods);
        View::assign('prom_order', $prom_order);
        return View::fetch();
    }
    /**
    * 问大家
    */
     public function goodsInfo_ask()
    {
        $goods_id=input('id');
//        $goods_id=1;
//        $user = new \app\api\controller\User();
//        $result=$user->ask_all_common($id,'brief');
//        $Goods = new \app\common\model\Goods();
//        $good_name = $Goods->where('goods_id',$id)->value('goods_name');
//        $question_num = $result['question_num'];
//        unset($result['question_num']);
//        View::assign('question_num', $question_num);
//        View::assign('question', $result);
        View::assign('goods_id', $goods_id);
////        var_dump($result);die;
        return View::fetch();
    }
    /**
    * 问大家详情
    */
     public function goodsInfo_askinfo()
    {
        $gid=input('gid');
        $qid=input('qid');
        View::assign('gid', $gid);
        View::assign('qid', $qid);
        return View::fetch();
    }
    //价格阶梯更改
    public function priceladder(){
        $goods_id = input('goods_id/d');
        $goods_num = input('goods_num/d');
        $Goods = new \app\common\model\Goods();
        $goods = $Goods->where('goods_id',$goods_id)->find();
        if (!empty($goods['price_ladder'])) {
            //如果有阶梯价格,就是用阶梯价格
            $goodsLogic = new \app\common\logic\GoodsLogic();
            $goods['shop_price'] = $goodsLogic->getGoodsPriceByLadder($goods_num, $goods['shop_price'], $goods['price_ladder']);
        }
        $data['price_ladder'] = $goods['price_ladder'];
        $data['shop_price'] = $goods['shop_price'];
        $this->ajaxReturn($data);
    }
 /**
         * 分类商品列表，搜索出的商品列表，点击购物车按钮弹出
         */
    public function goodsinfolist()
    {
        config('TOKEN_ON',true);        
        $goodsLogic = new GoodsLogic();
        $goods_id = input("post.id/d");

        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::find($goods_id);

        if(empty($goods) || ($goods['is_on_sale'] == 0) || ($goods['is_virtual']==1 && $goods['virtual_indate'] <= time())){
            $this->error('此商品不存在或者已下架');
        }
        if (cookie('user_id')) {
            $goodsLogic->add_visit_log(cookie('user_id'), $goods);
        }
        if($goods['brand_id']){
            $brnad = Db::name('brand')->where("id", $goods['brand_id'])->find();
            $goods['brand_name'] = $brnad['name'];
        }

        $goods_images_list = Db::name('GoodsImages')->where("goods_id", $goods_id)->select(); // 商品 图册
        $goods_attribute = Db::name('GoodsAttribute')->column('attr_name','attr_id'); // 查询属性
        $goods_attr_list = Db::name('GoodsAttr')->where("goods_id", $goods_id)->select(); // 查询商品属性表
        $filter_spec = $goodsLogic->get_spec($goods_id);
        $spec_goods_price  = Db::name('spec_goods_price')->where("goods_id", $goods_id)->column("key,price,store_count,item_id",'key'); // 规格 对应 价格 库存表
        View::assign('spec_goods_price', json_encode($spec_goods_price,true)); // 规格 对应 价格 库存表
        $goods['sale_num'] = Db::name('order_goods')->where(['goods_id'=>$goods_id,'is_send'=>1])->count();
        
        //当前用户收藏
        $user_id = cookie('user_id');
        $collect = Db::name('goods_collect')->where(array("goods_id"=>$goods_id ,"user_id"=>$user_id))->count();
        $goods_collect_count = Db::name('goods_collect')->where(array("goods_id"=>$goods_id))->count(); //商品收藏数

        View::assign('collect',$collect);
        View::assign('goods_attribute',$goods_attribute);//属性值     
        View::assign('goods_attr_list',$goods_attr_list);//属性列表
        View::assign('filter_spec',$filter_spec);//规格参数
        View::assign('goods_images_list',$goods_images_list);//商品缩略图
        View::assign('goods',$goods->toArray());
        //积分规则修改后的逻辑

        $kf_config['im_choose'] = tpCache('basic.im_choose');
        View::assign('kf_config',$kf_config);

        $point_rate = tpCache('integral.point_rate');
        //$point_rate = tpCache('shopping.point_rate');
        View::assign('goods_collect_count',$goods_collect_count); //商品收藏人数
        View::assign('point_rate', $point_rate);
        return View::fetch('goodsinfotwo');
    }

    public function add_cart_info(){
        config('TOKEN_ON', true);
        $goodsLogic = new GoodsLogic();
        $goods_id = input("id/d");
        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::find($goods_id);
        if (empty($goods) || ($goods['is_on_sale'] == 0)) {
            //$this->error('此商品不存在或者已下架');
        }
        if(($goods['is_virtual'] == 1 && $goods['virtual_indate'] <= time())){
            $goods->save(['is_on_sale' => 0]);
            //$this->error('此商品不存在或者已下架');
        }
        $user_id = cookie('user_id');
        if ($user_id) {
            $goodsLogic->add_visit_log($user_id, $goods);
            $collect = Db::name('goods_collect')->where(array("goods_id" => $goods_id, "user_id" => $user_id))->count(); //当前用户收藏
            View::assign('collect', $collect);
        }

        $recommend_goods = Db::name('goods')->where("is_recommend=1 and is_on_sale=1 and cat_id = {$goods['cat_id']}")->cache(7200)->limit(9)->field("goods_id, goods_name, shop_price")->select();
        View::assign('recommend_goods', $recommend_goods);
        View::assign('goods', $goods);
        return View::fetch();
        $data['html'] = View::fetch();
        //echo $data['html'];exit;
        $this->ajaxReturn($data);
    }
    public function activity()
    {
        $goods_id = input('goods_id/d');//商品id
        $item_id = input('item_id/d', 0);//规格id
        $goods_num = input('goods_num/d');//欲购买的商品数量
        $goodsPromFactory = new GoodsPromFactory();
        $goodsLogic = new GoodsLogic();
        $Goods = new \app\common\model\Goods();
        $goods = $Goods::where('goods_id',$goods_id)->find();

        $goods['activity_is_on'] = 0;
        $specGoodsPrice = SpecGoodsPrice::find($item_id);
        if($specGoodsPrice){
            $goods->shop_price = $specGoodsPrice->price;
        }
        $goods->shop_price = $goodsLogic->getGoodsPriceByLadder($goods_num, $goods['shop_price'], $goods['price_ladder']);//先使用价格阶梯
        if ($goodsPromFactory->checkPromType($goods['prom_type'])) {
            $goodsPromLogic = $goodsPromFactory->makeModule($goods, $specGoodsPrice);
            if ($goodsPromLogic->checkActivityIsAble()) {
                $goods = $goodsPromLogic->getActivityGoodsInfo();
                $goods['activity_is_on'] = 1;
                $goods['shop_price'] = round($goods['shop_price'],2);
                $this->ajaxReturn(['status' => 1, 'msg' => '该商品参与活动', 'result' => ['goods' => $goods]]);
            }
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '该商品没有参与活动', 'result' => ['goods' => $goods]]);
    }

    /*
     * 商品评论
     */
    public function comment()
    {
        $goods_id = input("goods_id/d", 0);
        View::assign('goods_id', $goods_id);
        return View::fetch();
    }

    /*
     * ajax获取商品评论
     */
    public function ajaxComment()
    {
       
        $goods_id = input("goods_id/d", 0);
        $commentType = input('commentType', '1'); // 1 全部 2好评 3 中评 4差评
        if ($commentType == 5) {
            $where = array(
                'goods_id' => $goods_id, 'parent_id' => 0, 'img' => ['<>', ''], 'is_show' => 1
            );
        } else {
            $typeArr = array('1' => '0,1,2,3,4,5', '2' => '4,5', '3' => '3', '4' => '0,1,2');
            $where = array(
                'is_show' => 1, 
                'goods_id' => $goods_id, 
                'parent_id' => 0, 
                [Db::Raw('ceil((deliver_rank + goods_rank + service_rank) / 3)') ,'in', $typeArr[$commentType]]
                    );
        }
        $count = Db::name('Comment')->where($where)->count();     
        $page_count = config('PAGESIZE');
        $page = new AjaxPage($count, $page_count);
        $list = Db::name('Comment')
            ->alias('c')
            ->join('users u', 'u.user_id = c.user_id', 'LEFT')
            ->where($where)->field('c.*,ceil((deliver_rank + goods_rank + service_rank) / 3) as goods_rank ,u.head_pic')
            ->order("add_time desc")
            ->limit($page->firstRow,$page->listRows)
            ->select()->toArray();
        $replyList = Db::name('Comment')->where(['goods_id' => $goods_id, 'parent_id' => ['>', 0]])->order("add_time desc")->select()->toArray();
        foreach ($list as $k => $v) {
            $list[$k]['img'] = unserialize($v['img']); // 晒单图片
            $replyList[$v['comment_id']] = Db::name('Comment')->where(['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => $v['comment_id']])->order("add_time desc")->select();
            $list[$k]['reply_num'] = Db::name('reply')->where(['comment_id' => $v['comment_id'], 'parent_id' => 0])->count();
        }
        View::assign('goods_id', $goods_id);//商品id
        View::assign('commentlist', $list);// 商品评论
        //$this->ajaxReturn($list);
        View::assign('commentType', $commentType);// 1 全部 2好评 3 中评 4差评 5晒图
        View::assign('replyList', $replyList); // 管理员回复
        View::assign('count', $count);//总条数
        View::assign('page_count', $page_count);//页数
        View::assign('current_count', $page_count * input('p',0));//当前条
        View::assign('p', input('p',0));//页数
        return View::fetch();
    }

    /*
     * 获取商品规格
     */
    public function goodsAttr()
    {
        $goods_id = input("get.goods_id/d", 0);
        $goods_attribute = Db::name('GoodsAttribute')->column('attr_name','attr_id'); // 查询属性
        $goods_attr_list = Db::name('GoodsAttr')->where("goods_id", $goods_id)->select(); // 查询商品属性表
        View::assign('goods_attr_list', $goods_attr_list);
        View::assign('goods_attribute', $goods_attribute);
        return View::fetch();
    }

    /**
     * 积分商城
     */
    public function integralMall()
    {
        $rank = input('get.rank');
        //以兑换量（购买量）排序
        if ($rank == 'num') {
            //$ranktype = 'sales_sum';
            $order =['sales_sum'=>'desc'];
        }
        //以需要积分排序
        if ($rank == 'integral') {
            //$ranktype = 'exchange_integral';
            //$order = 'desc';
            $order =['exchange_integral'=>'desc'];
        }
        //积分规则修改后的逻辑
        $point_rate = tpCache('integral.point_rate');

        //$point_rate = tpCache('shopping.point_rate');
        $goods_where = array(
            'is_on_sale' => 1,  //是否上架
        );
        //积分兑换筛选
        $goods_where[] = ['exchange_integral','>',0];

        // 分类id
        if (!empty($cat_id)) {
            $goods_where['cat_id'] = array('in', getCatGrandson($cat_id));
        }
        //我能兑换
        $user_id = cookie('user_id');
        if ($rank == 'exchange' && !empty($user_id)) {
            //获取用户积分
            $user_pay_points = intval(Db::name('users')->where(array('user_id' => $user_id))->value('pay_points'));
            if ($user_pay_points !== false) {
                $goods_where[] = ['exchange_integral','<',$user_pay_points];                
            }
        }
        //$goods_where[] = $exchange_integral_where_array;  //拼装条件
        $goods_list_count = Db::name('goods')->where($goods_where)->count();   //总页数
        $page = new Page($goods_list_count, 15);
        $Goods = new \app\common\model\Goods();
       
        $goods_list = $Goods->where($goods_where)->order($order)->limit($page->firstRow,$page->listRows)->select();
        $goods_category = Db::name('goods_category')->where(array('level' => 1))->select();
        set_goods_label_name($goods_list);
        View::assign('goods_list', $goods_list);
        View::assign('page', $page->show());
        View::assign('goods_list_count', $goods_list_count);
        View::assign('goods_category', $goods_category);//商品1级分类
        View::assign('point_rate', $point_rate);//兑换率
        View::assign('totalPages', $page->totalPages);//总页数
        if (IS_AJAX) {
            return View::fetch('ajaxIntegralMall'); //获取更多
        }
        return View::fetch();
    }


    /**
     * 商品搜索列表页
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function search()
    {
        $filter_param = array(); // 帅选数组
        $shopping_card_id = input('shopping_card_id/d',0);//购物卡id
        $coupon_id = input('coupon_id/d',0);//优惠券id
        $id = input('get.id/d', 0); // 当前分类id
        $brand_id = input('brand_id/d', 0);
        $sort = input('sort', 'sort'); // 排序
        $sort_asc = input('sort_asc', 'desc'); // 排序
        $price = input('price', ''); // 价钱
        $start_price = trim(input('start_price', '0')); // 输入框价钱
        $end_price = trim(input('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        //$brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        //$price && ($filter_param['price'] = $price); //加入帅选条件中
        $q = urldecode(trim(input('q', ''))); // 关键字搜索
        $q && ($filter_param['q'] = $q); //加入帅选条件中
        $qtype = input('qtype', '');
        $where = array('is_on_sale' => 1);
        if ($qtype) {
            $filter_param['qtype'] = $qtype;
            $where[$qtype] = 1;
        }
        if($shopping_card_id){
            $shopping_card_logic = new ShoppingCard();
            $goods_ids=$shopping_card_logic->where(['id'=>$shopping_card_id])->find()['goods_ids'];
            $where['goods_id']=['in',$goods_ids];
        }



        if ($q) $where['goods_name'] = array('like', '%' . $q . '%');

        $goodsLogic = new GoodsLogic();
        $filter_goods_ids = $filter_goods_id = Db::name('goods')->where($where)->cache(true)->column("goods_id");
        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id

            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        
        //筛选网站自营,入驻商家,货到付款,仅看有货,促销商品
        $sel = input('sel');
        if ($sel) {
            $goods_id_4 = $goodsLogic->getFilterSelected($sel);
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_4);
        }
        $filter_menu = $goodsLogic->get_filter_menu($filter_param, 'search'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_ids, $filter_param, 'search'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_ids, $filter_param, 'search'); // 获取指定分类下的帅选品牌

        $count = count($filter_goods_id);
        $page = new Page($count, 12);
        if ($count > 0) {
            $sort_asc = $sort_asc == 'asc' ? 'asc' : 'desc';
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort';
            $goods_list = (new \app\common\model\Goods())->where("goods_id", "in", implode(',', $filter_goods_id))->order([$sort => $sort_asc])->limit($page->firstRow,$page->listRows)
                ->field('goods_id,goods_name,sales_sum,virtual_sales_sum,shop_price,bespeak_template_id,label_id')->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if ($filter_goods_id2)
                $goods_images = Db::name('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->cache(true)->select();
            set_goods_label_name($goods_list);
        }
        $goods_category = Db::name('goods_category')->where('is_show=1')->cache(true)->column('id,name,parent_id,level','id'); // 键值分类数组
        View::assign('goods_list', $goods_list);
        View::assign('goods_category', $goods_category);
        View::assign('goods_images', $goods_images);  // 相册图片
        View::assign('filter_menu', $filter_menu);  // 帅选菜单
        View::assign('filter_brand', $filter_brand);// 列表页帅选属性 - 商品品牌
        View::assign('filter_price', $filter_price);// 帅选的价格期间
        View::assign('filter_param', $filter_param); // 帅选条件
        View::assign('page', $page);// 赋值分页输出
        View::assign('sort_asc', $sort_asc == 'asc' ? 'desc' : 'asc');
        config('TOKEN_ON', false);
        if (input('is_ajax'))
            return View::fetch('ajaxSearchList');
        else
            return View::fetch();
    }

    /**
     * 商品搜索列表页
     */
    public function ajaxSearch()
    {
        return View::fetch();
    }

    /**
     * 品牌街
     */
    public function brandstreet()
    {
        $getnum = 9;   //取出数量
        $goods = Db::name('goods')->where(array('is_recommend' => 1, 'is_on_sale' => 1,'brand_id'=>['>',0]))->page(1, $getnum)->cache(true, TPSHOP_CACHE_TIME)->select(); //推荐商品
        for ($i = 0; $i < ($getnum / 3); $i++) {
            //3条记录为一组
            $recommend_goods[] = array_slice($goods->toArray(), $i * 3, 3);
        }
        $where = array(
            'is_hot' => 1,  //1为推荐品牌
        );
        $count = Db::name('brand')->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20);
        $brand_list = Db::name('brand')->where($where)->limit($Page->firstRow,$Page->listRows)->order('sort desc')->select();
        View::assign('recommend_goods', $recommend_goods);  //品牌列表
        View::assign('brand_list', $brand_list);            //推荐商品
        View::assign('listRows', $Page->listRows);
        if (input('is_ajax')) {
            return View::fetch('ajaxBrandstreet');
        }
        return View::fetch();
    }

    /**
     * 用户收藏某一件商品
     */
    public function collect_goods()
    {
        $goods_id = input('goods_id/d');
        $goodsLogic = new GoodsLogic();
        $result = $goodsLogic->collect_goods(cookie('user_id'), $goods_id);
        $this->ajaxReturn($result);
    }
    function goodsInfojs(){

        $arr = Db::name('goods')->field('goods_id,goods_name,shop_price,(comment_count+virtual_comment_count) as comment_count,(sales_sum+virtual_sales_sum) as sales_sum')->find(input('id'));
        if($arr) $arr['status'] = 1;
        else $arr['status'] = 0;
        $this->ajaxReturn($arr);
    }
    /**
     * 搭配详情页
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function combination_details()
    {
        $goods_id = input('id/d');
        $item_id = input('item/d', 0);
        $combination_id = input('combination/d', 0);

        if (empty($goods_id)) {
            $this->error('参数错误');
        }
        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::find($goods_id);
        if (empty($goods) || ($goods['is_on_sale'] == 0) || ($goods['is_virtual'] == 1 && $goods['virtual_indate'] <= time())) {
            $this->error('此商品不存在或者已下架');
        }
        $combination = new \app\common\logic\Combination();
        $combination->setCombinationId($combination_id);
        $combination_list = [];
        try {
            $combination_list = $combination->getCombinationDetails();
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->error($error['msg']);
        }
        $good = [];
        foreach($combination_list[0]['combination_goods'] as $key=>$value){
            $good['goods_images'][]['image_url'] = $value['original_img'];
        }
        View::assign('goods', $good);
        View::assign('combination', $combination_list);
        View::assign('goods_id', $goods_id);
        View::assign('item_id', $item_id);
        return View::fetch();
    }

    /**
     * 获取商品分享海报
     */
    public function goodsSharePoster(){
        $goods_id = input("get.id/d",0);
        $item_id = input("get.item_id/d",0);
        $prom_id = input("get.prom_id/d",0);
        $prom_type = input("get.prom_type/d",0);
        $leader_id = input("get.leader_id/d",0);
        if($leader_id == 0 && cookie('user_id')){
            $leader_id = cookie('user_id');
        }

        $url = input('url');
        $url = urldecode($url); // 二维码的连接内容，可以空，默认手机端
        $data = ['goods_id'=>$goods_id,'item_id'=>$item_id,'prom_id'=>$prom_id,'prom_type'=>$prom_type,'first_leader'=>$leader_id,'codedata'=>$url];
        // 用户登录了，获取头像，昵称
        $user = session('user');
        if(!empty($user)){
            $data['head_pic'] = $user['head_pic'];
            $data['nickname'] = $user['nickname'];
        }
        if(empty($data['nickname'])){
            $data['nickname'] = '神秘人物';
        }
        $goodsLogic = new GoodsLogic();
        $goodsLogic->getGoodsSharePoster($data,2); // 加个2表示是手机端
    }

}