<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * 采用最新Thinkphp6
 * ============================================================================
 * 订单以及售后中心
 * @author soubao 当燃
 *  @Date: 2016-12-20
 */
namespace app\home\controller;
use think\facade\View;
use app\common\logic\CartLogic;
use app\common\logic\Message;
use app\common\logic\OrderLogic;
use app\common\logic\CommentLogic;
use app\common\logic\UsersLogic;
use app\common\logic\MessageTemplateLogic;
use app\common\model\OrderGoods;
use app\common\util\TpshopException;
use app\common\model\Order as OrderModel;
use think\facade\Db;
use think\Page;

class Order extends Base {

	public $user_id = 0;
	public $user = array();

    public function __construct() {      
        parent::__construct();
        if(session('?user'))
        {
        	$user = session('user');             
        	$this->user = $user;
        	$this->user_id = $user['user_id'];
        	View::assign('user',$user); //存储用户信息
        	View::assign('user_id',$this->user_id);
            //获取用户信息的数量
            $messageLogic = new Message();
            $user_message_count = $messageLogic->getUserMessageNoReadCount();
            View::assign('user_message_count', $user_message_count);
        }else{
            //redirect()->remember();
             //return redirect('/User/login');
             header('Location: '.url('/home/User/login'));
             exit;			
        }
        //用户中心面包屑导航
        $navigate_user = navigate_user();
        View::assign('navigate_user',$navigate_user);        
    }

    /**
     * 订单列表
     */
    public function order_list(){
        $type = input('type');
        $search_key = input('search_key');
        $search_key = trim($search_key);// 搜索订单 根据商品名称 或者 订单编号
        $order = new OrderModel();
        $where_arr = [
            'user_id'=>$this->user_id,
            'deleted'=>0,//删除的订单不列出来
            'prom_type'=>['in',[0,4,9,10]]
        ];
        $count = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->where(function ($query) use ($search_key) {
                if ($search_key) {
                    $query->where('order_sn', 'like', $search_key)->whereOr('order_id', 'IN', function ($query) use ($search_key) {
                        $query->name('order_goods')->where('goods_name', 'like', $search_key)->field('order_id');
                    });
                }
            })
            ->count();
        $Page = new Page($count,10);

        $order_list = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->where(function ($query) use ($search_key) {
                if ($search_key) {
                    $query->where('order_sn', 'like', $search_key)->whereOr('order_id', 'IN', function ($query) use ($search_key) {
                        $query->name('order_goods')->where('goods_name', 'like', $search_key)->field('order_id');
                    });
                }
            })
            ->limit($Page->firstRow,$Page->listRows)->order("order_id DESC")->select();

        View::assign('page',$Page);
        View::assign('order_list',$order_list);
        return View::fetch();
    }
    /*
     * 订单详情
     */
    public function order_detail(){
        $id = input('id/d', 0);
        $Order = new OrderModel();
        $order = $Order::where(['order_id' => $id, 'user_id' => $this->user_id])->find();
        if (!$order) {
            $this->error('没有获取到订单信息');
        }
		$childOrder = Db::name('order')->where('parent_sn', $order['order_id'])->select();
		if (!$childOrder->isEmpty()) {
			View::assign('childOrder', $childOrder);
		}
        //获取订单
        if ($order['prom_type'] == 5) {   //虚拟订单
            return redirect(url('virtual/virtual_order', ['order_id' => $id]));
        }
        View::assign('order', $order);
        return View::fetch();
    }

    public function del_order()
    {
        $order_id = input('order_id/d',0);
        $order = new  \app\common\logic\Order();
        try{
            $order->setOrderById($order_id);
            $order->setUserId($this->user_id);
            $order->userDelOrder();
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn(['status' => 1, 'msg' => $error[0]]);
        }
    }

    /*
     * 取消订单
     */
    public function cancel_order(){
        $id = input('id/d');
        //检查是否有积分，余额支付
        $logic = new OrderLogic();
        $data = $logic->cancel_order($this->user_id,$id);
        $this->ajaxReturn($data);
    }

    public function cancel_order_info(){
    	$order_id = input('order_id/d',0);
    	$order = Db::name('order')->where(array('order_id'=>$order_id,'order_status'=>3,'pay_status'=>['>',0]))->find();
    	View::assign('order', $order);
    	return View::fetch();
    }
    //取消订单弹窗
    public function refund_order()
    {
    	$order_id = input('get.order_id/d');
    	$order = Db::name('order')
                ->field('order_id,prom_type,pay_code,pay_name,user_money,integral_money,coupon_price,order_amount,total_amount')
                ->where(['order_id' => $order_id, 'user_id' => $this->user_id])
                ->find();
        if (!$order) {
            return $this->error('订单不存在');
        }
        View::assign('user',  $this->user);
        View::assign('order', $order);
    	return View::fetch();
    }

