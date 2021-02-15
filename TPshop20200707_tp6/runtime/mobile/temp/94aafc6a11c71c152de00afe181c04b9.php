<?php /*a:4:{s:43:"../template/mobile/rainbow/index/index.html";i:1593659407;s:45:"../template/mobile/rainbow/public/footer.html";i:1593659405;s:49:"../template/mobile/rainbow/public/footer_nav.html";i:1593659405;s:47:"../template/mobile/rainbow/public/wx_share.html";i:1593659405;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>首页-<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <link rel="stylesheet" href="/template/mobile/rainbow/static/css/style.css">
    <link rel="stylesheet" type="text/css" href="/template/mobile/rainbow/static/css/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/template/mobile/rainbow/static/css/index.css"/>
    <link rel="stylesheet" type="text/css" href="/template/mobile/rainbow/static/css/all_page.css"/>
    <script src="/template/mobile/rainbow/static/js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="/template/mobile/rainbow/static/js/zepto-1.2.0-min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="/template/mobile/rainbow/static/js/mobile-util.js" type="text/javascript" charset="utf-8"></script>
    <script src="/template/mobile/rainbow/static/js/swipeSlide.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/public/js/global.js"></script>
    <script src="/template/mobile/rainbow/static/js/layer.js"  type="text/javascript" ></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"/>
</head>
<body>
    <!--顶部搜索栏-s-->
    <header>
        <div class="content">
            <div class="ds-in-bl search">
                <div class="sea-box">
                    <span></span>
                    <form action=""  method="post">
                        <div class="sear-input">
                            <a href="<?php echo url('Goods/ajaxSearch'); ?>">
                                <input type="text" name="q" id="search_text" class="search_text"   value="" placeholder="请输入您所搜索的内容...">
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </header>
    <!--顶部搜索栏-e-->

    <!--顶部滚动广告栏-s-->
    <div class="banner ban1">
        <div class="banner_bg"></div>
        <div class="mslide" id="slideTpshop">
            <ul>
                <!--广告表-->
                <?php $pid =9;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593658800 and end_time > 1593658800 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("5")->select();
             
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


$c = 5- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
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
                    <li><a href="<?php echo $v['ad_link']; ?><?php echo !empty($edit_ad) ? '&suggestion=710*340'  :  ''; ?>">
                        <img src="<?php echo $v['ad_code']; ?>" title="<?php echo $v['title']; ?>" style="<?php echo $v['style']; ?>" alt="">
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!--顶部滚动广告栏-e-->

    <!--菜单-start-->
    <div class="floor dh">
        <nav>
            <a href="<?php echo url('Goods/brandstreet'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_07.png" alt="品牌街" />
                    <span>品牌街</span>
                </span>
            </a>
            <!--<a href="shopcar.html">-->
            <a href="<?php echo url('User/coupon'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_17.png" alt="购物车" />
                    <span>优惠卷</span>
                </span>
            </a>
            <a href="<?php echo url('Activity/promote_goods'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_09.png" alt="优惠活动" />
                    <span>优惠活动</span>
                </span>
            </a>
            <a href="<?php echo url('Activity/group_list'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_15.png" alt="团购" />
                    <span>团购</span>
                </span>
            </a>
            <a href="<?php echo url('Order/order_list'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_16.png" alt="我的订单" />
                    <span>我的订单</span>
                </span>
            </a>
            <a href="<?php echo url('Goods/integralMall'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_05.png" alt="积分商城" />
                    <span>积分商城</span>
                </span>
            </a>
            <!--<a href="my.html">-->
            <a href="javascript:void(0);" onclick="alert('请购买高级版支持哦!');">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_19.png" alt="拼团" />
                    <span>拼团</span>
                </span>
            </a>
            <a href="<?php echo url('Goods/categoryList'); ?>">
                <span>
                    <img src="/template/mobile/rainbow/static/images/icon_03.png" alt="全部分类" />
                    <span>全部分类</span>
                </span>
            </a>
        </nav>
    </div>
    <!--菜单-end-->

    <!--秒杀-start-->
    <?php if($flash_sale_list): ?>
        <div class="secondkill"  >
            <div class="content">
                <div class="time p">
                    <div class="l_1"><img src="/template/mobile/rainbow/static/images/mszc.png" alt=""></div>
                    <div class="djs lightning fl">
                        <!--<span class="add">秒杀</span>-->
                        <span class="red" id=""><?php echo date('H',$start_time); ?>点场</span>
                        <span class="hms"></span>
                    </div>
                    <div class="xsxl fr">
                        <a href="<?php echo url('Mobile/Activity/flash_sale_list'); ?>">
                            <span>限时抢购<img src="/template/mobile/rainbow/static/images/z-package-left.png" alt="" /></span>
                        </a>
                    </div>
                </div>
                <div class="shop p">
                    <?php if(is_array($flash_sale_list) || $flash_sale_list instanceof \think\Collection || $flash_sale_list instanceof \think\Paginator): if( count($flash_sale_list)==0 ) : echo "" ;else: foreach($flash_sale_list as $key=>$v): ?>
                        <a href="<?php echo url('Mobile/Goods/goodsInfo',array('id'=>$v['goods_id'],'item_id'=>$v['item_id'])); ?>">
                        <!--<a href="<?php echo url('Mobile/Activity/flash_sale_list'); ?>">-->
                            <div class="timerBox shopnum">
                                <img src="<?php echo goods_thum_images($v['goods_id'],200,200); ?>"/>
                                <p class="ms-title mian_hidde"><?php echo $v['goods_name']; ?></p>
                                <span class="o-price"><span style="font-size: .4267rem">￥</span><?php echo $v['price']; ?>.00</span>
                                <span class="c-price">￥<?php echo $v['shop_price']; ?></span>
                            </div>
                        </a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
    <?php endif; if(!$flash_sale_list): ?>
        <div class="higt_seckill">
            <div class="l_1"><img src="/template/mobile/rainbow/static/images/mszc.png" alt=""></div>
            <div class="xsxl fr">
                <img class="l_2" src="/template/mobile/rainbow/static/images/icon_flash_sale.png" alt="">
                <a href="<?php echo url('Mobile/Activity/flash_sale_list'); ?>">
                    <span>限时抢购<img src="/template/mobile/rainbow/static/images/z-package-left.png" alt="" /></span>
                </a>
            </div>
        </div>
    <?php endif; ?>
    <!--秒杀-end-->
    <div class="f-classify">
        <ul>
            <li>
                <?php $pid =507;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593658800 and end_time > 1593658800 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
             
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
    ?><!-- 中间广告图-左图1 -->
                    <?php if($_GET['edit_ad']  == 1): ?>
                        <a href="<?php echo $v['ad_link']; ?><?php echo !empty($edit_ad) ? '&suggestion=350*150'  :  ''; ?>">
                            <?php else: ?>
                                <a href="<?php echo get_bd_url($v['ad_link'],$v['media_type']); ?>">
                    <?php endif; ?>

                        <img  src="<?php echo $v['ad_code']; ?>" title="<?php echo $v['title']; ?>"/>
                    </a>
                <?php endforeach; ?>
            </li>
                <li>
                <?php $pid =508;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593658800 and end_time > 1593658800 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
             
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
    ?><!-- 中间广告图-左图2 -->
                    <?php if($_GET['edit_ad']  == 1): ?>
                        <a href="<?php echo $v['ad_link']; ?><?php echo !empty($edit_ad) ? '&suggestion=350*150'  :  ''; ?>">
                            <?php else: ?>
                                <a href="<?php echo get_bd_url($v['ad_link'],$v['media_type']); ?>">
                    <?php endif; ?>
                        <img  src="<?php echo $v['ad_code']; ?>" title="<?php echo $v['title']; ?>"/>
                    </a>
                <?php endforeach; ?>
            </li>
            <li>
                <?php $pid =509;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593658800 and end_time > 1593658800 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
             
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
    ?><!-- 中间广告图-右图1 -->
                    <?php if($_GET['edit_ad']  == 1): ?>
                        <a href="<?php echo $v['ad_link']; ?><?php echo !empty($edit_ad) ? '&suggestion=350*150'  :  ''; ?>">
                            <?php else: ?>
                                <a href="<?php echo get_bd_url($v['ad_link'],$v['media_type']); ?>">
                    <?php endif; ?>
                        <img  src="<?php echo $v['ad_code']; ?>" title="<?php echo $v['title']; ?>" />
                    </a>
                <?php endforeach; ?>
            </li>
            <li>
                <?php $pid =510;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593658800 and end_time > 1593658800 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
             
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
    ?><!-- 中间广告图-右图2 -->
                    <?php if($_GET['edit_ad']  == 1): ?>
                        <a href="<?php echo $v['ad_link']; ?><?php echo !empty($edit_ad) ? '&suggestion=350*150'  :  ''; ?>">
                            <?php else: ?>
                                <a href="<?php echo get_bd_url($v['ad_link'],$v['media_type']); ?>">
                    <?php endif; ?>
                        <img class="pos-smallad4" src="<?php echo $v['ad_code']; ?>" title="<?php echo $v['title']; ?>" />
                    </a>
                <?php endforeach; ?>
            </li>
        </ul>
    </div>
    <div class="h-showcase">
        <?php $pid =506;$ad_position = \think\facade\Db::name("ad_position")->column("position_id,position_name,ad_width,ad_height","position_id");$result = \think\facade\Db::name("ad")->where("pid=$pid  and enabled = 1 and start_time < 1593658800 and end_time > 1593658800 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
             
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
    if($_GET['edit_ad']  == 1): ?>
                <a href="<?php echo $v['ad_link']; ?><?php echo !empty($edit_ad) ? '&suggestion=710*190'  :  ''; ?>">
                <?php else: ?>
                <a href="<?php echo get_bd_url($v['ad_link'],$v['media_type']); ?>">
            <?php endif; ?>
                <img src="<?php echo $v['ad_code']; ?>" title="<?php echo $v['title']; ?>"/>
            </a>
        <?php endforeach; ?>
    </div>
    <div onclick=""    ></div>
    <!--热销商品-start-->
    <div class="floor newshop">
        <div class="banner banner_imgs">
            <img src="/template/mobile/rainbow/static/images/h-rxsp.png" alt="热销商品"/>
        </div>
        <div class="rxsp-list" id="J_ItemList">
            <ul>

            </ul>
        </div>
    </div>
    <!--热销商品-end-->
    <!--推荐商品-start-->
    <?php if($recommend_goods): ?><?php endif; ?>
        <div class="floor hotshop index_hot">
            <div class="banner banner_imgs ">
                <img src="/template/mobile/rainbow/static/images/h-tstj.png" alt="推荐商品"/>
            </div>
            <div class="f-recommend">
                <ul>
                    <?php if(is_array($recommend_goods) || $recommend_goods instanceof \think\Collection || $recommend_goods instanceof \think\Paginator): if( count($recommend_goods)==0 ) : echo "" ;else: foreach($recommend_goods as $key=>$v): ?>
                        <li>
                            <a href="<?php echo url('mobile/goods/goodsInfo',array('id'=>$v['goods_id'])); ?>">
                                <img src="<?php echo goods_thum_images($v['goods_id'],300,300); ?>" alt="<?php echo $v['goods_name']; ?>" style="height: 100%">
                                <div class="fr-pdetail">
                                    <p class="frp-title"><?php echo $v['goods_name']; ?></p>
                                    <?php if($v['label_id']): ?>
                                        <span class="frp-label"><?php echo $v['label_id']; ?></span>
                                        <?php else: ?>
                                            <span class="frp-label" style="border:none"></span>
                                    <?php endif; ?>
                                    <div style="padding-left: 0;" class="rxsp-price">
                                        <div class="frp-price">
                                            <!-- <span class="ro-sm"></span> -->
                                            <span class="ro-price"><b>￥</b><?php echo explode_price($v['shop_price'],0); ?>.<?php echo explode_price($v['shop_price'],1); ?></span>
                                        </div>
                                        <div class="wo-msg">
                                            <span>已售<?php echo $v['sales_sum']; ?>件</span>
                                            <!-- <span><?php echo $v['comment_statistics']['high_rate']; ?>%好评</span> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </if>
    <!--推荐商品-end-->

    <!--拼团列表-start-->
    <?php if($team_activity): ?>
        <div class="floor guesslike m-b-0">
            <div class="banner banner_imgs ">
                <img src="/template/mobile/rainbow/static/images/h-ptlbzs.png" alt="拼团列表"/>
            </div>
            <div class="vir" style="width: 0.683rem"></div>
            <div class="pt-group f-recommend">
                <ul>
                    <?php if(is_array($team_activity) || $team_activity instanceof \think\Collection || $team_activity instanceof \think\Paginator): if( count($team_activity)==0 ) : echo "" ;else: foreach($team_activity as $key=>$v): ?>
                        <li class="mian_width_2">
                            <a href="<?php echo $v['bd_url']; ?>">
                                <div class="content_img">
                                    <img src="<?php echo goods_thum_images($v['goods_id'],300,300); ?>" alt="<?php echo $v['goods_name']; ?>" style="width: 100%">
                                </div>
                                <p class="mian_h2 mian_hidde"><?php echo $v['goods_name']; ?></p>
                                <span class="rx-sp"><?php echo $v['team_type_desc']; ?></span>
                                <span class="rx-sp"><?php echo $v['needer']; ?>人团</span>
                                <span class="hd-img">
                                        <?php if(is_array($v['follow_users_head_pic']) || $v['follow_users_head_pic'] instanceof \think\Collection || $v['follow_users_head_pic'] instanceof \think\Paginator): if( count($v['follow_users_head_pic'])==0 ) : echo "" ;else: foreach($v['follow_users_head_pic'] as $key=>$p): ?>
                                            <span><img src="<?php echo $p['follow_user_head_pic']; ?>" alt="" style="width: 0.768rem;height: 0.768rem"></span>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                </span>
                                <div class="rxsp-price mian_flex_6">
                                    <div>
                                        <!-- <span class="ro-sm">￥</span> -->
                                        <span class="ro-price"><b>￥</b><?php echo explode_price($v['team_goods_item'][0]['team_price'],0); ?>.<?php echo explode_price($v['team_goods_item'][0]['team_price'],1); ?></span>
                                    </div>
                                    <span class="has-sold">已拼<?php echo $v['virtual_sale_num']; ?>件</span>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>



        <div class="jz-loods get_more">
            <span class="loading"><img src="/template/mobile/rainbow/static/images/loading2.gif" alt=""></span>
        	<span>商品加载中...</span>
        	<!--<em>商品加载完毕...</em>-->
        </div>
    <div class="c-line">
        <p>已显示完该类商品</p>
    </div>
    </div>
    <!--猜您喜欢-end-->

    <!--底部-start-->
    <footer>
    <style>
        .foot-log{
            height: 0.94rem;
            display: block;
            margin-bottom: 0;
            float: left;
        }
        footer .flool3{
			position: relative;
            background-color: #eef0f3;
            border-top: none;
            overflow: hidden;
            margin: 0.8rem 0;
        }
		footer .flool3 div{
			width: 100%;
			height: 100%;
			text-align:center;
		}
        footer .flool3 span{
            font-size: 0.47rem;
            width: auto;
            height: 0.94rem;
            display: inline-block;
            line-height: 0.94rem;
            text-align: center;
            padding-left: 2rem;
			/*background-image: url(<?php echo $tpshop_config['shop_info_wap_home_logo']; ?>);*/
            background-image: url('/public/upload/logo/2018/07-19/TPstore.png');
            background-size: 1.8rem;
            color: #999;
            background-position: left center;
            background-repeat: no-repeat;
        }

    </style>
    <!--<div class="flool1">-->
        <!--<ul>-->
        	<!--<?php if($is_weixin_browser == 1): ?>-->
        		<!--<li><a href="<?php echo url('Mobile/User/index'); ?>"><?php echo $uname; ?></a></li>-->
            <!--<?php elseif(!empty($_COOKIE['user_id']) && !$is_wx): ?>-->
                <!--<li><a href="<?php echo url('Mobile/User/index'); ?>"><?php echo getSubstr(urldecode($_COOKIE['uname']),0,3);?></a></li>-->
                <!--<li><a href="<?php echo url('Mobile/User/logout'); ?>">退出</a></li>-->
            <!--<?php else: ?>-->
                <!--<li><a href="<?php echo url('Mobile/User/login'); ?>">登录</a></li>-->
                <!--<li><a href="<?php echo url('Mobile/User/reg'); ?>">注册</a></li>-->
            <!--<?php endif; ?>-->
            <!--<li><a href="tel:<?php echo $tpshop_config['shop_info_phone']; ?>">反馈</a></li>-->
            <!--<li class="comebackTop">回到顶部</li>-->
        <!--</ul>-->
    <!--</div>-->
    <!--<div class="flool2">-->
        <!--<ul>-->
            <!--<li>-->
                <!--<a href="?">-->
                    <!--<div class="icon">-->
                        <!--<img src="/template/mobile/rainbow/static/images/ind_70.png"/>-->
                        <!--<p>客户端</p>-->
                    <!--</div>-->
                <!--</a>-->
            <!--</li>-->
            <!--<li>-->
                <!--<a href="<?php echo url('Mobile/Index/index'); ?>">-->
                    <!--<div class="icon black">-->
                        <!--<img src="/template/mobile/rainbow/static/images/ind_72.png"/>-->
                        <!--<p>触屏版</p>-->
                    <!--</div>-->
                <!--</a>-->
            <!--</li>-->
            <!--<li>-->
                <!--<a href="<?php echo url('Home/Index/index'); ?>">-->
                    <!--<div class="icon">-->
                        <!--<img src="/template/mobile/rainbow/static/images/ind_74.png"/>-->
                        <!--<p>电脑版</p>-->
                    <!--</div>-->
                <!--</a>-->
            <!--</li>-->
        <!--</ul>-->
    <!--</div>-->
    <div class="flool3">
	<div>
        <span><?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:config('shop_info.copyright')); ?>提供技术支持</span>
	</div>
    </div>
