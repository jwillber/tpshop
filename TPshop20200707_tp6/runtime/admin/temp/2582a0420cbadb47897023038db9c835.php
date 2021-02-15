<?php /*a:2:{s:40:"Z:\demo\app\admin\view\report/index.html";i:1593659384;s:41:"Z:\demo\app\admin\view\public/layout.html";i:1593659384;}*/ ?>
<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
    <link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
    <![endif]-->
    <link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">html, body { overflow: visible;}</style>
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
    <script type="text/javascript" src="/public/static/js/admin.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="/public/static/js/common.js"></script>
    <script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
    <script src="/public/js/myFormValidate.js"></script>
    <script src="/public/js/myAjax2.js"></script>
    <script src="/public/js/global.js"></script>
    <script type="text/javascript">
        function delfunc(obj){
            layer.confirm('确认删除？', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        // 确定
                        $.ajax({
                            type : 'post',
                            url : $(obj).attr('data-url'),
                            data : {act:'del',del_id:$(obj).attr('data-id')},
                            dataType : 'json',
                            success : function(data){
                                layer.closeAll();
                                if(data.status==1){
                                    layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                        location.href = '';
//                                $(obj).parent().parent().parent().remove();
                                    });
                                }else{
                                    layer.msg(data, {icon: 2,time: 2000});
                                }
                            }
                        })
                    }, function(index){
                        layer.close(index);
                        return false;// 取消
                    }
            );
        }

        function selectAll(name,obj){
            $('input[name*='+name+']').prop('checked', $(obj).checked);
        }

        function get_help(obj){

            window.open("http://www.tp-shop.cn/");
            return false;

            layer.open({
                type: 2,
                title: '帮助手册',
                shadeClose: true,
                shade: 0.3,
                area: ['70%', '80%'],
                content: $(obj).attr('data-url'),
            });
        }

        function delAll(obj,name){
            var a = [];
            $('input[name*='+name+']').each(function(i,o){
                if($(o).is(':checked')){
                    a.push($(o).val());
                }
            })
            if(a.length == 0){
                layer.alert('请选择删除项', {icon: 2});
                return;
            }
            layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
                        $.ajax({
                            type : 'get',
                            url : $(obj).attr('data-url'),
                            data : {act:'del',del_id:a},
                            dataType : 'json',
                            success : function(data){
                                layer.closeAll();
                                if(data == 1){
                                    layer.msg('操作成功', {icon: 1});
                                    $('input[name*='+name+']').each(function(i,o){
                                        if($(o).is(':checked')){
                                            $(o).parent().parent().remove();
                                        }
                                    })
                                }else{
                                    layer.msg(data, {icon: 2,time: 2000});
                                }
                            }
                        })
                    }, function(index){
                        layer.close(index);
                        return false;// 取消
                    }
            );
        }

        /**
         * 全选
         * @param obj
         */
        function checkAllSign(obj){
            $(obj).toggleClass('trSelected');
            if($(obj).hasClass('trSelected')){
                $('#flexigrid > table>tbody >tr').addClass('trSelected');
            }else{
                $('#flexigrid > table>tbody >tr').removeClass('trSelected');
            }
        }
        /**
         * 批量公共操作（删，改）
         * @returns {boolean}
         */
        function publicHandleAll(type){
            var ids = '';
            $('#flexigrid .trSelected').each(function(i,o){
                ids += $(o).data('id')+',';
            });
            if(ids == ''){
                layer.msg('至少选择一项', {icon: 2, time: 2000});
                return false;
            }
            publicHandle(ids,type); //调用删除函数
        }

        /**
         * 批量公共操作（删）新
         * @returns {boolean}
         */
        function publicUpdateAll(name,field_name){
            var ids = '';
            $('#flexigrid .trSelected').each(function(i,o){
                if(ids ==''){
                    ids = $(o).data('id');
                }else{
                    ids += ','+$(o).data('id');
                }
            });
            if(ids == ''){
                layer.msg('至少选择一项', {icon: 2, time: 2000});
                return false;
            }
            publicUpdate(ids,name,field_name); //调用删除函数
        }
        /**
         * 公共操作（删，改）
         * @param type
         * @returns {boolean}
         */
        function publicHandle(ids,handle_type){
            layer.confirm('确认当前操作？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        // 确定
                        $.ajax({
                            url: $('#flexigrid').data('url'),
                            type:'post',
                            data:{ids:ids,type:handle_type},
                            dataType:'JSON',
                            success: function (data) {
                                layer.closeAll();
                                if (data.status == 1){
                                    layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                        location.href = data.url;
                                    });
                                }else{
                                    layer.msg(data.msg, {icon: 2, time: 2000});
                                }
                            }
                        });
                    }, function (index) {
                        layer.close(index);
                    }
            );
        }

        /**
         * 公共操作（删，改）新
         * @param type
         * @returns {boolean}
         */
        function publicUpdate(ids,name,field_name){
            layer.confirm('确认当前操作？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        // 确定
                        $.ajax({
                            url: '/Admin/System/deleteData',
                            type:'post',
                            data:{ids:ids,name:name,field_name:field_name},
                            dataType:'JSON',
                            success: function (data) {
                                layer.closeAll();
                                if (data.status == 1){
                                    layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                        window.location.reload();
                                    });
                                }else{
                                    layer.msg(data.msg, {icon: 2, time: 2000});
                                }
                            }
                        });
                    }, function (index) {
                        layer.close(index);
                    }
            );
        }

        function delfuntion(obj) {
            // 删除按钮
            layer.confirm('确认删除？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '/Admin/System/deleteData',
                    data : {ids:$(obj).attr('data-id'),name:$(obj).attr('data-name'),field_name:$(obj).attr('data-field_name')},
                    dataType: 'json',
                    success: function (data) {
                        layer.closeAll();
                        if (data.status == 1) {
                            $(obj).parent().parent().parent().remove();
                            layer.closeAll();
                        } else {
                            layer.alert(data, {icon: 2});  //alert('删除失败');
                        }
                    }
                })
            }, function () {
                layer.closeAll();
            });
        }
    </script>