    //申请取消订单
    public function record_refund_order()
    {
        $order_id   = input('post.order_id', 0);
        $user_note  = input('post.user_note', '');
        $consignee  = input('post.consignee', '');
        $mobile     = input('post.mobile', '');
        $logic = new OrderLogic;
        $return = $logic->recordRefundOrder($this->user_id, $order_id, $user_note, $consignee, $mobile);
        $this->ajaxReturn($return);
    }
    
    public function virtual_order(){
        $Order = new \app\common\model\Order();
    	$order_id = input('get.order_id/d');
    	$map['order_id'] = $order_id;
    	$map['user_id'] = $this->user_id;
    	$orderobj = $Order->where($map)->find();
        if(!$orderobj) $this->error('没有获取到订单信息');
        // 添加属性  包括按钮显示属性 和 订单状态显示属性
        $order_info = $orderobj->append(['order_status_detail','order_goods'])->toArray();
    	//获取订单操作记录
    	$order_action = Db::name('order_action')->where(array('order_id'=>$order_id))->select();
    	View::assign('order_status',config('ORDER_STATUS'));
    	View::assign('pay_status',config('PAY_STATUS'));
    	View::assign('order_info',$order_info);
    	View::assign('order_action',$order_action);

    	if($order_info['pay_status'] == 1 && $order_info['order_status']!=3){
    		$vrorder = Db::name('vr_order_code')->where(array('order_id'=>$order_id))->select();
    		View::assign('vrorder',$vrorder);
    	}
    	return View::fetch();
    }

        
    /*
     * 评论晒单
     */
    public function comment()
    {
        $user_id = $this->user_id;
        $status = input('get.status', -1);
        $logic = new CommentLogic;
        $data = $logic->getComment($user_id, $status); //获取评论列表
        View::assign('page', $data['page']);// 赋值分页输出
        View::assign('comment_page', $data['page']);
        View::assign('comment_list', $data['result']);
        View::assign('active', 'comment');
        return View::fetch();
    }

    /**
     * 删除评价
     */
    public function delComment()
    {
        $comment_id = input('comment_id');
        if (empty($comment_id)) {
            $this->error('参数错误');
        }
        $comment = Db::name('comment')->where('comment_id', $comment_id)->find();
        if ($this->user_id != $comment['user_id']) {
            $this->error('不能删除别人的评论');
        }
        Db::name('reply')->where('comment_id', $comment_id)->delete();
        Db::name('comment')->where('comment_id', $comment_id)->delete();
        $this->success('删除评论成功');
    }

    /**
     *  点赞
     * @author dyr
     */
    public function ajaxZan()
    {
        $comment_id = input('post.comment_id/d');
        $user_id = $this->user_id;
        $comment_info = Db::name('comment')->where(array('comment_id' => $comment_id))->find();
        $comment_user_id_array = explode(',', $comment_info['zan_userid']);
        if (in_array($user_id, $comment_user_id_array)) {
            $result['success'] = 0;
        } else {
            array_push($comment_user_id_array, $user_id);
            $comment_user_id_string = implode(',', $comment_user_id_array);
            $comment_data['zan_num'] = $comment_info['zan_num'] + 1;
            $comment_data['zan_userid'] = $comment_user_id_string;
            Db::name('comment')->where(array('comment_id' => $comment_id))->save($comment_data);
            $result['success'] = 1;
        }
        exit(json_encode($result));
    }

    /**
     * 添加回复
     * @author dyr
     */
    public function reply_add()
    {
        $comment_id = input('post.comment_id/d');
        $reply_id = input('post.reply_id/d', 0);
        $content = input('post.content');
        $to_name = input('post.to_name', '');
        $goods_id = input('post.goods_id/d');
        $reply_data = array(
            'comment_id' => $comment_id,
            'parent_id' => $reply_id,
            'content' => $content,
            'user_name' => $this->user['nickname'],
            'to_name' => $to_name,
            'reply_time' => time()
        );
        $where = array('o.user_id' => $this->user_id, 'og.goods_id' => $goods_id, 'o.pay_status' => 1);
        $user_goods_count = Db::name('order')
            ->alias('o')
            ->join('order_goods og','o.order_id = og.order_id', 'LEFT')
            ->where($where)
            ->count();
        if ($user_goods_count > 0) {
            Db::name('reply')->insert($reply_data);
            Db::name('comment')->where(array('comment_id' => $comment_id))->inc('reply_num')->update();
            $json['success'] = true;
        } else {
            $json['success'] = false;
            $json['msg'] = '只有购买过该商品才能进行评价';
        }
        $this->ajaxReturn($json);
    }
    