</footer>

    <!--底部-end-->

    <!--底部导航-start-->
    <div class="foohi">
    <div class="footer">
        <ul id="get_mobile_foot_menu" style="display: none;">
            <li>
                <a <?php if('CONTROLLER_NAME' == 'Index'): ?>class="yello" <?php endif; ?>  href="<?php echo url('Index/index'); ?>">
                    <div class="icon">
                        <div class="icon_tp1 icon_tps"><img src="/template/mobile/rainbow/static/images/home1.png"/> </div>
                        <div class="icon_tp2 icon_tps"><img src="/template/mobile/rainbow/static/images/home2.png"/> </div>
                        <p>首页</p>
                    </div>
                </a>
            </li>
            <li>
                <a <?php if('CONTROLLER_NAME' == 'Goods'): ?>class="yello" <?php endif; ?> href="<?php echo url('Goods/categoryList'); ?>">
                    <div class="icon">
                    	<div class="icon_tp1 icon_tps"><img src="/template/mobile/rainbow/static/images/category1.png"/> </div>
                        <div class="icon_tp2 icon_tps"><img src="/template/mobile/rainbow/static/images/category2.png"/> </div>
                        <p>分类</p>
                    </div>
                </a>
            </li>
            <li>
                <a <?php if('CONTROLLER_NAME' == 'Cart'): ?>class="yello" <?php endif; ?> href="<?php echo url('Cart/index'); ?>">
                    <div class="icon">
                        <div class="icon_tp1 icon_tps"><img src="/template/mobile/rainbow/static/images/cart1.png"/> </div>
                        <div class="icon_tp2 icon_tps"><img src="/template/mobile/rainbow/static/images/cart2.png"/> </div>
                        <p>购物车</p>
                    </div>
                </a>
            </li>
            <li>
                <a <?php if('CONTROLLER_NAME' == 'User'): ?>class="yello" <?php endif; ?> href="<?php echo url('User/index'); ?>">
                    <div class="icon">
                        <div class="icon_tp1 icon_tps"><img src="/template/mobile/rainbow/static/images/user1.png"/> </div>
                        <div class="icon_tp2 icon_tps"><img src="/template/mobile/rainbow/static/images/user2.png"/> </div>
                        <p>我的</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<script src="/public/js/jqueryUrlGet.js"></script><!--获取get参数插件-->
