<?php /*a:3:{s:38:"../template/pc/rainbow/cart/index.html";i:1593659405;s:46:"../template/pc/rainbow/public/sign-header.html";i:1593659405;s:41:"../template/pc/rainbow/public/footer.html";i:1593659405;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>我的购物车列表</title>
	<link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/tpshop.css" />
	<script src="/pc/rainbow/static/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/public/js/global.js" type="text/javascript" charset="utf-8"></script>
	<script src="/public/js/locationJson.js"></script>
	<script src="/pc/rainbow/static/js/location.js" type="text/javascript" charset="utf-8"></script>
	<script src="/public/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
	<script src="/public/js/pc_common.js"></script>
	<link rel="stylesheet" href="/pc/rainbow/static/css/location.css" type="text/css"><!-- 收货地址，物流运费 -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"/>
</head>
<style>
	.coupon_whether{ overflow:auto; height: 500px; width:400px; }
</style>
<body>
<!--顶部广告-s-->
<?php $pid =1;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593666000 and end_time > 1593666000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
             
if(is_array($ad_position) && !in_array($pid,array_keys($ad_position)) && $pid)
{
  \think\facade\Db::name("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));

  \think\facade\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v["position"] = $ad_position[$v["pid"]]; 
    if(input("get.edit_ad") && $v["not_adv"] == 0 )
    {
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["ad_link"] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=".$v["ad_id"];         //
        $v["title"] = $ad_position[$v["pid"]]["position_name"]."===".$v["ad_name"];
        $v["target"] = 0;
    }
    ?>
	<div class="topic-banner" style="background: #f37c1e;">
		<div class="w1224">
			<a href="<?php echo $v['ad_link']; ?>">
				<img src="<?php echo $v['ad_code']; ?>"/>
			</a>
			<i onclick="$('.topic-banner').hide();"></i>
		</div>
	</div>
<?php endforeach; ?>
<!--顶部广告-e-->
<!--header-s-->
<div class="tpshop-tm-hander p" style="border-bottom: 0;">
	<link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/base.css" />
<div class="top-hander" moduleid="1539923">
    <div class="w1224 clearfix">
      <div class="fl">
          <div class="ls-dlzc fl nologin">
              <a href="<?php echo url('Home/user/login'); ?>">Hi,请登录</a>
              <a class="red" href="<?php echo url('Home/user/reg'); ?>">免费注册</a>
          </div>
          <div class="ls-dlzc fl islogin">
              <a class="red userinfo" href="<?php echo url('Home/user/index'); ?>"></a>
              <a href="<?php echo url('Home/user/logout'); ?>">退出</a>
          </div>
          <script>
              $(function (){
                      var uname = getCookie('uname');
                      if (uname == '') {
                          $('.islogin').remove();
                          $('.nologin').show();
                      } else {
                          $('.nologin').remove();
                          $('.islogin').show();
                          $('.userinfo').html(decodeURIComponent(uname).substring(0,11));
                      }
              })

          </script>
      </div>
      <ul class="top-ri-header fr clearfix">
          <li><a target="_blank" href="<?php echo url('Home/Order/order_list'); ?>">我的订单</a></li>
          <li class="spacer"></li>
          <li><a target="_blank" href="<?php echo url('Home/User/visit_log'); ?>">我的浏览</a></li>
          <li class="spacer"></li>
          <li><a target="_blank" href="<?php echo url('Home/User/goods_collect'); ?>">我的收藏</a></li>
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
                <li>
                    <a href="<?php echo url('Home/Goods/integralMall'); ?>">兑换中心</a>
                </li>
            </ul>
          </li>
      </ul>
    </div>
</div>


	<div class="nav-middan-z p tphsop2_0">
		<div class="header w1224">
			<div class="ecsc-logo fon_gwcshcar">
				<a href="/" class="logo">
					<img src="<?php echo (isset($tpshop_config['shop_info_store_logo']) && ($tpshop_config['shop_info_store_logo'] !== '')?$tpshop_config['shop_info_store_logo']:'/public/static/images/logo/pc_home_logo_default.png'); ?>" style="max-width: 240px;max-height: 80px;">
				</a>
			</div>
			<div class="ecsc-search mycarlist_search">
				<form id="sourch_form" name="sourch_form" method="post" action="<?php echo url('Home/Goods/search'); ?>" class="ecsc-search-form">
					<input autocomplete="off" name="q" id="q" type="text" value="<?php echo app('request')->param('q'); ?>" placeholder="搜索关键字" class="ecsc-search-input">
					<button type="submit" class="ecsc-search-button" onclick="if($.trim($('#q').val()) != '') $('#sourch_form').submit();">搜索</button>
					<div class="candidate p">
						<ul id="search_list"></ul>
					</div>
					<script type="text/javascript">
                        ;(function($){
                            $.fn.extend({
                                donetyping: function(callback,timeout){
                                    timeout = timeout || 1e3;
                                    var timeoutReference,
                                        doneTyping = function(el){
                                            if (!timeoutReference) return;
                                            timeoutReference = null;
                                            callback.call(el);
                                        };
                                    return this.each(function(i,el){
                                        var $el = $(el);
                                        $el.is(':input') && $el.on('keyup keypress',function(e){
                                            if (e.type=='keyup' && e.keyCode!=8) return;
                                            if (timeoutReference) clearTimeout(timeoutReference);
                                            timeoutReference = setTimeout(function(){
                                                doneTyping(el);
                                            }, timeout);
                                        }).on('blur',function(){
                                            doneTyping(el);
                                        });
                                    });
                                }
                            });
                        })(jQuery);

                        $('.ecsc-search-input').donetyping(function(){
                            search_key();
                        },500).focus(function(){
                            var search_key = $.trim($('#q').val());
                            if(search_key != ''){
                                $('.candidate').show();
                            }
                        });
                        $('.candidate').mouseleave(function(){
                            $(this).hide();
                        });

                        function searchWord(words){
                            $('#q').val(words);
                            $('#sourch_form').submit();
                        }
                        function search_key(){
                            var search_key = $.trim($('#q').val());
                            if(search_key != ''){
                                $.ajax({
                                    type:'post',
                                    dataType:'json',
                                    data: {key: search_key},
                                    url:"<?php echo url('Home/Api/searchKey'); ?>",
                                    success:function(data){
                                        if(data.status == 1){
                                            var html = '';
                                            $.each(data.result, function (n, value) {
                                                html += '<li onclick="searchWord(\''+value.keywords+'\');"><div class="search-item">'+value.keywords+'</div><div class="search-count">约'+value.goods_num+'个商品</div></li>';
                                            });
                                            html += '<li class="close"><div class="search-count">关闭</div></li>';
                                            $('#search_list').empty().append(html);
                                            $('.candidate').show();
                                        }else{
                                            $('#search_list').empty();
                                        }
                                    }
                                });
                            }
                        }
					</script>
				</form>
				<div class="keyword">
					<?php if(is_array($tpshop_config['hot_keywords']) || $tpshop_config['hot_keywords'] instanceof \think\Collection || $tpshop_config['hot_keywords'] instanceof \think\Paginator): if( count($tpshop_config['hot_keywords'])==0 ) : echo "" ;else: foreach($tpshop_config['hot_keywords'] as $k=>$wd): ?>
						<a class="key-item" href="<?php echo url('Home/Goods/search',array('q'=>$wd)); ?>" target="_blank"><?php echo $wd; ?></a>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="w1224">
		<?php if(app('request')->cookie('uname') == ''): ?>
			<div class="cont_aloinfon">
				<i class="tit_sad"></i>
				<span class="nitp">您还没有登录！登录后购物车的商品将保存在您的账号中</span>
				<!--<a class="loging_ex" href="<?php echo url('Home/User/login'); ?>">立即登录</a>-->
			</div>
		<?php endif; ?>
	</div>
</div>
<!--header-e-->
<div class="shopcar_empty" <?php if(!(empty($cartList) || (($cartList instanceof \think\Collection || $cartList instanceof \think\Paginator ) && $cartList->isEmpty()))): ?>style="display: none"<?php endif; ?>>
<div class="w1224">
	<div class="cart-empty">
		<div class="message">
			<ul>
				<li class="txt nologin">
					购物车内暂时没有商品，登录后将显示您之前加入的商品
				</li>
				<li class="txt islogin">
					购物车空空的哦~，去看看心仪的商品吧~
				</li>
				<li class="mt10" style="padding-left: 100px;">
					<a href="<?php echo url('Home/User/login'); ?>" class="btn-1 login-btn nologin">登录</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="/" class="btn-1 login-btn islogin">去购物</a>
				</li>
			</ul>
		</div>
	</div>
</div>
</div>
<!-- 购物车列表 -->
<?php if(!(empty($cartList) || (($cartList instanceof \think\Collection || $cartList instanceof \think\Paginator ) && $cartList->isEmpty()))): ?>
	<div id="tpshop-cart">
		<div class="li3_address w1224 p">
			<ul>
				<li class="current"><a>全部商品数<em>（<?php echo $userCartGoodsTypeNum; ?>）</em></a></li>
			</ul>
		</div>
		<div class="shoplist_deta p">
			<div class="w1224">
				<div class="cart-thead p">
					<div class="column cart-checkbox">
						<input class="check-box" name="checkboxes" type="checkbox" style="display: none;">
						<i class="checkall checkFull"></i>全选
					</div>
					<div class="column t-goods">商品</div>
					<div class="column t-props"></div>
					<div class="column t-price">单价</div>
					<div class="column t-quantity">数量</div>
					<div class="column t-sum">小计</div>
					<div class="column t-action">操作</div>
				</div>
			</div>
		</div>
		<!--购物车商品列表-s-->
		<div class="w1224">
			<!--<div class="meal-conts-name p">-->
			<!--<div class="fl">-->
			<!--<input class="check-box" name="checkItem" value="<?php echo $cart['id']; ?>" type="checkbox" <?php if($cart['selected'] == 1): ?>checked="checked"<?php endif; ?> style="display: none;">-->
			<!--<i data-goods-id="<?php echo $cart['goods_id']; ?>" data-goods-cat-id3="<?php echo $cart['goods']['cat_id']; ?>" data-cart-id="<?php echo $cart['id']; ?>" class="checkall checkItem <?php if($cart['selected'] == 1): ?>checkall-true<?php endif; ?>"></i>-->
			<!--</div>-->
			<!---->
			<!--<em class="meal-name-dev fl">TPshop</em>-->
			<!--<span class="meal-name-icon fl">-->
			<!---->
			<!--</span>-->
			<!--</div>-->
			<?php if(is_array($cartList) || $cartList instanceof \think\Collection || $cartList instanceof \think\Paginator): $i = 0; $__LIST__ = $cartList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cart): $mod = ($i % 2 );++$i;if($cart['combination_cart']): ?>
					<!--搭配套餐标题 s-->
					<div class="meal-conts-name p edge_<?php echo $cart['id']; ?>" style="border-bottom: 1px solid #d5d5d5;">
						<div class="fl">
							<input class="check-box" name="checkItem" value="<?php echo $cart['id']; ?>" type="checkbox" <?php if($cart['selected'] == 1): ?>checked="checked"<?php endif; ?> style="display: none;">
							<i data-goods-id="<?php echo $cart['goods_id']; ?>" data-goods-cat-id3="<?php echo $cart['goods']['cat_id']; ?>" data-cart-id="<?php echo $cart['id']; ?>" data-type="<?php echo $cart['prom_type']; ?>" class="checkall checkItem <?php if($cart['selected'] == 1): ?>checkall-true<?php endif; ?>"></i>
						</div>
						<!--<div class="meal-conts-tltle">-->
						<h5 class="fl" style="margin-left:16px "><?php echo $cart['combination']['title']; ?></h5>
						<!--</div>-->
						<!--<em class="meal-name-dev fl">TPshop</em>-->
						<!--<span class="meal-name-icon fl">-->
						<!---->
						<!--</span>-->
					</div>
				<?php endif; ?>
				<div class="shoplist_detail_a edge_<?php echo $cart['id']; ?>" style="border-top: <?php if($key==0): ?> 1px solid #d5d5d5<?php else: ?>none<?php endif; ?> ">

					<?php if($cart['combination_cart']): ?>
						<!--搭配套餐 s-->
						<div class="w1224">
							<!--输出主商品-模块-->
							<div  id="edge_<?php echo $cart['id']; ?>" data-type="<?php echo $cart['prom_type']; ?>" data-cart-id="<?php echo $cart['id']; ?>" data-goods-id="<?php echo $cart['goods_id']; ?>" data-goods-item="<?php echo $cart['item_id']; ?>"class="meal-conts-items">

								<div class="item-single p">
									<div class="breadth_phase">
										<div class="column ">
											<img class="msp_picture msp_picture_two" src="<?php echo goods_thum_images($cart['goods_id'],82,82,$cart['item_id']); ?>"/>
										</div>
										<div class="column t-goods">
											<p class="msp_spname">
												<a href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$cart['goods_id'])); ?>"><?php echo $cart['goods_name']; ?></a>
												<!--团购--><?php if($cart['prom_type'] == 2): ?><img  width="80" height="60" src="/public/images/groupby2.jpg" style="vertical-align:middle"><?php endif; ?>
												<!--抢购--><?php if($cart['prom_type'] == 1): ?><img  width="40" height="40" src="/public/images/qianggou2.jpg" style="vertical-align:middle"><?php endif; ?>
											</p>
											<div class="msp_return">
												<!--<?php if($store['qitian']): ?>
                                                    <i class="return7"></i><span class="f_blue">支持七天无理由退货</span>
                                                    <?php else: ?>
                                                    <i class="return7 return7-dark"></i><span class="f_dark">不支持七天无理由退货</span>
                                                <?php endif; ?>-->
											</div>
										</div>
									</div>
									<div class="column t-props he87 stang">
										<?php if(is_array($cart['spec_key_name_arr']) || $cart['spec_key_name_arr'] instanceof \think\Collection || $cart['spec_key_name_arr'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cart['spec_key_name_arr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec_key_name): $mod = ($i % 2 );++$i;?>
											<p><?php echo $spec_key_name; ?></p>
										<?php endforeach; endif; else: echo "" ;endif; ?>
									</div>
									<div class="column t-price">
										<span id="cart_<?php echo $cart['id']; ?>_goods_price">￥<?php echo $cart['goods_price']; ?></span>
										<?php if($cart['prom_type'] > 0): ?>
											<p class="red">活动价：<span>￥<?php echo $cart['member_goods_price']; ?></span></p>
										<?php endif; ?>
										<!--<?php if(!(empty($cart['prom_goods']) || (($cart['prom_goods'] instanceof \think\Collection || $cart['prom_goods'] instanceof \think\Paginator ) && $cart['prom_goods']->isEmpty()))): ?>-->
										<!--<div class="promptions_in">-->
										<!--<span class="cx"><em>促销详情</em><i></i></span>-->
										<!--<div class="promotion-cont">-->
										<!--<ul>-->
										<!--<li><?php echo $cart['prom_goods']['prom_detail']; ?></li>-->
										<!--</ul>-->
										<!--</div>-->
										<!--</div>-->
										<!--<?php endif; ?>-->
									</div>


									<div class="column t-quantity mtp quantity-form">
										<a href="javascript:void(0);" class="decrement" id="decrement_<?php echo $cart['id']; ?>">-</a>
										<input name="changeQuantity_<?php echo $cart['id']; ?>" type="text" id="changeQuantity_<?php echo $cart['id']; ?>" value="<?php echo $cart['goods_num']; ?>">
										<a href="javascript:void(0);" class="increment" id="increment_<?php echo $cart['id']; ?>">+</a>
										<!--无货时隐藏数量选择，显示无货-->
										<!--<span>无货</span>-->
									</div>

									<div class="column t-sum sumpri">
										<span id="cart_<?php echo $cart['id']; ?>_total_price">￥<?php echo $cart['goods_price']*$cart['goods_num']; ?></span>
										<?php if($cart['prom_type'] > 0): ?>
											<p class="red"><span id="cart_<?php echo $cart['id']; ?>_market_price">
	                                                        ￥<?php echo round($cart['member_goods_price']*$cart['goods_num'],2); ?>
	                                                    </span></p>
										<?php endif; ?>
									</div>
									<div class="column t-action">
										<p>
											<a href="javascript:void(0);" class="deleteGoods deleteItem" data-goodsid="<?php echo $cart['goods_id']; ?>" data-cart-id="<?php echo $cart['id']; ?>">
												删除</a>
										</p>
										<p><a class="moveCollect collectItem" data-id="<?php echo $cart['goods_id']; ?>">移到我的收藏</a></p>
									</div>
								</div>
							</div>
							<!--输出主商品-模块--结束-->
							<!--搭配套餐 遍历副商品-->
							<?php if(is_array($cart['combination_cart']) || $cart['combination_cart'] instanceof \think\Collection || $cart['combination_cart'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cart['combination_cart'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cart_v): $mod = ($i % 2 );++$i;?>
								<!--<div class="edge_tw" id="edge_<?php echo $cart['id']; ?>">-->
								<div  id="edge_<?php echo $cart_v['id']; ?>" data-type="<?php echo $cart_v['prom_type']; ?>" data-goods-id="<?php echo $cart_v['goods_id']; ?>" class="meal-conts-items">
									<i data-goods-id="<?php echo $cart_v['goods_id']; ?>" data-goods-cat-id3="<?php echo $cart_v['goods']['cat_id']; ?>" data-cart-id="<?php echo $cart_v['id']; ?>" class="checkall checkItem " style="display: none;"></i>
									<div class="item-single p">
										<div class="breadth_phase">
											<div class="column ">
												<img class="msp_picture msp_picture_two" src="<?php echo goods_thum_images($cart_v['goods_id'],82,82,$cart_v['item_id']); ?>"/>
											</div>
											<div class="column t-goods">
												<p class="msp_spname">
													<a href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$cart_v['goods_id'])); ?>"><?php echo $cart_v['goods_name']; ?></a>
													<!--团购--><?php if($cart_v['prom_type'] == 2): ?><img  width="80" height="60" src="/public/images/groupby2.jpg" style="vertical-align:middle"><?php endif; ?>
													<!--抢购--><?php if($cart_v['prom_type'] == 1): ?><img  width="40" height="40" src="/public/images/qianggou2.jpg" style="vertical-align:middle"><?php endif; ?>
												</p>
												<div class="msp_return">
													<!--<?php if($store['qitian']): ?>
                                                        <i class="return7"></i><span class="f_blue">支持七天无理由退货</span>
                                                        <?php else: ?>
                                                        <i class="return7 return7-dark"></i><span class="f_dark">不支持七天无理由退货</span>
                                                    <?php endif; ?>-->
												</div>
											</div>
										</div>
										<div class="column t-props he87 stang">
											<?php if(is_array($cart_v['spec_key_name_arr']) || $cart_v['spec_key_name_arr'] instanceof \think\Collection || $cart_v['spec_key_name_arr'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cart_v['spec_key_name_arr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec_key_name): $mod = ($i % 2 );++$i;?>
												<p><?php echo $spec_key_name; ?></p>
											<?php endforeach; endif; else: echo "" ;endif; ?>
										</div>
										<div class="column t-price">
											<span id="cart_<?php echo $cart_v['id']; ?>_goods_price">￥<?php echo $cart_v['goods_price']; ?></span>
											<?php if($cart_v['prom_type'] > 0): ?>
												<p class="red">活动价：<span>￥<?php echo $cart_v['member_goods_price']; ?></span></p>
											<?php endif; ?>
											<!--<?php if(!(empty($cart_v['prom_goods']) || (($cart_v['prom_goods'] instanceof \think\Collection || $cart_v['prom_goods'] instanceof \think\Paginator ) && $cart_v['prom_goods']->isEmpty()))): ?>-->
											<!--<div class="promptions_in">-->
											<!--<span class="cx"><em>促销详情</em><i></i></span>-->
											<!--<div class="promotion-cont">-->
											<!--<ul>-->
											<!--<li><?php echo $cart_v['prom_goods']['prom_detail']; ?></li>-->
											<!--</ul>-->
											<!--</div>-->
											<!--</div>-->
											<!--<?php endif; ?>-->
										</div>


										<div class="column t-quantity mtp quantity-form" style="padding-left: 40px;">
											<span  id="changeQuantity_<?php echo $cart_v['id']; ?>" style="color: #666"><?php echo $cart_v['goods_num']; ?></span>
											<!--<input name="changeQuantity_<?php echo $cart_v['id']; ?>" type="text" id="changeQuantity_<?php echo $cart_v['id']; ?>" value="<?php echo $cart_v['goods_num']; ?>" disabled>-->
											<!--无货时隐藏数量选择，显示无货-->
											<!--<span>无货</span>-->
										</div>

										<div class="column t-sum sumpri">
											<span id="cart_<?php echo $cart_v['id']; ?>_total_price">￥<?php echo $cart_v['goods_price']*$cart_v['goods_num']; ?></span>
											<?php if($cart_v['prom_type'] > 0): ?>
												<p class="red"><span id="cart_<?php echo $cart_v['id']; ?>_market_price">
	                                                        ￥<?php echo round($cart_v['member_goods_price']*$cart_v['goods_num'],2); ?>
	                                                    </span></p>
											<?php endif; ?>
										</div>
										<div class="column t-action">
											<p>
												<a href="javascript:void(0);" class="deleteGoods deleteItem" data-goodsid="<?php echo $cart_v['goods_id']; ?>" data-cart-id="<?php echo $cart_v['id']; ?>" data-cart-idd="<?php if($key==0): ?><?php echo $cart['id']; else: ?><?php echo $cart_v['id']; ?><?php endif; ?>">
													删除</a>
											</p>
											<p><a class="moveCollect collectItem " data-id="<?php echo $cart_v['goods_id']; ?>">移到我的收藏</a></p>
										</div>
                                    </div>
                                  </div>
								<!--</div>-->
							<?php endforeach; endif; else: echo "" ;endif; ?>
							<!--搭配套餐 遍历副商品-结束-->
						</div>

						<?php else: ?>
						<!--普通商品-->
						<div  id="edge_<?php echo $cart['id']; ?>" class="meal-conts-items">
							<?php if(!(empty($cart['prom_goods']) || (($cart['prom_goods'] instanceof \think\Collection || $cart['prom_goods'] instanceof \think\Paginator ) && $cart['prom_goods']->isEmpty()))): ?>
								<div class="brim_top">
									<!--满减和换购两种-->
									<span class="act_mjhg">促销</span>
									<a class="condi"><?php echo $cart['prom_goods']['title']; ?></a>
								</div>
							<?php endif; ?>
							<div class="item-single p">
								<div class="breadth_phase">
									<div class="column ">
										<input class="check-box" name="checkItem" value="<?php echo $cart['id']; ?>" type="checkbox" <?php if($cart['selected'] == 1): ?>checked="checked"<?php endif; ?> style="display: none;">
										<i data-goods-id="<?php echo $cart['goods_id']; ?>" data-goods-cat-id3="<?php echo $cart['goods']['cat_id']; ?>" data-cart-id="<?php echo $cart['id']; ?>" data-type="<?php echo $cart['prom_type']; ?>" class="checkall checkItem <?php if($cart['selected'] == 1): ?>checkall-true<?php endif; ?>"></i>
										<img class="msp_picture" src="<?php echo goods_thum_images($cart['goods_id'],82,82,$cart['item_id']); ?>"/>
									</div>
									<div class="column t-goods">
										<p class="msp_spname">
											<a href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$cart['goods_id'])); ?>"><?php echo $cart['goods_name']; ?></a>
											<!--团购--><?php if($cart['prom_type'] == 2): ?><img  width="80" height="60" src="/public/images/groupby2.jpg" style="vertical-align:middle"><?php endif; ?>
											<!--抢购--><?php if($cart['prom_type'] == 1): ?><img  width="40" height="40" src="/public/images/qianggou2.jpg" style="vertical-align:middle"><?php endif; ?>
										</p>
										<div class="msp_return">
											<!--<?php if($store['qitian']): ?>
                                                <i class="return7"></i><span class="f_blue">支持七天无理由退货</span>
                                                <?php else: ?>
                                                <i class="return7 return7-dark"></i><span class="f_dark">不支持七天无理由退货</span>
                                            <?php endif; ?>-->
										</div>
									</div>
								</div>
								<div class="column t-props he87 stang">
									<?php if(is_array($cart['spec_key_name_arr']) || $cart['spec_key_name_arr'] instanceof \think\Collection || $cart['spec_key_name_arr'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cart['spec_key_name_arr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec_key_name): $mod = ($i % 2 );++$i;?>
										<p><?php echo $spec_key_name; ?></p>
									<?php endforeach; endif; else: echo "" ;endif; ?>
								</div>
								<div class="column t-price">
									<span id="cart_<?php echo $cart['id']; ?>_goods_price">￥<?php echo $cart['goods_price']; ?></span>
									<?php if($cart['prom_type'] > 0): ?>
										<p class="red">活动价：<span>￥<?php echo $cart['member_goods_price']; ?></span></p>
									<?php endif; if(!(empty($cart['prom_goods']) || (($cart['prom_goods'] instanceof \think\Collection || $cart['prom_goods'] instanceof \think\Paginator ) && $cart['prom_goods']->isEmpty()))): ?>
										<div class="promptions_in">
											<span class="cx"><em>促销详情</em><i></i></span>
											<div class="promotion-cont">
												<ul>
													<li><?php echo $cart['prom_goods']['prom_detail']; ?></li>
												</ul>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<div class="column t-quantity mtp quantity-form">
									<a href="javascript:void(0);" class="decrement" id="decrement_<?php echo $cart['id']; ?>">-</a>
									<input name="changeQuantity_<?php echo $cart['id']; ?>" type="text" id="changeQuantity_<?php echo $cart['id']; ?>" value="<?php echo $cart['goods_num']; ?>">
									<a href="javascript:void(0);" class="increment" id="increment_<?php echo $cart['id']; ?>">+</a>
									<!--无货时隐藏数量选择，显示无货-->
									<!--<span>无货</span>-->
								</div>
								<div class="column t-sum sumpri">
									<span id="cart_<?php echo $cart['id']; ?>_total_price">￥<?php echo $cart['goods_price']*$cart['goods_num']; ?></span>
									<?php if($cart['prom_type'] > 0): ?>
										<p class="red"><span id="cart_<?php echo $cart['id']; ?>_market_price">
                                                        ￥<?php echo round($cart['member_goods_price']*$cart['goods_num'],2); ?>
                                                    </span></p>
									<?php endif; ?>
								</div>
								<div class="column t-action">
									<p>
										<a href="javascript:void(0);" class="deleteGoods deleteItem" data-goodsid="<?php echo $cart['goods_id']; ?>" data-cart-id="<?php echo $cart['id']; ?>">
											删除</a>
									</p>
									<p><a class="moveCollect collectItem" data-id="<?php echo $cart['goods_id']; ?>">移到我的收藏</a></p>
								</div>
							</div>
						</div>
						<!--普通商品 遍历-结束-->
					<?php endif; ?>

				</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>

		</div>
	</div>
	<!--购物车商品列表-e-->

	<!--全选按钮-s-->
	<div class="shoplist_deta floatflex">
		<div class="w1224">
			<div class="edge_tw_foot">
				<div class="item-single p">
					<div class="breadth_phase vermidd">
						<div class="column">
							<input class="check-box" name="checkboxes" type="checkbox" style="display: none;">
							<i class="checkall checkFull"></i>
							全选
							<a class="mal18 deleteGoods deleteAll" href="javascript:void(0);">删除选中的商品</a>
							<a class="mal18 moveCollect collectAll">移到我的收藏</a>
						</div>
					</div>
					<div class="row_foot_last">
						<div class="column t-quantity">
							<span class="chosewell chosew-add">已选择<em id="goods_num"></em>件商品</if></span>
						</div>
						<div class="column widallr">
							<div class="butpayin">
								<a class="paytotal" href="javascript:void(0)" data-url="<?php echo url('Home/Cart/cart2'); ?>">去结算</a>
							</div>
							<div class="totalprice">
								<span class="car_sumprice">总价：<em id="total_fee">￥0</em><i class="bulb"></i></span>
								<div class="price-tipsbox">
									<div class="ui-tips-main">不含运费及送装服务费</div>
									<span class="price-tipsbox-arrow"></span>
								</div>
								<span class="car_conta">已节省：<em id="goods_fee">-￥0</em></span>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--全选按钮-e-->
	<script type="text/javascript">
        //去结算旁边的小图标
        $(function(){
            $('.bulb').hover(function(){
                $('.price-tipsbox').show();
            },function(){
                $('.price-tipsbox').hide();
            })
        })
        //购物车商品高度超过屏幕时去结算浮动
        $(function(){
            var wi = $(window).height();
            var ff = $('.floatflex').offset().top - wi;
            if(wi > ff){
                $('.floatflex').removeClass('fdix');
            }else{
                $('.floatflex').addClass('fdix');
            }
            $(window).scroll(function(){
                var scr = $(document).scrollTop()
                if(scr > ff){
                    $('.floatflex').removeClass('fdix');
                }else{
                    $('.floatflex').addClass('fdix');
                }
            });
        })
        $(document).ready(function(){
            initDecrement();
            initCheckBox();
            getStoreCoupon();
        });
	</script>
	</div>
<?php endif; ?>
<!--底部猜你喜欢-->
<div class="shoplist_guess">
	<div class="w1224">
		<div class="main_shopcarlist">
			<div class="li3_address folahov p">
				<ul>
					<li class="current" data-id="guess-products"><a href="javascript:void(0);">猜你喜欢</a></li>
					<li data-id="collect-products"><a href="javascript:void(0);">我的收藏</a></li>
					<li data-id="history-products"><a href="javascript:void(0);">最近浏览</a></li>
				</ul>
			</div>

			<div class="totalswitch">
				<div class="switchable-panel" id="guess-products">
					<div class="goods-list-tab">
						<a class="s-item curr">&nbsp;</a>
						<a class="s-item">&nbsp;</a>
						<a class="s-item">&nbsp;</a>
					</div>
					<div class="s-panel-main">
						<?php
                                   
                                $md5_key = md5("select * from `__PREFIX__goods` where is_recommend = 1 AND is_virtual=0 AND is_on_sale = 1 order by sort DESC limit 12");
                                $goods_likes = $sql_result_item = cache("sql_".$md5_key);
                                if(empty($sql_result_item))
                                {                            
                                    $goods_likes = $sql_result_item = \think\facade\Db::query("select * from `__PREFIX__goods` where is_recommend = 1 AND is_virtual=0 AND is_on_sale = 1 order by sort DESC limit 12"); 
                                    cache("sql_".$md5_key,$sql_result_item,1);
                                }    
                              foreach($sql_result_item as $key=>$item): ?><?php endforeach; ?>
						<div class="goods-panel jsaddsucc p">
							<ul>
								<?php if(is_array($goods_likes) || $goods_likes instanceof \think\Collection || $goods_likes instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($goods_likes) ? array_slice($goods_likes,0,4, true) : $goods_likes->slice(0,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$like): $mod = ($i % 2 );++$i;?>
									<li>
										<div class="itemgoodbox">
											<div class="p-img" >
												<a target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$like['goods_id'])); ?>">
													<img src="<?php echo goods_thum_images($like['goods_id'],160,160); ?>">
												</a>
											</div>
											<div class="p-name">
												<a target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$like['goods_id'])); ?>"><?php echo $like['goods_name']; ?></a>
											</div>
											<div class="p-price">
												<strong><em>￥</em><i><?php echo $like['shop_price']; ?></i></strong>
											</div>
											<div class="p-btn-adc">
												<a onclick="javascript:AjaxAddCart(<?php echo $like['goods_id']; ?>,1);" class="btn-append"><b></b>加入购物车</a>
											</div>
										</div>
									</li>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
						<div class="goods-panel p">
							<ul>
								<?php if(is_array($goods_likes) || $goods_likes instanceof \think\Collection || $goods_likes instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($goods_likes) ? array_slice($goods_likes,4,4, true) : $goods_likes->slice(4,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$like): $mod = ($i % 2 );++$i;?>
									<li>
										<div class="itemgoodbox">
											<div class="p-img" >
												<a target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$like['goods_id'])); ?>">
													<img src="<?php echo goods_thum_images($like['goods_id'],160,160); ?>">
												</a>
											</div>
											<div class="p-name">
												<a target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$like['goods_id'])); ?>"><?php echo $like['goods_name']; ?></a>
											</div>
											<div class="p-price">
												<strong><em>￥</em><i><?php echo $like['shop_price']; ?></i></strong>
											</div>
											<div class="p-btn-adc">
												<a onclick="javascript:AjaxAddCart(<?php echo $like['goods_id']; ?>,1);" class="btn-append"><b></b>加入购物车</a>
											</div>
										</div>
									</li>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
						<div class="goods-panel p">
							<ul>
								<?php if(is_array($goods_likes) || $goods_likes instanceof \think\Collection || $goods_likes instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($goods_likes) ? array_slice($goods_likes,8,4, true) : $goods_likes->slice(8,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$like): $mod = ($i % 2 );++$i;?>
									<li>
										<div class="itemgoodbox">
											<div class="p-img" >
												<a target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$like['goods_id'])); ?>">
													<img src="<?php echo goods_thum_images($like['goods_id'],160,160); ?>">
												</a>
											</div>
											<div class="p-name">
												<a target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$like['goods_id'])); ?>"><?php echo $like['goods_name']; ?></a>
											</div>
											<div class="p-price">
												<strong><em>￥</em><i><?php echo $like['shop_price']; ?></i></strong>
											</div>
											<div class="p-btn-adc">
												<a onclick="javascript:AjaxAddCart(<?php echo $like['goods_id']; ?>,1);" class="btn-append"><b></b>加入购物车</a>
											</div>
										</div>
									</li>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
						<div class="c-page-acar">
							<a href="javascript:void(0)" class="c-prev">&lt;</a>
							<a href="javascript:void(0)" class="c-next">&gt;</a>
						</div>
					</div>
				</div>
				<div class="switchable-panel" id="collect-products" style="display: none">
					<div class="goods-list-tab" ></div>
					<div class="s-panel-main"></div>
				</div>
				<div class="switchable-panel" id="history-products" style="display: none">
					<div class="goods-list-tab" ></div>
					<div class="s-panel-main"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--底部猜你喜欢-->
<!--删除商品弹窗-s-->
<div class="ui-dialog">
	<div class="ui-dialog-title" style="width: 380px;">
		<span>删除</span>
	</div>
	<div class="ui-dialog-content" style="height: 128px; width: 380px; overflow: hidden;">
		<div class="tip-box icon-box">
			<span class="warn-icon m-icon"></span>
			<div class="item-fore">
				<h3 class="ftx-04">删除商品？</h3>
				<div class="ftx-03">您可以选择添加到收藏，或删除商品。</div>
			</div>
			<div class="op-btns ac">
				<a href="javascript:void(0);" id="removeGoods" class="btn-9 select-remove" >删除</a>
				<a href="javascript:void(0);" id="addCollect" class="btn-1 ml10 re-select-follow moveCollect">添加我的收藏</a>
			</div>
		</div>
	</div>
	<a class="ui-dialog-close" title="关闭">
		<span class="ui-icon ui-icon-delete"></span>
	</a>
</div>
<!--删除商品弹窗-e-->
<div class="ui-mask"></div>
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
<script type="text/javascript">
    $(document).ready(function(){
        my_collect();
        history_log();
        AsyncUpdateCart();
    });
    //购物车对象
    function CartItem(id, goods_num,selected) {
        this.id = id;
        this.goods_num = goods_num;
        this.selected = selected;
    }
    //初始化计算订单价格
    function AsyncUpdateCart(){
        var cart = new Array();
        var inputCheckItem = $("input[name^='checkItem']");
        inputCheckItem.each(function(i,o){
            var id = $(this).attr("value");

            var goods_num = $(this).parents('.item-single').find("input[id^='changeQuantity']").attr('value');
            if ($(this).attr("checked") == 'checked') {
                var cartItemCheck = new CartItem(id,goods_num,1);
                cart.push(cartItemCheck);
            }else{
                var cartItemNoCheck = new CartItem(id,goods_num,0);
                cart.push(cartItemNoCheck);
            }
        })
        $.ajax({
            type : "POST",
            url:"<?php echo url('Home/Cart/AsyncUpdateCart'); ?>",//,
            dataType:'json',
            data: {cart: cart},
            success: function(data){
                if(data.status == 1){
                    $('#goods_num').empty().html(data.result.cart_price_info.goods_num);
                    $('.current').find('em').text('（'+data.result.cart_price_info.goods_num+'）'); //数量
                    $('#total_fee').empty().html('￥'+data.result.cart_price_info.total_fee);
                    $('#goods_fee').empty().html('-￥'+data.result.cart_price_info.goods_fee);
					if(data.result.cart_list.length > 0){
						set_cart_list_price(data.result.cart_list);
					}else{
						$('.total_price').empty();
						$('.cut_price').empty();
					}
                }
            }
        });
    }

	//更新价格
	function set_cart_list_price(cart_list)
	{
		for (var i = 0; i < cart_list.length; i++) {
			$('#cart_' + cart_list[i].id + '_goods_price').empty().html('￥' + cart_list[i].goods_price);
			$('#cart_' + cart_list[i].id + '_total_price').empty().html('￥' + cart_list[i].total_fee);
			var member_goods_price = (cart_list[i].member_goods_price * cart_list[i].goods_num).toFixed(2);
			$('#cart_' + cart_list[i].id + '_market_price').empty().html('￥' + member_goods_price); //活动价格
			$('#changeQuantity_' + cart_list[i].id).empty().html(cart_list[i].goods_num); //数量
			if (cart_list[i].hasOwnProperty("combination_cart") && cart_list[i].combination_cart.length > 0) {
				set_cart_list_price(cart_list[i].combination_cart);
			}
		}
	}

    //关闭优惠券展开状态
    function close_coupon(){
        var coupon = $('.img_coupon');
        coupon.removeClass('img_coupon_show');
        coupon.siblings('.coupon_whether').fadeOut(300);
    }
    //关闭更多促销展开状态
    function close_promption(){
        var promption = $('.promptions_in .cx');
        promption.find('em').html('更多促销');
        promption.removeClass('cx-add');
        promption.siblings('.promotion-cont').fadeOut(100);
    }
    //更多促销点击事件
    $(function(){
        $('.promptions_in .cx').click(function(e){
            e.stopImmediatePropagation();
            $(this).toggleClass('cx-add');
            $(this).siblings('.promotion-cont').fadeToggle(300);
            $(this).parents('.shoplist_detail_a').siblings().find('.cx').removeClass('cx-add').siblings().fadeOut();
            close_coupon();
            if($(this).hasClass('cx-add')){
                $(this).find('em').html('促销信息');
            }else{
                $(this).find('em').html('更多促销');
            }
        })
        $('.promptions_in').click(function(e){
            e.stopImmediatePropagation();
        })
    })
    //优惠券点击事件
    $(function(){
        $('.img_coupon').click(function(e){
            if(getCookie('user_id') == ''){
                pop_login();
                return;
            }
            e.stopImmediatePropagation();
            close_promption();
            $(this).toggleClass('img_coupon_show');
            $(this).siblings('.coupon_whether').fadeToggle(300);
            $(this).parents('.shoplist_detail_a').siblings().find('.img_coupon').removeClass('img_coupon_show').siblings().hide();
        })
        $('.boximg_coupon').click(function(e){
            e.stopImmediatePropagation();
        })
    })

    //猜你喜欢导航滑过状态
    $(function(){
        $('.folahov ul li').hover(function(){
            $(this).addClass('current').siblings().removeClass('current');
            var id = $(this).data('id');
            $('.switchable-panel').hide();
            $('#' + id).show();
        })
    })
    //猜你喜欢点击事件
    $(function(){
        $(document).on("click",'.c-page-acar a',function(){
            var gp = $(this).parents('.s-panel-main').find('.goods-panel');
            var nxt = $(this).parents('.s-panel-main').find('.jsaddsucc');
            var gp_r = $(this).parents('.switchable-panel').find('a');
            var nxt_r = $(this).parents('.switchable-panel').find('.curr');
            //上一页
            if($(this).hasClass('c-prev')){
                nxt.prev().addClass('jsaddsucc').siblings().removeClass('jsaddsucc');
                nxt_r.prev().addClass('curr').siblings().removeClass('curr');
                if($('.hidden').hasClass('jsaddsucc')){
                    gp.eq(gp.length - 1).addClass('jsaddsucc').siblings().removeClass('jsaddsucc');
                    gp_r.eq(gp.length - 1).addClass('curr').siblings().removeClass('curr');
                }
            }
            //下一页
            if($(this).hasClass('c-next')){
                nxt.next().addClass('jsaddsucc').siblings().removeClass('jsaddsucc');
                nxt_r.next().addClass('curr').siblings().removeClass('curr');
                if($(this).parent().hasClass('jsaddsucc')){
                    gp.eq(0).addClass('jsaddsucc').siblings().removeClass('jsaddsucc');
                    gp_r.eq(0).addClass('curr').siblings().removeClass('curr');
                }
            }
        })
    })

    //减购买数量事件
    $(function () {
        $(document).on("click", '.decrement', function (e) {
            var changeQuantityNum = $(this).parent().find('input').val();
            if(!changeQuantityNum){
                $(this).val(1);
            }
            if (changeQuantityNum > 1) {
                $(this).parent().find('input').attr('value', parseInt(changeQuantityNum) - 1).val(parseInt(changeQuantityNum) -1);
            }
            initDecrement();
            changeNum(this);
        })
    })
    //加购买数量事件
    $(function () {
        $(document).on("click", '.increment', function (e) {
            var changeQuantityNum = $(this).parent().find('input').val();
            if(!changeQuantityNum){
                $(this).val(1);
            }
            if(changeQuantityNum > 199){
                changeQuantityNum = 199;
                layer.msg("购买商品数量不能大于200",{icon:2});
            }
            $(this).parent().find('input').attr('value', parseInt(changeQuantityNum) + 1).val(parseInt(changeQuantityNum) + 1);
            initDecrement();
            changeNum(this);
        })
    })
    //手动输入购买数量
    $(function () {
        $(document).on("blur", '.quantity-form input', function (e) {
            var changeQuantityNum = parseInt($(this).val());
            if(changeQuantityNum <= 0){
                layer.alert('商品数量必须大于0', {icon:2});
                $(this).val($(this).attr('value'));
            }else{
                $(this).attr('value', changeQuantityNum);
            }
            if(!changeQuantityNum){
                $(this).val(1);
            }
            initDecrement();
            changeNum(this);
        })
    })

    //更改购物车请求事件
    function changeNum(obj){
        var checkall = $(obj).parents('.item-single').find('.checkall');
        if(!checkall.hasClass('checkall-true')){
            checkall.trigger("click");
        }
        var input = $(obj).parents('.quantity-form').find('input');
        var cart_id = input.attr('id').replace('changeQuantity_','');
        var goods_num = input.attr('value');
        var cart = new CartItem(cart_id, goods_num, 1);
        var type = $(obj).parents('.meal-conts-items').attr('data-type');
        if(type == 7){
            var v = $(obj).parents('.meal-conts-items').next().find('.quantity-form span').text();

        }
        $.ajax({
            type: "POST",
            url: "<?php echo url('Home/Cart/changeNum'); ?>",//+tab,
            dataType: 'json',
            data: {cart: cart},
            success: function (data) {
                if(data.status == 1){
                    AsyncUpdateCart();
                }else{
                    input.val(data.result.limit_num);
                    input.attr('value',data.result.limit_num);
                    // input.val(v);
                    // input.attr('value',v);
                    layer.alert(data.msg,{icon:2});
                }
            }
        });
    }

    //多选框点击事件
    $(function () {
        $(document).on("click", '.checkall', function (e) {
            //模拟checkbox，选中时背景变色
            $(this).toggleClass('checkall-true');
            if($(this).hasClass('checkall-true')){
                $(this).parents('.edge_tw').addClass('edge_tw_bag');
                $(this).parent().find('.check-box').attr('checked', 'checked');
            }else{
                $(this).parents('.edge_tw').removeClass('edge_tw_bag');
                $(this).parent().find('.check-box').removeAttr('checked');
            }
            //选中店铺的多选框
            if($(this).hasClass('checkShop')){
//						alert('gg');
                if($(this).hasClass('checkall-true')){
                    $(this).parents('.shoplist_detail_a').find("input[name^='checkItem']").each(function(i,o){
                        $(o).attr('checked', 'checked');
                        $(o).parent().find('.checkall').addClass('checkall-true');
                    })
                }else{
                    $(this).parents('.shoplist_detail_a').find("input[name^='checkItem']").each(function(i,o){
                        $(o).removeAttr('checked', 'checked');
                        $(o).parent().find('.checkall').removeClass('checkall-true');
                    })
                }
            }
            //选中全选多选框
            if($(this).hasClass('checkFull')){
                if($(this).hasClass('checkall-true')){
                    $(".checkall").each(function(i,o){
                        $(this).addClass('checkall-true');
                        $(this).parent().find('.check-box').attr('checked', 'checked');
                    })
                }else{
                    $(".checkall").each(function(i,o){
                        $(this).removeClass('checkall-true');
                        $(this).parent().find('.check-box').removeAttr('checked');
                    })
                }
            }
            initCheckBox();
            AsyncUpdateCart();
        })
    })
    //删除购物车商品
    $(function () {
        //删除购物车商品事件
        $(document).on("click", '.deleteGoods', function (e) {
            var dh = $(document).height();
            var dw = $(document).width();
            $('.ui-mask').height(dh).width(dw).show();
            $('.ui-dialog').show();
            if($(this).hasClass('deleteItem')){
                //删除单个
                $('#removeGoods').removeClass('deleteAll').addClass('deleteItem').attr('data-cart-id',$(this).data('cart-id'));
                $('#addCollect').attr('data-id',$(this).data('goodsid'))
                $('#addCollect').removeClass('collectAll').addClass('collectItem');
            }else{
                //删除多个
                $('#removeGoods').removeClass('deleteItem').addClass('deleteAll');
                $('#addCollect').removeClass('collectItem').addClass('collectAll');
            }
        })
        //关闭删掉购物车提示框事件
        $(document).on("click", '.ui-dialog-close', function (e) {
            $('.ui-mask').hide();
            $('.ui-dialog').hide();
        })

    })
    //删除购物车商品确定事件
    $(function () {
        $(document).on("click", '#removeGoods', function (e) {
            $('.ui-dialog-close').trigger('click');
            var cart_ids = new Array();
            if($(this).hasClass('deleteItem')){
                //单个删除
                cart_ids.push($('#removeGoods').attr('data-cart-id'));
            }else{
                //多个删除
                $(".checkItem").each(function(i,o){
                    if($(this).hasClass('checkall-true')){
                        cart_ids.push($(this).data('cart-id'));
                    }
                })
            }
            $.ajax({
                type : "POST",
                url:"<?php echo url('Home/Cart/delete'); ?>",//,
                dataType:'json',
                data: {cart_ids: cart_ids},
                success: function(data){
                    if(data.status == 1){
                        for (var i = 0; i < cart_ids.length; i++) {
							var p = $('#edge_' + cart_ids[i]).parent();
							var types = $('#edge_' + cart_ids[i]).attr('data-type');
							var goods_id = $('#edge_' + cart_ids[i]).attr('data-cart-id');
                            var cart_id = p.find('.meal-conts-items').eq(0).attr('data-cart-id');//主商品cart_id
							// console.log(cart_id)
							$('#edge_' + cart_ids[i]).remove();
                            $('.edge_' + cart_ids[i]).remove();
                            //删除掉剩余一个主商品做处理
                            // console.log(p.find('.meal-conts-items').length);
                            if(p.find('.meal-conts-items').length == 1){
                                // console.log(p.children().eq(0))
                                if(types == 7 && cart_id != goods_id){
                                    // console.log(333)
                                    // console.log(cart_id)
                                    console.log(p.children().eq(0).attr('data-goods-item'));
                                    recoveryGoods(p.children().eq(0).attr('data-goods-id'),p.children().eq(0).attr('data-goods-item'))
                                }
                            }


                        }
                        var inputCheckShop = $("input[name^='checkShop']");
                        var inputCheckItemAll = $("input[name^='checkItem']");
                        inputCheckShop.each(function(i,o){
                            var inputCheckItem = $(this).parents('.shoplist_detail_a').find("input[name^='checkItem']");

                            if(inputCheckItem.length == 0){
                                $(this).parents('.shoplist_detail_a').remove();
                            }
                        })
                        if(inputCheckItemAll.length == 0){
                            $('#tpshop-cart').remove();
                            $('.shopcar_empty').show();
                            $('.shoplist_deta').empty();
                        }
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                    AsyncUpdateCart();
                }
            });
        })
    })


    /**
     * 搭配购删除回复普通商品原价
     */
    function recoveryGoods(id,item) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('Home/Cart/add'); ?>",
            data: {goods_id: id,goods_num: 1,item_id: item},//+tab,
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    window.location.reload();
                } else {
                    layer.msg('操作失败', {icon: 2});
                }
            }
        });
    }

    /**
     * 检测选项框
     */
    function initCheckBox(){
        $("input[name^='checkShop']").each(function(i,o){
            var isAllCheck = true;
            $(this).parents('.shoplist_detail_a').find("input[name^='checkItem']").each(function(i,o){
                if ($(this).attr("checked") != 'checked') {
                    isAllCheck = false;
                }
            })
            if(isAllCheck == false){
                $(this).removeAttr('checked');
                $(this).parent().find('.checkall').removeClass('checkall-true');
            }else{
                $(this).attr('checked', 'checked');
                $(this).parent().find('.checkall').addClass('checkall-true');
            }
        })
        var checkBoxsFlag = true;
        $("input[name^='checkItem']").each(function(i,o){
            if ($(this).attr("checked") != 'checked') {
                checkBoxsFlag = false;
            }
        })
        if(checkBoxsFlag == false){
            $("input[name^='checkboxes']").each(function(i,o){
                $(this).removeAttr('checked');
                $(this).parent().find('.checkall').removeClass('checkall-true');
            })
        }else{
            $("input[name^='checkboxes']").each(function(i,o){
                $(this).attr('checked', 'checked');
                $(this).parent().find('.checkall').addClass('checkall-true');
            })
        }
    }

    //更改购买数量对减购买数量按钮的操作
    function initDecrement(){
        $("input[id^='changeQuantity']").each(function(i,o){
            if($(o).val() == 1){
                $(o).parent().find('.decrement').addClass('disable');
            }
            if($(o).val() > 1){
                $(o).parent().find('.decrement').removeClass('disable');
            }
        })
    }

    //结算
    $('.paytotal').click(function(){
        if(getCookie('user_id') == ''){
            pop_login();
            return;
        }
        window.location.href = $(this).attr('data-url');
    })
    //移到我的收藏
    $(function () {
        $(document).on("click", '.moveCollect', function (e) {
            if(getCookie('user_id') == ''){
                pop_login();
                return;
            }
            var goods_arr = new Array();
            if($(this).hasClass('collectItem')){
                //单个收藏
                goods_arr.push($(this).data('id'));
            }else{
                //多个收藏
                $(".checkItem").each(function(i,o){
                    if($(this).hasClass('checkall-true')){
                        if($(this).attr('data-type') == 7){
                            var c = $(this).parents('.meal-conts-name').next().find('.w1224').children();
                            c.each(function (ii,oo) {
                                goods_arr.push($(this).attr('data-goods-id'));
                            })
						}else{
                            goods_arr.push($(this).data('goods-id'));

                        }

                    }
                })
            }
            $.ajax({
                type: "POST",
                url: "<?php echo url('Home/Goods/collect_goods'); ?>",//+tab,
                data: {goods_ids: goods_arr},//+tab,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        layer.msg(data.msg, {icon: 1});
                    } else {
                        layer.msg('操作失败', {icon: 2});
                    }
                }
            });
            $('.ui-dialog-close').trigger('click');
        })
    })
    //登录页面
    function pop_login(){
        layer.open({
            type: 2,
            title: "<b>登陆<?php echo config('shop_info.copyright'); ?>网</b>",
            skin: 'layui-layer-rim',
            shadeClose: true,
            shade: 0.5,
            area: ['490px', '460px'],
            content: "<?php echo url('Home/User/pop_login'); ?>",
        });
    }
    //我的收藏
    function my_collect()
    {
        var uname = getCookie('uname');
        if (uname == '') {
            $('#collect-products .s-panel-main').html('<p class="wefoc"><a href="<?php echo url('User/login'); ?>">登录</a>后将显示您之前关注的商品</p>');
        } else {
            $.ajax({
                type : "get",
                url:"<?php echo url('Home/User/myCollect'); ?>",//+tab,
                dataType:'json',
                success: function(data){
                    if(data.status == 1){
                        var products_html = '';
                        var tab_html = '';
                        for(var i = 0; i < data.result.length; i++){
                            if(i%4 == 0){
                                if(i == 0){
                                    tab_html += '<a class="s-item curr" href="#none"> </a>';
                                    products_html += '<div class="goods-panel jsaddsucc p"><ul>';
                                }else{
                                    tab_html += '<a class="s-item" href="#none"> </a>';
                                    products_html += '<div class="goods-panel p"><ul>';
                                }
                            }
                            products_html += '<li><div class="itemgoodbox"><div class="p-img" ><a target="_blank" href="'+data.result[i].url+'">' +
                                '<img src="'+data.result[i].imgUrl+'"></a></div><div class="p-name"><a target="_blank" href="'+data.result[i].url+'">' +
                                ''+data.result[i].goods[0].goods_name+'</a></div><div class="p-price"><strong><em>￥</em><i>'+data.result[i].goods[0].shop_price+'</i></strong></div><div class="p-btn-adc">';
                            if(data.result[i].goods[0].is_virtual != 1){
                                products_html +=  '<a onclick="javascript:AjaxAddCart('+data.result[i].goods_id+',1);" class="btn-append"><b></b>加入购物车</a>';
                            }else{
                                products_html +=  '<a href="/index.php/home/Goods/goodsInfo/id/'+data.result[i].goods_id+'" class="btn-append"><b></b>加入购物车</a>';
                            }
                            products_html += '</div></div></li>';
                            if(i%4 == 3){
                                products_html += '</ul></div>';
                            }
                        }
                        if(data.result.length > 4){
                            products_html += '<div class="c-page-acar"><a href="javascript:void(0)" class="c-prev">&lt;</a><a href="javascript:void(0)" class="c-next">&gt;</a></div>';
                        }
                        $('#collect-products .s-panel-main').html(products_html);
                        $('#collect-products .goods-list-tab').html(tab_html);
                    }else{
                        $('#collect-products .s-panel-main').html('<p class="wefoc">暂无结果</p>');
                    }
                }
            });
        }
    }
    //最近浏览
    function history_log()
    {
        var uname = getCookie('uname');
        if (uname == '') {
            $('#history-products .s-panel-main').html('<p class="wefoc"><a href="<?php echo url('User/login'); ?>">登录</a>后将显示您之前浏览的商品</p>');
        } else {
            $.ajax({
                type : "get",
                url:"<?php echo url('Home/User/historyLog'); ?>",//+tab,
                dataType:'json',
                success: function(data){
                    if(data.status == 1){
                        var products_html = '';
                        var tab_html = '';
                        for(var i = 0; i < data.result.length; i++){
                            if(i%4 == 0){
                                if(i == 0){
                                    tab_html += '<a class="s-item curr" href="#none"> </a>';
                                    products_html += '<div class="goods-panel jsaddsucc p"><ul>';
                                }else{
                                    tab_html += '<a class="s-item" href="#none"> </a>';
                                    products_html += '<div class="goods-panel p"><ul>';
                                }
                            }
                            products_html += '<li><div class="itemgoodbox"><div class="p-img" ><a target="_blank" href="'+data.result[i].url+'">' +
                                '<img src="'+data.result[i].imgUrl+'"></a></div><div class="p-name"><a target="_blank" href="'+data.result[i].url+'">' +
                                ''+data.result[i].goods[0].goods_name+'</a></div><div class="p-price"><strong><em>￥</em><i>'+data.result[i].goods[0].shop_price+'</i></strong></div><div class="p-btn-adc">';
                            if(data.result[i].goods[0].is_virtual != 1){
                                products_html +=  '<a onclick="javascript:AjaxAddCart('+data.result[i].goods_id+',1);" class="btn-append"><b></b>加入购物车</a>';
                            }else{
                                products_html +=  '<a href="/index.php/home/Goods/goodsInfo/id/'+data.result[i].goods_id+'" class="btn-append"><b></b>加入购物车</a>';
                            }
                            products_html += '</div></div></li>';
                            if(i%4 == 3){
                                products_html += '</ul></div>';
                            }
                        }
                        if(data.result.length > 4){
                            products_html += '<div class="c-page-acar"><a href="javascript:void(0)" class="c-prev">&lt;</a><a href="javascript:void(0)" class="c-next">&gt;</a></div>';
                        }
                        $('#history-products .s-panel-main').html(products_html);
                        $('#history-products .goods-list-tab').html(tab_html);
                    }else{
                        $('#history-products .s-panel-main').html('<p class="wefoc">暂无结果</p>');
                    }
                }
            });
        }
    }
    //获取店铺优惠券
    function getStoreCoupon(){
        var store_ids = new Array();
        var goods_ids = new Array();
        var goods_category_ids = new Array();
        $('.shoplist_detail_a').each(function(i,o){
            store_ids.push($(this).attr('data-store-id'));
        })
        //checkItem
        $('.checkItem').each(function(i,o){
            goods_category_ids.push($(this).attr('data-goods-cat-id3'));
            goods_ids.push($(this).attr('data-goods-id'));
        })
        $.ajax({
            type : "POST",
            url:"<?php echo url('Home/Cart/getStoreCoupon'); ?>",//+tab,
            dataType:'json',
            data:{'store_ids':store_ids,goods_ids:goods_ids,goods_category_ids:goods_category_ids},
            success: function(data){
                var newDate = new Date();
                if(data.status == 1){
                    $('.coupon_whether').each(function(i,o){
                        var store_id = $(this).attr('data-store-id');
                        var coupon_html = '';
                        var send_start_time = '';
                        var send_end_time = '';
                        for(var j = 0;j < data.result.length;j++){
                            newDate.setTime(parseInt(data.result[j].send_start_time)*1000)
                            send_start_time =newDate.toLocaleDateString();
                            newDate.setTime(parseInt(data.result[j].send_end_time)*1000)
                            send_end_time = newDate.toLocaleDateString();
                            if(data.result[j]['store_id'] == store_id) {
                                if (data.result[j]['is_get'] == 0) {
                                    coupon_html += '<div class="al_co3"><div class="co_pri"><span>￥' + data.result[j].money + '</span></div>' +
                                        '<div class="co_des"><span class="sc_coup">商券,满￥' + data.result[j].condition + '减￥' + data.result[j].money + '</span><span class="sc_date">' + send_start_time + '-' + send_end_time + ' </span> </div><div class="co_get"> <a  href="javascript:;" data-coupon-id="' + data.result[j]['id'] + '" onclick="getCoupon(this);" class="coupon_btn">领取</a> </div> </div>';
                                }
                            }
                        }
                        if(coupon_html == ''){
                            $(this).empty().html('<span>没有可领取的优惠券</span>');
                        }else{
                            $(this).empty().html(coupon_html);
                        }
                    })
                }else{
                    $('.coupon_whether').each(function(i,o){
                        $(this).empty().html('<span>没有可领取的优惠券</span>');
                    })
                }
            }
        });
    }
    //领取优惠券
    function getCoupon(obj){
        var coupon_id = $(obj).attr('data-coupon-id');
        $.ajax({
            type : "POST",
            url:"<?php echo url('Home/User/getCoupon'); ?>",
            dataType:'json',
            data: {coupon_id: coupon_id},
            success: function(data){
                if(data.status == 1){
                    $(obj).removeClass('coupon_btn').removeAttr('data-coupon-id').html('已领取');
                    $(obj).removeClass('coupon_btn').removeAttr('data-coupon-id').css('background-color','#999');
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            }
        });
    }
</script>
</body>


</html>