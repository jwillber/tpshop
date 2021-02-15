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

namespace app\home\controller;
use think\facade\View;


use app\common\logic\ActivityLogic;
use app\common\logic\FreightLogic;
use app\common\logic\GoodsPromFactory;
use app\common\logic\SearchWordLogic;
use app\common\logic\GoodsLogic;
use app\common\model\Combination;
use app\common\model\SpecGoodsPrice;
use app\common\model\UserAddress;
use app\common\util\TpshopException;
use app\common\model\ShoppingCard;
use app\common\model\Coupon;
use think\AjaxPage;
use think\Page;
use think\Validate;
use think\Verify;
use think\facade\Db;
use think\Cookie;

class Goods extends Base
{
    public function index()
    {
        return View::fetch();
    }


    /**
     * 商品详情页
     */
    public function goodsInfo()
    {
        $goodsLogic = new GoodsLogic();
        $goods_id = input("get.id/d");
        $Goods = new \app\common\model\Goods();
        $goods = $Goods->where('goods_id',$goods_id)->find();
		if (!input('preview',0) || cookie('user_id')) {
			if (empty($goods) || ($goods['is_on_sale'] == 0)) {
				$this->error('该商品已经下架', url('Index/index'));
			}
			if(($goods['is_virtual'] == 1 && $goods['virtual_indate'] <= time())){
				$goods->save(['is_on_sale' => 0]);
				$this->error('此商品不存在或者已下架', url('Index/index'));
			}
			if (cookie('user_id')) {
				$goodsLogic->add_visit_log(cookie('user_id'), $goods);
			}
			$goods->save(['click_count'=>$goods['click_count'] + 1]);//点击数
		} 
        $goods_attr_show_list = $goods->goodsAttr()->where('attr_index',1)->select(); 
        View::assign('goods', $goods);
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
        $this->ajaxReturn($goods['shop_price']);
    }