<script type="text/javascript">
$(document).ready(function(){
	  var cart_cn = getCookie('cn');
	  if(cart_cn == ''){
		$.ajax({
			type : "GET",
			url:"/index.php?m=Home&c=Cart&a=header_cart_list",//+tab,
			success: function(data){								 
				cart_cn = getCookie('cn');
				$('#cart_quantity').html(cart_cn);						
			}
		});	
	  }
	  $('#cart_quantity').html(cart_cn);
});

set_first_leader();//设置推荐人
if($(".footer ul li a").hasClass("yello")){
	$(".footer ul li .yello").find(".icon_tp2").show();
	$(".footer ul li .yello").find(".icon_tp1").hide();
}
$(function(){

    $.ajax({
        type : "GET",
        dataType:"json",
        url:"/index.php?m=Home&c=Api&a=get_mobile_foot_menu",//+tab,
        success: function(data){
            if(data.status == 1){
                var html = '';
                var Things = data.result.footmenu.nav;
                for (var i = 0; i < Things.length; i++) {
                    html += get_html(Things[i]);
                }
                $("#get_mobile_foot_menu").html(html).find('li').css('width',(100/Things.length)+'%')
            }
            $("#get_mobile_foot_menu").show();
        }
    });
    function get_html(data){
        var controller_name = '<?php echo strtolower(CONTROLLER_NAME); ?>/<?php echo strtolower(ACTION_NAME); ?>';
        var html = ''
        var cls = '';
        if(data.url.toLowerCase().indexOf(controller_name) > 0){
            data.pic1 = data.pic2;
            cls = 'class="yello"';
        }
        html += '<li>'
        html += '    <a href="'+data.url+'" '+cls+'>'
        html += '        <div class="icon">'
        html += '            <div class="icon_tp1 icon_tps"><img src="'+data.pic1+'"> </div>'
        html += '            <p>'+data.title_name+'</p>'
        html += '        </div>'
        html += '    </a>'
        html += '</li>'
        return html;
    }
})
</script>
<!-- 微信浏览器 调用微信 分享js-->
<script type="text/javascript" src="<?php echo HTTP; ?>://res.wx.qq.com/open/js/jweixin-1.0.0.js" ></script>
<script type="text/javascript">

 //如果微信分销配置正常, 商品详情分享内容中的"图标"不显示, 则检查域名否配置了https, 如果配置了https,分享图片地址也要https开头