</head>
<style>
    .fa-check-circle,.fa-ban{cursor:pointer}
</style>
<script src="/public/static/js/layer/laydate/laydate.js"></script>
<style>
	.flexigrid .sDiv2{
		border: none;
	}
	.flexigrid .sDiv2 .select{
		border: 1px solid #D7D7D7;
		border-radius: 4px;
		margin-right: 10px;
	}
	.flexigrid .sDiv2 .qsbox{
		border: 1px solid #D7D7D7;
		border-radius: 4px;
	}
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>统计报表 - 销售概况</h3>
				<h5>网站系统销售概况</h5>
			</div>
			<a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/57.html" style="display: <?php echo tpCache('basic.is_manual')?'block':'none'; ?>"  class="manual" target="_blank"><i class="fa fa-calendar"></i>销售概况手册</a>
		</div>
	</div>
	<!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="bckopa-tips">
            <div class="title">
                <img src="/public/static/images/handd.png" alt="">
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            </div>
            <ul>
                <li>可根据时间查询某个时间段的销售统计.</li>
                <li>每日销售金额、销售商品数.</li>
            </ul>
        </div>
        <span title="收起提示" id="explanationZoom" style="display: block;"></span>
	</div>
	<div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
				<h3>销售概况</h3>
				<h5>今日销售总额：￥<?php if(empty($today['today_amount']) || (($today['today_amount'] instanceof \think\Collection || $today['today_amount'] instanceof \think\Paginator ) && $today['today_amount']->isEmpty())): ?>0<?php else: ?><?php echo $today['today_amount']; ?><?php endif; ?>|人均客单价：￥<?php echo $today['sign']; ?>|今日订单数：<?php echo $today['today_order']; ?>|今日取消订单：<?php echo $today['cancel_order']; ?></h5>
			</div>
			<a href="" class="refresh-date"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
			<form class="navbar-form form-inline" id="search-form" method="get" action="<?php echo url('Report/index'); ?>" >
				<div class="sDiv">
					<div class="sDiv2" style="margin-right: 10px;">
						<input type="text" size="30" name="start_time" id="start_time" value="<?php echo $start_time; ?>" placeholder="起始时间" class="qsbox" autocomplete="off">
						<input type="button" class="btn" value="起始时间">
					</div>
					<div class="sDiv2" style="margin-right: 10px;">
						<input type="text" size="30" name="end_time" id="end_time" value="<?php echo $end_time; ?>" placeholder="截止时间" class="qsbox" autocomplete="off">
						<input type="button" class="btn" value="截止时间">
					</div>
					<div class="sDiv2">
						<input class="btn" value="搜索" type="button" onclick="return check_form();">
					</div>
				</div>
			</form>
		</div>
		<div id="statistics" style="height: 400px;"></div>
		<div class="hDiv">
			<div class="hDivBox">
				<table cellspacing="0" cellpadding="0">
					<thead>
					<tr>
						<th class="sign" axis="col0">
							<div style="width: 24px;"><i class="ico-check"></i></div>
						</th>
						<th align="center" abbr="article_title" axis="col3" class="">
							<div style="text-align: center; width: 120px;" class="">时间</div>
						</th>
						<th align="center" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 100px;" class="">订单数</div>
						</th>
						<th align="center" abbr="article_show" axis="col5" class="">
							<div style="text-align: center; width: 100px;" class="">销售总额</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 100px;" class="">客单价</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 100px;" class="">购买人数</div>
						</th>
						<th align="center" axis="col1" class="handle">
							<div style="text-align: center; width: 150px;">操作</div>
						</th>
						<th align="center" axis="col1" class="handle">
							<a href="javascript:export_report()" class="fbutton" style="margin-bottom: 0px;">
								<div class="add" title="选定行数据导出excel文件,如果不选中行，将导出列表所有数据" style="min-width: 50px !important;">
									<span><i class="fa fa-plus"></i>导出数据</span>
								</div>
							</a> 
						</th>
						<th style="width:100%" axis="col7">
							<div></div>
						</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
		<div class="bDiv" style="height: auto;">
			<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
				<table>
					<tbody>
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
						<tr data-time="<?php echo $vo['day']; ?>">
							<td class="sign">
								<div style="width: 24px;"><i class="ico-check"></i></div>
							</td>
							<td align="center" class="">
								<div style="text-align: center; width: 120px;"><?php echo $vo['day']; ?></div>
							</td>
							<td align="center" class="">
								<div style="text-align: center; width: 100px;"><?php echo $vo['order_num']; ?></div>
							</td>
							<td align="center" class="">
								<div style="text-align: center; width: 100px;"><?php echo $vo['amount']; ?></div>
							</td>
							<td align="center" class="">
								<div style="text-align: center; width: 100px;"><?php echo $vo['sign']; ?></div>
							</td>
							<td align="center" class="">
								<div style="text-align: center; width: 100px;"><?php echo $vo['user_num']; ?></div>
							</td>
							<td align="center" class="handle">
								<div style="text-align: center; width: 170px; max-width:170px;">
									<a href="<?php echo url('Report/saleOrder',array('start_time'=>$vo['day'],'end_time'=>$vo['end'])); ?>" class="btn blue"></i>查看订单列表</a>
								</div>
							</td>
							<td align="" class="" style="width: 100%;">
								<div>&nbsp;</div>
							</td>
						</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
			</div>
			<div class="iDiv" style="display: none;"></div>
		</div>
	 </div>
