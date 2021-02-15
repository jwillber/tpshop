<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 应用地址
    'app_host'         => Env::get('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 是否启用事件
    'with_event'       => true,
    // 默认应用
    'default_app'      => 'home',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => true,
	
	
	
    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    // 密码加密串
    'AUTH_CODE' => "TPSHOP", //安装完毕之后不要改变，否则所有密码都会出错
    
    'ORDER_STATUS' =>[
        0 => '待确认',
        1 => '已确认',
        2 => '已收货',
        3 => '已取消',                
        4 => '已完成',//评价完
        5 => '已作废',
    ],
    'SHIPPING_STATUS' => array(
        0 => '未发货',
        1 => '已发货',
        2 => '部分发货'
    ),
    'PAY_STATUS' => array(
        0 => '未支付',
        1 => '已支付',
        2 => '部分支付',
        3 => '已退款',
        4 => '拒绝退款'
    ),
    'SEX' => [
        0 => '保密',
        1 => '男',
        2 => '女'
    ],
    'COUPON_TYPE' => [
    	0 => '下单赠送',
        1 => '指定发放',
        2 => '免费领取',
        3 => '线下发放',
    ],
	'PROM_TYPE' => [
		0 => '默认',
		1 => '抢购',
		2 => '团购',
		3 => '优惠'			
	],
    'TEAM_FOUND_STATUS' => array(
        '0'=>'待开团',
        '1'=>'已开团',
        '2'=>'拼团成功',
        '3'=>'拼团失败',
    ),
    'TEAM_FOLLOW_STATUS' => array(
        '0'=>'待拼单',
        '1'=>'拼单成功',
        '2'=>'成团成功',
        '3'=>'成团失败',
    ),
    //用户消息类型
    'CATEGORY'=>['系统通知','物流通知', '优惠促销','商品提醒','我的资产'],
    'TEAM_TYPE' => [0 => '分享团', 1 => '佣金团', 2 => '抽奖团'],
    'FREIGHT_TYPE' => [0 => '件数', 1 => '重量', 2 => '体积'],
    // 订单用户端显示状态
    'WAITPAY'=>' AND pay_status = 0 AND order_status = 0 AND pay_code !="cod" ', //订单查询状态 待支付
    'WAITSEND'=>' AND (pay_status=1 OR pay_code="cod") AND shipping_status !=1 AND order_status in(0,1) ', //订单查询状态 待发货
    'WAITRECEIVE'=>' AND shipping_status=1 AND order_status = 1 ', //订单查询状态 待收货    
    'WAITCCOMMENT'=> ' AND order_status=2 ', // 待评价 确认收货     //'FINISHED'=>'  AND order_status=1 ', //订单查询状态 已完成 
    'FINISH'=> ' AND order_status = 4 ', // 已完成
    'CANCEL'=> ' AND order_status = 3 ', // 已取消
    'CANCELLED'=> 'AND order_status = 5 ',//已作废
    'PAYED'=>' AND (order_status=2 OR (order_status=1 AND pay_status=1) ) ', //虚拟订单状态:已付款
	
	// 商品显示筛选
	'AUDIT_PASS'=>['audit' => 0],  //审核通过，前台可展示
	'AUDIT_WAIT'=>['audit' => 1],  //待审核
	'AUDIT_FAIL'=>['audit' => 2],  //审核失败
    
    'ORDER_STATUS_DESC' => [
        'WAITPAY' => '待支付',
        'WAITSEND'=>'待发货',
        'PORTIONSEND'=>'部分发货',
        'WAITRECEIVE'=>'待收货',
        'WAITCCOMMENT'=> '待评价',
        'CANCEL'=> '已取消',
        'FINISH'=> '已完成', //
        'CANCELLED'=> '已作废',
        'CANCEL_REFUND'=>'已取消(已退款)'
    ],

    'REFUND_STATUS'=>array(
        -2 => '服务单取消',//会员取消
        -1 => '审核失败',//不同意
        0  => '待审核',//卖家审核
        1  => '审核通过',//同意
        2  => '买家发货',//买家发货
        3  => '已收货',//服务单完成
        4  => '换货完成',
        5  => '退款完成',
    ),
    /**
     * 售后类型
     */
    'RETURN_TYPE'=>array(
        0=>'仅退款',
        1=>'退货退款',
        2=>'换货',
    ),
    //短信使用场景
    'SEND_SCENE' => array(
        '1'=>array('用户注册','验证码${code}，用户注册新账号, 请勿告诉他人，感谢您的支持!','regis_sms_enable'),
        '2'=>array('用户找回密码','验证码${code}，用于密码找回，如非本人操作，请及时检查账户安全','forget_pwd_sms_enable'),
        '3'=>array('客户下单','您有新订单，收货人：${consignee}，联系方式：${phone}，请您及时查收.','order_add_sms_enable'),
        '4'=>array('客户支付','客户下的单(订单ID:${orderId})已经支付，请及时发货.','order_pay_sms_enable'),
        '5'=>array('商家发货','尊敬的${userName}用户，您的订单已发货，收货人${consignee}，请您及时查收','order_shipping_sms_enable'),
        '6'=>array('身份验证','尊敬的用户，您的验证码为${code}, 请勿告诉他人.','bind_mobile_sms_enable'),
        '7'=>array('购买虚拟商品通知','尊敬的用户，您购买的虚拟商品${goodsName}兑换码已生成,请注意查收.','virtual_goods_sms_enable'),
        '8'=>array('发货超时提醒','已有${num}订单,发货超时，请尽快发货','delivery_timeout_sms_enable'),
        '9'=>array('待确认的取消订单','已有${num}条取消订单待确认,请尽快确认','unconfirmed_cancel_order_sms_enable'),
        '10'=>array('未付款订单催付','您的订单${order_sn}还未支付，请尽快支付！','urgent_pay_sms_enable'),
    ),
    
    'APP_TOKEN_TIME' => 60 * 60 * 24 , //App保持token时间 , 此处为1天
    
    /**
     *  订单用户端显示按钮     
        去支付     AND pay_status=0 AND order_status=0 AND pay_code ! ="cod"
        取消按钮  AND pay_status=0 AND shipping_status=0 AND order_status=0 
        确认收货  AND shipping_status=1 AND order_status=0 
        评价      AND order_status=1 
        查看物流  if(!empty(物流单号))   
        退货按钮（联系客服）  所有退换货操作， 都需要人工介入   不支持在线退换货
     */

    /*分页每页显示数*/
    'PAGESIZE' => 10,
    'finally_pay_time'=>1*24*3600,
    'WX_PAY2' => 1,

    /**假设这个访问地址是 www.tpshop.cn/home/goods/goodsInfo/id/1.html 
     *就保存名字为 home_goods_goodsinfo_1.html     
     *配置成这样, 指定 模块 控制器 方法名 参数名      
     */
    'HTML_CACHE_ARR'=> [
    //['mca'=>'home_Goods_goodsInfo','p'=>['id']],  
    //['mca'=>'home_Index_index'],  // 缓存首页静态页面
    //['mca'=>'home_Goods_ajaxComment','p'=>['goods_id','commentType','p']],  // 缓存评论静态页面 http://www.tpshop2.0.com/index.php?m=Home&c=Goods&a=ajaxComment&goods_id=142&commentType=1&p=1
    //['mca'=>'home_Goods_ajax_consult','p'=>['goods_id','consult_type','p']],  // 缓存咨询静态页面 http://www.tpshop2.0.com/index.php?m=Home&c=Goods&a=ajax_consult&goods_id=142&consult_type=0&p=2
    ],

    /*订单操作*/
    'CONVERT_ACTION'=>[
        'pay'=> '付款',
        'pay_cancel'=>'取消付款',
        'confirm'=>'确认订单',
        'cancel'=>'取消确认',
        'invalid'=>'作废订单',
        'remove'=>'删除订单',
        'delivery'=>'确认发货',
        'delivery_confirm'=>'确认收货',
    ],
    'WITHDRAW_STATUS'=>[
        '-2'=>'删除作废',
        '-1'=>'审核失败',
        '0'=>'申请中',
        '1'=>'审核通过',
        '2'=>'付款成功',
        '3'=>'付款失败',
    ],
    'RECHARGE_STATUS'=>[
        '0'=>'待支付',
        '1'=>'支付成功',
        '2'=>'交易关闭',
    ],
    'erasable_type' =>['.gif','.jpg','.jpeg','.bmp','.png','.mp4','.3gp','.flv','.avi','.wmv'],
    'COUPON_USER_TYPE'=>['全店通用','指定商品可用','指定分类商品可用'],
    'INVOUCE_TYPE'=>['普通发票','电子发票','增值税发票'],
    'image_upload_limit_size'=>1024 * 1024 * 5,//上传图片大小限制
	
	
];
