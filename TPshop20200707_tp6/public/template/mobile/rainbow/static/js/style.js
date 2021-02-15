/*
 * Public js
 */
//导航颜色
$(function(){
	var $_header=$('header');
	$(window).scroll(function(){
          var hei = $(window).scrollTop();
   	  	  //if(hei > $_header.height()){
		if(hei > 10){
			  $_header.addClass('headerbg');
   	  	  }else{
			  $_header.removeClass('headerbg');
   	  	  };
	});
});

//回到顶部
$(function(){
	$("footer .comebackTop").click(function () {
	        var speed=300;//滑动的速度
	        $('body,html').animate({ scrollTop: 0 }, speed);
	        return false;
	});
});

//ajax开始加载前显示loading，加载完后隐藏loading
$(document).ajaxStart(function(){
    $('.loadbefore').show();
}).ajaxStop(function(){
    $('.loadbefore').hide();
})

//底部导航
$(function(){
	$(".footer ul li a").click(function () {
	        $(this).addClass('yello').parent().siblings().find('a').removeClass('yello')
	});
});

//轮播
$(function(){
    $('#slideTpshop').swipeSlide({
        continuousScroll:true,
        speed : 3000,
        transitionType : 'cubic-bezier(0.22, 0.69, 0.72, 0.88)',
        firstCallback : function(i,sum,me){
            me.find('.dot').children().first().addClass('cur');
        },
        callback : function(i,sum,me){
            me.find('.dot').children().eq(i).addClass('cur').siblings().removeClass('cur');
        }
    });
    //圆点
    var ed = $('.mslide ul li').length - 2;
	$('.mslide').append("<div class=" + "dot" + "></div>");
	for(var i = 0; i<ed ;i++){
		$('.mslide .dot').append("<span></span>");
	};
	$('.mslide .dot span:first').addClass('cur');
	var wid = - ($('.mslide .dot').width() / 2);
	$('.mslide .dot').css('position','absolute').css('left','50%').css('margin-left',wid);
});

//radio选中
$(function(){
	$('.radio .che').click(function(){
		$(this).toggleClass('check_t');
	})
})
$(function(){
	$('.radio .all').click(function(){
		$(this).siblings().toggleClass('check_t');
	})
})


$(function(){
	//头部菜单
	$('.classreturn .menu a:last').click(function(e){
		$('.tpnavf').toggle();
		e.stopPropagation();
	});
	$('body').click(function(){
		$('.tpnavf').hide();
	});
	//左侧导航
	$('.classlist ul li').click(function(){
		$(this).addClass('red').siblings().removeClass('red');
	});
})

//黑色遮罩层-隐藏
function undercover(){
	$('.mask-filter-div').hide();
}
//黑色遮罩层-显示
function cover(){
	$('.mask-filter-div').show();
}
//action文件导航切换
$(function(){
	$('.paihang-nv ul li').click(function(){
		$(this).addClass('ph').siblings().removeClass('ph');
	})
})
//确认收货和催单
$(function(){
	$('.receipt').click(function(){
        var order_id = $(this).data('order-id');
        $('#surshko'+order_id).show();
        cover();
	})
	$('.weiyi a').click(function(){
		$('.surshko').hide();
		undercover();
	})
});
$(function(){
	$('.tuid').click(function(){
		$('.cuidd').show();
		cover();
	})
	$('.weiyi a').click(function(){
		$('.cuidd').hide();
		undercover();
	})
});
/**
 * 留言字数限制
 * tea ：要限制数字的class名
 * nums ：允许输入的最大值
 * zero ：输入时改变数值的ID
 */
function checkfilltextarea(tea,nums){
    var len = $.trim($(tea).val()).length;
    if(len > nums){
        $(tea).val($(tea).val().substring(0,nums));
    }
    var num = nums - len;
    num <= 0 ? $("#zero").text(0): $("#zero").text(num);  //防止出现负数
}

/**
 * 加减数量
 * n 点击一次要改变多少
 * maxnum 允许的最大数量(库存)
 * number ，input的id
 */