    public function order_confirm()
    {
    	$id = input('post.order_id/d', 0);
    	$data = confirm_order($id, $this->user_id);
        $this->ajaxReturn($data);
    }

    /**
     * 可申请退换货
     */
    public function return_goods_index(){
        $sale_t = input('sale_t/i',0);
        $keywords = input('keywords');
        $model = new OrderLogic();
        $data = $model->getReturnGoodsIndex($sale_t,$keywords,$this->user_id);
    	View::assign('store_list',$data['store_list']);
    	View::assign('order_list',$data['order_list']);
    	View::assign('page',$data['show']);
    	return View::fetch();
    }
    
    /**
     * 申请退货
     */
    public function return_goods()
    {
        $rec_id = input('rec_id',0);
        $return_goods = Db::name('return_goods')->where(array('rec_id'=>$rec_id))->find();
        if(!empty($return_goods))
        {
            $this->error('已经提交过退货申请!',url('Order/return_goods_info',array('id'=>$return_goods['id'])));
        }
        $order_goods = Db::name('order_goods')->where(array('rec_id'=>$rec_id))->find();
        $order = Db::name('order')->where(array('order_id'=>$order_goods['order_id'],'user_id'=>$this->user_id))->find();
        $auto_service_date = tpCache('shopping.auto_service_date'); //申请售后时间段
        $confirm_time = strtotime("-$auto_service_date day");
        if ($order['add_time'] < $confirm_time) {
            return View::fetch('return_error');
        }
        if(empty($order))$this->error('非法操作');
        if(IS_POST)
        {
            $model = new OrderLogic();
            $res = $model->addReturnGoods($rec_id,$order);  //申请售后
            if($res['status']==1)$this->success($res['msg'],url('Order/return_goods_list'));
            $this->error($res['msg']);
        }
		if ($order_goods['suppliers_id'] == 0) {
			$region_id[] = tpCache('shop_info.province');        
			$region_id[] = tpCache('shop_info.city');        
			$region_id[] = tpCache('shop_info.district');
			$region_id[] = 0; 
		} else {//供应商地址
			$supplier = Db::name('suppliers')->where('suppliers_id', $order_goods['suppliers_id'])->find();
			$region_id[] = $supplier['province_id'];        
			$region_id[] = $supplier['city_id'];      
			$region_id[] = $supplier['district_id'];
			$region_id[] = 0; 
			View::assign('supplier', $supplier);
		}
        $return_address = Db::name('region')->where("id in (".implode(',', $region_id).")")->column('name','id');
        View::assign('return_address',implode(',',$return_address));
        View::assign('return_type', config('RETURN_TYPE'));
        View::assign('goods', $order_goods);
    	View::assign('order',$order);
        return View::fetch();
    }

    /**
     * 退换货列表
     */
    public function return_goods_list()
    {
        $where = " user_id=$this->user_id ";
        // 搜索订单 根据商品名称 或者 订单编号
        $search_key = trim(input('search_key'));
        if($search_key)
        {
            $where .= " and order_sn=$search_key";
        }
        $count = Db::name('return_goods')->where($where)->count();
        $page = new Page($count,10);
        $list = Db::name('return_goods')->where($where)->order("id desc")->limit($page->firstRow,$page->listRows)->select();
        $goods_id_arr = get_arr_column($list, 'goods_id');
        if(!empty($goods_id_arr))
            $goodsList = Db::name('goods')->where("goods_id","in", implode(',',$goods_id_arr))->column('goods_name','goods_id');
        $state = config('REFUND_STATUS');
        View::assign('state',$state);
        View::assign('goodsList', $goodsList);
        View::assign('list', $list);
        View::assign('page', $page->show());// 赋值分页输出
        return View::fetch();
    }

