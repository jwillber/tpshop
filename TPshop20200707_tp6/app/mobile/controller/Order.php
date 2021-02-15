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
 * 2015-11-21
 */
namespace app\mobile\controller;
use think\facade\View;

use app\common\model\BespeakTemplateUnit;
use app\common\model\OrderBespeak;
use app\common\model\ReturnGoods;
use app\common\model\ShopOrder;
use app\common\model\TeamFound;
use app\common\logic\UsersLogic;
use app\common\logic\OrderLogic;
use app\common\logic\CommentLogic;
use app\common\model\Order as OrderModel;
use think\Page;
use think\Request;
use think\facade\Db;

class Order extends MobileBase
{

    public $user_id = 0;
    public $user = array();

    public function __construct()
    {
        parent::__construct();
        if (session('?user')) {
            $user = session('user');
            $user = Db::name('users')->where("user_id", $user['user_id'])->find();
            session('user', $user);  //覆盖session 中的 user
            $this->user = $user;
            $this->user_id = $user['user_id'];
            View::assign('user', $user); //存储用户信息
            View::assign('user_id', $this->user_id);
        } else {
            header("location:" . url('User/login'));
            exit;
        }
        $order_status_coment = array(
            'WAITPAY' => '待付款 ', //订单查询状态 待支付
            'WAITSEND' => '待发货', //订单查询状态 待发货
            'WAITRECEIVE' => '待收货', //订单查询状态 待收货
            'WAITCCOMMENT' => '待评价', //订单查询状态 待评价
        );
        View::assign('order_status_coment', $order_status_coment);
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function order_list()
    {
        $type = input('type');
        $is_shop = input('is_shop/d');
        $order = new OrderModel();
        $where_arr = [
            'user_id'=>$this->user_id,
            'deleted'=>0,//删除的订单不列出来
//            'prom_type'=>['<',5],//虚拟拼团订单不列出来
            'prom_type'=>['in',[0,1,2,3,4,10]],//虚拟拼团订单不列出来
            'shop_id'=>0,//过滤预约
        ];
        if($is_shop){
            $where_arr['shop_id'] = ['>', 0];
	    //TODO 添加判断shop_order表中   goods_id = 0
        }
        $count = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->count();
        $Page = new Page($count, 10);
        $order_list = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->limit($Page->firstRow,$Page->listRows)->order("order_id DESC")->select();
        View::assign('order_list', $order_list);
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_order_list');
        }
        return View::fetch();
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function shopOrderList()
    {
        $type = input('type');
        $p = input('p/d',1);
        $order = new OrderModel();
        $where_arr = [
            'user_id'=>$this->user_id,
            'deleted'=>0,//删除的订单不列出来
            'prom_type'=>['=',9],//自提订单为9

        ];


        $order_list = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->page($p,5)->order("order_id DESC")->select();
        View::assign('order_list', $order_list);
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_order_list');
        }
        return View::fetch();
    }