function altergoodsnum(n){
	var goods_id = $('input[name="goods_id"]').val();
	var exchange_integral = $("input[name='exchange_integral']").attr('value');// 兑换积分
	var point_rate = $("input[name='point_rate']").attr('value');// 积分金额比
	var num = parseInt($('#number').val());
	var maxnum = parseInt($('#number').attr('max'));
	if(maxnum > 200){
		maxnum = 200;
	}

    if(isNaN(num)){num = 0}
	num += n;
	num <= 0 ? num = 1 :  num;
	if(num >= maxnum){
		$(this).addClass('no-mins');
		num = maxnum;
	}
	$.ajax({
		type: "POST",
		url: "/index.php?m=Mobile&c=Goods&a=priceladder",
		dataType: 'json',
		data: {goods_id: goods_id,goods_num:num},
		success: function (data) {
			if(data.price_ladder > '0'){
				var integral = round(data.shop_price - (exchange_integral / point_rate), 2);
				if(exchange_integral == 0){
					$("#goods_price").html("<em>￥</em>" + data.shop_price);
					$("#price").html("<em>￥</em>"+ data.shop_price); //变动价格显示
				}else{
					$("#goods_price").html(integral + '+' +exchange_integral + '积分'); //变动价格显示
					$("#price").html(integral + '+' +exchange_integral + '积分'); //变动价格显示
				}
			}
		}
	});
	$('#store_count').text(maxnum - num); //更新库存数量
	$('#number').val(num);

	//$('#store_count').text(maxnum-num); //更新库存数量
	//$('#number').val(num)
}
/**
 * 提示弹窗
 * */
function showErrorMsg(msg){
    layer.open({content: msg,time:2});
}
/**
 * 提示弹窗
 * msg 显示的信息
 * type 成功失败的类型  true 成功 false 失败
 * time 弹框显示时间 默认2S
 * */
function white_hint(msg,type,time){
	if(typeof type == 'undefined') type = false;
	if(typeof time == 'undefined') time = 2000;
	if(type == false){
		var html ='<div class="white_hint"> <img src="/template/mobile/rainbow/static/images/white_hint_error.png" alt=""> <div>'+msg+'</div> </div>';
	}else{
		var html ='<div class="white_hint"> <img src="/template/mobile/rainbow/static/images/white_hint_success.png" alt=""> <div>'+msg+'</div> </div>';
	}
	$("body").append(html);
	setTimeout(function(){
		$("body .white_hint").remove();
	},time)
	return false;
}
/**
 * 正在登录,加载中等-进行状态提示弹窗-显示
 * */
function on_going(msg){
	if(typeof time == 'undefined') time = 2000;
	var html ='<div class="on_going"> <img src="/template/mobile/rainbow/static/images/ongoing.png" alt=""> <div>'+msg+'</div> </div>';
	$("body").append(html);
	var angle = 0;
	setInterval(function(){
		angle+=3;
		$(".on_going img").rotate(angle);  
	},20)	
}
/**
 * 正在登录,加载中等-进行状态提示弹窗-隐藏
 * */
function on_going_hide(){
	$("body .on_going").remove();
}

//将对象转成url 参数
var parseParam = function(data, key){
	var paramStr="";
	if(data instanceof String||data instanceof Number||data instanceof Boolean){
		paramStr+="&"+key+"="+encodeURIComponent(data);
	}else{
		$.each(data,function(i){
			var k=key==null?i:key+(data instanceof Array?"["+i+"]":"."+i);
			paramStr+='&'+parseParam(this, k);
		});
	}
	return paramStr.substr(1);
};

//简单的请求方法处理
function request(url,data,cb,method,dataType){
	if(typeof method =='undefined') method='GET';
	if(typeof dataType =='undefined') dataType='json';
	if(typeof data =='undefined') data={};
	if(method=='GET' || method =='get'){
		var str = parseParam(data)
		if(str){
			url += ((url.indexOf('?') > 0  ) ? '&' : '?') + str;
		}
		data = {};
	}
	$.ajax({
		url : url,
		type:method,
		dataType:dataType,
		data:data,
		success:function(res){
			if(res.status == -102){
				white_hint('登录已过期！',false);
				setTimeout(function(){
					window.location.href = 'Mobile/User/login';
				},2000)
			}
			if(typeof cb =='function')  cb(res);
		}
	})



}