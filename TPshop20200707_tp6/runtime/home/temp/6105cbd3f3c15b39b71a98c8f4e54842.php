<?php /*a:3:{s:46:"../template/pc/rainbow/order/order_detail.html";i:1593659405;s:39:"../template/pc/rainbow/user/header.html";i:1593659405;s:41:"../template/pc/rainbow/public/footer.html";i:1593659405;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的账户-<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <meta name="keywords" content="<?php echo $tpshop_config['shop_info_store_keyword']; ?>" />
    <meta name="description" content="<?php echo $tpshop_config['shop_info_store_desc']; ?>" />
    <link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/tpshop.css" />
    <link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/myaccount.css" />
    <link rel="shortcut  icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"  />
    <script src="/pc/rainbow/static/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body class="bg-f5">
<script src="/public/js/global.js" type="text/javascript"></script>
<link rel="stylesheet" href="/pc/rainbow/static/css/location.css" type="text/css"><!-- 收货地址，物流运费 -->
<script src="/public/static/js/layer/layer.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/base.css"/>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"/>
<style>
	.list1 li{float:left;}
</style>
<div class="tpshop-tm-hander home-index-top p">
	<div class="top-hander clearfix">
		<div class="w1224 pr clearfix">
			<div class="fl">
				<div class="sendaddress pr fl">
				  <span>送货至：</span>
				  <!-- <span>深圳<i class="share-a_a1"></i></span>-->
				  <span>
					  <ul class="list1">
						  <li class="summary-stock though-line">
							  <div class="dd" style="border-right:0px;width:200px;">
								  <div class="store-selector add_cj_p">
									  <div class="text"><div></div><b></b></div>
									  <div onclick="$(this).parent().removeClass('hover')" class="close"></div>
								  </div>
							  </div>
						  </li>
					  </ul>
				  </span>
				</div>
				<div class="fl nologin">
					<a class="red" href="<?php echo url('Home/user/login'); ?>">登录</a>
					<a href="<?php echo url('Home/user/reg'); ?>">注册</a>
				</div>
				<div class="fl islogin hide">
					<a class="red userinfo" href="<?php echo url('Home/user/index'); ?>"></a>
					<a  href="<?php echo url('Home/user/logout'); ?>"  title="退出" target="_self">安全退出</a>
				</div>
			</div>
			<ul class="top-ri-header fr clearfix">
				<li><a target="_blank" href="<?php echo url('Home/Order/order_list'); ?>">我的订单</a></li>
				<li class="spacer"></li>
				<li><a target="_blank" href="<?php echo url('Home/User/visit_log'); ?>">我的浏览</a></li>
				<li class="spacer"></li>
				<li><a target="_blank" href="<?php echo url('Home/User/goods_collect'); ?>">我的收藏</a></li>
				<li class="spacer"></li>
				<li>客户服务</li>
				<li class="spacer"></li>
				<li class="hover-ba-navdh">
					<div class="nav-dh">
						<span>网站导航</span>
						<i class="share-a_a1"></i>
					</div>
					<ul class="conta-hv-nav clearfix">
						<li>
							<a href="<?php echo url('Home/Activity/promoteList'); ?>">优惠活动</a>
						</li>
						<li>
							<a href="<?php echo url('Home/Activity/pre_sell_list'); ?>">预售活动</a>
						</li>
						<!--<li>
							<a href="<?php echo url('Home/Goods/integralMall'); ?>">拍卖活动</a>
						</li>-->
						<li>
							<a href="<?php echo url('Home/Goods/integralMall'); ?>">兑换中心</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="nav-middan-z p home-index-head">
	<div class="header w1224">
		<div class="ecsc-logo" >
			<a href="/" class="logo">
                <img src="<?php echo (isset($tpshop_config['shop_info_store_user_logo']) && ($tpshop_config['shop_info_store_user_logo'] !== '')?$tpshop_config['shop_info_store_user_logo']:'/public/static/images/logo/pc_home_user_logo_default.png'); ?>" style="width: 159px;height: 58px">
            </a>
		</div>
		<div class="m-index">
			<a href="<?php echo url('Home/User/index'); ?>" class="index">我的商城</a>
			<a href="/" class="home">返回商城首页</a>
		</div>
		<div class="m-navitems">
			<ul class="fixed p">
				<li><a href="<?php echo url('Home/Index/index'); ?>">首页</a></li>
				<li>
					<div class="u-dl">
						<div class="u-dt">
							<span>账户设置</span>
							<i></i>
						</div>
						<div class="u-dd">
							<a href="<?php echo url('Home/User/info'); ?>">个人信息</a>
							<!--<a href="<?php echo url('Home/User/safety_settings'); ?>">安全设置</a>-->
							<a href="<?php echo url('Home/User/address_list'); ?>">收货地址</a>
						</div>
					</div>
				</li>
				<li class="u-msg"><a class="J-umsg" href="<?php echo url('Home/User/message_notice'); ?>">消息<span><?php if($user_message_count > 0): ?><?php echo $user_message_count; ?><?php endif; ?></span></a></li>
				<li><a href="<?php echo url('Home/Goods/integralMall'); ?>">积分商城</a></li>
				<li class="search_li">
				   <form id="sourch_form" id="sourch_form" method="post" action="<?php echo url('Home/Goods/search'); ?>">
		           	<input class="search_usercenter_text" name="q" id="q" type="text" value="<?php echo app('request')->param('q'); ?>"/>
		           	<a class="search_usercenter_btn" href="javascript:;" onclick="if($.trim($('#q').val()) != '') $('#sourch_form').submit();">搜索</a>
		           </form>
		        </li>
			</ul>
		</div>
		<div class="u-g-cart fr" id="hd-my-cart">
			<a href="<?php echo url('Home/Cart/index'); ?>">
			<div class="c-n fl">
				<i class="share-shopcar-index"></i>
				<span>我的购物车</span>
				<em class="shop-nums" id="cart_quantity"></em>
			</div>
			</a>
			<div class="u-fn-cart" id="show_minicart">
				<div class="minicartContent" id="minicart">
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/pc/rainbow/static/js/common.js"></script>
<!--------收货地址，物流运费-开始-------------->
<script src="/public/js/locationJson.js"></script>
<script src="/pc/rainbow/static/js/location.js"></script>
<script>doInitRegion();</script>


<div class="home-index-middle">
    <div class="w1224">
        <div class="g-crumbs">
            <a href="<?php echo url('User/index'); ?>">我的商城</a><i class="litt-xyb"></i>
            <a href="<?php echo url('Order/order_list'); ?>">订单中心</a><i class="litt-xyb"></i>
            <span><b>订单：<?php echo $order['order_sn']; ?></b></span>
        </div>
        <div class="home-main">
            <div class="com-topyue">
                <div class="wacheng fl">
                    <p class="ddn1"><span>订单号：</span><span><?php echo $order['order_sn']; ?></span></p>
                    <?php if($order['prom_type'] == 4): ?>
                        <p class="ddn1"><span>订单类型：</span><span>预售订单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        <?php if($order['pre_sell']['deposit_price'] > 0): ?>
                            <p class="ddn1"><span>尾款支付开始时间：</span>
                                <span><?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_start_time'])? strtotime($order['pre_sell']['pay_start_time']) : $order['pre_sell']['pay_start_time']); ?></span>
                            </p>
                            <p class="ddn1"><span>尾款支付截止时间：</span>
                                <span><?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_end_time'])? strtotime($order['pre_sell']['pay_end_time']) : $order['pre_sell']['pay_end_time']); ?></span>
                            </p>
                        <?php endif; if($order['pre_sell']['is_finished'] == 3): ?>
                            <p class="ddn1"><span>关闭原因：</span><span>商家取消活动，返还订金</span></p>
                            <h3 style="font: 700 24px/34px 'Microsoft YaHei';color: #e4393c; padding-top:20px;">订单关闭</h3>
                        <?php endif; if($order['pre_sell']['is_finished'] == 2): if(time() > $order['pre_sell']['pay_end_time'] AND $order['pay_status'] != 1): ?>
                                <p class="ddn1"><span>关闭原因：</span><span>已过支付尾款时间</span></p>
                                <h3 style="font: 700 24px/34px 'Microsoft YaHei';color: #e4393c; padding-top:20px;">订单关闭</h3>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; if($order['pay_btn'] == 1): ?>
                        <h3 style="font: 700 24px/34px 'Microsoft YaHei';color: #e4393c; padding-top:20px;">等待付款</h3>
                        <a class="ddn3" style="margin-top:20px;" href="<?php echo url('Home/Cart/cart4',array('order_id'=>$order['order_id'])); ?>">立即付款</a>
                    <?php else: if(!(empty($childOrder) || (($childOrder instanceof \think\Collection || $childOrder instanceof \think\Paginator ) && $childOrder->isEmpty()))): ?>
							<h1 class="ddn2">已拆单</h1>
							<p class="ddn1"><span>订单被拆分，可点击下列新订单号查看信息</span></p>
							<?php if(is_array($childOrder) || $childOrder instanceof \think\Collection || $childOrder instanceof \think\Paginator): if( count($childOrder)==0 ) : echo "" ;else: foreach($childOrder as $key=>$vo): ?>
								<p class="ddn1"><a href="<?php echo url('Order/order_detail',['id' => $vo['order_id']]); ?>"><?php echo $vo['order_sn']; ?></a></p>
							<?php endforeach; endif; else: echo "" ;endif; else: ?>
							<h1 class="ddn2"><?php echo $order['order_status_detail']; ?></h1>
						<?php endif; ?>
                        <!--<a class="ddn3" href="">评价</a>-->
                    <?php endif; if($order['receive_btn'] == 1 && $order['pay_status'] == 1): ?>
                        <a class="ddn3" style="margin-top:20px;" href="javascript:;" onclick="order_confirm(<?php echo $order['order_id']; ?>)">确认收货</a>
                    <?php endif; if($order['cancel_btn'] == 1): if($order['pay_status'] == 0): ?>
                            <a class="ddn3" style="margin-top:10px;color:#666;" href="javascript:;" onclick="cancel_order(<?php echo $order['order_id']; ?>)">取消订单</a>
                        <?php else: ?>
                            <a class="consoorder ddn3" href="javascript:void(0);" data-url="<?php echo url('Home/Order/refund_order',array('order_id'=>$order['order_id'])); ?>" onClick="refund_order(this)" >取消订单</a>
                        <?php endif; ?>
                    <?php endif; if($order['pay_tail_btn'] == 1): ?>
                        <a class="ddn3" style="margin-top:20px;" href="<?php echo url('/Home/Cart/cart4',array('order_id'=>$order['order_id'])); ?>">支付尾款</a>
                    <?php endif; ?>

                   <!-- <p class="ddn4"><a href="<?php echo url('order_detail',array('id'=>$order['order_id'],'act'=>'print')); ?>" target="_blank"><i class="y-comp6"></i>打印订单</a></p>-->
                </div>
                <div class="wacheng2 fr">
                    <p class="dd2n">感谢您在商城购物，欢迎您对本次交易及所购商品进行评价。</p>
                    <div class="liuchaar p">
                        <ul>
                            <li>
                                <div class="aloinfe">
                                    <i class="y-comp"></i>
                                    <div class="ddfon">
                                        <p>提交订单</p>
                                        <p><?php echo date('Y-m-d H:i:s',!is_numeric($order['add_time'])? strtotime($order['add_time']) : $order['add_time']); ?></p>
                                    </div>
                                </div>
                            </li>
                            <?php if($order['pay_code'] != 'cod'): ?>
                                <li><i class='y-comp91 <?php if($order['pay_time'] == 0): ?>top322<?php endif; ?>'></i></li>
                                <li>
                                    <div class="aloinfe fime1">
                                        <i class='y-comp2 <?php if($order['pay_time'] == 0): ?>lef64<?php endif; ?>'></i>
                                        <div class="ddfon">
                                            <p>付款成功</p>
                                            <?php if($order['pay_time'] > 0): ?>
                                                <p><?php echo date('Y-m-d H:i:s',!is_numeric($order['pay_time'])? strtotime($order['pay_time']) : $order['pay_time']); ?></p>
                                            <?php endif; if($order['prom_type'] == 4 AND $order['pre_sell']['deposit_price'] > 0): ?>
                                                尾款支付<?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_start_time'])? strtotime($order['pre_sell']['pay_start_time']) : $order['pre_sell']['pay_start_time']); ?>至<?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_end_time'])? strtotime($order['pre_sell']['pay_end_time']) : $order['pre_sell']['pay_end_time']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <li><i class='y-comp91 <?php if(($order['shop_id'] == 0 and $order['shipping_time'] == 0 and $order['shipping_status'] == 0 or $order['shipping_status'] == 2) or $order['shop_order']['is_write_off'] === 0): ?>top322<?php endif; ?>'></i></li>
                            <li>
                                <div class="aloinfe fime2">
                                    <i class='y-comp3 <?php if(($order['shop_id'] == 0 and $order['shipping_time'] == 0 and $order['shipping_status'] == 0 or $order['shipping_status'] == 2) or $order['shop_order']['is_write_off'] === 0): ?>lef64<?php endif; ?>'></i>
                                    <div class="ddfon">
                                        <p><?php if($order['shop_id'] == 0): ?>卖家发货<?php else: ?>等待自提<?php endif; ?></p>
                                        <?php if($order['shipping_time'] > 0): ?>
                                            <p><?php echo date('Y-m-d',!is_numeric($order['shipping_time'])? strtotime($order['shipping_time']) : $order['shipping_time']); ?></p>
                                            <p><?php echo date('H:i:s',!is_numeric($order['shipping_time'])? strtotime($order['shipping_time']) : $order['shipping_time']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                            <?php if($order['shop_id'] == 0): ?>
                                <li><i class='y-comp91 <?php if(($order['shop_id'] == 0 and $order['shipping_time'] == 0 and $order['shipping_status'] == 0 or $order['shipping_status'] == 2)): ?>top322<?php endif; ?>'></i></li>
                            <li>
                                <div class="aloinfe fime3">
                                    <i class='y-comp4 <?php if(($order['shop_id'] == 0 and $order['shipping_time'] == 0 and $order['shipping_status'] == 0 or $order['shipping_status'] == 2)): ?>lef64<?php endif; ?>'></i>
                                    <div class="ddfon">
                                        <p>等待收货</p>
                                    </div>
                                </div>
                            </li>
                            <?php endif; if($order['pay_code'] == 'cod'): ?>
                                <li><i class='y-comp91 <?php if($order['pay_time'] == 0): ?>top322<?php endif; ?>'></i></li>
                                <li>
                                    <div class="aloinfe fime1">
                                        <i class='y-comp2 <?php if($order['pay_time'] == 0): ?>lef64<?php endif; ?>'></i>
                                        <div class="ddfon">
                                            <p>付款成功</p>
                                            <?php if($order['pay_time'] > 0): ?>
                                                <p><?php echo date('Y-m-d H:i:s',!is_numeric($order['pay_time'])? strtotime($order['pay_time']) : $order['pay_time']); ?></p>
                                            <?php endif; if($order['prom_type'] == 4 AND $order['pre_sell']['deposit_price'] > 0): ?>
                                                尾款支付<?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_start_time'])? strtotime($order['pre_sell']['pay_start_time']) : $order['pre_sell']['pay_start_time']); ?>至<?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_end_time'])? strtotime($order['pre_sell']['pay_end_time']) : $order['pre_sell']['pay_end_time']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <li><i class='y-comp91 <?php if($order['confirm_time'] == 0): ?>top322<?php endif; ?>'></i></li>
                            <li>
                                <div class="aloinfe fime4">
                                    <i class='y-comp5 <?php if($order['confirm_time'] == 0): ?>lef64<?php endif; ?>'></i>
                                    <div class="ddfon">
                                        <p>完成</p>
                                        <?php if($order['confirm_time'] > 0): ?>
                                            <p><?php echo date('Y-m-d H:i:s',!is_numeric($order['confirm_time'])? strtotime($order['confirm_time']) : $order['confirm_time']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="grouupanjf">
                        <?php if($order['pay_status'] == 0): ?>
                            <a href="javascript:;">完成订单可能获得:<i class="y-comp7"></i>积分&nbsp;&nbsp;<i class="y-comp8"></i>会员成长值&nbsp;&nbsp;<i class="y-comp7"></i>优惠券</a>
                            <?php else: ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if($order['shipping_status'] == 1 and !empty($order['shipping_code'])): if(is_array($order['delivery_doc']) || $order['delivery_doc'] instanceof \think\Collection || $order['delivery_doc'] instanceof \think\Paginator): if( count($order['delivery_doc'])==0 ) : echo "" ;else: foreach($order['delivery_doc'] as $key=>$v): ?>
                <div class="home-main reseting ma-to-20">
                    <div class="com-topyue">
                        <div class="wacheng fl">
                            <div class="shioeboixe">
                                <div class="sohstyle p">
                                    <div class="odjpyes">
                                        <img src="/pc/rainbow/static/images/kuaidi.jpg"/>
                                    </div>
                                    <div class="osnhptek">
                                        <p><span>送货方式：</span><span><?php echo $v['shipping_name']; ?></span></p>
                                        <p><span>快递单号：</span><span><?php echo $v['invoice_no']; ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wacheng2 fr">
                            <div class="listchatu">
                                <ul id="express_info_<?php echo $v['invoice_no']; ?>">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(function() {
                        $.ajax({
                            type : "GET",
                            dataType: "json",
                            url:"<?php echo url('home/api/queryExpress'); ?>?shipping_code=<?php echo $v['shipping_code']; ?>&invoice_no=<?php echo $v['invoice_no']; ?>",//+tab,
                            success: function(data){
                                var html = '';
                                if(data.status == 200){
                                    $.each(data.data, function(i,n){
                                        if(i == 0){
                                            html += "<li class='first'><i class='node-icon' style='margin-left:20px'></i><span class='time'>"+n.time+"</span><span class='txt'>"+n.context+"</span></li>";
                                        }else{
                                            html += "<li><i class='node-icon' style='margin-left:20px'></i><span class='time'>"+n.time+"</span><span class='txt'>"+n.context+"</span></li>";
                                        }
                                    });
                                }else{
                                    //快递鸟
                                    var traces = data.Traces;
                                    if(traces.length>0){
                                        $.each(traces, function(i,n){
                                            if(i == 0){
                                                html += "<li class='first'><i class='node-icon' style='margin-left:20px'></i><span class='time'>"+n.AcceptTime+"</span><span class='txt'>"+n.AcceptStation+"</span></li>";
                                            }else{
                                                html += "<li><i class='node-icon' style='margin-left:20px'></i><span class='time'>"+n.AcceptTime+"</span><span class='txt'>"+n.AcceptStation+"</span></li>";
                                            }
                                        });
                                    }else{
                                        html += "<li class='first' style='margin-left:20px'><i class='node-icon'></i><span class='txt'>"+data.message+"</span></li>";
                                    }

                                }
                                $("#express_info_<?php echo $v['invoice_no']; ?>").html(html);
                            }
                        });
                    });
                </script>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        <?php endif; ?>
        <div class="home-main ma-to-20">
            <div class="rshrinfmas">
                <?php if($order['shop_id'] > 0): ?>
                   <div class="spff spff-two">
                        <h2>自提信息</h2>
                        <div class="psbaowq">
                            <p><span class="fircl">地址：</span>
                                <span class="lascl"><?php echo $order['full_address']; ?></span>
                            </p>
                            <p><span class="fircl">提货时间：</span><span class="lascl"><?php echo $order['shop_order']['0']['take_time']; ?></span></p>
                            <p><span class="fircl">提货人：</span><span class="lascl"><?php echo $order['consignee']; ?></span></p>
                            <p><span class="fircl">手机号码：</span><span class="lascl"><?php echo $order['mobile']; ?></span></p>
                            <p><span class="fircl">提货码：</span><span class="lascl"><?php echo $order['shop_order']['0']['bar_code']; ?></span></p>
                            <p><span class="fircl">联系自提点：</span><span class="lascl"><?php echo $order['shop']['phone']; ?></span></p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="spff spff-two">
                        <h2>收货人信息</h2>
                        <div class="psbaowq">
                            <p><span class="fircl">收货人：</span><span class="lascl"><?php echo $order['consignee']; ?></span></p>
                            <p><span class="fircl">地址：</span>
                                <span class="lascl"><?php echo $order['full_address']; ?></span>
                            </p>
                            <p><span class="fircl">手机号码：</span><span class="lascl"><?php echo $order['mobile']; ?></span></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="spff spff-two">
                    <h2>配送信息</h2>
                    <div class="psbaowq">
                        <p><span class="fircl">配送方式：</span><span class="lascl"><?php echo $order['delivery_method']; ?></span></p>
                        <?php if($order['shop_id'] == 0): ?>
                        <p><span class="fircl">快递公司：</span>
                            <?php if(is_array($order['delivery_doc']) || $order['delivery_doc'] instanceof \think\Collection || $order['delivery_doc'] instanceof \think\Paginator): if( count($order['delivery_doc'])==0 ) : echo "" ;else: foreach($order['delivery_doc'] as $key=>$v): ?>
                                <span class="lascl"><?php echo $v['shipping_name']; ?></span>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </p>
                        <?php endif; ?>
                        <p><span class="fircl">运费：</span><span class="lascl"><em>￥</em><?php echo (isset($order['shipping_price']) && ($order['shipping_price'] !== '')?$order['shipping_price']:0); ?></span></p>
                        <?php if($order['prom_type'] == 4 AND $order['paid_money'] > 0): ?>
                            <p><span class="fircl">预售发货时间描述：</span><span class="lascl"><?php echo $order['pre_sell']['delivery_time_desc']; ?></span></p>
                        <?php endif; ?>
                        <p><span class="fircl">买家留言：</span><span class="lascl"><?php echo (isset($order['user_note']) && ($order['user_note'] !== '')?$order['user_note']:''); ?></span></p>
                    </div>
                </div>
                <div class="spff spff-two">
                    <h2>付款信息</h2>
                    <div class="psbaowq">
                        <p><span class="fircl">付款方式：</span><span class="lascl">
                            <?php if($order['pay_status'] == 1 and empty($order['pay_name'])): ?>
                                在线支付
                                <?php else: ?>
                                <?php echo $order['pay_name']; ?>
                            <?php endif; ?>
                        </span></p>
                        <p><span class="fircl">付款时间：</span><span class="lascl"><?php if($order['pay_status'] > 0): ?><?php echo date('Y-m-d H:i:s',!is_numeric($order['pay_time'])? strtotime($order['pay_time']) : $order['pay_time']); else: ?>未支付<?php endif; ?></span></p>
                        <?php if($order['prom_type'] != 4): ?>
                            <p><span class="fircl">商品总额：</span><span class="lascl"><em>￥</em><?php echo $order['goods_price']; ?></span></p>
                            <p><span class="fircl">运费金额：</span><span class="lascl"><em>￥</em><?php echo $order['shipping_price']; ?></span></p>
                            <p><span class="fircl">优惠券：</span><span class="lascl"><em>￥</em><?php echo $order['coupon_price']; ?></span></p>
                            <p><span class="fircl">积分抵扣：</span><span class="lascl"><em>￥</em><?php echo $order['integral_money']; ?></span></p>
                            <p><span class="fircl">订单优惠：</span><span class="lascl"><em>￥</em><?php echo $order['order_prom_amount']; ?></span></p>
                        <?php endif; ?>
                        <p><span class="fircl">余额支付：</span><span class="lascl"><em>￥</em><?php echo $order['user_money']; ?></span></p>
                        <!-- 预售商品 start -->
                        <?php if($order['prom_type'] == 4 AND $order['paid_money'] > 0): if($order['pay_status'] == 1): ?>
                                <p><span class="fircl">已付尾款：</span><span class="lascl"><em>￥</em><?php echo $order['order_amount']; ?></span></p>
                            <?php endif; ?>
                            <tr>
                                <p><span class="fircl">已付订金：</span><span class="lascl"><em>￥</em><?php echo $order['paid_money']; ?></span></p>
                            </tr>
                            <tr>
                                <p><span class="fircl">尾款支付时间：</span><span class="lascl"><?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_start_time'])? strtotime($order['pre_sell']['pay_start_time']) : $order['pre_sell']['pay_start_time']); ?>至<?php echo date('Y-m-d H:i:s',!is_numeric($order['pre_sell']['pay_end_time'])? strtotime($order['pre_sell']['pay_end_time']) : $order['pre_sell']['pay_end_time']); ?></span></p>
                            </tr>
                        <?php endif; ?>
                        <!-- 预售商品 end -->
                    </div>
                </div>
                <div class="spff mar0 spff-two" style="display: none" txt="原代码">
                    <h2>发票信息</h2>
                    <div class="psbaowq">
                        <p><span class="fircl">发票类型：</span><span class="lascl"><?php echo (isset($order['invoice']['invoice_type']) && ($order['invoice']['invoice_type'] !== '')?$order['invoice']['invoice_type']:'不开发票'); ?></span></p>
                        <p><span class="fircl">发票抬头：</span><span class="lascl"><?php echo !empty($order['invoice']['invoice_type']) ? $order['invoice_title'] : ''; ?></span></p>
                        <p><span class="fircl">纳税人识别号：</span><span class="lascl"><?php echo $order['taxpayer']; ?></span></p>
                    </div>
                </div>
                <div class="spff mar0 spff-two">
                    <h2>发票信息</h2>
                    <div class="psbaowq">
                        <p><span class="fircl">发票类型：</span>
                            <span class="lascl">
                                   <?php echo $order['invoice_desc']; ?>
                            
                            </span>
                        </p>
                        <p><span class="fircl">发票抬头：</span><span class="lascl"><?php if(($order['invoice_desc'] != '不开发票')): ?>
                            <?php echo !empty($order['invoice_title']) ? $order['invoice_title'] : ''; ?>
                        <?php endif; ?></span></p>
                        <p><span class="fircl">纳税人识别号：</span><span class="lascl"><?php echo (isset($order['taxpayer']) && ($order['taxpayer'] !== '')?$order['taxpayer']:'无'); ?></span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="beenovercom">
            <div class="shoptist">
                <span><?php echo $tpshop_config['shop_info_store_name']; ?><a href="tencent://message/?uin=<?php echo $tpshop_config['shop_info_qq']; ?>&Site=<?php echo config('shop_info.copyright'); ?>商城&Menu=yes" target="_blank"><i class="y-comp9"></i></a></span>
            </div>
            <div class="orderbook-list">
                <div class="book-tit">
                    <ul>
                        <li class="sx1">商品</li>
                        <li class="sx2">商品编号</li>
                        <li class="sx3">购买单价</li>
                        <li class="sx4">赠送积分</li>
                        <li class="sx5">商品数量</li>
                        <li class="sx6">操作</li>
                    </ul>
                </div>
            </div>
            <div class="order-alone-li">
                <?php if(is_array($order['order_goods']) || $order['order_goods'] instanceof \think\Collection || $order['order_goods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $order['order_goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?>
                    <table width="100%" border="" cellspacing="" cellpadding="">
                        <tr class="conten_or">
                            <td class="sx1">
                                <div class="shop-if-dif">
                                    <div class="shop-difimg">
                                        <a href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$goods['goods_id'])); ?>"><img src="<?php echo goods_thum_images($goods['goods_id'],78,78); ?>"></a>
                                    </div>
                                    <div class="cebigeze">
                                        <div class="shop_name"><a href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$goods['goods_id'])); ?>"><?php echo $goods['goods_name']; ?></a></div>
                                        <p class="mayxl"><?php echo $goods['spec_key_name']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="sx2"><?php echo $goods['goods_sn']; ?></td>
                            <td class="sx3"><span>￥</span><span><?php echo $goods['member_goods_price']; ?></span></td>
                            <td class="sx4">
                                <span><?php echo $goods['give_integral']; ?></span>
                            </td>
                            <td class="sx5">
                                <span><?php echo $goods['goods_num']; ?></span>
                            </td>
                            <td class="sx6">
                                <div class="twrbac">
                                    <?php if(($order['comment_btn'] == 1) and ($goods['is_comment'] == 0) and ($goods['is_send'] < 3)): ?>
                                        <a href="<?php echo url('Home/Order/comment_list',array('order_id'=>$order['order_id'],'rec_id'=>$goods['rec_id'])); ?>">评价</a>
                                    <?php endif; if(($order['return_btn'] == 1 and $goods['is_send'] <= 1)): ?>
                                        <span>|</span>
                                        <a href="<?php echo url('Home/Order/return_goods',['rec_id'=>$goods['rec_id']]); ?>">申请售后</a>
                                    <?php endif; ?>
                                    <p>
                                        <a class="songobuy" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$goods['goods_id'])); ?>">再次购买</a>
                                    </p>
                                    <?php if($goods['is_send'] == 0 and $order['shop_id'] == 0): ?><a>未发货</a><?php endif; if($goods['is_send'] == 1): ?><a>已发货</a><?php endif; if($goods['is_send'] > 1): ?><a>已申请售后</a><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="numzjsehe">
            <p><span class="sp_tutt">商品总额：</span><span class="smprice"><em>￥</em><?php echo $order['goods_price']; ?></span></p>
            <p><span class="sp_tutt">返&nbsp;&nbsp;&nbsp;&nbsp;现：</span><span class="smprice"><em>￥</em>0.00</span></p>
            <p><span class="sp_tutt">运&nbsp;&nbsp;&nbsp;&nbsp;费：</span><span class="smprice"><em>￥</em><?php echo $order['shipping_price']; ?></span></p>
            <p><span class="sp_tutt">价格调整：</span><span class="smprice"><em>￥</em>
                <?php if($order['discount'] > 0): ?>
                    -<?php echo abs($order['discount']); else: ?>
                    <?php echo abs($order['discount']); ?>
                <?php endif; ?>
                </span></p>
            <p><span class="sp_tutt">应付总额：</span><span class="smprice red"><em>￥</em><?php echo $order['order_amount']; ?></span></p>
        </div>
    </div>
</div>
<!--footer-s-->

<div class="tpshop-service">
	<ul class="w1224 clearfix">
<!--		<li>-->
<!--			<i class="ico ico-day7"><?php echo $tpshop_config['shopping_auto_service_date']; ?></i>-->
<!--			<p class="service"><?php echo $tpshop_config['shopping_auto_service_date']; ?>天无理由退货</p>-->
<!--		</li>-->
<!--		<li>-->
<!--			<i class="ico ico-day15"><?php echo $tpshop_config['shopping_auto_service_date']; ?></i>-->
<!--			<p class="service"><?php echo $tpshop_config['shopping_auto_service_date']; ?>天免费换货</p>-->
<!--		</li>-->
<!--		<li>-->
<!--			<i class="ico ico-quality"></i>-->
<!--			<p class="service">正品行货 品质保障</p>-->
<!--		</li>-->
<!--		<li>-->
<!--			<i class="ico ico-service"></i>-->
<!--			<p class="service">专业售后服务</p>-->
<!--		</li>-->
		<?php
                                   
                                $md5_key = md5("select * from `__PREFIX__slogan` ");
                                $result_name = $sql_result_v = cache("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\facade\Db::query("select * from `__PREFIX__slogan` "); 
                                    cache("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
			<li>
				<i class="ico" style="background: url(<?php echo $v['img']; ?>)"></i>
				<p class="service"><?php echo $v['remark']; ?></p>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<div class="footer">
	<div class="w1224 clearfix" style="padding-bottom: 10px;">
		<div class="left-help-list">
			<div class="help-list-wrap clearfix">
				<?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article_cat` where cat_id < 6  order by sort_order asc");
                                $result_name = $sql_result_v = cache("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\facade\Db::query("select * from `__PREFIX__article_cat` where cat_id < 6  order by sort_order asc"); 
                                    cache("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
					<dl>
						<dt><?php echo $v['cat_name']; ?></dt>
						<?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article` where cat_id = ".$v['cat_id']."  and is_open=1 limit 5");
                                $result_name = $sql_result_v2 = cache("sql_".$md5_key);
                                if(empty($sql_result_v2))
                                {                            
                                    $result_name = $sql_result_v2 = \think\facade\Db::query("select * from `__PREFIX__article` where cat_id = ".$v['cat_id']."  and is_open=1 limit 5"); 
                                    cache("sql_".$md5_key,$sql_result_v2,1);
                                }    
                              foreach($sql_result_v2 as $k2=>$v2): ?>
						<dd><a href="<?php echo url('Home/Article/detail',array('article_id'=>$v2['article_id'])); ?>"><?php echo $v2['title']; ?></a></dd>
						<?php endforeach; ?>
					</dl>
				<?php endforeach; ?>
			</div>
			<div class="friendship-links clearfix">
	            <span>友情链接 : </span>
                <div class="links-wrap-h clearfix">
                <?php
                                   
                                $md5_key = md5("select * from `__PREFIX__friend_link` where is_show=1");
                                $result_name = $sql_result_v = cache("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\facade\Db::query("select * from `__PREFIX__friend_link` where is_show=1"); 
                                    cache("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
                    <a href="<?php echo $v['link_url']; ?>" <?php if($v['target'] == 1): ?>target="_blank"<?php endif; ?> ><?php echo $v['link_name']; ?></a>
                <?php endforeach; ?>
                </div>
	        </div>	
		</div>
		<div class="right-contact-us">
			<h3 class="title">联系我们</h3>
			<span class="phone"><?php echo $tpshop_config['shop_info_phone']; ?></span>
			<p class="tips">周一至周日8:00-18:00<br />(仅收市话费)</p>
			<!--<div class="qr-code-list clearfix">-->
				<!--<a class="qr-code" href="javascript:;"><img class="w-100" src="/pc/rainbow/static/images/qrcode.png" alt="" /></a>-->
				<!--<a class="qr-code qr-code-tpshop" href="javascript:;"><img class="w-100" src="/pc/rainbow/static/images/qrcode.png" alt="" /></a>-->
			<!--</div>-->
		</div>
	</div>
    <div class="mod_copyright p">
        <div class="grid-top">
            <?php
                                   
                                $md5_key = md5("SELECT * FROM `__PREFIX__navigation` where is_show = 1 AND position = 'bottom' ORDER BY `sort` DESC");
                                $result_name = $sql_result_vv = cache("sql_".$md5_key);
                                if(empty($sql_result_vv))
                                {                            
                                    $result_name = $sql_result_vv = \think\facade\Db::query("SELECT * FROM `__PREFIX__navigation` where is_show = 1 AND position = 'bottom' ORDER BY `sort` DESC"); 
                                    cache("sql_".$md5_key,$sql_result_vv,1);
                                }    
                              foreach($sql_result_vv as $kk=>$vv): ?>
                <a href="<?php echo $vv['url']; ?>" <?php if($vv['is_new'] == 1): ?> target="_blank" <?php endif; ?> ><?php echo $vv['name']; ?></a><span>|</span>
            <?php endforeach; ?>
            <!--<a href="javascript:void (0);">关于我们</a><span>|</span>-->
            <!--<a href="javascript:void (0);">联系我们</a><span>|</span>-->
            <!--<?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article` where cat_id = 5 and is_open=1");
                                $result_name = $sql_result_v = cache("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\facade\Db::query("select * from `__PREFIX__article` where cat_id = 5 and is_open=1"); 
                                    cache("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>-->
                <!--<a href="<?php echo url('Home/Article/detail',array('article_id'=>$v['article_id'])); ?>"><?php echo $v['title']; ?></a>-->
                <!--<span>|</span>-->
            <!--<?php endforeach; ?>-->
        </div>
        <p>Copyright © 2016-2025 <?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:config('shop_info.copyright')); ?> 版权所有 保留一切权利 备案号:<a href="http://www.beian.miit.gov.cn" ><?php echo $tpshop_config['shop_info_record_no']; ?></a></p>
        <p class="mod_copyright_auth">
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_1" href="" target="_blank">经营性网站备案中心</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_2" href="" target="_blank">可信网站信用评估</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_3" href="" target="_blank">网络警察提醒你</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_4" href="" target="_blank">诚信网站</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_5" href="" target="_blank">中国互联网举报中心</a>
        </p>
    </div>
</div>
<style>
    .mod_copyright {
        border-top: 1px solid #EEEEEE;
    }
    .grid-top {
        margin-top: 20px;
        text-align: center;
    }
    .grid-top span {
        margin: 0 10px;
        color: #ccc;
    }
    .mod_copyright > p {
        margin-top: 10px;
        color: #666;
        text-align: center;
    }
    .mod_copyright_auth_ico {
        overflow: hidden;
        display: inline-block;
        margin: 0 3px;
        width: 103px;
        height: 32px;
        background-image: url(/pc/rainbow/static/images/ico_footer.png);
        line-height: 1000px;
    }
    .mod_copyright_auth_ico_1 {
        background-position: 0 -151px;
    }
    .mod_copyright_auth_ico_2 {
        background-position: -104px -151px;
    }
    .mod_copyright_auth_ico_3 {
        background-position: 0 -184px;
    }
    .mod_copyright_auth_ico_4 {
        background-position: -104px -184px;
    }
    .mod_copyright_auth_ico_5 {
        background-position: 0 -217px;
    }
</style>
<script>
    // 延时加载二维码图片
    jQuery(function($) {
        $('img[img-url]').each(function() {
            var _this = $(this),
                    url = _this.attr('img-url');
            _this.attr('src',url);
        });
    });
</script>

<!--footer-e-->
<script>
    //未支付取消订单
    function cancel_order(id){
        layer.confirm("确定取消订单?",{
            btn:['确定','取消']
        },function(){
            $.ajax({
                url:"<?php echo url('Home/Order/cancel_order'); ?>",
                type:'POST',
                dataType:'JSON',
                data:{id:id},
                success:function(data){
                    if(data.status == 1){
                        layer.alert(data.msg, {icon: 1},function(){
                            location.href ='/index.php?m=home&c=Virtual&a=virtual_order&order_id='+id;
                        })
                    }else{
                        layer.alert(data.msg, {icon: 2});
                    }
                },
                error : function() {
                    layer.alert('网络失败，请刷新页面后重试', {icon: 2});
                }
            });
        }, function(tmp){
            layer.close(tmp);
        })
    }
    //已支付取消订单
    function refund_order(obj){
        layer.open({
            type: 2,
            title: '<b>订单取消申请</b>',
            skin: 'layui-layer-rim',
            shadeClose: true,
            shade: 0.5,
            area: ['600px', '500px'],
            content: $(obj).attr('data-url'),
        });
    }
    //确定收货
    function order_confirm(order_id){
        layer.confirm("你确定收到货了吗?",{
            btn:['确定','取消']
        },function(){
            $.ajax({
                type : 'post',
                url : '/index.php?m=Home&c=Order&a=order_confirm',
                data:{order_id:order_id},
                dataType : 'json',
                success : function(data){
                    if(data.status == 1){
                        layer.alert(data.msg, {icon: 1},function () {
                            window.location.href = data.url;
                        });
                    }else{
                        layer.alert(data.msg,{icon:2});
                        verify();
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    showErrorMsg('网络失败，请刷新页面后重试');
                }
            })
        }, function(index){
            layer.close(index);
        });
    }
</script>
</body>
</html>