    //拼团订单列表
    public function team_list(){
        $type = input('type');
        $Order = new OrderModel();
        $order_where = ['prom_type' => 6, 'user_id' => $this->user_id, 'deleted' => 0,'pay_code'=>['<>','cod']];//拼团基础查询
        $listRows = 10;
        switch (strval($type)) {
            case 'WAITPAY':
                //待支付订单
                $order_where['pay_status'] = 0;
                $order_where['order_status'] = 0;
                break;
            case 'WAITTEAM':
                //待成团订单
                $found_order_id = Db::name('team_found')->where(['user_id'=>$this->user_id,'status'=>1])->limit($listRows)->order('found_id desc')->column('order_id');//团长待成团
                $follow_order_id = Db::name('team_follow')->where(['follow_user_id'=>$this->user_id,'status'=>1])->limit($listRows)->order('follow_id desc')->column('order_id');//团员待成团
                $team_order_id = array_merge($found_order_id,$follow_order_id);
                if (count($team_order_id) > 0) {
                    $order_where['order_id'] = ['in', $team_order_id];
                }else{
                    $order_where['order_id'] = 0;
                }
                break;
            case 'WAITSEND':
                //待发货
                $order_where['pay_status'] = 1;
                $order_where['shipping_status'] = ['<>',1];
                $order_where['order_status'] = ['<=',1];
                $found_order_id = Db::name('team_found')->where(['user_id'=>$this->user_id,'status'=>2])->limit($listRows)->order('found_id desc')->column('order_id');//团长待成团
                $follow_order_id = Db::name('team_follow')->where(['follow_user_id'=>$this->user_id,'status'=>2])->limit($listRows)->order('follow_id desc')->column('order_id');//团员待成团
                $team_order_id = array_merge($found_order_id,$follow_order_id);
                if (count($team_order_id) > 0) {
                    $order_where['order_id'] = ['in', $team_order_id];
                }else{
                    $order_where['order_id'] = 0;
                }
                break;
            case 'WAITRECEIVE':
                //待收货
                $order_where['shipping_status'] = 1;
                $order_where['order_status'] = 1;
                break;
            case 'WAITCCOMMENT':
                //已完成
                $order_where['order_status'] = 2;
                break;
        }        
        $order_count = $Order->where($order_where)->count();
        $page = new Page($order_count, $listRows);
        $order_list = $Order->with(['orderGoods'])->where($order_where)->limit($page->firstRow,$page->listRows)->order('order_id desc')->select();
        View::assign('order_list',$order_list);
        if (request()->isAjax()) {
            return View::fetch('ajax_team_list');
//            $this->ajaxReturn(['status'=>1,'msg'=>'获取成功','result'=>$order_list]);
        }
        return View::fetch();
    }

    public function team_detail(){
        $order_id = input('order_id');
        $Order = new \app\common\model\Order();
        $TeamFound = new TeamFound();
        $order_where = ['prom_type' => 6, 'order_id' => $order_id, 'deleted' => 0];
        $order = $Order->with(['orderGoods'])->where($order_where)->find();
        if (empty($order)) {
            $this->error('该订单记录不存在或已被删除');
        }
        $orderTeamFound = $order->teamFound;
        if ($orderTeamFound) {
            //团长的单
            View::assign('orderTeamFound', $orderTeamFound);//团长
        } else {
            //去找团长
            $teamFound = $TeamFound::where(['found_id' => $order->teamFollow['found_id']])->find();
            View::assign('orderTeamFound', $teamFound);//团长
        }
        View::assign('order', $order);
        return View::fetch();
    }

    /**
     * 订单详情
     * @return mixed
     */
    public function order_detail()
    {
        $id = input('id/d', 0);

        $Order = new OrderModel();
        $order = $Order::where(['order_id' => $id, 'user_id' => $this->user_id])->find();
        if (!$order) {
            $this->error('没有获取到订单信息');
        }
		$orderModel = new OrderModel();
		$childOrder = $orderModel->where('parent_sn', $order['order_id'])->select();
		if (!$childOrder->isEmpty()) {
			View::assign('childOrder', $childOrder);
		}
        //获取订单
        if ($order['prom_type'] == 5) {   //虚拟订单
            return redirect(url('virtual/virtual_order', ['order_id' => $id]));
        }
        if($order['invoice_desc'] == '不开发票')  $order['taxpayer'] = '';
        View::assign('order', $order);
        if($order['receive_btn']){
            //待收货详情
            return View::fetch('wait_receive_detail');
        }

        View::assign('type', input('type'));
        return View::fetch();
    }
    