var httpPrefix = "<?php echo SITE_URL; ?>";
<?php if('ACTION_NAME' == 'goodsInfo'): ?>
var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Goods&a=goodsInfo&id=<?php echo $goods['goods_id']; ?>"; //默认分享链接
	var ShareImgUrl = "<?php echo goods_thum_images($goods['goods_id'],100,100); ?>"; // 分享图标
	var ShareTitle = "<?php echo (isset($goods['goods_name']) && ($goods['goods_name'] !== '')?$goods['goods_name']:$tpshop_config['shop_info_store_title']); ?>"; // 分享标题
	var ShareDesc = "<?php echo del_eol((isset($goods['goods_remark']) && ($goods['goods_remark'] !== '')?$goods['goods_remark']:$tpshop_config['shop_info_store_desc'])); ?>"; // 分享描述
<?php elseif('ACTION_NAME' == 'info'): ?>
	var ShareLink = "<?php echo $team['bd_url']; ?>"; //默认分享链接
	var ShareImgUrl = "<?php echo $team['bd_pic']; ?>"; //分享图标
	var ShareTitle = "<?php echo $team['share_title']; ?>"; //分享标题
	var ShareDesc = "<?php echo $team['share_desc']; ?>"; //分享描述
<?php elseif('ACTION_NAME' == 'found'): ?>
var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Team&a=found&id=<?php echo $teamFound['found_id']; ?>"; //默认分享链接
	var ShareImgUrl = "<?php echo $team['bd_pic']; ?>"; //分享图标
	var ShareTitle = "<?php echo $team['share_title']; ?>"; //分享标题
	var ShareDesc = "<?php echo $team['share_desc']; ?>"; //分享描述