    /**
     *  退货详情
     */
    public function return_goods_info()
    {
        $id = input('id/d',0);
        $ReturnGoodsModel = new \app\common\model\ReturnGoods();
        $return_goods=$ReturnGoodsModel::where(['id' => $id,'user_id'=>$this->user_id])->find();
        if(empty($return_goods)) $this->error('参数错误');
        if(IS_POST){
            $data = input('post.');
            $data['delivery'] = serialize($data['delivery']);
            $data['status'] = 2;
            Db::name('return_goods')->where(['id'=>$data['id'],'user_id'=>$this->user_id])->save($data);
            $this->success('发货提交成功',url('Home/Order/return_goods_info',array('id'=>$data['id'])));
        }
        $return_goods['seller_delivery'] = unserialize($return_goods['seller_delivery']);  //订单的物流信息，服务类型为换货会显示
        $return_goods['delivery'] = unserialize($return_goods['delivery']);  //订单的物流信息，服务类型为换货会显示
        if($return_goods['imgs'])
            $return_goods['imgs'] = explode(',', $return_goods['imgs']);
        $goods = Db::name('goods')->where("goods_id", $return_goods['goods_id'])->find();
		if ($return_goods['suppliers_id'] > 0 && $return_goods['receiving_address'] == 1) {//此时收货方是供应商
			$supplier = Db::name('suppliers')->where('suppliers_id', $return_goods['suppliers_id'])->find();
			View::assign('supplier', $supplier);
		}
        View::assign('state',config('REFUND_STATUS'));
        View::assign('return_type', config('RETURN_TYPE'));
        View::assign('goods',$goods);
        View::assign('return_goods',$return_goods);
        $region_list = get_region_list();
        View::assign('region_list',$region_list);
        return View::fetch();
    }
    
    public function return_goods_refund()
    {
    	$order_sn = input('order_sn');
    	$where = array('user_id'=>$this->user_id);
    	if($order_sn){
    		$where['order_sn'] = $order_sn;
    	}
    	$where['status'] = 5;
    	$count = Db::name('return_goods')->where($where)->count();
    	$page = new Page($count,10);
    	$list = Db::name('return_goods')->where($where)->order("id desc")->limit($page->firstRow, $page->listRows)->select();
    	$goods_id_arr = get_arr_column($list, 'goods_id');
    	if(!empty($goods_id_arr))
    		$goodsList = Db::name('goods')->where("goods_id in (".  implode(',',$goods_id_arr).")")->column('goods_name','goods_id');
    	View::assign('goodsList', $goodsList);
    	$state = config('REFUND_STATUS');
    	View::assign('list', $list);
    	View::assign('state',$state);
    	View::assign('page', $page->show());// 赋值分页输出
    	return View::fetch();
    }

    /**
     * 取消服务单
     */
    public function return_goods_cancel(){
    	$id = input('id/d',0);
    	if(empty($id))$this->ajaxReturn(['status'=>0,'msg'=>'参数错误']);
    	$res=Db::name('return_goods')->where(['id'=>$id,'user_id'=>$this->user_id])->save(array('status'=>-2,'canceltime'=>time()));
        if($res){
            $this->ajaxReturn(['status'=>1,'msg'=>'成功取消服务单','url'=>url('Order/return_goods_info',['id'=>$id])]);
        }
            $this->ajaxReturn(['status'=>0,'msg'=>'服务单不存在']);
    }

    /**
     * 换货商品确认收货
     * @author lxl
     * @time  17-4-25
     * */
    public function receiveConfirm(){
        $return_id=input('return_id/d');
        $return_info=Db::name('return_goods')->field('order_id,order_sn,goods_id,spec_key')->where('id',$return_id)->find(); //查找退换货商品信息
        $update = Db::name('return_goods')->where('id',$return_id)->save(['status'=>3]);  //要更新状态为已完成
        if($update) {
            Db::name('order_goods')->where(array(
                'order_id' => $return_info['order_id'],
                'goods_id' => $return_info['goods_id'],
                'spec_key' => $return_info['spec_key']))->save(['is_send' => 2]);  //订单商品改为已换货
            $this->success("操作成功", url("User/return_goods_info", array('id' => $return_id)));
        }
        $this->error("操作失败");
    }

    public function lower_list(){
    	$level = input('get.level',1);
    	$keyword = input('post.keyword','','trim');
    	$condition = array(1=>'first_leader',2=>'second_leader',3=>'third_leader');
    	$where = "$condition[$level] = $this->user_id";
    	$keyword && $where .= " and (nickname like '%$keyword%' or user_id = '$keyword' or mobile = '$keyword')";
    	$count = Db::name('users')->where($where)->count();
    	$page = new Page($count,10);
    	$list = Db::name('users')->where($where)->limit($page->firstRow,$page->listRows)->order('user_id desc')->select();
    	View::assign('count', $count);// 总人数
    	View::assign('level',$level);
    	View::assign('page', $page->show());// 赋值分页输出
    	View::assign('member',$list); // 下线
    	return View::fetch();
    }
    