    public function activity()
    {
        $goods_id = input('goods_id/d');//商品id
        $item_id = input('item_id/d');//规格id
        $goods_num = input('goods_num/d');//欲购买的商品数量
        $Goods = new \app\common\model\Goods();
        $goods = $Goods::find($goods_id);
        $goodsPromFactory = new GoodsPromFactory();
        $goods['activity_is_on'] = 0;
        if ($goodsPromFactory->checkPromType($goods['prom_type'])) {
            //这里会自动更新商品活动状态，所以商品需要重新查询
            if ($item_id) {
                $specGoodsPrice = SpecGoodsPrice::find($item_id);
                $goodsPromLogic = $goodsPromFactory->makeModule($goods, $specGoodsPrice);
            } else {
                $goodsPromLogic = $goodsPromFactory->makeModule($goods, null);
            }
            if ($goodsPromLogic->checkActivityIsAble()) {
                $goods = $goodsPromLogic->getActivityGoodsInfo();
                $goods['activity_is_on'] = 1;
                $this->ajaxReturn(['status' => 1, 'msg' => '该商品参与活动', 'result' => ['goods' => $goods]]);
            } else {
                if (!empty($goods['price_ladder'])) {
                    $goodsLogic = new GoodsLogic();
                    $goods->shop_price = $goodsLogic->getGoodsPriceByLadder($goods_num, $goods['shop_price'], $goods['price_ladder']);
                }
                $this->ajaxReturn(['status' => 1, 'msg' => '该商品没有参与活动', 'result' => ['goods' => $goods]]);
            }
        }
        if (!empty($goods['price_ladder'])) {
            $goodsLogic = new GoodsLogic();
            $goods->shop_price = $goodsLogic->getGoodsPriceByLadder($goods_num, $goods['shop_price'], $goods['price_ladder']);
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '该商品没有参与活动', 'result' => ['goods' => $goods]]);
    }

    /**
     * 获取可发货地址
     */
    public function getRegion()
    {
        $goodsLogic = new GoodsLogic();
        $region_list = $goodsLogic->getRegionList();//获取配送地址列表
        $region_list['status'] = 1;
        $this->ajaxReturn($region_list);
    }

    /**
     * 商品列表页
     */
    public function goodsList()
    {

        $key = md5($_SERVER['REQUEST_URI'] . input('start_price') . '_' . input('end_price'));
        $html = cache($key);
        if (!empty($html)) {
            return $html;
        }

        $filter_param = array(); // 筛选数组                        
        $id = input('get.id/d', 1); // 当前分类id
        $brand_id = input('get.brand_id', 0);
        $spec = input('get.spec', 0); // 规格
        $attr = input('get.attr', ''); // 属性
        $sort = input('get.sort', 'sort'); // 排序
        $sort_asc = input('get.sort_asc', 'desc'); // 排序
        $price = input('get.price', ''); // 价钱
        $start_price = trim(input('post.start_price', '0')); // 输入框价钱
        $end_price = trim(input('post.end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱

        $filter_param['id'] = $id; //加入筛选条件中                       
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入筛选条件中
        $spec && ($filter_param['spec'] = $spec); //加入筛选条件中
        $attr && ($filter_param['attr'] = $attr); //加入筛选条件中
        $price && ($filter_param['price'] = $price); //加入筛选条件中

        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类

        // 分类菜单显示
        $goodsCate = Db::name('GoodsCategory')->where("id", $id)->find();// 当前分类
        //($goodsCate['level'] == 1) && header('Location:'.url('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        $cateArr = $goodsLogic->get_goods_cate($goodsCate);

        // 筛选 品牌 规格 属性 价格
        $cat_id_arr = getCatGrandson($id);
        $goods_where = ['is_on_sale' => 1, 'exchange_integral' => 0, 'cat_id' => ['in',$cat_id_arr]];
        $filter_goods_id = Db::name('goods')->where($goods_where)->cache(true)->column("goods_id");
        // 过滤筛选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个筛选条件的结果 的交集
        }
        if ($spec)// 规格
        {
            $goods_id_2 = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_2); // 获取多个筛选条件的结果 的交集
        }
        if ($attr)// 属性
        {
            $goods_id_3 = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_3); // 获取多个筛选条件的结果 的交集
        }
        $filter_menu = $goodsLogic->get_filter_menu($filter_param, 'goodsList'); // 获取显示的筛选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'goodsList'); // 筛选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'goodsList'); // 获取指定分类下的筛选品牌
        $filter_spec = $goodsLogic->get_filter_spec($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的筛选规格
        $filter_attr = $goodsLogic->get_filter_attr($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的筛选属性

        $count = count($filter_goods_id);
        $page = new Page($count, 20);
        if ($count > 0) {
            /*有的数据库分页第二页数据重复
            $p = isset($_GET['p']) ? $_GET['p'] : 1;
            if ($sort_asc == 'asc') {
                asort($filter_goods_id);
            } else {
                arsort($filter_goods_id);
            }
            $page_num = ceil($count / 20); //总页数
            if($page_num > 1){
                for ($i=0;$i<$count;$i++) {
                    $filter_goods_id = array_slice($filter_goods_id, ($p-1)*20, 20);
                }
            }
            $goods_list = Db::name('goods')->where("goods_id", "in", implode(',', $filter_goods_id))->limit(20)->select();*/
            $sort_asc = $sort_asc == 'asc' ? 'desc' : 'asc'; // 防注入
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort'; // 防注入
            $goods_list = Db::name('goods')->where("goods_id", "in", implode(',', $filter_goods_id))->order([$sort => $sort_asc])->limit($page->firstRow,$page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if ($filter_goods_id2)
                $goods_images = Db::name('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->cache(true)->select();
        }
        $goods_category = Db::name('goods_category')->where('is_show=1')->cache(true)->column('id,name,parent_id,level','id'); // 键值分类数组
        View::assign('goods_list', $goods_list);
        View::assign('goods_category', $goods_category);
        View::assign('goods_images', $goods_images);  // 相册图片
        View::assign('filter_menu', $filter_menu);  // 筛选菜单
        View::assign('filter_spec', $filter_spec);  // 筛选规格
        View::assign('filter_attr', $filter_attr);  // 筛选属性
        View::assign('filter_brand', $filter_brand);  // 列表页筛选属性 - 商品品牌
        View::assign('filter_price', $filter_price);// 筛选的价格期间
        View::assign('goodsCate', $goodsCate);
        View::assign('cateArr', $cateArr);
        View::assign('filter_param', $filter_param); // 筛选条件
        View::assign('cat_id', $id);
        View::assign('page', $page);// 赋值分页输出
        $html = View::fetch();
        cache($key, $html);
        return $html;
    }

    /**
     *  查询配送地址，并执行回调函数
     */
    public function region()
    {
        $fid = input('fid/d');
        $callback = input('callback');
        $parent_region = Db::name('region')->field('id,name')->where(array('parent_id' => $fid))->cache(true)->select();
        echo $callback . '(' . json_encode($parent_region) . ')';
        exit;
    }

    /**
     * 商品物流配送和运费
     */
    public function dispatching()
    {
        $goods_id = input('goods_id/d');//143
        $region_id = input('region_id/d');//28242
        $Goods = new \app\common\model\Goods();
        $goods = $Goods->cache(true)->where('goods_id', $goods_id)->find();
        $freightLogic = new FreightLogic();
        $freightLogic->setGoodsModel($goods);
        $freightLogic->setRegionId($region_id);
        $freightLogic->setGoodsNum(1);
        $isShipping = $freightLogic->checkShipping();
        if ($isShipping) {
            $freightLogic->doCalculation();
            $freight = $freightLogic->getFreight();
            $dispatching_data = ['status' => 1, 'msg' => '可配送', 'result' => ['freight' => $freight]];
        } else {
            $dispatching_data = ['status' => 0, 'msg' => '该地区不支持配送', 'result' => ''];
            //如果用户还没有填地址，则默认配送
            $user_id = session('user.user_id');
            if($user_id){
                $count =  UserAddress::where(['user_id'=>$user_id])->cache(60)->count();
                if(0 == $count){
                    $dispatching_data = ['status' => 1, 'msg' => '暂无收货地址', 'result' =>  ['freight' => 0]];
                }
            }
        }
        $this->ajaxReturn($dispatching_data);
    }

    /**
     * 商品搜索列表页
     */
    public function search()
    {
        //config('URL_MODEL',0);
        $filter_param = array(); // 筛选数组                        
        $id = input('get.id/d', 0); // 当前分类id
        $shopping_card_id = input('shopping_card_id/d',0);//购物卡id
        $coupon_id = input('coupon_id/d',0);//优惠券id
        $brand_id = input('brand_id', 0);
        $sort = input('sort', 'sort'); // 排序
        $sort_asc = input('sort_asc', 'desc'); // 排序
        $price = input('price', ''); // 价钱
        $start_price = trim(input('start_price', '0')); // 输入框价钱
        $end_price = trim(input('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $q = urldecode(trim(input('q', ''))); // 关键字搜索
//        empty($q) && $this->error('请输入搜索词');
        $id && ($filter_param['id'] = $id); //加入筛选条件中                       
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入筛选条件中
        $price && ($filter_param['price'] = $price); //加入筛选条件中
        $q && ($_GET['q'] = $filter_param['q'] = $q); //加入筛选条件中
        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类
        $SearchWordLogic = new SearchWordLogic();
        $where = $SearchWordLogic->getSearchWordWhere($q);
        $where['is_on_sale'] = 1;
        Db::name('search_word')->where('keywords', $q)->inc('search_num')->update();

        $goodsHaveSearchWord = Db::name('goods')->where($where)->count();

        if ($goodsHaveSearchWord) {
            $SearchWordIsHave = Db::name('search_word')->where('keywords', $q)->find();
            if ($SearchWordIsHave) {
                Db::name('search_word')->where('id', $SearchWordIsHave['id'])->update(['goods_num' => $goodsHaveSearchWord]);
            } else {
                $SearchWordData = [
                    'keywords' => $q,
                    'pinyin_full' => $SearchWordLogic->getPinyinFull($q),
                    'pinyin_simple' => $SearchWordLogic->getPinyinSimple($q),
                    'search_num' => 1,
                    'goods_num' => $goodsHaveSearchWord
                ];
                Db::name('search_word')->insert($SearchWordData);
            }
        }

        if ($id) {
            $cat_id_arr = getCatGrandson($id);
            $where['cat_id'] = array('in', implode(',', $cat_id_arr));
        }
        $cat=Db::name('goods_category')->where(['name'=>['like',"%$q%"]])->find();
        if($cat){
            $cat_id_arr = getCatGrandson($cat['id']);
            $cat_ids=implode(',', $cat_id_arr);
            $condition="cat_id in ({$cat_ids}) and is_virtual <> 2 and is_on_sale =1";
//            $condition['cat_id'] = array('in', implode(',', $cat_id_arr));
//            $condition['is_virtual']=['<>',2];
//            $condition['is_on_sale']=1;
        }
        if($shopping_card_id){
            $shopping_card_logic = new ShoppingCard();
            $goods_ids=$shopping_card_logic->where(['id'=>$shopping_card_id])->find()['goods_ids'];
            $where['goods_id']=['in',$goods_ids];
        }
        if($coupon_id){
            $coupon_logic = new Coupon();
            $goods_ids=$coupon_logic->where(['id'=>$coupon_id])->find()['goods_ids'];
            $where['goods_id']=['in',$goods_ids];
        }
        //暂时屏蔽pc预约商品
        $where['is_virtual'] = ['<>',2];

        $search_goods = Db::name('goods')->where($where)->whereOr($condition)->column('cat_id','goods_id');
        
        $filter_goods_id = array_keys($search_goods);
        $filter_cat_id = array_unique($search_goods); // 分类需要去重
        if ($filter_cat_id) {
            $cateArr = Db::name('goods_category')->where("id", "in", implode(',', $filter_cat_id))->select()->toArray();
            $tmp = $filter_param;
            foreach ($cateArr as $k => $v) {
                $tmp['id'] = $v['id'];
                $cateArr[$k]['href'] = url("/Home/Goods/search", $tmp);
            }
        }
        // 过滤筛选的结果集里面找商品        
        if ($brand_id || $price) {
            // 品牌或者价格
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个筛选条件的结果 的交集
        }
        $filter_menu = $goodsLogic->get_filter_menu($filter_param, 'search'); // 获取显示的筛选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'search'); // 筛选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'search'); // 获取指定分类下的筛选品牌

        $count = count($filter_goods_id);
        $page = new Page($count, 20);
        if ($count > 0) {
            $sort_asc = $sort_asc == 'asc' ? 'desc' : 'asc'; // 防注入
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort'; // 防注入
            $goods_list = Db::name('goods')->where(['is_on_sale' => 1, 'goods_id' => ['in', implode(',', $filter_goods_id)]])->order([$sort => $sort_asc])->limit($page->firstRow,$page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if ($filter_goods_id2)
                $goods_images = Db::name('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->select();
        }

        View::assign('goods_list', $goods_list);
        View::assign('goods_images', $goods_images);  // 相册图片
        View::assign('filter_menu', $filter_menu);  // 筛选菜单
        View::assign('filter_brand', $filter_brand);  // 列表页筛选属性 - 商品品牌
        View::assign('filter_price', $filter_price);// 筛选的价格期间
        View::assign('cateArr', $cateArr);
        View::assign('filter_param', $filter_param); // 筛选条件
        View::assign('cat_id', $id);
        View::assign('page', $page);// 赋值分页输出
        View::assign('q', input('q'));
        config('TOKEN_ON', false);
        return View::fetch();
    }

    /**
     * 商品咨询ajax分页
     */
    public function ajax_consult()
    {
        $goods_id = input("goods_id/d", '0');
        $consult_type = input('consult_type', '0'); // 0全部咨询  1 商品咨询 2 支付咨询 3 配送 4 售后
        $where = ['parent_id' => 0, 'goods_id' => $goods_id, 'is_show' => 1];
        $consult_type > 0 ? $where['consult_type'] = $consult_type : false;
        $goodsConsultModel = new  \app\common\model\GoodsConsult();
        $count = $goodsConsultModel->where($where)->count();
        $page = new AjaxPage($count, 5);
        $consultList = $goodsConsultModel->where($where)->order("id desc")->limit($page->firstRow, $page->listRows)->order('add_time desc')->select();
        View::assign('consultList', $consultList);// 商品咨询
        View::assign('page', $page);// 赋值分页输出
        return View::fetch();
    }

    /**
     * 商品评论ajax分页
     */
    public function ajaxComment()
    {
        $goods_id = input("goods_id/d", '0');
        $commentType = input('commentType', '1'); // 1 全部 2好评 3 中评 4差评
        $where = ['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => 0];
        if ($commentType == 5) {
            $where['img'] = ['<>', ''];
        } else {
            $typeArr = array('1' => '0,1,2,3,4,5', '2' => '4,5', '3' => '3', '4' => '0,1,2');
            $where[] = [Db::raw('ceil((deliver_rank + goods_rank + service_rank) / 3)'),'in', $typeArr[$commentType]];
                
        }
        $count = Db::name('Comment')->where($where)->count();
        $page = new AjaxPage($count, 10);
        $show = $page->show();

        $list = Db::name('Comment')->alias('c')->join('users u', 'u.user_id = c.user_id', 'LEFT')->where($where)
            ->field('c.*,ceil((deliver_rank + goods_rank + service_rank) / 3) as goods_rank ,u.head_pic')
            ->order("sort asc , add_time desc")->limit($page->firstRow,$page->listRows)->select()->toArray();;

//        $replyList = Db::name('Comment')->where(['is_show'=>1,'goods_id'=>$goods_id,'parent_id'=>['>',0]])->order("add_time desc")->select();

        foreach ($list as $k => $v) {
            $list[$k]['img'] = unserialize($v['img']); // 晒单图片
            $replyList[$v['comment_id']] = Db::name('Comment')->where(['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => $v['comment_id']])->order("add_time desc")->select();
        }
        View::assign('commentlist', $list);// 商品评论
        View::assign('replyList', $replyList); // 管理员回复
        View::assign('page', $show);// 赋值分页输出
        return View::fetch();
    }

    /**
     *  商品咨询
     */
    public function consult()
    {
        $goods_id = input("goods_id/d", '0'); // 商品id
        $consult_type = input("consult_type", '1'); // 商品咨询类型
        $username = input("username", "{$this->config['copyright']}用户"); // 网友咨询
        $content = trim(input("content", '')); // 咨询内容
        $verify_code = input('verify_code');
        if (strlen($content) > 500) {
            $this->ajaxReturn(['status' => 0, 'msg' => '咨询内容不得超过500字符']);
        }
        $verify = new Verify();
        if (!$verify->check($verify_code, 'consult')) {
            $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
        }
        $rule = [
            ['username', 'require|max:50', '名称不能为空|名称最多不能超过50个字符'],
            ['content', 'require|max:500', '内容不能为空|名称最多不能超过500个字符'],
        ];

        $data = array(
            'goods_id' => $goods_id,
            'consult_type' => $consult_type,
            'username' => $username,
            'content' => $content,
            'is_show' => 1,
            'add_time' => time(),
        );

        $validate = new Validate($rule);
        $result = $validate->check($data);
        if (!$result) {
            $this->ajaxReturn(['status' => 0, 'msg' => $validate->getError()]);
        }

        Db::name('goodsConsult')->insert($data);
        $this->ajaxReturn(['status' => 1, 'msg' => '咨询已提交!']);
    }

    /**
     * 用户收藏商品
     */
    public function collect_goods()
    {
        $goods_ids = input('goods_ids/a', []);
        if (empty($goods_ids)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请至少选择一个商品', 'result' => '']);
        }
        $goodsLogic = new GoodsLogic();
        $result = [];
        foreach ($goods_ids as $key => $val) {
            $result[] = $goodsLogic->collect_goods(cookie('user_id'), $val);
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '已添加至我的收藏', 'result' => $result]);
    }

    /**
     * 加入购物车弹出
     */
    public function open_add_cart()
    {
        return View::fetch();
    }

    /**
     * 积分商城
     */
    public function integralMall()
    {
        $cat_id = input('get.id/d');
        $minValue = input('get.minValue');
        $maxValue = input('get.maxValue');
        $brandType = input('get.brandType');
        //积分规则修改后的逻辑
        $point_rate = tpCache('integral.point_rate');
        //$point_rate = tpCache('shopping.point_rate');
        $is_new = input('get.is_new', 0);
        $exchange = input('get.exchange', 0);
        $goods_where = array(
            'is_on_sale' => 1,  //是否上架
            'is_virtual' => 0,
        );
        //积分兑换筛选
        $exchange_integral_where_array = ['exchange_integral','>',0];// array(array('>', 0));
        // 分类id
        if (!empty($cat_id)) {
            $goods_where['cat_id'] = array('in', getCatGrandson($cat_id));
        }
        //积分截止范围
        if (!empty($maxValue)) {            
            array_push($exchange_integral_where_array, ['exchange_integral','<=',$maxValue]);
        }
        //积分起始范围
        if (!empty($minValue)) {
            array_push($exchange_integral_where_array, ['exchange_integral','>=',$minValue]);
        }
        //积分+金额
        if ($brandType == 1) {
            //array_push($exchange_integral_where_array, array('exp', ' < shop_price* ' . $point_rate));
            array_push($exchange_integral_where_array, ['exchange_integral','exp',Db::raw('< shop_price *'. $point_rate)]);   
        }
        //全部积分
        if ($brandType == 2) {
            //array_push($exchange_integral_where_array, array('exp', ' = shop_price* ' . $point_rate));
            array_push($exchange_integral_where_array, ['exchange_integral','exp',Db::raw('= shop_price*'.$point_rate)]);
        }
        //新品
        if ($is_new == 1) {
            $goods_where['is_new'] = $is_new;
        }
        //我能兑换
        $user_id = cookie('user_id');
        if ($exchange == 1 && !empty($user_id)) {
            $user_pay_points = intval(Db::name('users')->where(array('user_id' => $user_id))->value('pay_points'));
            if ($user_pay_points !== false) {
                //array_push($exchange_integral_where_array, array('<', $user_pay_points));
                array_push($exchange_integral_where_array, ['exchange_integral','<',$user_pay_points]);
            }
        }

        //$goods_where['exchange_integral'] = $exchange_integral_where_array;
        $goods_where = [$exchange_integral_where_array];
        
        //print_r($goods_where);
      //  exit;
        $goods_list_count = Db::name('goods')->where($goods_where)->count();   //总页数
        $page = new Page($goods_list_count, 15);
        $Goods = new \app\common\model\Goods();
        $goods_list = $Goods->where($goods_where)->limit($page->firstRow,$page->listRows)->select();
        $goods_category = Db::name('goods_category')->where(array('level' => 1))->select();

        View::assign('goods_list', $goods_list);
        View::assign('page', $page->show());
        View::assign('goods_list_count', $goods_list_count);
        View::assign('goods_category', $goods_category);//商品1级分类
        View::assign('point_rate', $point_rate);//兑换率
        View::assign('nowPage', $page->nowPage);// 当前页
        View::assign('totalPages', $page->totalPages);//总页数
        return View::fetch();
    }

    /**
     * 全部商品分类
     * @author lxl
     * @time17-4-18
     */
    public function all_category()
    {
        return View::fetch();
    }

    /**
     * 全部品牌列表
     * @author lxl
     * @time17-4-18
     */
    public function all_brand()
    {
        return View::fetch();
    }


    /**
     * 获取搭配购列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function combination()
    {
        $goods_id = input('goods_id/d');
        $item_id = input('item_id/d', 0);
        if (empty($goods_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $combination = new \app\common\logic\Combination();
        $combination_list = [];
        $combination->setItemId($item_id);
        $combination->setGoodsId($goods_id);
        $combination->getGoodCombination();
        try {
            $combination_list = $combination->getCombinationDetails();
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $combination_list]);

    }
}