     /**
     * 预约订单列表
     * @return mixed
     */
    public function bespeak_list()
    {
        $type = input('type');
        $order = new OrderModel();
        $where_arr = [
            'user_id'=>$this->user_id,
            'deleted'=>0,//删除的订单不列出来
            'prom_type'=>['=',7],  //7为预约订单
        ];
        $count = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->count();
        $Page = new Page($count, 10);
        $order_list = $order->where($where_arr)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where("1=1".config(strtoupper($type)));
                }
            })
            ->limit($Page->firstRow,$Page->listRows)->order("order_id DESC")->select();
        View::assign('bespeak_list', $order_list);
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_bespeak_list');
        }

        return View::fetch();
    }

    /**
     * 预约订单详情
     * @return mixed
     */
    public function bespeak_detail()
    {
        $id = input('id/d', 0);
        $Order = new OrderModel();
        $BespeakTemplateUnit = new BespeakTemplateUnit();
        $order = $Order::where(['order_id' => $id, 'user_id' => $this->user_id])->find();
        if (!$order) {
            $this->error('没有获取到订单信息');
        }
        //获取预约表单信息
        $order_bespeak = $BespeakTemplateUnit::hasWhere('OrderBespeak',['order_id'=>$id])->field('OrderBespeak.*')->order('sort desc')->select();
        View::assign('order_bespeak', $order_bespeak);
        View::assign('order', $order);
        //$this->ajaxReturn($order['shop_order']['take_time']);
        if($order['receive_btn']){
            //待收货详情
            return View::fetch('wait_receive_detail');
        }
        return View::fetch();
    }

    /**
     * 物流信息
     * @return mixed
     */
    public function express()
    {
        $order_id = input('get.order_id/d', 0);
        $order_goods = Db::name('order_goods')->where("order_id", $order_id)->select();
        if($order_goods->isEmpty() || empty($order_id)){
            $this->error('没有获取到订单信息');
        }
        $delivery = Db::name('delivery_doc')->where("order_id", $order_id)->find();
        View::assign('order_goods', $order_goods);
        View::assign('delivery', $delivery);
        return View::fetch();
    }

    /**
    * 取消订单
    */
    public function cancel_order()
    {
        $id = input('get.id/d');
        //检查是否有积分，余额支付
        $logic = new OrderLogic();
        $data = $logic->cancel_order($this->user_id, $id);
        $this->ajaxReturn($data);
    }

    /**
     * 取消详情
     * @return \think\mixed
     */
    public function cancel_order_info(){
        $order_id = input('order_id/d',0);
        $Orders = new OrderModel();
        $order = $Orders->where(array('order_id'=>$order_id,'order_status'=>3,'pay_status'=>['>',0]))->find();

        if(!$order){
            $this->error('非法操作！');
        }

        View::assign('order', $order);
        return View::fetch();
    }




    /**
     * 确定收货成功
     */
    public function order_confirm()
    {
        $id = input('id/d', 0);
        $data = confirm_order($id, $this->user_id);
        if(request()->isAjax()){
            $this->ajaxReturn($data);
        }
        if ($data['status'] != 1) {
            $this->error($data['msg'],url('Mobile/Order/order_list'));
        } else {
            $model = new UsersLogic();
            $order_goods = $model->get_order_goods($id);
            View::assign('order_goods', $order_goods);
            return View::fetch();
            exit;
        }
    }
    //订单支付后取消订单
    public function refund_order()
    {
        $order_id = input('get.order_id/d');

        $order = Db::name('order')
            ->field('order_id,pay_code,pay_name,user_money,integral_money,coupon_price,order_amount,consignee,mobile,prom_type')
            ->where(['order_id' => $order_id, 'user_id' => $this->user_id])
            ->find();

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

        $logic = new \app\common\logic\OrderLogic;
        $return = $logic->recordRefundOrder($this->user_id, $order_id, $user_note, $consignee, $mobile);

        $this->ajaxReturn($return);
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
        //退换货商品信息
        $count = Db::name('return_goods')->where("user_id", $this->user_id)->count();
        $pagesize = config('PAGESIZE');
        $page = new Page($count, $pagesize);
        $list = Db::name('return_goods')->alias('rg')
            ->field('rg.*,og.goods_name,og.spec_key_name')
            ->join('order_goods og','rg.rec_id=og.rec_id','LEFT')
            ->where(['rg.user_id'=>$this->user_id])
            ->order("rg.id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $state = config('REFUND_STATUS');
        View::assign('list', $list);
        View::assign('state',$state);
        View::assign('page', $page->show());// 赋值分页输出
        if (input('is_ajax')) {
            return View::fetch('ajax_return_goods_list');
            exit;
        }
        return View::fetch();
    }

    /**
     *  退货详情
     */
    public function return_goods_info()
    {
        $id = input('id/d', 0);
        $returnGoods = new ReturnGoods();
        $return_goods = $returnGoods->where("id = $id")->find();
        if(empty($return_goods)){
            $this->error('参数错误');
        }
        $return_goods['seller_delivery'] = unserialize($return_goods['seller_delivery']);  //订单的物流信息，服务类型为换货会显示
        $return_goods['delivery'] = unserialize($return_goods['delivery']);  //订单的物流信息，服务类型为换货会显示
        if ($return_goods['imgs'])
            $return_goods['imgs'] = explode(',', $return_goods['imgs']);
        $goods = Db::name('order_goods')->where("rec_id = {$return_goods['rec_id']} ")->find();
		if ($return_goods['suppliers_id'] > 0 && $return_goods['receiving_address'] == 1) {//此时收货方是供应商
			$supplier = Db::name('suppliers')->where('suppliers_id', $return_goods['suppliers_id'])->find();
			View::assign('supplier', $supplier);
		}
		$region_list = get_region_list();
        View::assign('region_list',$region_list);
        View::assign('state',config('REFUND_STATUS'));
        View::assign('return_type', config('RETURN_TYPE'));
        View::assign('goods', $goods);
        View::assign('return_goods', $return_goods);
        return View::fetch();
    }

    /**
     * 修改退货状态，发货
     */
    public function checkReturnInfo()
    {
        $data = input('post.');
        $data['delivery'] = serialize($data['delivery']);
        $data['status'] = 2;
        $res = Db::name('return_goods')->where(['id'=>$data['id'],'user_id'=>$this->user_id])->save($data);
        if($res !== false){
            $this->ajaxReturn(['status'=>1,'msg'=>'发货提交成功','url'=>url('Mobile/Order/return_goods_info',['id'=>$data['id']])]);
        }else{
            $this->ajaxReturn(['status'=>-1,'msg'=>'提交失败','url'=>'']);
        }
    }

    /**
     * @return mixed
     * @throws \think\exception\DbException
     * @throws db\exception\DataNotFoundException
     * @throws db\exception\ModelNotFoundException
     */
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
     * 取消售后服务
     * @author lxl
     * @time 2017-4-19
     */
    public function return_goods_cancel(){
        $id = input('id',0);
        if(empty($id))$this->ajaxReturn(['status'=>-1,'msg'=>'参数错误']);
        $return_goods = Db::name('return_goods')->where(array('id'=>$id,'user_id'=>$this->user_id))->find();
        if(empty($return_goods)) $this->ajaxReturn(['status'=>-1,'msg'=>'参数错误']);
        $res= Db::name('return_goods')->where(array('id'=>$id))->save(array('status'=>-2,'canceltime'=>time()));
        if ($res !== false){
            $this->ajaxReturn(['status'=>1,'msg'=>'取消成功']);
        }else{
            $this->ajaxReturn(['status'=>-1,'msg'=>'取消失败']);
        }
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
            $this->success("操作成功", url("Order/return_goods_info", array('id' => $return_id)));
        }
        $this->error("操作失败");
    }

    /**
     *  评论晒单
     * @return mixed
     */
    public function comment()
    {
        $user_id = $this->user_id;
        $status = input('get.status');
        $logic = new CommentLogic;
        $data = $logic->getComment($user_id, $status); //获取评论列表
        View::assign('page', $data['page']);// 赋值分页输出
        View::assign('comment_page', $data['page']);
        View::assign('comment_list', $data['result']);
        View::assign('active', 'comment');
        if(input('is_ajax')){
            return View::fetch('ajax_comment_list');
        }
        return View::fetch();
    }

    /**
     *添加评论
     */
    public function add_comment()
    {
        if (IS_POST) {
            // 晒图片
            if($_FILES['comment_img_file']['error'] != 4)
            $files = request()->file('comment_img_file');
            $save_url = 'comment/' . date('Y', time()) . '/' . date('m-d', time()).'/';
            $save_url2 = UPLOAD_PATH.$save_url;
            if($files) {
                foreach ($files as $file) {
                    // 移动到框架应用根目录/public/uploads/ 目录下                    
                    $originalName = strtolower($file->getOriginalName());
                    if(strstr($originalName,'.php') || strstr($originalName,'.js'))                             
                        $this->ajaxReturn(['status' => -1,'msg' =>'上错图片格式错误']);

                        $upload_max_filesize = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
                        $maxsize = 30000000;

                        if($file->getSize() > $maxsize)
                            $this->ajaxReturn(['status' => -1,'msg' =>'上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件,且系统配置不能超过:'.$upload_max_filesize]);
                        
                        $extension = strtolower($file->extension());
                        if(!in_array($extension,['jpg','jpeg','gif','png']))
                                $this->ajaxReturn(['status' => -1,'msg' =>'仅可上传jpg,jpeg,gif,png文件']);
                        $savename = md5(mt_rand()).'.'.$file->extension();
                        \think\facade\Filesystem::disk('public')->putFileAs($save_url, $file,$savename);            
                        $comment_img[] = '/' . $save_url2 . '/' . $savename;            
                }
            }
            if (!empty($comment_img)) {
                $add['img'] = serialize($comment_img);
            }

            $user_info = session('user');
            $logic = new UsersLogic();
            $add['rec_id'] = input('rec_id/d');
            $add['goods_id'] = input('goods_id/d');
            $add['email'] = $user_info['email'];
            $hide_username = input('hide_username');
            $add['username'] = $user_info['nickname'];
            $add['is_anonymous'] = $hide_username;  //是否匿名评价:0不是\1是
            $add['order_id'] = input('order_id/d');
            $add['service_rank'] = input('service_rank');
            $add['deliver_rank'] = input('deliver_rank');
            $add['goods_rank'] = input('goods_rank');
            $add['is_show'] = 1; //默认显示
            $add['content'] = input('content');
            $add['item_id'] = input('item_id');
            $add['add_time'] = time();
            $add['ip_address'] = request()->ip();
            $add['user_id'] = $this->user_id;

            //添加评论
            $row = $logic->add_comment($add);
            if ($row['status'] == 1) {
                $this->ajaxReturn(['status' => 1,'msg' =>'评论成功','url' =>url('/Mobile/Order/comment',['status'=>1])]);
            } else {
                $this->ajaxReturn(['status' => -1,'msg' =>$row['msg']]);
            }
        }
        $rec_id = input('rec_id/d');
        $order_goods = Db::name('order_goods')->where("rec_id", $rec_id)->find();
        $order_info = Db::name('order')->where("order_id", $order_goods['order_id'])->find();
        View::assign('order_goods', $order_goods);
        View::assign('rec_id', $rec_id);
        View::assign('order_info', $order_info);
        return View::fetch();
    }

    /**
     * 待收货列表

     */
    public function wait_receive()
    {
        $where = ' user_id=' . $this->user_id;
        //条件搜索
        if (input('type') == 'WAITRECEIVE') {
            $where .= config(strtoupper(input('type')));
        }
        $count = Db::name('order')->where($where)->count();
        $pagesize = config('PAGESIZE');
        $Page = new Page($count, $pagesize);
        $show = $Page->show();
        $order_str = "order_id DESC";
        $order = new OrderModel();
        $order_list = $order->order($order_str)->where($where)->limit($Page->firstRow,$Page->listRows)->select();
        View::assign('page', $show);
        View::assign('order_list', $order_list);
        if ($_GET['is_ajax']) {
            return View::fetch('ajax_wait_receive');
            exit;
        }
        return View::fetch();
    }

    /**
     * 评论详情
     * @return mixed
     */
    public function comment_info(){
        $commentLogic = new \app\common\logic\CommentLogic;
        $comment_id = input('comment_id/d');
        if(empty($comment_id)){
            $this->error('评论不存在');
        }
        $res = $commentLogic->getCommentInfo($comment_id);
        if(empty($res['comment_info'])){
            $this->error('评论不存在');
        }
        if(!empty($res['comment_info']['img'])) $res['comment_info']['img'] = unserialize($res['comment_info']['img']);
        $user = get_user_info($res['comment_info']['user_id']);
        if($user){
            $res['comment_info']['nickname'] = $user['nickname'];
            $res['comment_info']['head_pic'] = $user['head_pic'];
        }else{
            $res['comment_info']['nickname'] = $res['comment_info']['username'];
        }
        View::assign('comment_info',$res['comment_info']);
        View::assign('comment_id',$res['comment_info']['comment_id']);
        View::assign('reply',$res['reply']);
        View::assign('user',$this->user);
        return View::fetch();
    }

    /**
     * 评论别的用户评论
     */
    public function replyComment(){
        $data=input('post.');
        $data['reply_time'] = time();
        $data['deleted'] = 0;
        $data['head_pic'] = Db::name('users')->where(['user_id'=>$this->user_id])->value('head_pic');
        $data['user_id'] = $this->user_id;
        $return = Db::name('reply')->insertGetId($data);
        if($return){
            Db::name('comment')->where(['comment_id'=>$data['comment_id']])->inc('reply_num')->update();
            $data['reply_time'] = date('Y-m-d H:m',$data['reply_time']);
            $this->ajaxReturn(['status'=>1,'msg'=>'评论成功！','result'=>$data]);
            exit;
        } else {
            $this->ajaxReturn(['status'=>0,'msg'=>"评论失败"]);
        }
    }

    /**
     *  点赞
     */
    public function ajaxZan()
    {
        $comment_id = input('post.comment_id/d');
        $user_id = $this->user_id;
        $comment_info = Db::name('comment')->where(array('comment_id' => $comment_id))->find();  //获取点赞用户ID
        $comment_user_id_array = explode(',', $comment_info['zan_userid']);
        if (in_array($user_id, $comment_user_id_array)) {  //判断用户有没点赞过
            $result = ['status' => 0, 'msg' => '您已经点过赞了~', 'result' => ''];
        } else {
            array_push($comment_user_id_array, $user_id);  //加入用户ID
            $comment_user_id_string = implode(',', $comment_user_id_array);
            $comment_data['zan_num'] = $comment_info['zan_num'] + 1;  //点赞数量加1
            $comment_data['zan_userid'] = $comment_user_id_string;
            Db::name('comment')->where(array('comment_id' => $comment_id))->save($comment_data);
            $result = ['status' => 1, 'msg' => '点赞成功~', 'result' => ''];
        }
        exit(json_encode($result));
    }

    public function getOrderBarCode(){
        $order = new \app\common\logic\OrderLogic();
        $order->createOrderBarCode();
    }

    public function paySuccess(){
        $order_id = input('id/d',false);
        if(!$order_id){
            $this->error('订单不存在');
        }
        $order = Db::name('order')->where(['order_id'=>$order_id,'user_id'=>$this->user_id])->field('order_id,pay_status,order_sn,total_amount,user_money,order_amount,prom_type,paid_money')->find();
        if(!$order){
            $this->error('订单不存在');
        }
        // 如果是预售
        if($order['prom_type'] == 4){
			// 部分订金支付
            if($order['pay_status'] != 1 ){
                $order['total_amount'] = $order['paid_money'];
            }else{
                $order['total_amount'] = $order['total_amount'] - $order['paid_money'];
            }
        }else{
            $order['total_amount'] = $order['user_money'] + $order['order_amount'];
        }
        View::assign('order',$order);
        return View::fetch();
    }

}