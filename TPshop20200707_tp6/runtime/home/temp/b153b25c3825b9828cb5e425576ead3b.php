<?php /*a:3:{s:38:"../template/pc/rainbow/cart/cart4.html";i:1593659405;s:46:"../template/pc/rainbow/public/sign-header.html";i:1593659405;s:41:"../template/pc/rainbow/public/footer.html";i:1593659405;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>订单支付-<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <meta name="keywords" content="<?php echo $tpshop_config['shop_info_store_keyword']; ?>"/>
    <meta name="description" content="<?php echo $tpshop_config['shop_info_store_desc']; ?>"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"/>
    <link rel="stylesheet" href="/pc/rainbow/static/css/pay.min.css">
    <link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/tpshop.css" />
    <link rel="stylesheet" type="text/css" href="/pc/rainbow/static/css/base.css"/>
    <link href="/pc/rainbow/static/css/common.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/pc/rainbow/static/css/jh.css">
    <script src="/pc/rainbow/static/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/public/js/global.js"></script>
    <script src="/public/js/pc_common.js"></script>
    <script src="/public/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
</head>
<style>
.dsfzf-ee ul li {
    float: left;
    width: auto;
    padding-bottom: 25px;
    margin-right: 30px;
}
.payment-area label img {
    width: 36px;
    height: 36px;
    border: none;
}
</style>
<body>
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


<div class="fn-cart-pay">
    <!-- cart-title -->
    <div class="wrapper1190">
        <div class="order-header">
            <div class="layout after">
                <div class="fl">
                    <div class="logo pa-to-36 wi345">
                        <a href="/"><img src="<?php echo (isset($tpshop_config['shop_info_store_logo']) && ($tpshop_config['shop_info_store_logo'] !== '')?$tpshop_config['shop_info_store_logo']:'/public/static/images/logo/pc_home_logo_default.png'); ?>" alt="" style="max-width: 240px;max-height: 80px;"></a>
                    </div>
                </div>
                <div class="fr">
                    <div class="pa-to-36 progress-area">
                        <div class="progress-area-wd" style="display:none">我的购物车</div>
                        <div class="progress-area-tx" style="display:none">填写核对订单信息</div>
                        <div class="progress-area-cg">成功提交订单</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end cart-title -->

        <div class="layout after-ta order-ha">
            <div class="erhuh">
                <i class="icon-succ"></i>

                <h3>订单提交成功，请您尽快付款！</h3>

                <p class="succ-p">
                    <?php if($master_order_sn != ''): ?>
                        订单号：&nbsp;&nbsp;<?php echo $master_order_sn; ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                        付款金额（元）：&nbsp;&nbsp;<b><?php echo $sum_order_amount; ?></b>&nbsp;<b>元</b>
                        <?php else: ?>
                        订单号：&nbsp;&nbsp;<?php echo $order['order_sn']; ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                        付款金额（元）：&nbsp;&nbsp;<b><?php echo $order['order_amount']; ?></b>&nbsp;<b>元</b>
                    <?php endif; ?>
                </p>
                <div class="succ-tip">
                    请您在&nbsp;&nbsp;<b><?php echo $pay_date; ?></b>&nbsp;完成支付，否则订单将自动取消
                </div>
            </div>
            <div class="ddxq-xiaq">
                <?php if($master_order_sn != ''): ?>
                    <a href="<?php echo url('Home/Order/order_list'); ?>">订单详情<i></i></a>
                    <?php else: ?>
                    <a href="<?php echo url('Home/Order/order_detail',array('id'=>$order['order_id'])); ?>">订单详情<i></i></a>
                <?php endif; ?>
            </div>
            <form action="<?php echo url('Home/Payment/getCode'); ?>" method="post" name="cart4_form" id="cart4_form">
                <div class="orde-sjyy">
                    <h3 class="titls">选择支付方式</h3>

                    <div class="bsjy-g">
                        <dl>
                            <dd>
                                <div class="order-payment-area">
                                    <div class="dsfzfpte">
                                        <b>选择支付方式</b>
                                    </div>
                                    <div class="po-re dsfzf-ee">
                                        <ul>
                                            <?php if(is_array($paymentList) || $paymentList instanceof \think\Collection || $paymentList instanceof \think\Paginator): if( count($paymentList)==0 ) : echo "" ;else: foreach($paymentList as $k=>$v): ?>
                                                <li>
                                                    <div class="payment-area">
                                                        <input type="radio" id="input-ALIPAY-1" value="pay_code=<?php echo $v['code']; ?>" class="radio vam" name="pay_radio">
                                                        <label for="">
                                                            <img src="/plugins/<?php echo $v['type']; ?>/<?php echo $v['code']; ?>/<?php echo $v['icon']; ?>" width="120" height="40" onClick="change_pay(this);"/>
															<?php echo $v['name']; ?>
                                                        </label>
                                                    </div>
                                                </li>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </ul>
                                    </div>
                                </div>

                                <!--第三方网银支付 start-->
                                <?php if(is_array($bankCodeList) || $bankCodeList instanceof \think\Collection || $bankCodeList instanceof \think\Paginator): if( count($bankCodeList)==0 ) : echo "" ;else: foreach($bankCodeList as $k=>$v): ?>
                                    <div class="order-payment-area">
                                        <div class="dsfzfpte">
                                            <b><?php echo $paymentList[$k]['name']; ?></b>
                                            <em>网银支付</em>
                                        </div>
                                        <div class="po-re dsfzf-ee">
                                            <ul>
                                                <?php if(is_array($v) || $v instanceof \think\Collection || $v instanceof \think\Paginator): if( count($v)==0 ) : echo "" ;else: foreach($v as $k2=>$v2): ?>
                                                    <li>
                                                        <div class="payment-area">
                                                            <input type="radio" name="pay_radio" class="radio vam" value="pay_code=<?php echo $k; ?>&bank_code=<?php echo $v2; ?>" id="input-ALIPAY-1">
                                                            <label for="">
                                                                <img src="/pc/rainbow/static/images/images-out/<?php echo $bank_img[$k2]; ?>" width="120" height="40" onClick="change_pay(this);"/>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                <!--第三方网银支付 end -->

                            </dd>
                        </dl>
                        <div class="order-payment-action-area">
                            <a class="button-style-5 button-confirm-payment" href="javascript:void(0);" onClick="$('#cart4_form').submit();">确认支付方式</a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="master_order_sn" value="<?php echo $master_order_sn; ?>"/>
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>"/>
            </form>
        </div>
    </div>
</div>


<!--微信扫一扫支付对话框  -->
<div id="wchatQrcodeDlg" class="g-cartpay-dlg" style="display: none;" data-show="">
    <div class="g-cartpay-content">
        <div class="g-h"><span class="u-close"></span></div>
        <div class="g-c">
            <div class="g-t"> 使用微信支付<span>￥<small class='wx_amount'>118</small></span></div>
            <div class="g-qrcode"><img alt="使用微信支付" src="images/loading.gif"/></div>
        </div>
        <div class="g-f fixed"><i class="icon_scan"></i>

            <div class="u-label">
                <p>请使用微信扫一扫<br>
                    扫描二维码完成支付</p>
            </div>
        </div>
    </div>
    <div class="u-mask"></div>
</div>
<!--微信扫一扫支付对话框 / -->
<div id="addCardNewBind"></div>
<!--footer-s-->
<div class="footer p">
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
</div>
<!--footer-e-->
<script>
    $(document).ready(function () {
        $("input[name='pay_radio']").first().trigger('click');
    });
    // 切换支付方式
    function change_pay(obj) {
        $(obj).parent().siblings('input[name="pay_radio"]').trigger('click');
    }
</script>
</body>
</html>