    public function income(){
        $result = Db::name('rebate_log')->field('sum(goods_price) as goods_price, sum(money) as money')
            ->where(['status'=>3,'user_id' => $this->user_id])->find();
    	$result['goods_price'] = $result['goods_price'] ? $result['goods_price'] : 0;
    	$result['money'] = $result['money'] ? $result['money'] : 0;
    	$status = input('get.status',-2);
    	$order_sn = input('order_sn');

    	if($status=='0' || $status>0){
    		$condition['status'] = $status;
    	}
        if($order_sn){
            $condition['order_sn']=$order_sn;
        }
    	 
    	$condition['user_id'] = $this->user_id;
    	$count = Db::name('rebate_log')->where($condition)->count();
    	$page = new Page($count,10);
    	$rebate_log = Db::name('rebate_log')->where($condition)->limit($page->firstRow,$page->listRows)->order('user_id desc')->select();
    	View::assign('page', $page->show());// 赋值分页输出
    	View::assign('rebate_log',$rebate_log);
    	View::assign('status',$status);
    	View::assign('result',$result);
    	return View::fetch();
    }

    /**
     * 订单商品评价列表
     */
    public function comment_list()
    {
        $order_id = input('order_id/d');
        $rec_id = input('rec_id/d');
        if (empty($order_id) || empty($rec_id)) {
            $this->error("参数错误");
        } else {
            //查找订单
            $order_comment_where['order_id'] = $order_id;
            $order_info = Db::name('order')->field('order_sn,order_id,add_time,prom_type') ->where($order_comment_where)->find();
            //查找评价商品
            $order_comment_where['rec_id'] = $rec_id;
            $order_goods = Db::name('order_goods')
                ->field('rec_id,goods_id,is_comment,goods_name,goods_num,goods_price,spec_key_name,item_id')
                ->where($order_comment_where)
                ->find();
            $order_info = array_merge($order_info,$order_goods);
            View::assign('order_info', $order_info);
            return View::fetch();
        }
    }

    /*
    *添加评论
    */
    public function add_comment()
    {
        $user_info = session('user');
        $comment_img = input('comment_img/a'); // 上传的图片文件
        $add['rec_id'] = input('rec_id/d');
        $add['goods_id'] = input('goods_id/d');
        $add['email'] = $user_info['email'];
        $hide_username = input('hide_username','');
        $add['username'] = $user_info['nickname'];
        $add['is_anonymous'] = $hide_username;  //是否匿名评价:0不是\1是
        $add['order_id'] = input('order_id/d');
        $add['service_rank'] = input('service_rank');
        $add['deliver_rank'] = input('deliver_rank');
        $add['goods_rank'] = input('goods_rank');
        $add['is_show'] = 1; //默认显示
        $add['content'] = input('content');
        $add['item_id'] = input('item_id');
        $comment_img && $add['img'] = serialize($comment_img);
        $add['add_time'] = time();
        $add['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $add['user_id'] = $this->user_id;
        $logic = new UsersLogic();
        //添加评论
        $row = $logic->add_comment($add);
        clearCache();
        exit(json_encode($row));
    }

    /**
     * 再来一单
     */
    public function oneMore()
    {
        $order_id = input('order_id');
        $order_goods_list = OrderGoods::where(['order_id' => $order_id])->select();
        if (empty($order_goods_list)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '订单商品不存在']);
        }
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $error_list = [];
        $order_goods_list_count = count($order_goods_list);
        $url = '';
        foreach ($order_goods_list as $order_goods) {
            $cartLogic->setGoodsModel($order_goods['goods_id']);
            $cartLogic->setSpecGoodsPriceByKey($order_goods['spec_key']);
            $cartLogic->setGoodsBuyNum(1);
            try {
                $cartLogic->addGoodsToCart();
            } catch (TpshopException $t) {
                $error = $t->getErrorArr();
                $error_list[] = $order_goods['goods_name'] . $order_goods['spec_key_name'] . $error['msg'];
                $url = $error['result']['url'] ? $error['result']['url'] : '';
            }
        }
        if ($order_goods_list_count != count($error_list)) {
            $this->ajaxReturn(['status' => 1, 'msg' => '加入购物车成功', 'result' => ['error_list' => $error_list, 'url' => $url]]);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '加入购物车失败', 'result' => ['error_list' => $error_list, 'url' => $url]]);
        }
    }
}