<?php elseif('ACTION_NAME' == 'my_store'): ?>
	var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Distribut&a=my_store"; 
	var ShareImgUrl = "<?php echo $tpshop_config['shop_info_store_logo']; ?>";
	var ShareTitle = "<?php echo $share_title; ?>"; 
	var ShareDesc = httpPrefix+"/index.php?m=Mobile&c=Distribut&a=my_store}";
 <?php elseif('ACTION_NAME' == 'goodsList'): ?>
 var ShareLink = httpPrefix+"/index.php/Mobile/Goods/goodsList/id/<?php echo app('request')->param('id'); ?>"; //默认分享链接
 var ShareImgUrl = "<?php echo $tpshop_config['shop_info_wap_home_logo']; ?>"; //分享图标
 var ShareTitle = "<?php echo $tpshop_config['shop_info_store_title']; ?>"; //分享标题
 var ShareDesc = "<?php echo $tpshop_config['shop_info_store_desc']; ?>"; //分享描述

 <?php elseif(strtolower(CONTROLLER_NAME) == 'bargain' and 'ACTION_NAME' == 'index'): ?>
 var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Bargain&a=index&id=<?php echo app('request')->param('id'); ?>";
 var ShareImgUrl = "<?php echo goods_thum_images($data['bargain_info']['goods_id'],400,400); ?>";
 var ShareTitle = "<?php echo $data['bargain_info']['title']; ?>";
 var ShareDesc = "<?php echo $data['bargain_info']['description']; ?>"; // description
