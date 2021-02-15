<?php /*a:3:{s:42:"../template/mobile/rainbow/user/login.html";i:1593659405;s:45:"../template/mobile/rainbow/public/header.html";i:1593659405;s:49:"../template/mobile/rainbow/public/header_nav.html";i:1593659405;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>登录--<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <link rel="stylesheet" href="/template/mobile/rainbow/static/css/style.css">
    <link rel="stylesheet" type="text/css" href="/template/mobile/rainbow/static/css/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/template/mobile/rainbow/static/css/all_page.css"/>
    <script src="/template/mobile/rainbow/static/js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="/template/mobile/rainbow/static/js/zepto-1.2.0-min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="/template/mobile/rainbow/static/js/style.js" type="text/javascript" charset="utf-8"></script>
    <script src="/template/mobile/rainbow/static/js/mobile-util.js" type="text/javascript" charset="utf-8"></script>
    <script src="/public/js/global.js"></script>
    <script src="/template/mobile/rainbow/static/js/layer.js"  type="text/javascript" ></script>
    <script src="/template/mobile/rainbow/static/js/swipeSlide.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/template/mobile/rainbow/static/js/jquery.rotate.js" type="text/javascript" charset="utf-8"></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"/>
</head>
<body class="menu-hide">

<?php if(empty(app('request')->param('app'))): ?>
<div class="classreturn loginsignup ">
    <div class="content">
        <div class="ds-in-bl return">
            <a id="[back]" <?php  if(request()->action() == 'userinfo' && $_GET["action"]==""){  ?>  href="<?php echo url('User/index'); ?>" <?php  }else{  ?> href="javascript:history.back(-1);" <?php  }  ?> ><img src="/template/mobile/rainbow/static/images/return.png" alt="返回"></a>
        </div>
        <div class="ds-in-bl search center">
            <span></span>
        </div>
        <div class="ds-in-bl menu">
            <a href="javascript:void(0);"><img src="/template/mobile/rainbow/static/images/class1.png" alt="菜单"></a>
        </div>
    </div>
</div>
<div class="flool up-tpnavf-wrap tpnavf [top-header]">
    <div class="footer up-tpnavf-head">
    	<div class="up-tpnavf-i"> </div>
        <ul>
            <li>
                <a class="yello" href="<?php echo url('Index/index'); ?>">
                    <div class="icon">
                        <i class="icon-shouye iconfont"></i>
                        <p>首页</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo url('Goods/categoryList'); ?>">
                    <div class="icon">
                        <i class="icon-fenlei iconfont"></i>
                        <p>分类</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo url('Cart/index'); ?>">
                    <div class="icon">
                        <i class="icon-gouwuche iconfont"></i>
                        <p>购物车</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo url('User/index'); ?>">
                    <div class="icon">
                        <i class="icon-wode iconfont"></i>
                        <p>我的</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
	
$(function(){	
	$(window).scroll(function() {		
		if($(window).scrollTop() >= 1){ 
			$('.tpnavf').hide()
		}
	})
})
</script>
<?php endif; ?>
<link rel="stylesheet" href="/template/mobile/rainbow/static/css/user.css">
<div class="login-c">
    <a class="login-logo-wrap" href="#">
         <img src="<?php echo (isset($tpshop_config['shop_info_wap_login_logo']) && ($tpshop_config['shop_info_wap_login_logo'] !== '')?$tpshop_config['shop_info_wap_login_logo']:'/public/static/images/logo/pc_home_user_logo_default.png'); ?>" alt="LOGO"/>
    </a>
    <!--登录表单-s-->
    <form id="loginform" method="post">
        <!-- 验证码登录-s -->
        <div class="verify-enter verify-login">
            <input class="inputccc input-none login-input" maxlength="11" placeholder="请输入手机号码" type="text" oninput="onInput()">
            <div class="color999 mt20 ts">未注册的手机号验证后自动创建登录账号</div>
            <div disabled class="get-code">获取短信验证码</div>
        </div>
        <!--手机号登录-s -->
        <div class="account verify-login" style="display: none;">
            <input class="inputccc input-none login-input account-phone"  maxlength="11" placeholder="请输入账号"  name="username" type="text" onInput="onInput1()">
            <input class="inputccc input-none login-input account-pwd mt30" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" name="password" placeholder="请输入密码" type="password" onInput="onInput1()">
            <div class="eye-c">
                <i class="eye"></i>
                <a href="/Mobile/User/forget_pwd.html" class="ya_mima"><b></b> 忘记密码</a>
            </div>
            <div disabled class="get-code">登录</div>
        </div>
        <div class="agreement mt30">
            <div class="mian_flex_2"> 
                <div class="cut">
                    <div data-xy="1" class="xy-cut image"></div>
                    <img data-xy="2" class="xy-cut image1" src="/template/mobile/rainbow/static/images/agree_t.png" alt="">
                </div>
                <div class="mian_flex_2">同意<a href="/Mobile/Article/agreement/doc_code/agreement"><span> 《登录注册协议》</span></a></div>
            </div>
            <div data-xy="1" class="login-cut mian_color login_switch">账号密码登录</div>
        </div>
    </form>
    <!--登录表单-e-->
    <!--第三方登陆-s-->
    <div class="other-login" >
        <div class="log-t">其他登录方式</div>
        <div class="third-login-list"><!--$is_wx-->
            <a id="wx_login" style="display: none;" href="<?php echo url('User/binding',array('oauth'=>'wx')); ?>" title="wx">
                <img src="/template/mobile/rainbow/static/images/login_wx.png" width="70"/>
                <div>微信</div>
            </a>
            <?php
                                   
                                $md5_key = md5("select * from __PREFIX__plugin where type='login' AND status = 1");
                                $result_name = $sql_result_v = cache("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\facade\Db::query("select * from __PREFIX__plugin where type='login' AND status = 1"); 
                                    cache("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): if($v['code'] == 'qq'): ?>
                    <a id="qq_login" href="<?php echo url('LoginApi/login',array('oauth'=>'qq')); ?>" target="_blank" title="QQ">
                        <img src="/template/mobile/rainbow/static/images/login_qq.png" width="70"/>
                        <div>QQ</div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
   
    <div class="mask-filter-div" style="display: none;">
        <div class="bind-content">
            <div class="bind-tit">密码错误超过10次，您可以找回密码，或20分钟后再试</div>
            <div class="bind-operation">
              <div class="cancel">取消</div>
              <div class="button" >找回密码</div>
            </div>
        </div>
    </div>
     <!--第三方登陆-e-->
</div>

<script src="/public/static/js/vue.js"></script>
<script type="text/javascript">
    $(function(){
        on_going_hide();
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            $('#wx_login').show();
          /*  $('#qq_login').hide();*/
        }
    });
    var mobile = '';
    //验证码输入监听
    function onInput() {
        var val=$(".verify-enter .login-input").val();
        if(val.length ==11){
            mobile = val;
        }else{
            mobile = '';
        }
        showBtn()
    }
    // 账号密码输入监听
    function onInput1() {
        showBtn();
    }

    // 取消弹窗
    $(".bind-operation .cancel").click(function(){
        $(".mask-filter-div").fadeToggle(300);
    })
    // 找回密码
    $(".bind-operation .button").click(function(){
        window.location.href = "<?php echo url('Mobile/User/forget_pwd'); ?>";
    })
     //切换密码框的状态  verify-login .eye-c
     $(function(){
        $('.verify-login .eye-c i').click(function(){
            $(this).toggleClass('eye');
            if ($(this).hasClass('eye')) { 
                $('.account-pwd').attr('type','password')
            } else{
                $('.account-pwd').attr('type','text')
            }
        });
    })

    function showBtn(){
        var login_method = $('.login_switch').data('xy');
        if(login_method == 1){
            if(data_xy==2 && mobile.length ==11){
                $(".verify-enter .get-code").addClass("bth-check");
                $(".verify-enter .get-code").prop('disabled',true);
            }else{
                $(".verify-enter .get-code").removeClass("bth-check");
                $(".verify-enter .get-code").prop('disabled',false);
            }
        }else{
            var val1=$(".account .account-phone").val();
            var val2=$(".account .account-pwd").val();
            if(val1.length == 11 && val2.length >= 6 && data_xy== 2 ){
                $(".account .get-code").addClass("bth-check");
                $(".account .get-code").prop('disabled',true);
            }else{
                $(".account .get-code").removeClass("bth-check");
                $(".account .get-code").prop('disabled',false);
            }
        }

    }
   
    // 同意协议
    var data_xy=2;  //1 未同意 2 已同意
    $(document).on("click",".xy-cut",function(){
        var xy=$(this).data(xy);
        if(xy.xy==1){
            data_xy=2;
            $(".agreement .image").hide(1,function(){
                $(".agreement .image1").show(100);
            });
        }else{
            data_xy=1;
            $(".agreement .image1").hide(1,function(){
                $(".agreement .image").show(100);
            });

        }
        showBtn()
    });
    // 登录切换 
    $(document).on("click",".login-cut",function(){    
        var xy=$(this).data(xy);
        if(xy.xy==1){
            $(this).data("xy",2);
            $(this).text("验证码登录");
            $(".verify-enter").hide();
            $(".account").show();
        }else{
            $(this).data("xy",1);
            $(this).text("账号密码登录");
            $(".verify-enter").show();
            $(".account").hide();
        }
    });
    // 获取短信验证码
    $(".verify-enter .get-code").click(function(){
        if(mobile.length !=11){
            white_hint('手机号码有误！',false);
            return false;
        }
        if(data_xy==1){
            white_hint('请勾选登录协议!',false);
            return false;
        }
        //请求验证码接口
        $.ajax({
            url : "/index.php?m=Home&c=Api&a=send_validate_code&scene=6&type=mobile",
            type:'post',
            dataType:'json',
            data:{mobile:mobile},
            success:function(res){
                if(res.status == 1){
                    on_going('发送中...');
                    setTimeout(function(){
                        window.location.href = "<?php echo url('Mobile/User/code_login?'); ?>"+'?mobile='+mobile;
                    },1200)
                   
                }else{
                    white_hint(res.msg,false);
                }
            }
        })

    })
    $(document).ready(function(){
        showBtn()
    })

    // 密码登录
    $(".account .get-code").click(function(){
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();
        if(username == ''){
            white_hint('请输入登录账号！',false);
            return false;
        }
        if(data_xy==1){
            white_hint('请勾选登录协议!',false);
            return false;
        }
        on_going('正在登录')
        //请求验证码接口
        $.ajax({
            url : "/index.php?m=Mobile&c=User&a=do_login",
            type:'post',
            dataType:'json',
            data:{username:username,password:password},
            success:function(res){
                on_going_hide()
                if(res.status == 1){
                    window.location.href =res.url;
                }else{
                    if(res.password_error_num >=10){
                        $(".mask-filter-div").fadeToggle(300);//登录多次问题
                    }else{
                        white_hint(res.msg,false);
                    }

                }
            }
        })

    })
</script>
</body>
</html>
