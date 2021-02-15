<?php
//000000000000
 exit();?>
s:448955:"<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>商品列表</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/tpshop.css" />
	<script src="/pc/rainbow/static/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
    <link rel="shortcut icon" type="image/x-icon" href="/public/upload/logo/2018/04-09/516bc70315079d81dc3726991672b4af.png" media="screen"/>
	<script src="/public/js/layer/layer-min.js"></script>
	<script src="/public/js/global.js"></script>
	<script src="/public/js/pc_common.js"></script>
    <style>
        @media screen and (min-width:1260px) and (max-width: 1465px) {
            .w1430{width: 1224px;}
        }
        @media screen and (max-width: 1260px) {
            .w1430{width: 983px;}
        }
    </style>
</head>
<body>
<style>
	.cata-nav-rigth.img_right{
		position: relative;
		overflow: visible !important;
		height: 0;
		margin: 10px 40px 0 0  !important;
	}
	.cata-nav-rigth.img_right img{
		width: auto;
		max-width: 360px;
		height: 460px;
	}
</style>
<link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/base.css"/>
<link rel="shortcut icon" type="image/x-icon" href="/public/upload/logo/2018/04-09/516bc70315079d81dc3726991672b4af.png" media="screen"/>
<div class="tpshop-tm-hander">
	<div class="top-hander">
		<div class="w1430 pr clearfix">
			<div class="fl">
			                          <link rel="stylesheet" href="/pc/rainbow/static/css/location.css" type="text/css"><!-- 收货地址，物流运费 -->
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
					<script src="/public/js/locationJson.js"></script>
				  	<script src="/pc/rainbow/static/js/location.js"></script>
					<script>doInitRegion();</script>
                				<div class="fl nologin">
					<a class="red" href="/index.php/Home/user/login">登录</a>
					<a href="/index.php/Home/user/reg">注册</a>
				</div>
				<div class="fl islogin hide">
					<a class="red userinfo" href="/index.php/Home/user/index"></a>
					<a  href="/index.php/Home/user/logout"  title="退出" target="_self">安全退出</a>
				</div>
			</div>
			<ul class="top-ri-header fr clearfix">
				<li><a target="_blank" href="/index.php/Home/Order/order_list">我的订单</a></li>
				<li class="spacer"></li>
				<li><a target="_blank" href="/index.php/Home/User/visit_log">我的浏览</a></li>
				<li class="spacer"></li>
				<li><a target="_blank" href="/index.php/Home/User/goods_collect">我的收藏</a></li>
				<li class="spacer"></li>
				<li><a target="_blank" href="/index.php/Home/Article/detail">帮助中心</a></li>
				<li class="spacer"></li>
				<li class="hover-ba-navdh">
					<div class="nav-dh">
						<span>网站导航</span>
						<i class="share-a_a1"></i>
					</div>
					<ul class="conta-hv-nav clearfix">
                        <li>
                            <a href="/index.php/Home/Activity/promoteList">优惠活动</a>
                        </li>
                        <li>
                            <a href="/index.php/Home/Activity/pre_sell_list">预售活动</a>
                        </li>
                        <!--<li>
                            <a href="/index.php/Home/Goods/integralMall">拍卖活动</a>
                        </li>-->
                        <li>
                            <a href="/index.php/Home/Goods/integralMall">兑换中心</a>
                        </li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="nav-middan-z w1430 clearfix">
		<a class="ecsc-logo" href="/index.php/Home/index/index">
            <img src="/public/upload/logo/2018/04-09/814d7e9a0eddcf3754f2e8373a50a19c.png"/>
        </a>
		<div class="ecsc-search">
			<form id="searchForm" name="" method="get" action="/index.php/Home/Goods/search" class="ecsc-search-form">
				<input autocomplete="off" name="q" id="q" type="text" value="" class="ecsc-search-input" placeholder="请输入搜索关键字...">
				<button type="submit" class="ecsc-search-button">搜索</button>
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
                        $('#searchForm').submit();
                    }
                    function search_key(){
                        var search_key = $.trim($('#q').val());
                        if(search_key != ''){
                            $.ajax({
                                type:'post',
                                dataType:'json',
                                data: {key: search_key},
                                url:"/index.php/Home/Api/searchKey",
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
			<div class="keyword clearfix">
								<a class="key-item" href="/index.php/Home/Goods/search/q/%E6%89%8B%E6%9C%BA" target="_blank">手机</a>
								<a class="key-item" href="/index.php/Home/Goods/search/q/%E5%B0%8F%E7%B1%B3" target="_blank">小米</a>
								<a class="key-item" href="/index.php/Home/Goods/search/q/iphone" target="_blank">iphone</a>
								<a class="key-item" href="/index.php/Home/Goods/search/q/%E4%B8%89%E6%98%9F" target="_blank">三星</a>
								<a class="key-item" href="/index.php/Home/Goods/search/q/%E5%8D%8E%E4%B8%BA" target="_blank">华为</a>
								<a class="key-item" href="/index.php/Home/Goods/search/q/%E5%86%B0%E7%AE%B1" target="_blank">冰箱</a>
							</div>
		</div>
		<div class="u-g-cart fr" id="hd-my-cart">
			<a href="/index.php/Home/Cart/index">
			<div class="c-n fl">
				<i class="share-shopcar-index"></i>
				<span>购物车</span>
				<em class="shop-nums" id="cart_quantity"></em>
			</div>
			</a>
			<div class="u-fn-cart" id="show_minicart">
				<div class="minicartContent" id="minicart">
				</div>
			</div>
		</div>
	</div>
	<div class="nav w1430 clearfix">
		<div class="categorys home_categorys">
			<div class="dt">
				<a href="" target="_blank"><i class="share-a_a2"></i>全部商品分类</a>
			</div>
			<!--全部商品分类-s-->
			<div class="dd">
				<div class="cata-nav" id="cata-nav">
									<div class="item">
												<div class="item-left">
							<h3 class="cata-nav-name">
								<div class="cata-nav-wrap">
									<!--<i class="ico ico-nav-0"></i>-->
									<img src="" style="display: inline-block;width: 25px;height: 25px;margin-right: 20px;background-repeat: no-repeat;">
									<a href="/index.php/Home/Goods/goodsList/id/31" title="手机">手机</a>
								</div>
								<!--<a href="" >手机店</a>-->
							</h3>
						</div>
												<div class="cata-nav-layer">
							<div class="cata-nav-left">
								 <!-- 如果没有热门分类就隐藏 --> 
								 								<div class="cata-layer-title" >
																	</div>
							 
								<div class="subitems">
																			<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/32" target="_blank">手机/运营商/数码22</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/62" target="_blank">手机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/63" target="_blank">老人手机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/64" target="_blank">游戏手机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/65" target="_blank">对讲机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/66" target="_blank">以旧换新</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/67" target="_blank">手机维修</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/33" target="_blank">运营商</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/68" target="_blank">合约机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/69" target="_blank">选号码</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/70" target="_blank">固定宽带</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/71" target="_blank">办套餐</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/72" target="_blank">充话费/流量</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/73" target="_blank">中国移动</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/74" target="_blank">中国联通</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/34" target="_blank">手机配件</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/75" target="_blank">手机壳</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/76" target="_blank">贴膜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/77" target="_blank">手机储存卡</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/78" target="_blank">数据线</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/79" target="_blank">充电宝</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/80" target="_blank">手机耳机</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/35" target="_blank">摄影摄像</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/84" target="_blank">运动相机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/81" target="_blank">数码相机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/82" target="_blank">单反相机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/85" target="_blank">摄像头</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/86" target="_blank">数码相框</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/87" target="_blank">镜头</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/88" target="_blank">户外器材</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/36" target="_blank">数码配件</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/160" target="_blank">三角架/云台</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/161" target="_blank">滤器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/162" target="_blank">闪光灯/手柄</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/163" target="_blank">相机清洁</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/164" target="_blank">机身附件</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/165" target="_blank">读卡器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/167" target="_blank">电池/充电器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/89" target="_blank">相机包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/90" target="_blank">储存卡</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/168" target="_blank">影音娱乐</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/169" target="_blank">耳机/耳麦</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/170" target="_blank">音箱/音响</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/171" target="_blank">智能音箱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/172" target="_blank">无线音箱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/173" target="_blank">收音机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/174" target="_blank">麦克风</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/175" target="_blank">MP3/MP4</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/176" target="_blank">专业音频</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/177" target="_blank">电子教育</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/178" target="_blank">学生平板</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/179" target="_blank">点读机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/180" target="_blank">录音笔</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/181" target="_blank">电子词典</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/182" target="_blank">复读机</a>
																																				</dd>
										</dl>
																										</div>
							</div>
							<div class="advertisement_down">
															</div>
														<a href="javascript:void(0);" class="cata-nav-rigth img_right" >
								<img class="w-100" src="/public/upload/ad/2018/04-09/6ef2f9b7347fe73acbe067ea77327778.jpg" title="" />
							</a>
													</div>
					</div>
										<div class="item">
												<div class="item-left">
							<h3 class="cata-nav-name">
								<div class="cata-nav-wrap">
									<!--<i class="ico ico-nav-1"></i>-->
									<img src="" style="display: inline-block;width: 25px;height: 25px;margin-right: 20px;background-repeat: no-repeat;">
									<a href="/index.php/Home/Goods/goodsList/id/12" title="服饰">服饰</a>
								</div>
								<!--<a href="" >手机店</a>-->
							</h3>
						</div>
												<div class="cata-nav-layer">
							<div class="cata-nav-left">
								 <!-- 如果没有热门分类就隐藏 --> 
								 								<div class="cata-layer-title" >
																	</div>
							 
								<div class="subitems">
																			<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/13" target="_blank">女装</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/183" target="_blank">半身裙</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/184" target="_blank">短裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/185" target="_blank">旗袍</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/186" target="_blank">休闲裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/187" target="_blank">牛仔裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/188" target="_blank">中老年女装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/189" target="_blank">小西装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/190" target="_blank">打底衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/191" target="_blank">打底裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/192" target="_blank">马甲</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/193" target="_blank">礼服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/194" target="_blank">婚纱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/195" target="_blank">吊带/背心</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/196" target="_blank">毛尼大衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/197" target="_blank">羽绒服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/14" target="_blank">新品推荐</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/15" target="_blank">连衣裙</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/16" target="_blank">T恤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/17" target="_blank">衬衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/18" target="_blank">雪纺衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/19" target="_blank">短外套</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/20" target="_blank">卫衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/21" target="_blank">针秀衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/22" target="_blank">风衣</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/23" target="_blank">男装</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/24" target="_blank">当季热卖</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/25" target="_blank">新品推荐</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/215" target="_blank">工装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/198" target="_blank">T恤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/199" target="_blank">牛仔裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/200" target="_blank">休闲裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/201" target="_blank">衬衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/202" target="_blank">短裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/203" target="_blank">羽绒服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/204" target="_blank">棉服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/205" target="_blank">夹克</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/206" target="_blank">卫衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/207" target="_blank">毛尼大衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/208" target="_blank">西服套装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/209" target="_blank">风衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/210" target="_blank">马甲/背心</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/211" target="_blank">西服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/212" target="_blank">西裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/216" target="_blank">羊毛衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/213" target="_blank">中老男装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/214" target="_blank">设计师/潮牌</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/29" target="_blank">加绒裤</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/26" target="_blank">内衣</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/219" target="_blank">男士内裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/220" target="_blank">女士内裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/221" target="_blank">文胸套装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/222" target="_blank">情侣睡衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/223" target="_blank">少女文胸</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/224" target="_blank">商务男袜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/226" target="_blank">打底裤袜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/227" target="_blank">内衣配件</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/228" target="_blank">泳衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/229" target="_blank">秋衣秋裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/230" target="_blank">保暖内衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/231" target="_blank">情趣内衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/217" target="_blank">文胸</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/27" target="_blank">配饰</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/232" target="_blank">女士围巾/披肩</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/233" target="_blank">男士丝巾/围巾</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/234" target="_blank">太阳镜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/235" target="_blank">防辐射眼镜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/236" target="_blank">老花镜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/237" target="_blank">游泳镜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/238" target="_blank">领带/领结</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/239" target="_blank">毛线帽</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/240" target="_blank">棒球帽</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/241" target="_blank">遮阳伞/雨伞</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/242" target="_blank">男士腰带</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/243" target="_blank">女士腰带</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/244" target="_blank">真皮手套</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/245" target="_blank">毛线手套</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/28" target="_blank">童装</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/246" target="_blank">套装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/247" target="_blank">卫衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/248" target="_blank">裤子</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/249" target="_blank">外套/大衣</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/250" target="_blank">毛衣/针织衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/251" target="_blank">衬衫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/252" target="_blank">户外/运动服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/254" target="_blank">裙子</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/255" target="_blank">亲子装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/256" target="_blank">礼服/演出服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/257" target="_blank">羽绒服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/258" target="_blank">棉服</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/259" target="_blank">内衣裤</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/260" target="_blank">口罩</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/261" target="_blank">耳罩/耳包</a>
																																				</dd>
										</dl>
																										</div>
							</div>
							<div class="advertisement_down">
															</div>
														<a href="javascript:void(0);" class="cata-nav-rigth img_right" >
								<img class="w-100" src="/public/upload/ad/2018/04-09/6ef2f9b7347fe73acbe067ea77327778.jpg" title="" />
							</a>
													</div>
					</div>
										<div class="item">
												<div class="item-left">
							<h3 class="cata-nav-name">
								<div class="cata-nav-wrap">
									<!--<i class="ico ico-nav-2"></i>-->
									<img src="" style="display: inline-block;width: 25px;height: 25px;margin-right: 20px;background-repeat: no-repeat;">
									<a href="/index.php/Home/Goods/goodsList/id/37" title="电脑">电脑</a>
								</div>
								<!--<a href="" >手机店</a>-->
							</h3>
						</div>
												<div class="cata-nav-layer">
							<div class="cata-nav-left">
								 <!-- 如果没有热门分类就隐藏 --> 
								 								<div class="cata-layer-title" >
																	</div>
							 
								<div class="subitems">
																			<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/38" target="_blank">电脑整机</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/91" target="_blank">笔记本</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/92" target="_blank">游戏本</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/93" target="_blank">平板电脑</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/94" target="_blank">台试机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/95" target="_blank">一体机</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/39" target="_blank">电脑配件</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/147" target="_blank">显示器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/148" target="_blank">CPU</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/149" target="_blank">主板</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/150" target="_blank">显卡</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/151" target="_blank">硬盘</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/152" target="_blank">内存</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/153" target="_blank">机箱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/154" target="_blank">电源</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/96" target="_blank">散热器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/97" target="_blank">装机配件</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/98" target="_blank">组装电脑</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/40" target="_blank">外设产品</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/102" target="_blank">手写板</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/155" target="_blank">鼠标垫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/156" target="_blank">电脑工具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/157" target="_blank">电脑清洁</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/158" target="_blank">插座</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/99" target="_blank">鼠标</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/100" target="_blank">键盘</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/101" target="_blank">U盘</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/103" target="_blank">摄像头</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/41" target="_blank">游戏设备</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/128" target="_blank">游戏软件</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/129" target="_blank">游戏周边</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/130" target="_blank">手柄/方向盘</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/104" target="_blank">游戏机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/105" target="_blank">游戏耳机</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/42" target="_blank">网络产品</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/123" target="_blank">交换机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/124" target="_blank">网络存储卡</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/125" target="_blank">网卡</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/127" target="_blank">4G/3G上网</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/121" target="_blank">路由器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/122" target="_blank">网络机顶盒</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/126" target="_blank">网络配件</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/43" target="_blank">办公设备</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/131" target="_blank">投像机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/133" target="_blank">多功能一体机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/134" target="_blank">打印机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/135" target="_blank">传真设备</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/136" target="_blank">验钞/点钞机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/137" target="_blank">收银机</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/159" target="_blank">线缆</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/132" target="_blank">投影配件</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/44" target="_blank">文具耗材</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/138" target="_blank">硒鼓/墨粉</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/139" target="_blank">墨盒</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/140" target="_blank">色带</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/141" target="_blank">纸类</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/142" target="_blank">办公文具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/143" target="_blank">学生文具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/144" target="_blank">文件收纳</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/145" target="_blank">计算器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/146" target="_blank">财会用品</a>
																																				</dd>
										</dl>
																										</div>
							</div>
							<div class="advertisement_down">
															</div>
														<a href="javascript:void(0);" class="cata-nav-rigth img_right" >
								<img class="w-100" src="/public/upload/ad/2018/04-09/6ef2f9b7347fe73acbe067ea77327778.jpg" title="" />
							</a>
													</div>
					</div>
										<div class="item">
												<div class="item-left">
							<h3 class="cata-nav-name">
								<div class="cata-nav-wrap">
									<!--<i class="ico ico-nav-3"></i>-->
									<img src="" style="display: inline-block;width: 25px;height: 25px;margin-right: 20px;background-repeat: no-repeat;">
									<a href="/index.php/Home/Goods/goodsList/id/30" title="家居">家居</a>
								</div>
								<!--<a href="" >手机店</a>-->
							</h3>
						</div>
												<div class="cata-nav-layer">
							<div class="cata-nav-left">
								 <!-- 如果没有热门分类就隐藏 --> 
								 								<div class="cata-layer-title" >
																	</div>
							 
								<div class="subitems">
																			<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/45" target="_blank">厨具</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/262" target="_blank">水具酒具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/263" target="_blank">餐具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/264" target="_blank">厨房配件</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/265" target="_blank">刀剪菜板</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/266" target="_blank">锅具套装</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/268" target="_blank">茶具/咖啡具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/269" target="_blank">保温杯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/270" target="_blank">保鲜盒</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/46" target="_blank">家纺</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/275" target="_blank">凉席</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/276" target="_blank">毛巾浴巾</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/277" target="_blank">地毯地垫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/278" target="_blank">床垫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/279" target="_blank">毯子</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/280" target="_blank">抱枕靠垫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/281" target="_blank">窗帘/窗纱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/282" target="_blank">床单</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/283" target="_blank">被套</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/284" target="_blank">电热垫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/285" target="_blank">桌布/罩件</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/48" target="_blank">灯具</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/291" target="_blank">吸顶灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/292" target="_blank">吊灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/293" target="_blank">台灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/294" target="_blank">筒灯射灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/295" target="_blank">装饰灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/296" target="_blank">LED灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/297" target="_blank">氛围照明</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/298" target="_blank">落地灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/299" target="_blank">庭院灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/300" target="_blank">节能灯</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/301" target="_blank">应急灯/手电</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/49" target="_blank">家具</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/302" target="_blank">卧室家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/303" target="_blank">客厅家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/304" target="_blank">餐厅家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/305" target="_blank">书房家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/306" target="_blank">儿童家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/307" target="_blank">储物家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/308" target="_blank">阳台/户外</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/309" target="_blank">办公家具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/310" target="_blank">床</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/311" target="_blank">床垫</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/312" target="_blank">沙发</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/313" target="_blank">电脑椅</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/314" target="_blank">衣柜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/315" target="_blank">电视柜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/316" target="_blank">餐桌</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/317" target="_blank">电脑桌</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/318" target="_blank">鞋架/衣帽椅</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/319" target="_blank">儿童桌椅</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/320" target="_blank">儿童床</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/321" target="_blank">晾衣架</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/47" target="_blank">生活日品</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/286" target="_blank">收纳用品</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/287" target="_blank">净化除味</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/288" target="_blank">浴室用品</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/289" target="_blank">缝纫/针织用品</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/290" target="_blank">清洁工具</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/106" target="_blank">雨伞雨具</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/50" target="_blank">家装主材</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/324" target="_blank">瓷砖</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/325" target="_blank">地板</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/326" target="_blank">油漆涂料</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/327" target="_blank">壁纸</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/329" target="_blank">涂刷辅料</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/51" target="_blank">厨房卫浴</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/328" target="_blank">水槽</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/330" target="_blank">龙头</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/331" target="_blank">马桶</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/332" target="_blank">智能马桶盖</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/333" target="_blank">浴室柜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/334" target="_blank">垃圾处理器</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/323" target="_blank">装修定制</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/335" target="_blank">装修设计</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/336" target="_blank">全包装修</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/337" target="_blank">局部装修</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/338" target="_blank">橱柜</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/339" target="_blank">门窗</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/340" target="_blank">散热器</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/341" target="_blank">安装服务</a>
																																				</dd>
										</dl>
																										</div>
							</div>
							<div class="advertisement_down">
															</div>
														<a href="javascript:void(0);" class="cata-nav-rigth img_right" >
								<img class="w-100" src="/public/upload/ad/2018/04-09/6ef2f9b7347fe73acbe067ea77327778.jpg" title="" />
							</a>
													</div>
					</div>
										<div class="item">
												<div class="item-left">
							<h3 class="cata-nav-name">
								<div class="cata-nav-wrap">
									<!--<i class="ico ico-nav-4"></i>-->
									<img src="" style="display: inline-block;width: 25px;height: 25px;margin-right: 20px;background-repeat: no-repeat;">
									<a href="/index.php/Home/Goods/goodsList/id/56" title="鞋类">鞋类</a>
								</div>
								<!--<a href="" >手机店</a>-->
							</h3>
						</div>
												<div class="cata-nav-layer">
							<div class="cata-nav-left">
								 <!-- 如果没有热门分类就隐藏 --> 
								 								<div class="cata-layer-title" >
																	</div>
							 
								<div class="subitems">
																			<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/57" target="_blank">时尚女鞋</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/443" target="_blank">新品推荐</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/444" target="_blank">单鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/445" target="_blank">休闲鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/446" target="_blank">帆布鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/447" target="_blank">布鞋/绣花鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/448" target="_blank">女靴</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/449" target="_blank">马丁靴</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/450" target="_blank">高跟鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/451" target="_blank">运动鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/452" target="_blank">凉鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/453" target="_blank">内增高</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/454" target="_blank">防水台</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/455" target="_blank">鞋配件</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/58" target="_blank">潮流女包</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/456" target="_blank">真皮包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/457" target="_blank">女士钱包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/458" target="_blank">单肩包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/459" target="_blank">斜跨包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/460" target="_blank">手提包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/461" target="_blank">腰包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/462" target="_blank">化妆包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/463" target="_blank">钥匙包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/464" target="_blank">双肩包</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/465" target="_blank">精品男包</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/466" target="_blank">男士钱包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/467" target="_blank">双肩包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/468" target="_blank">单肩/斜跨包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/469" target="_blank">商务公文包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/470" target="_blank">男士手包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/471" target="_blank">钥匙包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/472" target="_blank">腰包</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/570" target="_blank">男士鞋子</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/571" target="_blank">新品推荐</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/572" target="_blank">当季热卖</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/573" target="_blank">运动鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/575" target="_blank">休闲鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/576" target="_blank">凉鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/577" target="_blank">棉拖鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/578" target="_blank">牛皮鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/579" target="_blank">布帆鞋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/580" target="_blank">解放鞋</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/473" target="_blank">功能箱包</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/474" target="_blank">旅行箱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/475" target="_blank">万向轮箱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/476" target="_blank">旅行袋</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/477" target="_blank">拉杆箱</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/478" target="_blank">电脑包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/479" target="_blank">休闲运动包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/481" target="_blank">登山包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/482" target="_blank">相机包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/483" target="_blank">妈咪包</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/484" target="_blank">旅行配件</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/485" target="_blank">珠宝首饰</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/486" target="_blank">黄金</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/487" target="_blank">K金</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/488" target="_blank">时尚饰品</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/489" target="_blank">砖石</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/490" target="_blank">银饰</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/491" target="_blank">铂金</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/492" target="_blank">珍珠</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/493" target="_blank">发饰</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/494" target="_blank">水晶玛瑙</a>
																																				</dd>
										</dl>
																												<dl class="clearfix">
											<dt><a href="/index.php/Home/Goods/goodsList/id/495" target="_blank">时尚钟表</a></dt>
											<dd class="clearfix">
																									<a href="/index.php/Home/Goods/goodsList/id/499" target="_blank">浪琴</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/500" target="_blank">卡西欧</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/498" target="_blank">DW</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/501" target="_blank">西铁城</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/502" target="_blank">天王</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/503" target="_blank">瑞表</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/504" target="_blank">国表</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/505" target="_blank">日韩表</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/506" target="_blank">欧美表</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/507" target="_blank">儿童手表</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/508" target="_blank">智能手表</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/509" target="_blank">闹钟</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/510" target="_blank">挂钟</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/511" target="_blank">座钟</a>
																																						<a href="/index.php/Home/Goods/goodsList/id/512" target="_blank">钟表配件</a>
																																				</dd>
										</dl>
																										</div>
							</div>
							<div class="advertisement_down">
															</div>
														<a href="javascript:void(0);" class="cata-nav-rigth img_right" >
								<img class="w-100" src="/public/upload/ad/2018/04-09/6ef2f9b7347fe73acbe067ea77327778.jpg" title="" />
							</a>
													</div>
					</div>
										
				</div>
				<script>
					$('#cata-nav').find('.item').hover(function () {
						$(this).addClass('nav-active').siblings().removeClass('nav-active');
					},function () {
						$(this).removeClass('nav-active');
					})
				</script>
			</div>
			<!--全部商品分类-e-->
		</div>
		<ul class="navitems clearfix" id="navitems">
			<li ><a href="/index.php/home/Index/index">首页</a></li>
						<li >
       			<a href="/index.php/Home/Goods/goodsList/id/32" target="_blank"  >手机城</a>
       		</li>
						<li >
       			<a href="/index.php/Home/Goods/goodsList/id/52" target="_blank"  >电器城</a>
       		</li>
						<li class='selected'>
       			<a href="/index.php/Home/Goods/goodsList/id/30" target="_blank"  >家居城</a>
       		</li>
						<li >
       			<a href="/index.php/Home/Goods/goodsList/id/83" target="_blank"  >图书馆</a>
       		</li>
						<li >
       			<a href="/index.php?m=Home&amp;c=Activity&amp;a=pre_sell_list" target="_blank"  >预售活动</a>
       		</li>
						<li >
       			<a href="/index.php/Home/Goods/goodsList/id/36" target="_blank"  >数码城</a>
       		</li>
						<li >
       			<a href="/index.php?m=Home&amp;c=Goods&amp;a=integralMall"  >积分商城</a>
       		</li>
						<li >
       			<a href="/index.php?m=Home&amp;c=Activity&amp;a=group_list"  >团购</a>
       		</li>
					</ul>
	</div>
</div>

<div class="search-box p">
	<div class="w1430">
		<div class="search-path fl">
			<a href="/index.php/Home/Goods/goodsList/id/30">全部结果</a>
			<i class="litt-xyb"></i>
			<!--如果当前分类是三级分类 则二级也要显示-->
						<!--如果当前分类是三级分类 则二级也要显示-->
						<!--当前分类-->
							<div class="havedox">
					<div class="disenk"><span><a href="/index.php/Home/Goods/goodsList/id/30">家居</a></span><i class="litt-xxd"></i></div>
					<div class="hovshz">
						<ul>
															<li><a href="/index.php/Home/Goods/goodsList/id/45">厨具</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/46">家纺</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/48">灯具</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/49">家具</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/47">生活日品</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/50">家装主材</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/51">厨房卫浴</a></li>
															<li><a href="/index.php/Home/Goods/goodsList/id/323">装修定制</a></li>
													</ul>
					</div>
				</div>
				<i class="litt-xyb"></i>
					</div>
				<div class="search-clear fr">
			<span><a href="/index.php/Home/Goods/goodsList/id/30">清空筛选条件</a></span>
		</div>
	</div>
</div>
<!-- 筛选 start -->
<div class="search-opt troblect">
    <div class="w1430">
        <div class="opt-list">
            <!-- 分类筛选 start -->
                            <dl class="brand-section m-tr">
                    <dt>分类筛选</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/45">
                                                <span >厨具</span>
                                            </a>
                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/46">
                                                <span >家纺</span>
                                            </a>
                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/47">
                                                <span >生活日品</span>
                                            </a>
                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/48">
                                                <span >灯具</span>
                                            </a>
                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/49">
                                                <span >家具</span>
                                            </a>
                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/50">
                                                <span >家装主材</span>
                                            </a>
                                                                                                                        <a href="/index.php/Home/Goods/goodsList/id/51">
                                                <span >厨房卫浴</span>
                                            </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href="/index.php/Home/Goods/goodsList/id/323">
                                                <span >装修定制</span>
                                            </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="gd_more">更多</span><i class="litt-tcr"></i></a>
                        </div>
                    </dd>
                </dl>
                        <!-- 品牌筛选 start -->
                        <!-- 品牌筛选 end -->
            <!-- 规格筛选 start -->
                                <dl class="brand-section m-tr">
                        <dt>颜色分类</dt>
                        <dd class="ri-section">
                            <div class="lf-list">
                                <div class="brand-list">
                                    <div class="clearfix p">
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/33_88" data-href="/index.php/home/Goods/goodsList/id/30/spec/33_88" data-key='33' data-val='88'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>30cm</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/33_89" data-href="/index.php/home/Goods/goodsList/id/30/spec/33_89" data-key='33' data-val='89'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>40cm</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/33_90" data-href="/index.php/home/Goods/goodsList/id/30/spec/33_90" data-key='33' data-val='90'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>50cm</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/33_91" data-href="/index.php/home/Goods/goodsList/id/30/spec/33_91" data-key='33' data-val='91'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>60cm</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/33_92" data-href="/index.php/home/Goods/goodsList/id/30/spec/33_92" data-key='33' data-val='92'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>70cm</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/33_93" data-href="/index.php/home/Goods/goodsList/id/30/spec/33_93" data-key='33' data-val='93'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>80cm</span>
                                            </a>
                                                                            </div>
                                    <div class="surclofix p">
                                        <a href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
                                        <a href="javascript:;" class="u-cancel">取消</a>
                                    </div>
                                </div>
                            </div>
                            <div class="lr-more">
                                <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                            </div>
                        </dd>
                    </dl>
                                                <dl class="brand-section m-tr">
                        <dt>颜色分类</dt>
                        <dd class="ri-section">
                            <div class="lf-list">
                                <div class="brand-list">
                                    <div class="clearfix p">
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/27_106" data-href="/index.php/home/Goods/goodsList/id/30/spec/27_106" data-key='27' data-val='106'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>黑色框小号</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/27_107" data-href="/index.php/home/Goods/goodsList/id/30/spec/27_107" data-key='27' data-val='107'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>黑色框大号</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/27_108" data-href="/index.php/home/Goods/goodsList/id/30/spec/27_108" data-key='27' data-val='108'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>金色框小号</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/27_115" data-href="/index.php/home/Goods/goodsList/id/30/spec/27_115" data-key='27' data-val='115'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>金色框大号</span>
                                            </a>
                                                                            </div>
                                    <div class="surclofix p">
                                        <a href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
                                        <a href="javascript:;" class="u-cancel">取消</a>
                                    </div>
                                </div>
                            </div>
                            <div class="lr-more">
                                <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                            </div>
                        </dd>
                    </dl>
                                                <dl class="brand-section m-tr">
                        <dt>尺寸</dt>
                        <dd class="ri-section">
                            <div class="lf-list">
                                <div class="brand-list">
                                    <div class="clearfix p">
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/28_109" data-href="/index.php/home/Goods/goodsList/id/30/spec/28_109" data-key='28' data-val='109'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>。</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/28_110" data-href="/index.php/home/Goods/goodsList/id/30/spec/28_110" data-key='28' data-val='110'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>。</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/28_111" data-href="/index.php/home/Goods/goodsList/id/30/spec/28_111" data-key='28' data-val='111'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>.</span>
                                            </a>
                                                                            </div>
                                    <div class="surclofix p">
                                        <a href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
                                        <a href="javascript:;" class="u-cancel">取消</a>
                                    </div>
                                </div>
                            </div>
                            <div class="lr-more">
                                <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                            </div>
                        </dd>
                    </dl>
                                                <dl class="brand-section m-tr">
                        <dt>颜色分类</dt>
                        <dd class="ri-section">
                            <div class="lf-list">
                                <div class="brand-list">
                                    <div class="clearfix p">
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/37_112" data-href="/index.php/home/Goods/goodsList/id/30/spec/37_112" data-key='37' data-val='112'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>白色</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/37_113" data-href="/index.php/home/Goods/goodsList/id/30/spec/37_113" data-key='37' data-val='113'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>浅绿色</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/37_114" data-href="/index.php/home/Goods/goodsList/id/30/spec/37_114" data-key='37' data-val='114'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>浅蓝色</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/37_120" data-href="/index.php/home/Goods/goodsList/id/30/spec/37_120" data-key='37' data-val='120'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>粉红色</span>
                                            </a>
                                                                            </div>
                                    <div class="surclofix p">
                                        <a href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
                                        <a href="javascript:;" class="u-cancel">取消</a>
                                    </div>
                                </div>
                            </div>
                            <div class="lr-more">
                                <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                            </div>
                        </dd>
                    </dl>
                                                <dl class="brand-section m-tr">
                        <dt>选择颜色</dt>
                        <dd class="ri-section">
                            <div class="lf-list">
                                <div class="brand-list">
                                    <div class="clearfix p">
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/1_1" data-href="/index.php/home/Goods/goodsList/id/30/spec/1_1" data-key='1' data-val='1'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>图片色</span>
                                            </a>
                                                                            </div>
                                    <div class="surclofix p">
                                        <a href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
                                        <a href="javascript:;" class="u-cancel">取消</a>
                                    </div>
                                </div>
                            </div>
                            <div class="lr-more">
                                <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                            </div>
                        </dd>
                    </dl>
                                                <dl class="brand-section m-tr">
                        <dt>码数</dt>
                        <dd class="ri-section">
                            <div class="lf-list">
                                <div class="brand-list">
                                    <div class="clearfix p">
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/2_6" data-href="/index.php/home/Goods/goodsList/id/30/spec/2_6" data-key='2' data-val='6'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>155（S）</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/2_7" data-href="/index.php/home/Goods/goodsList/id/30/spec/2_7" data-key='2' data-val='7'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>160（M）</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/2_18" data-href="/index.php/home/Goods/goodsList/id/30/spec/2_18" data-key='2' data-val='18'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>165（L）</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/2_19" data-href="/index.php/home/Goods/goodsList/id/30/spec/2_19" data-key='2' data-val='19'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>170（XL）</span>
                                            </a>
                                                                                    <a href="/index.php/home/Goods/goodsList/id/30/spec/2_53" data-href="/index.php/home/Goods/goodsList/id/30/spec/2_53" data-key='2' data-val='53'>
                                                <input class="shaix_la" type="checkbox" style="display: none"/>
                                                <span>175（XXL）</span>
                                            </a>
                                                                            </div>
                                    <div class="surclofix p">
                                        <a href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
                                        <a href="javascript:;" class="u-cancel">取消</a>
                                    </div>
                                </div>
                            </div>
                            <div class="lr-more">
                                <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                            </div>
                        </dd>
                    </dl>
                                        <!-- 规格筛选 end -->
            <!-- 属性筛选 start -->
                            <dl class="brand-section m-tr">
                    <dt>风格</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/21_%E7%AE%80%E7%BA%A6%E7%8E%B0%E4%BB%A3" data-href="/index.php/home/Goods/goodsList/id/30/attr/21_%E7%AE%80%E7%BA%A6%E7%8E%B0%E4%BB%A3" data-val='简约现代' data-key='21'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >简约现代</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/21_%E7%94%B0%E5%9B%AD" data-href="/index.php/home/Goods/goodsList/id/30/attr/21_%E7%94%B0%E5%9B%AD" data-val='田园' data-key='21'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >田园</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/21_%E5%8C%97%E6%AC%A7%E9%A3%8E%E6%A0%BC" data-href="/index.php/home/Goods/goodsList/id/30/attr/21_%E5%8C%97%E6%AC%A7%E9%A3%8E%E6%A0%BC" data-val='北欧风格' data-key='21'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >北欧风格</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/21_%E6%96%B0%E5%8F%A4%E5%85%B8" data-href="/index.php/home/Goods/goodsList/id/30/attr/21_%E6%96%B0%E5%8F%A4%E5%85%B8" data-val='新古典' data-key='21'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >新古典</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>填充物:</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/20_%E6%B0%B4%E6%99%B6" data-href="/index.php/home/Goods/goodsList/id/30/attr/20_%E6%B0%B4%E6%99%B6" data-val='水晶' data-key='20'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >水晶</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/20_%E5%90%B8%E5%B0%98" data-href="/index.php/home/Goods/goodsList/id/30/attr/20_%E5%90%B8%E5%B0%98" data-val='吸尘' data-key='20'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >吸尘</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/20_%E6%B0%B4%E6%B4%97" data-href="/index.php/home/Goods/goodsList/id/30/attr/20_%E6%B0%B4%E6%B4%97" data-val='水洗' data-key='20'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >水洗</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/20_%E5%85%B6%E4%BB%96%2Fothe" data-href="/index.php/home/Goods/goodsList/id/30/attr/20_%E5%85%B6%E4%BB%96%2Fothe" data-val='其他/othe' data-key='20'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >其他/othe</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>摆件类型</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/19_%E5%8D%8A%E6%89%8B%E5%B7%A5" data-href="/index.php/home/Goods/goodsList/id/30/attr/19_%E5%8D%8A%E6%89%8B%E5%B7%A5" data-val='半手工' data-key='19'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >半手工</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/19_%E6%89%8B%E5%B7%A5%E7%BB%87%E9%80%A0" data-href="/index.php/home/Goods/goodsList/id/30/attr/19_%E6%89%8B%E5%B7%A5%E7%BB%87%E9%80%A0" data-val='手工织造' data-key='19'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >手工织造</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/19_%E9%9D%A0%E5%9E%AB%E5%A5%97%2F%E6%8A%B1%E6%9E%95%E5%A5%97" data-href="/index.php/home/Goods/goodsList/id/30/attr/19_%E9%9D%A0%E5%9E%AB%E5%A5%97%2F%E6%8A%B1%E6%9E%95%E5%A5%97" data-val='靠垫套/抱枕套' data-key='19'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >靠垫套/抱枕套</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/19_%E6%A1%8C%E9%9D%A2%E6%91%86%E4%BB%B6" data-href="/index.php/home/Goods/goodsList/id/30/attr/19_%E6%A1%8C%E9%9D%A2%E6%91%86%E4%BB%B6" data-val='桌面摆件' data-key='19'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >桌面摆件</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>适用空间</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/18_%E9%9D%99%E7%89%A9" data-href="/index.php/home/Goods/goodsList/id/30/attr/18_%E9%9D%99%E7%89%A9" data-val='静物' data-key='18'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >静物</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/18_%E5%AE%A2%E5%8E%85+%E9%97%A8%E5%8E%85+%E5%8D%A7%E5%AE%A4" data-href="/index.php/home/Goods/goodsList/id/30/attr/18_%E5%AE%A2%E5%8E%85+%E9%97%A8%E5%8E%85+%E5%8D%A7%E5%AE%A4" data-val='客厅 门厅 卧室' data-key='18'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >客厅 门厅 卧室</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/18_%E5%AE%A2%E5%8E%85+%E5%8D%A7%E5%AE%A4+%E6%A1%8C%E9%9D%A2+%E5%BA%8A%E5%A4%B4+%E5%B1%85%E5%AE%B6......" data-href="/index.php/home/Goods/goodsList/id/30/attr/18_%E5%AE%A2%E5%8E%85+%E5%8D%A7%E5%AE%A4+%E6%A1%8C%E9%9D%A2+%E5%BA%8A%E5%A4%B4+%E5%B1%85%E5%AE%B6......" data-val='客厅 卧室 桌面 床头 居家......' data-key='18'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >客厅 卧室 桌面 床头 居家......</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/18_%E7%8E%84%E5%85%B3%E7%94%B5%E8%A7%86%E6%9F%9C%E9%85%92%E9%AC%BC%E4%B9%A6%E6%88%BF%E4%B9%A6%E6%9F%9C" data-href="/index.php/home/Goods/goodsList/id/30/attr/18_%E7%8E%84%E5%85%B3%E7%94%B5%E8%A7%86%E6%9F%9C%E9%85%92%E9%AC%BC%E4%B9%A6%E6%88%BF%E4%B9%A6%E6%9F%9C" data-val='玄关电视柜酒鬼书房书柜' data-key='18'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >玄关电视柜酒鬼书房书柜</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>品牌</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/17_%E9%82%B8%E9%AB%98" data-href="/index.php/home/Goods/goodsList/id/30/attr/17_%E9%82%B8%E9%AB%98" data-val='邸高' data-key='17'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >邸高</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/17_%E4%BC%98%E7%AB%8B" data-href="/index.php/home/Goods/goodsList/id/30/attr/17_%E4%BC%98%E7%AB%8B" data-val='优立' data-key='17'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >优立</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/17_FOUNDHOME" data-href="/index.php/home/Goods/goodsList/id/30/attr/17_FOUNDHOME" data-val='FOUNDHOME' data-key='17'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >FOUNDHOME</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/17_MISS+LAPIN" data-href="/index.php/home/Goods/goodsList/id/30/attr/17_MISS+LAPIN" data-val='MISS LAPIN' data-key='17'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >MISS LAPIN</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/17_%E7%B1%B3%E8%8E%B1%E5%AE%B6%E5%B1%85" data-href="/index.php/home/Goods/goodsList/id/30/attr/17_%E7%B1%B3%E8%8E%B1%E5%AE%B6%E5%B1%85" data-val='米莱家居' data-key='17'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >米莱家居</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>颜色分类</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%AB%98+%E4%B8%AD+%E7%9F%AE" data-href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%AB%98+%E4%B8%AD+%E7%9F%AE" data-val='高 中 矮' data-key='22'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >高 中 矮</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%BB%84%E9%BA%BB%E5%9D%90%E5%87%B3+%E6%96%B0%E6%AC%BE%E6%96%B9%E6%A0%BC+%E7%BB%8F%E5%85%B8%E6%AC%BE" data-href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%BB%84%E9%BA%BB%E5%9D%90%E5%87%B3+%E6%96%B0%E6%AC%BE%E6%96%B9%E6%A0%BC+%E7%BB%8F%E5%85%B8%E6%AC%BE" data-val='黄麻坐凳 新款方格 经典款' data-key='22'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >黄麻坐凳 新款方格 经典款</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%9B%A8%E7%BA%BF%EF%BC%88%E5%B0%8F%EF%BC%89+%E9%9B%A8%E7%BA%BF%EF%BC%88%E5%A4%A7%EF%BC%89" data-href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%9B%A8%E7%BA%BF%EF%BC%88%E5%B0%8F%EF%BC%89+%E9%9B%A8%E7%BA%BF%EF%BC%88%E5%A4%A7%EF%BC%89" data-val='雨线（小） 雨线（大）' data-key='22'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >雨线（小） 雨线（大）</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%87%91%E8%89%B2" data-href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%87%91%E8%89%B2" data-val='金色' data-key='22'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >金色</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%BB%91%E8%89%B2%E6%A1%86%E5%A4%A7%E5%8F%B7+%E9%BB%91%E8%89%B2%E6%A1%86%E5%B0%8F%E5%8F%B7+%E9%87%91%E8%89%B2%E6%A1%86%E5%A4%A7%E5%8F%B7+%E9%87%91%E8%89%B2%E6%A1%86%E5%B0%8F%E5%8F%B7" data-href="/index.php/home/Goods/goodsList/id/30/attr/22_%E9%BB%91%E8%89%B2%E6%A1%86%E5%A4%A7%E5%8F%B7+%E9%BB%91%E8%89%B2%E6%A1%86%E5%B0%8F%E5%8F%B7+%E9%87%91%E8%89%B2%E6%A1%86%E5%A4%A7%E5%8F%B7+%E9%87%91%E8%89%B2%E6%A1%86%E5%B0%8F%E5%8F%B7" data-val='黑色框大号 黑色框小号 金色框大号 金色框小号' data-key='22'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >黑色框大号 黑色框小号 金色框大号 金色框小号</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>售卖方式</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/23_%E6%8B%9B%E8%B4%A2" data-href="/index.php/home/Goods/goodsList/id/30/attr/23_%E6%8B%9B%E8%B4%A2" data-val='招财' data-key='23'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >招财</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/23_%E6%88%90%E5%93%81%E5%9C%B0%E6%AF%AF%EF%BC%88%E5%85%83%2F%E5%9D%97%EF%BC%89" data-href="/index.php/home/Goods/goodsList/id/30/attr/23_%E6%88%90%E5%93%81%E5%9C%B0%E6%AF%AF%EF%BC%88%E5%85%83%2F%E5%9D%97%EF%BC%89" data-val='成品地毯（元/块）' data-key='23'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >成品地毯（元/块）</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/23_%E6%88%90%E5%93%81%E8%8A%B1%E7%93%B6%EF%BC%88%E5%85%83%2F%E4%B8%AA%EF%BC%89" data-href="/index.php/home/Goods/goodsList/id/30/attr/23_%E6%88%90%E5%93%81%E8%8A%B1%E7%93%B6%EF%BC%88%E5%85%83%2F%E4%B8%AA%EF%BC%89" data-val='成品花瓶（元/个）' data-key='23'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >成品花瓶（元/个）</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>风格</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/43_%E5%8C%97%E6%AC%A7" data-href="/index.php/home/Goods/goodsList/id/30/attr/43_%E5%8C%97%E6%AC%A7" data-val='北欧' data-key='43'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >北欧</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>颜色分类</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/44_30cm-%E4%B8%89%E8%89%B2%E5%8F%98%E5%85%89-10W+40cm...." data-href="/index.php/home/Goods/goodsList/id/30/attr/44_30cm-%E4%B8%89%E8%89%B2%E5%8F%98%E5%85%89-10W+40cm...." data-val='30cm-三色变光-10W 40cm....' data-key='44'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >30cm-三色变光-10W 40cm....</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>智能类型</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/45_%E4%B8%8D%E6%94%AF%E6%8C%81%E6%99%BA%E8%83%BD" data-href="/index.php/home/Goods/goodsList/id/30/attr/45_%E4%B8%8D%E6%94%AF%E6%8C%81%E6%99%BA%E8%83%BD" data-val='不支持智能' data-key='45'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >不支持智能</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>灯头个数</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/46_1%E4%B8%AA" data-href="/index.php/home/Goods/goodsList/id/30/attr/46_1%E4%B8%AA" data-val='1个' data-key='46'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >1个</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>品牌</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/47_other%2F%E5%85%B6%E4%BB%96" data-href="/index.php/home/Goods/goodsList/id/30/attr/47_other%2F%E5%85%B6%E4%BB%96" data-val='other/其他' data-key='47'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >other/其他</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>产地</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/48_%E4%B8%AD%E5%9B%BD%E5%A4%A7%E9%99%86" data-href="/index.php/home/Goods/goodsList/id/30/attr/48_%E4%B8%AD%E5%9B%BD%E5%A4%A7%E9%99%86" data-val='中国大陆' data-key='48'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >中国大陆</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>电压</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/49_111V%7E240V%EF%BC%88%E5%90%AB%EF%BC%89" data-href="/index.php/home/Goods/goodsList/id/30/attr/49_111V%7E240V%EF%BC%88%E5%90%AB%EF%BC%89" data-val='111V~240V（含）' data-key='49'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >111V~240V（含）</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>是否智能操控</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/50_%E5%90%A6" data-href="/index.php/home/Goods/goodsList/id/30/attr/50_%E5%90%A6" data-val='否' data-key='50'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >否</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>吊灯类型</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/51_%E9%93%81%E8%89%BA%E5%90%8A%E7%81%AF" data-href="/index.php/home/Goods/goodsList/id/30/attr/51_%E9%93%81%E8%89%BA%E5%90%8A%E7%81%AF" data-val='铁艺吊灯' data-key='51'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >铁艺吊灯</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>型号</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/52_BS-DD42" data-href="/index.php/home/Goods/goodsList/id/30/attr/52_BS-DD42" data-val='BS-DD42' data-key='52'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >BS-DD42</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>适用空间</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/53_%E9%A4%90%E5%8E%85+%E4%B9%A6%E6%88%BF+%E5%8D%A7%E5%AE%A4" data-href="/index.php/home/Goods/goodsList/id/30/attr/53_%E9%A4%90%E5%8E%85+%E4%B9%A6%E6%88%BF+%E5%8D%A7%E5%AE%A4" data-val='餐厅 书房 卧室' data-key='53'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >餐厅 书房 卧室</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>灯具型状</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/54_%E6%9E%9D%E5%9E%8B%E5%90%8A%E7%81%AF" data-href="/index.php/home/Goods/goodsList/id/30/attr/54_%E6%9E%9D%E5%9E%8B%E5%90%8A%E7%81%AF" data-val='枝型吊灯' data-key='54'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >枝型吊灯</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>货号</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/57_DCR-01" data-href="/index.php/home/Goods/goodsList/id/30/attr/57_DCR-01" data-val='DCR-01' data-key='57'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >DCR-01</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/57_FHP182001X-1D" data-href="/index.php/home/Goods/goodsList/id/30/attr/57_FHP182001X-1D" data-val='FHP182001X-1D' data-key='57'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >FHP182001X-1D</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/57_ML180034-1" data-href="/index.php/home/Goods/goodsList/id/30/attr/57_ML180034-1" data-val='ML180034-1' data-key='57'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >ML180034-1</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>尺寸</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/8_%E5%A4%A7%E5%8F%B7%E5%B0%8F%E5%8F%B7" data-href="/index.php/home/Goods/goodsList/id/30/attr/8_%E5%A4%A7%E5%8F%B7%E5%B0%8F%E5%8F%B7" data-val='大号小号' data-key='8'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >大号小号</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/8_30x50%EF%BC%88%E5%90%AB%E8%8A%AF%EF%BC%89+30x50%EF%BC%88%E4%B8%8D%E5%90%AB%E8%8A%AF%EF%BC%89" data-href="/index.php/home/Goods/goodsList/id/30/attr/8_30x50%EF%BC%88%E5%90%AB%E8%8A%AF%EF%BC%89+30x50%EF%BC%88%E4%B8%8D%E5%90%AB%E8%8A%AF%EF%BC%89" data-val='30x50（含芯） 30x50（不含芯）' data-key='8'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >30x50（含芯） 30x50（不含芯）</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>材质</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/58_%E7%90%89%E7%92%83" data-href="/index.php/home/Goods/goodsList/id/30/attr/58_%E7%90%89%E7%92%83" data-val='琉璃' data-key='58'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >琉璃</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/58_%E9%87%91%E5%B1%9E" data-href="/index.php/home/Goods/goodsList/id/30/attr/58_%E9%87%91%E5%B1%9E" data-val='金属' data-key='58'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >金属</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>品牌</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/59_%E4%B8%89%E5%94%90%E4%B8%80%E5%93%81" data-href="/index.php/home/Goods/goodsList/id/30/attr/59_%E4%B8%89%E5%94%90%E4%B8%80%E5%93%81" data-val='三唐一品' data-key='59'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >三唐一品</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/59_%E4%BC%98%E9%A5%B0" data-href="/index.php/home/Goods/goodsList/id/30/attr/59_%E4%BC%98%E9%A5%B0" data-val='优饰' data-key='59'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >优饰</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/59_COLYOULIF" data-href="/index.php/home/Goods/goodsList/id/30/attr/59_COLYOULIF" data-val='COLYOULIF' data-key='59'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >COLYOULIF</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/59_%E5%88%9B%E9%A5%B0%E7%BA%AA" data-href="/index.php/home/Goods/goodsList/id/30/attr/59_%E5%88%9B%E9%A5%B0%E7%BA%AA" data-val='创饰纪' data-key='59'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >创饰纪</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>功能</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/60_%E6%8B%9B%E8%B4%A2" data-href="/index.php/home/Goods/goodsList/id/30/attr/60_%E6%8B%9B%E8%B4%A2" data-val='招财' data-key='60'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >招财</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>风格</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/62_%E6%96%B0%E4%B8%AD%E5%BC%8F" data-href="/index.php/home/Goods/goodsList/id/30/attr/62_%E6%96%B0%E4%B8%AD%E5%BC%8F" data-val='新中式' data-key='62'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >新中式</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/62_%E7%AE%80%E7%BA%A6%E7%8E%B0%E4%BB%A3" data-href="/index.php/home/Goods/goodsList/id/30/attr/62_%E7%AE%80%E7%BA%A6%E7%8E%B0%E4%BB%A3" data-val='简约现代' data-key='62'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >简约现代</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>颜色分类:</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/64_%E5%B0%8F%E5%8F%B7+%E4%B8%AD%E5%8F%B7+%E5%A4%A7%E5%8F%B7" data-href="/index.php/home/Goods/goodsList/id/30/attr/64_%E5%B0%8F%E5%8F%B7+%E4%B8%AD%E5%8F%B7+%E5%A4%A7%E5%8F%B7" data-val='小号 中号 大号' data-key='64'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >小号 中号 大号</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/64_%E9%87%91%E8%89%B2+%E9%BB%91%E7%82%B9%E9%87%91" data-href="/index.php/home/Goods/goodsList/id/30/attr/64_%E9%87%91%E8%89%B2+%E9%BB%91%E7%82%B9%E9%87%91" data-val='金色 黑点金' data-key='64'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >金色 黑点金</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/64_%E7%99%BD%E8%89%B2+%E7%B2%89%E7%BA%A2%E8%89%B2+%E6%B5%85%E7%BB%BF%E8%89%B2+%E6%B5%85%E8%93%9D%E8%89%B2" data-href="/index.php/home/Goods/goodsList/id/30/attr/64_%E7%99%BD%E8%89%B2+%E7%B2%89%E7%BA%A2%E8%89%B2+%E6%B5%85%E7%BB%BF%E8%89%B2+%E6%B5%85%E8%93%9D%E8%89%B2" data-val='白色 粉红色 浅绿色 浅蓝色' data-key='64'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >白色 粉红色 浅绿色 浅蓝色</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>货号:</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/66_STYP-ZC8259" data-href="/index.php/home/Goods/goodsList/id/30/attr/66_STYP-ZC8259" data-val='STYP-ZC8259' data-key='66'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >STYP-ZC8259</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/66_YS-12" data-href="/index.php/home/Goods/goodsList/id/30/attr/66_YS-12" data-val='YS-12' data-key='66'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >YS-12</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/66_oy1690" data-href="/index.php/home/Goods/goodsList/id/30/attr/66_oy1690" data-val='oy1690' data-key='66'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >oy1690</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>适用对象</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/1_%E9%9D%92%E5%B9%B4" data-href="/index.php/home/Goods/goodsList/id/30/attr/1_%E9%9D%92%E5%B9%B4" data-val='青年' data-key='1'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >青年</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>裤门襟:</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/4_%E8%A3%A4%E9%97%A8%E8%A5%9F%3A+%E7%9A%AE%E7%AD%8B" data-href="/index.php/home/Goods/goodsList/id/30/attr/4_%E8%A3%A4%E9%97%A8%E8%A5%9F%3A+%E7%9A%AE%E7%AD%8B" data-val='裤门襟: 皮筋' data-key='4'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >裤门襟: 皮筋</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>裤长</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/25_%E4%B9%9D%E5%88%86%E8%A3%A4" data-href="/index.php/home/Goods/goodsList/id/30/attr/25_%E4%B9%9D%E5%88%86%E8%A3%A4" data-val='九分裤' data-key='25'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >九分裤</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>尺码</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/27_S+M+L+XL+XXL" data-href="/index.php/home/Goods/goodsList/id/30/attr/27_S+M+L+XL+XXL" data-val='S M L XL XXL' data-key='27'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >S M L XL XXL</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>图案</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/33_%E6%9D%A1%E7%BA%B9" data-href="/index.php/home/Goods/goodsList/id/30/attr/33_%E6%9D%A1%E7%BA%B9" data-val='条纹' data-key='33'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >条纹</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>颜色分类</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/35_%E7%B2%89%E7%81%B0%E6%9D%A1%E7%BA%B9%E5%8E%9A%E6%AC%BE%E5%A5%B3%E4%B9%9D%E5%88%86%E8%A3%A4" data-href="/index.php/home/Goods/goodsList/id/30/attr/35_%E7%B2%89%E7%81%B0%E6%9D%A1%E7%BA%B9%E5%8E%9A%E6%AC%BE%E5%A5%B3%E4%B9%9D%E5%88%86%E8%A3%A4" data-val='粉灰条纹厚款女九分裤' data-key='35'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >粉灰条纹厚款女九分裤</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>款式</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/38_%E5%85%B6%E4%BB%96%2Fother" data-href="/index.php/home/Goods/goodsList/id/30/attr/38_%E5%85%B6%E4%BB%96%2Fother" data-val='其他/other' data-key='38'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >其他/other</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>厚薄</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/39_%E5%8A%A0%E5%8E%9A" data-href="/index.php/home/Goods/goodsList/id/30/attr/39_%E5%8A%A0%E5%8E%9A" data-val='加厚' data-key='39'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >加厚</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>工艺</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/69_%E5%8D%8A%E6%89%8B%E5%B7%A5" data-href="/index.php/home/Goods/goodsList/id/30/attr/69_%E5%8D%8A%E6%89%8B%E5%B7%A5" data-val='半手工' data-key='69'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >半手工</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>功能</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/70_%E6%8B%9B%E8%B4%A2" data-href="/index.php/home/Goods/goodsList/id/30/attr/70_%E6%8B%9B%E8%B4%A2" data-val='招财' data-key='70'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >招财</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>材质</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/61_%E5%90%88%E9%87%91" data-href="/index.php/home/Goods/goodsList/id/30/attr/61_%E5%90%88%E9%87%91" data-val='合金' data-key='61'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >合金</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/61_%E9%99%B6%E7%93%B7" data-href="/index.php/home/Goods/goodsList/id/30/attr/61_%E9%99%B6%E7%93%B7" data-val='陶瓷' data-key='61'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >陶瓷</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>适用空间</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/63_%E5%AE%A2%E5%8E%85" data-href="/index.php/home/Goods/goodsList/id/30/attr/63_%E5%AE%A2%E5%8E%85" data-val='客厅' data-key='63'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >客厅</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/63_%E5%AE%A2%E5%8E%85%E9%A4%90%E5%8E%85%E6%A1%8C%E9%9D%A2%E8%8C%B6%E5%87%A0+%E6%91%86%E6%94%BE" data-href="/index.php/home/Goods/goodsList/id/30/attr/63_%E5%AE%A2%E5%8E%85%E9%A4%90%E5%8E%85%E6%A1%8C%E9%9D%A2%E8%8C%B6%E5%87%A0+%E6%91%86%E6%94%BE" data-val='客厅餐厅桌面茶几 摆放' data-key='63'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >客厅餐厅桌面茶几 摆放</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>工艺</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/65_%E7%BA%AF%E6%89%8B%E5%B7%A5" data-href="/index.php/home/Goods/goodsList/id/30/attr/65_%E7%BA%AF%E6%89%8B%E5%B7%A5" data-val='纯手工' data-key='65'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >纯手工</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>外观造型</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/67_%E5%85%B6%E4%BB%96" data-href="/index.php/home/Goods/goodsList/id/30/attr/67_%E5%85%B6%E4%BB%96" data-val='其他' data-key='67'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >其他</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>摆件类型</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/68_%E8%90%BD%E5%9C%B0%E6%91%86%E4%BB%B6" data-href="/index.php/home/Goods/goodsList/id/30/attr/68_%E8%90%BD%E5%9C%B0%E6%91%86%E4%BB%B6" data-val='落地摆件' data-key='68'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >落地摆件</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>适用场景</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/72_%E5%85%B6%E5%AE%83" data-href="/index.php/home/Goods/goodsList/id/30/attr/72_%E5%85%B6%E5%AE%83" data-val='其它' data-key='72'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >其它</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/72_%E5%AE%A2%E5%8E%85+%E5%8D%A7%E5%AE%A4+%E6%A1%8C%E9%9D%A2+%E5%BA%8A%E5%A4%B4+%E5%B1%85%E5%AE%B6+%E5%85%AC" data-href="/index.php/home/Goods/goodsList/id/30/attr/72_%E5%AE%A2%E5%8E%85+%E5%8D%A7%E5%AE%A4+%E6%A1%8C%E9%9D%A2+%E5%BA%8A%E5%A4%B4+%E5%B1%85%E5%AE%B6+%E5%85%AC" data-val='客厅 卧室 桌面 床头 居家 公' data-key='72'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >客厅 卧室 桌面 床头 居家 公</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>是否可定制</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/71_%E6%98%AF" data-href="/index.php/home/Goods/goodsList/id/30/attr/71_%E6%98%AF" data-val='是' data-key='71'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >是</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>组合形式</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/73_%E8%8A%B1%E5%8D%89+%E8%8A%B1%E5%8D%89%2B%E8%8A%B1%E7%93%B6" data-href="/index.php/home/Goods/goodsList/id/30/attr/73_%E8%8A%B1%E5%8D%89+%E8%8A%B1%E5%8D%89%2B%E8%8A%B1%E7%93%B6" data-val='花卉 花卉+花瓶' data-key='73'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >花卉 花卉+花瓶</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>仿真花类型</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/74_%E5%AE%B6%E5%B1%85%E6%A0%B7%E6%9D%BF%E6%88%BF%E8%BD%AF%E8%A3%85%E8%8A%B1%E8%89%BA" data-href="/index.php/home/Goods/goodsList/id/30/attr/74_%E5%AE%B6%E5%B1%85%E6%A0%B7%E6%9D%BF%E6%88%BF%E8%BD%AF%E8%A3%85%E8%8A%B1%E8%89%BA" data-val='家居样板房软装花艺' data-key='74'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >家居样板房软装花艺</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                            <dl class="brand-section m-tr">
                    <dt>花器种类</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/attr/119_%E5%8F%B0%E9%9D%A2%E8%8A%B1%E7%93%B6" data-href="/index.php/home/Goods/goodsList/id/30/attr/119_%E5%8F%B0%E9%9D%A2%E8%8A%B1%E7%93%B6" data-val='台面花瓶' data-key='119'>
                                            <input class="shaix_la" type="checkbox" style="display: none"/>
                                            <span >台面花瓶</span>
                                        </a>
                                                                    </div>
                                <div class="surclofix p">
                                    <a href="javascript:;" class="u-confirm"  onClick="submitMoreFilter('attr',this);">确定</a>
                                    <a href="javascript:;" class="u-cancel">取消</a>
                                </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>
                                                    </div>
                    </dd>
                </dl>
                        <!-- 属性筛选 end -->
            <!-- 价格筛选 start -->
                            <dl class="brand-section m-tr">
                    <dt>价格</dt>
                    <dd class="ri-section">
                        <div class="lf-list">
                            <div class="brand-list">
                                <div class="clearfix p">
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/price/0-620">
                                            <span>620元以下</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/price/620-1240">
                                            <span>620-1240元</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/price/1240-1860">
                                            <span>1240-1860元</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/price/1860-2480">
                                            <span>1860-2480元</span>
                                        </a>
                                                                            <a href="/index.php/home/Goods/goodsList/id/30/price/2480-3100">
                                            <span>2480-3100元</span>
                                        </a>
                                                                    </div>
                            </div>
                        </div>
                        <div class="lr-more">
                            <!--<a href="javascript:void(0)"><span class="dx_choice">多选</span><i class="litt-pluscr"></i></a>-->
                            <!--<a href="javascript:void(0)"><span class="gd_more">更多</span><i class="litt-tcr"></i></a>-->
                            <!--填写筛选价格区间-s-->
                            <form action="/index.php/Home/Goods/goodsList/id/30" method="post" id="price_form">
                                <input type="text" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" name="start_price" id="start_price" value=""/>
                                <span>-</span>
                                <input type="text" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')"  name="end_price" id="end_price" value=""/>
                                <input type="submit" value="确定" onClick="if($('#start_price').val() !='' && $('#end_price').val() !='' ) $('#price_form').submit();"/>
                            </form>
                            <!--填写筛选价格区间-e-->
                        </div>
                    </dd>
                </dl>
                        <!-- 价格筛选 end -->
        </div>
        <p class="moreamore"><a >浏览更多</a></p>
    </div>
</div>
<!-- 筛选 end -->


<div class="shop-list-tour ma-to-20 p">
	<div class="w1430">
		<div class="tjhot fl">
			<div class="sx_topb"><h3>推荐热卖</h3></div>
			<div class="tjhot-shoplist" id="ajax_hot_goods">
                                    <div class="alone-shop">
                        <a href="/index.php/Home/Goods/goodsInfo/id/1" target="_blank"><img class="lazy" data-original="/public/upload/goods/thumb/1/goods_thumb_1_0_180_180.png"/></a>
                        <p class="line-two-hidd"><a href="/index.php/Home/Goods/goodsInfo/id/1">TPshop vivoX21 6GB+128GB 4G全网通 全面屏 拍照手机</a></p>
                        <p class="price-tag"><span class="li_xfo">￥</span><span>2999.00</span></p>
                    </div>
                                    <div class="alone-shop">
                        <a href="/index.php/Home/Goods/goodsInfo/id/7" target="_blank"><img class="lazy" data-original="/public/upload/goods/thumb/7/goods_thumb_7_0_180_180.jpeg"/></a>
                        <p class="line-two-hidd"><a href="/index.php/Home/Goods/goodsInfo/id/7">TPshop 哥弟女装2018春季新款口袋趣味图案贴标连帽针织长开衫A400065 连帽设计 实穿美观</a></p>
                        <p class="price-tag"><span class="li_xfo">￥</span><span>480.00</span></p>
                    </div>
                                    <div class="alone-shop">
                        <a href="/index.php/Home/Goods/goodsInfo/id/12" target="_blank"><img class="lazy" data-original="/public/upload/goods/thumb/12/goods_thumb_12_0_180_180.png"/></a>
                        <p class="line-two-hidd"><a href="/index.php/Home/Goods/goodsInfo/id/12">TPshop 所有失去的都会以另一种方式归来</a></p>
                        <p class="price-tag"><span class="li_xfo">￥</span><span>20.00</span></p>
                    </div>
                                    <div class="alone-shop">
                        <a href="/index.php/Home/Goods/goodsInfo/id/8" target="_blank"><img class="lazy" data-original="/public/upload/goods/thumb/8/goods_thumb_8_0_180_180.png"/></a>
                        <p class="line-two-hidd"><a href="/index.php/Home/Goods/goodsInfo/id/8">TPshop 迪芙斯（D:FUSE）女鞋 牛皮革细跟露趾性感高跟鞋</a></p>
                        <p class="price-tag"><span class="li_xfo">￥</span><span>179.00</span></p>
                    </div>
                                    <div class="alone-shop">
                        <a href="/index.php/Home/Goods/goodsInfo/id/9" target="_blank"><img class="lazy" data-original="/public/upload/goods/thumb/9/goods_thumb_9_0_180_180.png"/></a>
                        <p class="line-two-hidd"><a href="/index.php/Home/Goods/goodsInfo/id/9">TPshop 东方泥土 陶瓷艺术品招财摆件 客厅办公室/烈焰釉大貔貅D69-39 D69-39 大貔貅 烈焰釉</a></p>
                        <p class="price-tag"><span class="li_xfo">￥</span><span>1580.00</span></p>
                    </div>
                			</div>
		</div>
		<div class="stsho fr">
			<div class="sx_topb ba-dark-bg">
				<div class="f-sort fl">
					<ul>
						<li class="red">
                            <a href="/index.php/Home/Goods/goodsList/id/30">综合</a>
                        </li>
						<li class="">
                            <a href="/index.php/Home/Goods/goodsList/id/30/sort/sales_sum">销量</a>
                        </li>
													<li class="">
                                <a href="/index.php/Home/Goods/goodsList/id/30/sort/shop_price/sort_asc/desc">价格<i class="litt-zzx1"></i></a>
                            </li>
												<li class="">
                            <a href="/index.php/Home/Goods/goodsList/id/30/sort/comment_count">评论</a>
                        </li>
						<li class="">
                            <a href="/index.php/Home/Goods/goodsList/id/30/sort/is_new">新品</a>
                        </li>
					</ul>
				</div>
				<div class="f-address fl">
					<!--<div class="shd_address">-->
						<!--<div class="shd">收货地：</div>-->
						<!--<div class="add_cj_p"><input type="text" id="city" /></div>-->
					<!--</div>-->
				</div>
				<div class="f-total fr">
										<div class="all-sec">共<span>33</span>个商品</div>
					<div class="all-fy">
						<a >&lt;</a>
							<p class="fy-y"><span class="z-cur">1</span>/<span>2</span></p>
						<a href="/index.php/Home/Goods/goodsList/id/30/p/2" >&gt;</a>
					</div>
				</div>
			</div>
			<div class="shop-list-splb p">
				<ul>
                                                <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/4" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/4/goods_thumb_4_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/4/goods_sub_thumb_14_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/4/goods_sub_thumb_15_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/4/goods_sub_thumb_16_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>169.00</em></span>
                                        <span class="old"><em>￥</em><em>269.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/4">
                                            TPshop 苏泊尔28cm玻璃盖火红点煎锅平底锅无油烟不粘电磁炉通用PJ28K4                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_4" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(4);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(4);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(4,$('#number_'+4).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/26" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/26/goods_thumb_26_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/26/goods_sub_thumb_107_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/26/goods_sub_thumb_108_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/26/goods_sub_thumb_109_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/26/goods_sub_thumb_110_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/26/goods_sub_thumb_111_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/26/goods_sub_thumb_112_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>3099.00</em></span>
                                        <span class="old"><em>￥</em><em>3599.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/26">
                                            TPshop 雷士（NVC） 【满1699减550】雷士照明 简约欧式吊灯壁灯 客厅卧室餐厅走廊吊灯壁灯                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_26" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(26);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(26);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(26,$('#number_'+26).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/44" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/44/goods_thumb_44_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/44/goods_sub_thumb_169_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/44/goods_sub_thumb_170_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/44/goods_sub_thumb_171_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/44/goods_sub_thumb_172_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/44/goods_sub_thumb_173_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/44/goods_sub_thumb_174_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>999.00</em></span>
                                        <span class="old"><em>￥</em><em>1599.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/44">
                                            TPshop LUOLAI罗莱家纺 229纱支纯棉四件套 全棉床上用品床品套件床单被罩 WD5006晨暮间 220*250                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_44" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(44);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(44);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(44,$('#number_'+44).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/41" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/41/goods_thumb_41_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/41/goods_sub_thumb_160_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/41/goods_sub_thumb_161_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/41/goods_sub_thumb_162_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/41/goods_sub_thumb_163_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>59.00</em></span>
                                        <span class="old"><em>￥</em><em>79.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/41">
                                            TPshop 天堂伞 全遮光黑胶转印宇宙星空三折晴雨伞太阳伞                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_41" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(41);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(41);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(41,$('#number_'+41).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/46" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/46/goods_thumb_46_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/46/goods_sub_thumb_177_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/46/goods_sub_thumb_178_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/46/goods_sub_thumb_179_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/46/goods_sub_thumb_180_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/46/goods_sub_thumb_181_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/46/goods_sub_thumb_182_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>1699.00</em></span>
                                        <span class="old"><em>￥</em><em>2898.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/46">
                                            TPshop 雅琼 沙发北欧布艺沙发可拆洗小户型三人位懒人沙发现代简约客厅家具A68 两件套（备注颜色） 海绵坐垫（送地毯）                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_46" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(46);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(46);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(46,$('#number_'+46).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/47" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/47/goods_thumb_47_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/47/goods_sub_thumb_183_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/47/goods_sub_thumb_184_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/47/goods_sub_thumb_185_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/47/goods_sub_thumb_186_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/47/goods_sub_thumb_187_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/47/goods_sub_thumb_188_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>208.00</em></span>
                                        <span class="old"><em>￥</em><em>266.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/47">
                                            TPshop 巢里巢外 电表箱装饰画推拉式 配电箱定制液压翻盖画 餐厅现代简约客厅装饰画 简约花卉                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_47" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(47);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(47);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(47,$('#number_'+47).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/262" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/262/goods_thumb_262_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/262/goods_sub_thumb_1019_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/262/goods_sub_thumb_1020_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/262/goods_sub_thumb_1021_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/262/goods_sub_thumb_1022_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/262/goods_sub_thumb_1023_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/262/goods_sub_thumb_1024_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>159.00</em></span>
                                        <span class="old"><em>￥</em><em>199.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/262">
                                            TPshop 纯棉床品四件套1.8米床笠简约床单全棉儿童1.2米三件套2.0米被套                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_262" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(262);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(262);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(262,$('#number_'+262).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/194" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/194/goods_thumb_194_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/194/goods_sub_thumb_766_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/194/goods_sub_thumb_765_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/194/goods_sub_thumb_764_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/194/goods_sub_thumb_763_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/194/goods_sub_thumb_767_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/194/goods_sub_thumb_768_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>2000.00</em></span>
                                        <span class="old"><em>￥</em><em>2699.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/194">
                                            TPshop 尚品宅配橱柜定制 欧式风格整体橱柜智能厨柜定制多功能橱柜厨房定做 石英石台面厨柜                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_194" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(194);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(194);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(194,$('#number_'+194).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/195" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/195/goods_thumb_195_0_236_236.png"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/195/goods_sub_thumb_769_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/195/goods_sub_thumb_770_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/195/goods_sub_thumb_771_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>209.00</em></span>
                                        <span class="old"><em>￥</em><em>236.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/195">
                                            TPshop 贝尔（BBL） 贝尔地板 实木 地板 东南亚进口 番龙眼 18mm家用环保 红枫古道 深色                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_195" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(195);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(195);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(195,$('#number_'+195).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/249" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/249/goods_thumb_249_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/249/goods_sub_thumb_1367_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/249/goods_sub_thumb_1366_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/249/goods_sub_thumb_1365_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/249/goods_sub_thumb_1368_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/249/goods_sub_thumb_1369_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/249/goods_sub_thumb_1370_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>119.00</em></span>
                                        <span class="old"><em>￥</em><em>298.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/249">
                                            TPshop [正常发货]南极人家纺蚊帐免安装钢丝蒙古包纹帐多开门可折叠拉链帐子大方顶防蚊1.5m 1.8m 小时代-月光蓝                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_249" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(249);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(249);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(249,$('#number_'+249).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/267" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/267/goods_thumb_267_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1047_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1048_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1049_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1050_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1051_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1052_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/267/goods_sub_thumb_1053_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>134.00</em></span>
                                        <span class="old"><em>￥</em><em>165.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/267">
                                            TPshop 夏季冰丝四件套欧式真丝床单丝滑裸睡天丝绸被套夏凉床上用品床笠                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_267" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(267);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(267);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(267,$('#number_'+267).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/268" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/268/goods_thumb_268_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/268/goods_sub_thumb_1054_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/268/goods_sub_thumb_1055_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/268/goods_sub_thumb_1056_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/268/goods_sub_thumb_1057_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/268/goods_sub_thumb_1058_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>125.00</em></span>
                                        <span class="old"><em>￥</em><em>134.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/268">
                                            TPshop ins风简约网红全棉四件套纯棉床单被套女1.5m1.8米床上用品三件套                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_268" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(268);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(268);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(268,$('#number_'+268).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/283" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/283/goods_thumb_283_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/283/goods_sub_thumb_1138_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/283/goods_sub_thumb_1139_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/283/goods_sub_thumb_1140_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/283/goods_sub_thumb_1141_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/283/goods_sub_thumb_1142_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/283/goods_sub_thumb_1143_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>98.00</em></span>
                                        <span class="old"><em>￥</em><em>187.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/283">
                                            TPshop SAUMUR鸟笼软装饰品黄铜金属简欧式家居客厅轻奢美式样板房间摆件                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_283" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(283);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(283);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(283,$('#number_'+283).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/284" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/284/goods_thumb_284_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/284/goods_sub_thumb_1144_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/284/goods_sub_thumb_1145_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/284/goods_sub_thumb_1146_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/284/goods_sub_thumb_1147_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/284/goods_sub_thumb_1148_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/284/goods_sub_thumb_1149_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>56.00</em></span>
                                        <span class="old"><em>￥</em><em>68.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/284">
                                            TPshop 北欧风格现代简约家居陶瓷创意水果摆件设 玄关客厅酒柜软装饰品                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_284" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(284);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(284);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(284,$('#number_'+284).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/286" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/286/goods_thumb_286_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/286/goods_sub_thumb_1156_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/286/goods_sub_thumb_1157_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/286/goods_sub_thumb_1158_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/286/goods_sub_thumb_1159_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/286/goods_sub_thumb_1160_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/286/goods_sub_thumb_1161_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>135.00</em></span>
                                        <span class="old"><em>￥</em><em>142.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/286">
                                            TPshop 铁艺花瓶摆件桌面装饰样板房家居摆件 客厅插花装饰干花花瓶                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_286" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(286);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(286);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(286,$('#number_'+286).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/287" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/287/goods_thumb_287_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1162_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1163_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1164_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1165_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1166_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1167_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1168_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/287/goods_sub_thumb_1169_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>235.00</em></span>
                                        <span class="old"><em>￥</em><em>248.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/287">
                                            TPshop 羊毛地毯客厅阳台飘窗垫窗台垫茶几毯床边毯卧室羊毛垫子地垫                                        </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_287" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(287);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(287);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(287,$('#number_'+287).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/288" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/288/goods_thumb_288_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/288/goods_sub_thumb_1170_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/288/goods_sub_thumb_1171_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>167.00</em></span>
                                        <span class="old"><em>￥</em><em>198.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/288">
                                            TPshop 定制 金色铁艺镂空无极花器花瓶花插摆件客厅书房软装家居饰品                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_288" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(288);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(288);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(288,$('#number_'+288).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/289" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/289/goods_thumb_289_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1172_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1173_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1174_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1175_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1176_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1177_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/289/goods_sub_thumb_1178_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>896.00</em></span>
                                        <span class="old"><em>￥</em><em>1209.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/289">
                                            TPshop 地毯客厅卧室满铺床边毯茶几地毯垫简约现代北欧风长方形欧式ins                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_289" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(289);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(289);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(289,$('#number_'+289).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/290" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/290/goods_thumb_290_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1179_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1180_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1181_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1182_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1183_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1184_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/290/goods_sub_thumb_1185_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>198.00</em></span>
                                        <span class="old"><em>￥</em><em>308.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/290">
                                            TPshop 定制 久忆手工缝边圆形剑麻地毯简约书房客厅茶几餐桌麻编转椅地垫定制                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_290" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(290);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(290);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(290,$('#number_'+290).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                                    <li>
                                <div class="s_xsall">
                                    <div class="xs_img">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/291" target="_blank">
                                            <img class="lazy-list" data-original="/public/upload/goods/thumb/291/goods_thumb_291_0_236_236.jpeg"/>
                                        </a>
                                    </div>
                                    <div class="xs_slide">
                                        <div class="small-xs-shop">
                                            <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/291/goods_sub_thumb_1186_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/291/goods_sub_thumb_1187_236_236.png"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/291/goods_sub_thumb_1188_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/291/goods_sub_thumb_1189_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/291/goods_sub_thumb_1190_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                            <li>
                                                            <a href="javascript:void(0);">
                                                                <img class="s-lazy-list" data-original="/public/upload/goods/thumb/291/goods_sub_thumb_1191_236_236.jpeg"/>
                                                            </a>
                                                        </li>
                                                                                                                                                </ul>
                                        </div>
                                    </div>
                                    <div class="price-tag">
                                        <span class="now"><em class="li_xfo">￥</em><em>78.00</em></span>
                                        <span class="old"><em>￥</em><em>98.00</em></span>
                                    </div>
                                    <div class="shop_name2">
                                        <a href="/index.php/Home/Goods/goodsInfo/id/291">
                                            TPshop 定制 加厚飘窗垫子窗台垫 现代简约卧室榻榻米亚麻防滑阳台坐垫定制                                         </a>
                                    </div>
                                    <div class="J_btn_statu">
                                        <div class="p-num">
                                            <input class="J_input_val" id="number_291" type="text" value="1">
                                            <p class="act">
                                                <a href="javascript:void(0);" onClick="goods_add(291);" class="litt-zzyl1"></a>
                                                <a href="javascript:void(0);" onClick="goods_cut(291);"  class="litt-zzyl2"></a>
                                            </p>
                                        </div>
                                        <div class="p-btn">
                                                                                            <a href="javascript:void(0);" onclick="AjaxAddCart(291,$('#number_'+291).val());">加入购物车</a>
                                                                                    </div>
                                    </div>
                                </div>
                            </li>
                                            
				</ul>
			</div>
			<div class="page p">
				<div class='dataTables_paginate paging_simple_numbers'><ul class='pagination'>  <li class="paginate_button active"><a tabindex="0" data-dt-idx="1" aria-controls="example1" href="#">1</a></li><li class="paginate_button"><a class="num" href="/index.php/home/Goods/goodsList/id/30/p/2">2</a></li> <li id="example1_next" class="paginate_button next"><a class="next" href="/index.php/home/Goods/goodsList/id/30/p/2">下一页</a></li> </ul></div>			</div>
		</div>
	</div>
</div>
<div class="underheader-floor p specilike">
	<div class="w1430">
		<div class="layout-title">
			<span class="fl">猜你喜欢</span>
            <span class="update_h fr" onclick="favourite();">
                <i class="litt-hyh"></i>
                换一换
            </span>
		</div>
		<ul class="ul-li-column p" id="favourite_goods">
		</ul>
	</div>
</div>
<script>
	/****猜你喜欢****/
	var favorite_page = 0;
	function favourite()
	{
		favorite_page++;
		$.ajax({
			type: "GET",
			url: "/index.php?m=Home&c=Index&a=ajax_favorite&i=7&p="+favorite_page,//+tab,
			success: function (data) {
			    console.log('test')
				if(data == '' && favorite_page > 1){
					favorite_page = 0;
					favourite();
				}else{
					$('#favourite_goods').html(data);
					lazy_ajax();
				}

			}
		});
	}
</script>
<!--footer-s-->
<div class="tpshop-service">
	<ul class="w1224 clearfix">
<!--		<li>-->
<!--			<i class="ico ico-day7">16</i>-->
<!--			<p class="service">16天无理由退货</p>-->
<!--		</li>-->
<!--		<li>-->
<!--			<i class="ico ico-day15">16</i>-->
<!--			<p class="service">16天免费换货</p>-->
<!--		</li>-->
<!--		<li>-->
<!--			<i class="ico ico-quality"></i>-->
<!--			<p class="service">正品行货 品质保障</p>-->
<!--		</li>-->
<!--		<li>-->
<!--			<i class="ico ico-service"></i>-->
<!--			<p class="service">专业售后服务</p>-->
<!--		</li>-->
			</ul>
</div>
<div class="footer">
	<div class="w1224 clearfix" style="padding-bottom: 10px;">
		<div class="left-help-list">
			<div class="help-list-wrap clearfix">
									<dl>
						<dt>新手上路</dt>
												<dd><a href="/index.php/Home/Article/detail/article_id/35">测试文章</a></dd>
												<dd><a href="/index.php/Home/Article/detail/article_id/36">[首届]四川海峡两岸摄影家大会新闻发布会发言稿</a></dd>
												<dd><a href="/index.php/Home/Article/detail/article_id/37">暨首届海峡两岸摄影家摄影展</a></dd>
											</dl>
									<dl>
						<dt>购物指南</dt>
											</dl>
									<dl>
						<dt>售后服务</dt>
											</dl>
									<dl>
						<dt>支付方式</dt>
											</dl>
									<dl>
						<dt>配送方式</dt>
											</dl>
							</div>
			<div class="friendship-links clearfix">
	            <span>友情链接 : </span>
                <div class="links-wrap-h clearfix">
                                    <a href="https://www.taobao.com"  >淘宝网</a>
                                    <a href="https://www.jd.com"  >京东</a>
                                    <a href="https://www.vip.com"  >唯品会</a>
                                    <a href="https://www.suning.com" target="_blank" >苏宁易购</a>
                                    <a href="https://www.baidu.com"  >百度</a>
                                    <a href="http://www.yhd.com"  >1号店</a>
                                    <a href="http://www.kxys.org.cn" target="_blank" >四川省科学养生促进会</a>
                                </div>
	        </div>	
		</div>
		<div class="right-contact-us">
			<h3 class="title">联系我们</h3>
			<span class="phone">0755-86140485</span>
			<p class="tips">周一至周日8:00-18:00<br />(仅收市话费)</p>
			<!--<div class="qr-code-list clearfix">-->
				<!--<a class="qr-code" href="javascript:;"><img class="w-100" src="/pc/rainbow/static/images/qrcode.png" alt="" /></a>-->
				<!--<a class="qr-code qr-code-tpshop" href="javascript:;"><img class="w-100" src="/pc/rainbow/static/images/qrcode.png" alt="" /></a>-->
			<!--</div>-->
		</div>
	</div>
    <div class="mod_copyright p">
        <div class="grid-top">
                        <!--<a href="javascript:void (0);">关于我们</a><span>|</span>-->
            <!--<a href="javascript:void (0);">联系我们</a><span>|</span>-->
            <!---->
        </div>
        <p>Copyright © 2016-2025 TPshop开源商城 版权所有 保留一切权利 备案号:<a href="http://www.beian.miit.gov.cn" >粤ICP备123456号</a></p>
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
<div class="soubao-sidebar">
    <div class="soubao-sidebar-bg"></div>
    <div class="sidertabs tab-lis-1">
        <div class="sider-top-stra sider-midd-1">
            <div class="icon-tabe-chan">
                <a href="/index.php/Home/User/index">
                    <i class="share-side share-side1"></i>
                    <i class="share-side tab-icon-tip triangleshow"></i>
                </a>

                <div class="dl_login">
                    <div class="hinihdk">
                        <img src="/pc/rainbow/static/images/dl.png"/>

                        <p class="loginafter nologin"><span>你好，请先</span><a href="/index.php/Home/user/login">登录！</a></p>
                        <!--未登录-e--->
                        <!--登录后-s--->
                        <p class="loginafter islogin">
                            <span class="id_jq userinfo">陈xxxxxxx</span>
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/index.php/Home/user/logout" title="点击退出">退出！</a>
                        </p>
                        <!--未登录-s--->
                    </div>
                </div>
            </div>
            <div class="icon-tabe-chan shop-car">
                <a href="javascript:void(0);" onclick="ajax_side_cart_list()">
                    <div class="tab-cart-tip-warp-box">
                        <div class="tab-cart-tip-warp">
                            <i class="share-side share-side1"></i>
                            <i class="share-side tab-icon-tip"></i>
                            <span class="tab-cart-tip">购物车</span>
                            <span class="tab-cart-num J_cart_total_num" id="tab_cart_num">0</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="icon-tabe-chan massage">
                <a href="/index.php/Home/User/message_notice" target="_blank">
                    <i class="share-side share-side1"></i>
                    <!--<i class="share-side tab-icon-tip"></i>-->
                    <span class="tab-tip">消息</span>
                </a>
            </div>
            <!-- 优惠券 -->
            <div style="display: none;" class="icon-tabe-chan couponlis_show">
                <i class="counput_i"></i>
            </div>
        </div>
        <div class="sider-top-stra sider-midd-2">
            <div class="icon-tabe-chan mmm">
                <a href="/index.php/Home/User/goods_collect" target="_blank">
                    <i class="share-side share-side1"></i>
                    <!--<i class="share-side tab-icon-tip"></i>-->
                    <span class="tab-tip">收藏</span>
                </a>
            </div>
            <div class="icon-tabe-chan hostry">
                <a href="/index.php/Home/User/visit_log" target="_blank">
                    <i class="share-side share-side1"></i>
                    <!--<i class="share-side tab-icon-tip"></i>-->
                    <span class="tab-tip">足迹</span>
                </a>
            </div>
            <!--<div class="icon-tabe-chan sign">-->
                <!--<a href="" target="_blank">-->
                    <!--<i class="share-side share-side1"></i>-->
                    <!--&lt;!&ndash;<i class="share-side tab-icon-tip"></i>&ndash;&gt;-->
                    <!--<span class="tab-tip">签到</span>-->
                <!--</a>-->
            <!--</div>-->
        </div>
    </div>
    <div class="sidertabs tab-lis-2">
        <div class="icon-tabe-chan advice">
            <a title="点击这里给我发消息" href="tencent://message/?uin=63316504424&amp;Site=搜豹网络商城&amp;Menu=yes" target="_blank">
                <i class="share-side share-side1"></i>
                <!--<i class="share-side tab-icon-tip"></i>-->
                <span class="tab-tip">在线咨询</span>
            </a>
        </div>
        <!--<div class="icon-tabe-chan request">-->
            <!--<a href="" target="_blank">-->
                <!--<i class="share-side share-side1"></i>-->
                <!--&lt;!&ndash;<i class="share-side tab-icon-tip"></i>&ndash;&gt;-->
                <!--<span class="tab-tip">意见反馈</span>-->
            <!--</a>-->
        <!--</div>-->
        <div class="icon-tabe-chan qrcode">
            <a href="" target="_blank">
                <i class="share-side share-side1"></i>
                <i class="share-side tab-icon-tip triangleshow"></i>
                <span class="tab-tip qrewm">
                    <img img-url="/index.php?m=Home&c=Index&a=qr_code&data=http://demo.tpshop3.com/index.php/Mobile/index/app_down&head_pic=http://demo.tpshop3.com/public/upload/logo/2018/04-09/814d7e9a0eddcf3754f2e8373a50a19c.png&back_img="/>
                    扫一扫下载APP
                </span>
            </a>
        </div>
        <div class="icon-tabe-chan comebacktop">
            <a href="" target="_blank">
                <i class="share-side share-side1"></i>
                <!--<i class="share-side tab-icon-tip"></i>-->
                <span class="tab-tip">返回顶部</span>
            </a>
        </div>
    </div>
    <div class="shop-car-sider">

    </div>

    <div class="fiexd_couponlist_1">
        <div class="h3">
            <b></b>优惠卷
            <div class="dax_1"><img src="/pc/rainbow/static/images/copute/dax_3.png" alt=""></div>
        </div>
        <p class="p_1"><b>可领取的券</b></p>
        <div class="list_1">
        </div>
        <p class="p_1"><b>已领取的券</b></p>
        <div class="list_1">
        </div>
    </div>
</div>
<script>
    // 判断导航条上的优惠券显示
    var widow_heft = window.location.href.indexOf('/Goods/goodsInfo');
    if(widow_heft !== -1){
        document.getElementsByClassName('couponlis_show')[0].style = 'block';
        $('.couponlis_show').show(); 
    }
    //优惠卷
    $('.couponlis_show').on('click',function(){
        if($('.fiexd_couponlist_1').is(':hidden')){
            $('.shop-car-sider').animate({left:'35px',opacity:'hide'},'normal',function(){
				$('.shop-car-sider').removeClass('sh-hi');
				$('.shop-car .tab-cart-tip-warp-box').css('background-color','');
				$('.shop-car .tab-icon-tip').removeClass('jsshow');
			});
            $('.fiexd_couponlist_1').stop().animate({right: '35px',opacity:'show'});
        }else{
            $('.fiexd_couponlist_1').stop().animate({right: '-280px',opacity:'hide'});
        }
    })
    $('.fiexd_couponlist_1').on('click','.dax_1',function(){
        $('.fiexd_couponlist_1').stop().animate({right: '-280px',opacity:'hide'},300);
    })
</script>
<script src="/pc/rainbow/static/js/common.js"></script>
<!--footer-e-->
<script src="/pc/rainbow/static/js/lazyload.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/pc/rainbow/static/js/popt.js" type="text/javascript" charset="utf-8"></script>
<script src="/pc/rainbow/static/js/headerfooter.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
//        更多
         $('.gd_more').parent().click(function(){
         	var jed = $(this).parents('.lr-more').siblings();
         	jed.toggleClass('ov-inh').find('.brand-box').toggleClass('ov-inh');
         	if(jed.toggleClass('ov-inh').find('.brand-list')){
         		jed.toggleClass('ov-inh').find('.brand-list').toggleClass('ov-inh');
         	}
         	if(jed.hasClass('ov-inh')){
         		$(this).find('.gd_more').html('收起');
         	}else{
         		$(this).find('.gd_more').html('更多');
         	}
         })
        var cancelBtn = null;
        /***多选 start*****/
        $('.dx_choice').parent().click(function(){
            var _this = this;
            var st = 0;
            var jed = $(_this).parents('.ri-section'); //当前选项层DIV

            //关闭前一个多选框
            if(cancelBtn != null){
                $(cancelBtn).parent().siblings('.clearfix').find('a').each(function(i,o){
                    $(o).removeClass('addhover-js').find('.litt-zd').hide(); //针对品牌筛选的红色边框和右下角对勾隐藏
                    $(o).removeClass('red_hov_cli')    //针对纯文字型选项，隐藏筛选的选中状态
                    .attr('href',$(o).data('href'))  //还原连接
                    .children('input').prop('checked',false).hide(); //隐藏多选框
                    $(o).unbind('click');
                });
                $(cancelBtn).parents('.lf-list').siblings('.lr-more').show(); //显示其它多选按钮
                $(cancelBtn).parents('.ri-section').removeClass('sum_ov_inh'); //移除多选样式

            }
            cancelBtn = jed.find('.u-cancel'); //前一个取消按钮

            //打开多选
            jed.addClass('sum_ov_inh'); //添加这一行的样式
            //遍历a标签
            jed.find('.clearfix>a').each(function(i,o){
                $(o).attr('href','javascript:;'); //移除连接
                $(o).children('input').show();  //显示多选框
                $(o).bind('click',function(){
                    if($(o).hasClass('red_hov_cli')){
                        //取消选中
                        $(o).find('i').toggle()
                        $(o).removeClass('addhover-js'); //针对品牌选项，改变筛选的选中状态
                        $(o).removeClass('red_hov_cli')
                        $(o).children('input').prop("checked",false);
                        $(o).parent().siblings('.surclofix').children('.u-confirm').removeClass('u-confirm01');
                        st--;
                    }else{
                        $(o).addClass('red_hov_cli')
                        $(o).children('input').prop("checked",true);
                        $(o).find('i').toggle()
                        $(o).addClass('addhover-js');
                        $(o).parent().siblings('.surclofix').children('.u-confirm').addClass('u-confirm01');
                        st++;
                    }
                    //如果没有选中项,确定按钮点不了
                    if(st==0){
                        jed.find('.u-confirm').disabled=true;
                    }
                });
            });
            //隐藏当前多选按钮
            $(_this).parent().hide();
        });

        /***多选 end*****/
        //############   取消多选        ###########
        $('.surclofix .u-cancel').each(function(){
            $(this).click(function(){
                var jed = $(this).parents('.ri-section');
                //遍历a标签
                jed.find('.clearfix>a').each(function(i,o){
                    $(o).removeClass('addhover-js').find('.litt-zd').hide(); //针对品牌筛选的红色边框和右下角对勾隐藏
                    $(o).removeClass('red_hov_cli')    //针对纯文字型选项，隐藏筛选的选中状态
                     .attr('href',$(o).data('href'))  //还原连接
                     .children('input').prop('checked',false).hide(); //隐藏多选框
                    $(o).unbind('click');
                });
                jed.find('.lr-more').show(); //显示多选按钮
                jed.removeClass('sum_ov_inh') //移除这一行的样式
                $('.u-confirm').removeClass('u-confirm01'); //移除确定按钮可点击标识
            });
        })

    $(function() {
        favourite();
        //左侧边栏JS
//		ajax_hot_goods();
//		ajax_sales_goods();
    //############   更多类别属性筛选  start     ###########
    $('.moreamore').click(function(){
        $('.m-tr').each(function(i,o){
            if(i >  7){
                var attrdisplay = $(o).css('display');
                if(attrdisplay == 'none'){
                    $(o).css('display','block');
                }
                if(attrdisplay == 'block'){
                    $(o).css('display','none');
                }
            }
        });
        if($(this).hasClass('checked')){
            $(this).removeClass('checked').html('<a class="red">收起</a>');
        }else{
            $(this).addClass('checked').html('<a >更多选项</a>');
        }
    })
    $('.moreamore').trigger('click').html('<a >更多选项</a>'); //  默认点击一下
    //############   更多类别属性筛选   end    ###########

    /***价格排序 start*****/
    var price_i = 0;
    $('.f-sort ul li').click(function(){
        $(this).addClass('red').siblings().removeClass('red').find('i').removeClass('litt-zzx2').removeClass('litt-zzx3').addClass('litt-zzx1');
        var jd = $(this).find('i');
        price_i++;
        if(price_i>2)price_i=1;
        switch(price_i){
            case 1:jd.addClass('litt-zzx2').removeClass('litt-zzx1').removeClass('litt-zzx3');break;
            case 2:jd.addClass('litt-zzx3').removeClass('litt-zzx2').removeClass('litt-zzx1');break;
        }
    })
    /***价格排序 end*******/
    /***地址选择 start*****/
    $("#city").click(function (e) {
        SelCity(this,e);
    });
    /***地址选择 end*****/
    /***是否自营 start****/
    $('.choice-mo-shop ul li').click(function(){
        $(this).find('.litt-zzdg1').toggleClass('litt-zzdg2');
        $(this).find('a').toggleClass('red');
    })
    /***是否自营 end****/
    /***滑过浏览商品 start***/
    $('.small-xs-shop ul li').hover(function(){
        $(this).addClass('bored').siblings().removeClass('bored');
        var small_src = $(this).find('img').attr('src');
        $(this).parents('.s_xsall').find('.xs_img').find('img').attr('src',small_src);
    },function(){

    })
    /***滑过浏览商品 end***/
})

	/****加减购物车数额***/
	function goods_cut($val){
		var num_val=document.getElementById('number_'+$val);
		var new_num=num_val.value;
		var Num = parseInt(new_num);
		if(Num>1)Num=Num-1;
		num_val.value=Num;
	}

	function goods_add($val){
		var num_val=document.getElementById('number_'+$val);
		var new_num=num_val.value;
		var Num = parseInt(new_num);
		Num=Num+1;
		num_val.value=Num;
	}
	/****加减购物车数额***/

        //############   点击多选确定按钮      ############
// t 为类型  是品牌 还是 规格 还是 属性
// btn 是点击的确定按钮用于找位置
get_parment = {"id":"30"};
function submitMoreFilter(t,btn)
{
    // 没有被勾选的时候
    if(!$(btn).hasClass("u-confirm01")){
        return false;
    }

    // 获取现有的get参数
    var key = ''; // 请求的 参数名称
    var val = new Array(); // 请求的参数值
    $(btn).parent().siblings(".clearfix").find(".red_hov_cli").each(function(i,o){
        key = $(o).data('key');
        val.push($(o).data('val'));
    });
    //parment = key+'_'+val.join('_');
//        return false;
    // 品牌
    if(t == 'brand')
    {
        get_parment.brand_id = val.join('_');
    }
    // 规格
    if(t == 'spec')
    {
        if(get_parment.hasOwnProperty('spec'))
        {
            get_parment.spec += '@'+key+'_'+val.join('_');
        }
        else
        {
            get_parment.spec = key+'_'+val.join('_');
        }
    }
    // 属性
    if(t == 'attr')
    {
        if(get_parment.hasOwnProperty('attr'))
        {
            get_parment.attr += '@'+key+'_'+val.join('_');
        }
        else
        {
            get_parment.attr = key+'_'+val.join('_');
        }
    }
    // 组装请求的url
    var url = '';
    for ( var k in get_parment )
    {
        url += "&"+k+'='+get_parment[k];
    }
    location.href ="/index.php?m=Home&c=Goods&a=goodsList"+url;
}
//媒体查询
/*$(function(){
    window.onresize=resizeauto;
    resizeauto();
    function resizeauto(){
        var windowW = $(window).width();
        if(windowW > 1447){
            $('.w1430,.w1224,.w1000').addClass('w1430').removeClass('w1224').removeClass('w1000');
        }else if(windowW <= 1447 && windowW > 1241){
            $('.w1430,.w1224,.w1000').addClass('w1224').removeClass('w1430').removeClass('w1000');
        }else if(windowW <= 1241){
            $('.w1430,.w1224,.w1000').addClass('w1000').removeClass('w1224').removeClass('w1430');
        }
    }
})*/
</script>
</body>
</html>";