<?php else: ?>
	var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Index&a=index"; //默认分享链接
	var ShareImgUrl = "<?php echo $tpshop_config['shop_info_wap_home_logo']; ?>"; //分享图标
	var ShareTitle = "<?php echo $tpshop_config['shop_info_store_title']; ?>"; //分享标题
	var ShareDesc = "<?php echo $tpshop_config['shop_info_store_desc']; ?>"; //分享描述
<?php endif; ?>
 if(ShareImgUrl.indexOf('http') < 0){
	 ShareImgUrl = httpPrefix+ShareImgUrl
 }
var is_distribut = getCookie('is_distribut'); // 是否分销代理
var user_id = getCookie('user_id'); // 当前用户id
// 如果已经登录了, 并且是分销商 拼团分销链接分享
if(parseInt(is_distribut) == 1 && parseInt(user_id) > 0)
{
	if(ShareLink.indexOf('&')>0){
		ShareLink = ShareLink + "&first_leader="+user_id;
	}else{
		ShareLink = ShareLink + "/first_leader/"+user_id;
	}
}

$(function() {
	// type='0:pc, 1:wap, 2:安卓， 3：苹果， 4：小程序',打开页面，停留5秒，则增加一次访问次数
	setTimeout(function(){
		$.ajax({
			type : "POST",
			url:"/index.php?m=Home&c=Api&a=add_visit",
			data:{type:1,goods_id:'<?php echo (isset($goods['goods_id']) && ($goods['goods_id'] !== '')?$goods['goods_id']:"0"); ?>'},
			dataType:'JSON',
			timeout:3000,
			success: function(res){},
			error:function(res){}
		});
	},5000)
	if(isWeiXin() && parseInt(user_id)>0){
		$.ajax({
			type : "POST",
			url:"/index.php?m=Mobile&c=Index&a=ajaxGetWxConfig&t="+Math.random(),
			data:{'askUrl':encodeURIComponent(location.href.split('#')[0])},		
			dataType:'JSON',
			success: function(res)
			{
				//微信配置
				wx.config({
				    debug: false, 
				    appId: res.appId,
				    timestamp: res.timestamp, 
				    nonceStr: res.nonceStr, 
				    signature: res.signature,
				    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareQZone','hideOptionMenu'] // 功能列表，我们要使用JS-SDK的什么功能
				});
			},
			error:function(res){
				console.log("wx.config error:");
				console.log(res);
				return false;
			}
		}); 

		// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在 页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready 函数中。
		wx.ready(function(){
		    // 获取"分享到朋友圈"按钮点击状态及自定义分享内容接口
		    wx.onMenuShareTimeline({
		        title: ShareTitle, // 分享标题
		        link:ShareLink,
		        desc: ShareDesc,
		        imgUrl:ShareImgUrl, // 分享图标
                success: function () {
                    setTimeout(function(){
                        //test();
                    }, 500);
                }
		    });

		    // 获取"分享给朋友"按钮点击状态及自定义分享内容接口
		    wx.onMenuShareAppMessage({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
		    });
			// 分享到QQ
			wx.onMenuShareQQ({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
			});	
			// 分享到QQ空间
			wx.onMenuShareQZone({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
			});

		   <?php if('CONTROLLER_NAME' == 'User'): ?> 
				wx.hideOptionMenu();  // 用户中心 隐藏微信菜单
		   <?php endif; ?>	
		});
	}
});
 //测试分享朋友圈成功后执行的回调方法
 function test(){
     alert(111);
 }
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
</script>
<!--微信关注提醒   start-->
<?php if(app('request')->session('subscribe') == 0 and 1 == 2): ?>
<button class="guide" style="display:none;" onclick="follow_wx()">关注公众号</button>
<style type="text/css">
.guide{width:0.627rem;height:2.83rem;text-align: center;border-radius: 8px ;font-size:0.512rem;padding:8px 0;border:1px solid #adadab;color:#000000;background-color: #fff;position: fixed;right: 6px;bottom: 200px;z-index: 99;}
#cover{display:none;position:absolute;left:0;top:0;z-index:18888;background-color:#000000;opacity:0.7;}
#guide{display:none;position:absolute;top:5px;z-index:19999;}
#guide img{width: 70%;height: auto;display: block;margin: 0 auto;margin-top: 10px;}
div.layui-m-layerchild h3{font-size:0.64rem;height:1.24rem;line-height:1.24rem;}
.layui-m-layercont img{height:8.96rem;width:8.96rem;}
</style>
<script type="text/javascript">
  //关注微信公众号二维码	 
function follow_wx()
{
	layer.open({
		type : 1,  
		title: '关注公众号',
		content: '<img src="<?php echo $wechat_config['qr']; ?>">',
		style: ''
	});
}
  
$(function(){
	if(isWeiXin()){
		var subscribe = getCookie('subscribe'); // 是否已经关注了微信公众号		
		if(subscribe == 0)
			$('.guide').show();
	}else{
		$('.guide').show();
	}
})
 
</script>
<?php endif; ?>
<!--微信关注提醒  end-->
<!-- 微信浏览器 调用微信 分享js  end-->
    <!--底部导航-end-->
	<script src="/template/mobile/rainbow/static/js/style.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/template/mobile/rainbow/static/js/sourch_submit.js"></script>
<script type="text/javascript">
    $(function(){
//        加载热销商品信息
        ajax_sourch_submit();
    })
    $('.hd-img').children('span').each(function () {
        console.log($(this).index())
        $(this).css('right',$(this).index()*$('.vir').width())
    })

    /**
     * 秒杀模块倒计时
     * */
    function GetRTime(end_time){
        var NowTime = new Date();
        var t = (end_time*1000) - NowTime.getTime();
        // var d = Math.floor(t/1000/60/60/24);
        var h = Math.floor(t/1000/60/60%24)<10?'0' + Math.floor(t/1000/60/60%24):Math.floor(t/1000/60/60%24);
        var m = Math.floor(t/1000/60%60)<10?'0' + Math.floor(t/1000/60%60):Math.floor(t/1000/60%60);
        var s = Math.floor(t/1000%60)<10?'0' + Math.floor(t/1000%60):Math.floor(t/1000%60);
        if(s >= 0)
            return h + ':' + m + ':' +s+'';
    }
    function GetRTime2(){
        var text = GetRTime('<?php echo $end_time; ?>');
        if (text== 0){
            $(".hms").text('活动已结束');
        }else{
            $(".hms").text(text);
        }
    }
    setInterval(GetRTime2,1000);

    /**
     * 继续加载猜您喜欢
     * */
    var before_request = 1; // 上一次请求是否已经有返回来, 有才可以进行下一次请求
    var page = 0;
    function ajax_sourch_submit(){
        if(before_request == 0)// 上一次请求没回来 不进行下一次请求
            return false;
        before_request = 0;
        page++;
        $.ajax({
            type : "get",
            url:"/index.php?m=Mobile&c=Index&a=ajaxGetMore&p="+page,
            success: function(data)
            {
                if(data){
                    $("#J_ItemList>ul").append(data);
                    before_request = 1;
                }else{
                    $('.get_more').hide();
                    $('.c-line').show()
                }
            }
        });
    }
</script>
	</body>
</html>