</div>
<script src="/public/js/echart/echarts.min.js" type="text/javascript"></script>
<script src="/public/js/echart/macarons.js"></script>
<script src="/public/js/echart/china.js"></script>
<script src="/public/dist/js/app.js" type="text/javascript"></script>
<script type="text/javascript">
	var res = <?php echo $result; ?>;
	var myChart = echarts.init(document.getElementById('statistics'),'macarons');
	option = {
		tooltip : {
			trigger: 'axis'
		},
		toolbox: {
			show : true,
			feature : {
				mark : {show: true},
				dataView : {show: true, readOnly: false},
				magicType: {show: true, type: ['line', 'bar']},
				restore : {show: true},
				saveAsImage : {show: true}
			}
		},
		calculable : true,
		legend: {
			data:['交易金额','订单数','客单价']
		},
		xAxis : [
			{
				type : 'category',
				data : res.time
			}
		],
		yAxis : [
			{
				type : 'value',
				name : '金额',
				axisLabel : {
					formatter: '{value} ￥'
				}
			},
			{
				type : 'value',
				name : '客单价',
				axisLabel : {
					formatter: '{value} ￥'
				}
			}
		],
		series : [
			{
				name:'交易金额',
				type:'bar',
				data:res.amount
			},
			{
				name:'订单数',
				type:'bar',
				data:res.order
			},
			{
				name:'客单价',
				type:'line',
				yAxisIndex: 1,
				data:res.sign
			}
		]
	};
	myChart.setOption(option);
	$(document).ready(function(){
		// 表格行点击选中切换
		$('#flexigrid > table>tbody >tr').click(function(){
			$(this).toggleClass('trSelected');
		});

		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});

		$('#start_time').layDate();
		$('#end_time').layDate();
	});

	function check_form(){
		var start_time = $.trim($('#start_time').val());
		var end_time =  $.trim($('#end_time').val());
		if(start_time == '' || end_time == ''){
			layer.alert('请选择完整的时间间隔', {icon: 2});
			return false;
		}		 
		$('#search-form').submit();
	}
	function export_report() {
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		var selected_times  = [];
        $('.trSelected' , '#flexigrid').each(function(i){
            selected_times.push($(this).data('time'));    
        });
        var tmp_url = "/Admin/Report/export_report?selected_times=" + selected_times + "&start_time=" + start_time + "&end_time=" + end_time;
  		window.open(tmp_url, "_blank");
	}
</script>
</body>
</html>