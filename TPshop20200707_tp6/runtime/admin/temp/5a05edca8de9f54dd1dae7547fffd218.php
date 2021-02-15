<?php /*a:2:{s:52:"Z:\demo\app\admin\view\promotion/group_buy_list.html";i:1593659384;s:41:"Z:\demo\app\admin\view\public/layout.html";i:1593659384;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>团购管理</h3>
				<h5>网站系统抢购活动审核与管理</h5>
			</div>
			<a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/48.html" style="display: <?php echo tpCache('basic.is_manual')?'block':'none'; ?>"  class="manual" target="_blank"><i class="fa fa-calendar"></i>团购帮助手册</a>
		</div>
	</div>
	<!-- 操作说明 -->
	<div id="explanation" class="explanation" >
        <div class="bckopa-tips">
            <div class="title">
                <img src="/public/static/images/handd.png" alt="">
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            </div>
            <ul>
                <li>团购管理, 由平台设置管理.</li>
				<li>团购活动删除：没有订单参与的团购活动，后台可删除。若是团购活动里已有订单，将不能删除。</li>
				<li>团购活动没有账号限购设置，也就是说，只要在团购库存内，同个账号购买团购数量将不受任何限制。</li>
				<li>已结束或未开始的团购活动不显示在前台，只有进行中的才会显示在前台。</li>
            </ul>
        </div>
        <span title="收起提示" id="explanationZoom" style="display: block;"></span>
	</div>
	<div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
				<h3>团购活动列表</h3>
				<h5>(共<?php echo $page->totalRows; ?>条记录)</h5>
			</div>
            <a href="<?php echo url('Promotion/group_buy'); ?>"><div class="fbutton"><div title="添加团购" class="add"><span><i class="fa fa-plus"></i>添加团购</span></div></div></a>
            <div class="fbutton"><a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/48.html" target="_blank"><div class="add" title="帮助"><span>帮助</span></div></a></div>
			<a href="" class="refresh-date"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
		</div>
		<div class="hDiv">
			<div class="hDivBox">
				<table cellspacing="0" cellpadding="0">
					<thead>
					<tr>
						<th class="sign" axis="col0">
							<div style="width: 24px;"><i class="ico-check"></i></div>
						</th>
						<th align="left" abbr="article_title" axis="col3" class="">
							<div style="text-align: left; width: 240px;" class="">团购标题</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 80px;" class="">团购价格</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 120px;" class="">开始时间</div>
						</th>
						<th align="center" abbr="article_show" axis="col5" class="">
							<div style="text-align: center; width: 120px;" class="">结束时间</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">已参团</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">参团库存</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">折扣</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">活动状态</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">是否启动</div>
						</th>
						<th align="left" axis="col1" class="handle">
							<div style="text-align: center; width: 150px;">操作</div>
						</th>
						<th style="width:100%" axis="col7">
							<div></div>
						</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
		<!--<div class="tDiv">
			<div class="tDiv2">
				<a href="<?php echo url('Promotion/group_buy'); ?>">
					<div class="fbutton">
						<div title="添加团购" class="add">
							<span><i class="fa fa-plus"></i>添加团购</span>
						</div>
					</div>
				</a>
			</div>
			<div style="clear:both"></div>
		</div>-->
		<div class="bDiv" style="height: auto;">
			<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
				<table>
					<tbody>
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
						<tr>
							<td class="sign">
								<div style="width: 24px;"><i class="ico-check"></i></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 240px;"><?php echo getSubstr($vo['title'],0,30); ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $vo['price']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 120px;"><?php echo date('Y-m-d H:i',!is_numeric($vo['start_time'])? strtotime($vo['start_time']) : $vo['start_time']); ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 120px;"><?php echo date('Y-m-d H:i',!is_numeric($vo['end_time'])? strtotime($vo['end_time']) : $vo['end_time']); ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $vo['buy_num']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $vo['goods_num']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $vo['rebate']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $vo['status_desc']; ?></div>
							</td>

							<td align="left" class="">
								<div style="text-align: center; width: 80px;">
									<?php if($vo['is_end'] == 0): ?>
										<span class="yes" onClick="changeTableVal2('group_buy','id','<?php echo $vo['id']; ?>','is_end',this,'是','否','<?php echo $vo['status_desc']; ?>')" ><i class="fa fa-check-circle"></i>是</span>
										<?php else: ?>
										<span class="no" onClick="changeTableVal2('group_buy','id','<?php echo $vo['id']; ?>','is_end',this,'是','否')" ><i class="fa fa-ban"></i>否</span>
									<?php endif; ?>
								</div>
							</td>

							<td align="left" class="handle">
								<div style="text-align: left; width: 170px; max-width:170px;">
									<a class="btn blue" target="_blank" href="<?php echo url('Home/Goods/goodsInfo',array('id'=>$vo['goods_id'],'item_id'=>$vo['item_id'])); ?>">查看</a>
									<a class="btn blue" href="<?php echo url('Promotion/group_buy',array('act'=>'edit','id'=>$vo['id'])); ?>">编辑</a>
									<a class="btn red" href="javascript:void(0)" data-url="<?php echo url('Promotion/groupbuyHandle'); ?>" data-id="<?php echo $vo['id']; ?>" onClick="delfun(this)">删除</a>
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
		<!--分页位置-->
		<?php echo $page->show(); ?> </div>
</div>
<script>
	$(document).ready(function(){
        //单选全选
        $('.ico-check ' , '.hDivBox').click(function(){
            $('tr' ,'.hDivBox').toggleClass('trSelected' , function(index,currentclass){
                var hasClass = $(this).hasClass('trSelected');
                $('tr' , '#flexigrid').each(function(){
                    if(hasClass){
                        $(this).addClass('trSelected');
                    }else{
                        $(this).removeClass('trSelected');
                    }
                });
            });
        });
		// 表格行点击选中切换
		$('#flexigrid > table>tbody >tr').click(function(){
			$(this).toggleClass('trSelected');
		});

		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});
	});

	function changeStatus(status,id,tab){
		if(status>1){
			layer.confirm('确认删除？', {btn: ['确定','取消']}, function(){
				$.ajax({
					type : 'GET',
					url : "<?php echo url('Promotion/activity_handle'); ?>",
					data : {'id':id,'tab':tab,'status':status},
					dataType :'JSON',
					success : function(res){
						layer.closeAll();
						if(res == 1){
							layer.msg('操作成功', {icon: 1});
							window.location.reload();
						}else{
							layer.msg('操作失败', {icon: 2,time: 2000});
						}
					}
				});
			}, function(index){
				layer.close(index);
				return false;// 取消
			});
		}else{
			$.ajax({
				type : 'GET',
				url : "<?php echo url('Promotion/activity_handle'); ?>",
				data : {'id':id,'tab':tab,'status':status},
				dataType :'JSON',
				success : function(res){
					if(res == 1){
						layer.msg('操作成功', {icon: 1});
						window.location.reload();
					}else{
						layer.msg('操作失败', {icon: 2,time: 2000});
					}
					layer.closeAll();
				}
			});
		}
	}

	function delfun(obj) {
		// 删除按钮
		layer.confirm('确认删除？', {
			btn: ['确定', '取消'] //按钮
		}, function () {
			$.ajax({
				type: 'post',
				url: $(obj).attr('data-url'),
				data : {act:'del',id:$(obj).attr('data-id')},
				dataType: 'json',
				success: function (data) {
					layer.closeAll();
					if (data == 1) {
						$(obj).parent().parent().parent().remove();
					} else {
						layer.alert('删除失败', {icon: 2});  //alert('删除失败');
					}
				}
			})
		}, function () {
			layer.closeAll();
		});
	}

    // 修改指定表的指定字段值 包括有按钮点击切换是否 或者 排序 或者输入框文字
    function changeTableVal2(table, id_name, id_value, field, obj,yes,no,status) {
        if (status == '进行中') {
            layer.confirm('活动进行中，确认关闭？', {
                btn: ['确定', '取消'] //按钮
            },function () {
                var value = $(obj).val();
                if(yes == '' || typeof(yes)== 'undefined')yes='是';
                if(no == '' || typeof(no) == 'undefined')no='否';
                if ($(obj).hasClass('yes')) // 图片点击是否操作
                {
                    $(obj).removeClass('no').addClass('yes');
                    $(obj).html("<i class='fa fa-check-circle'></i>"+yes+"");
                    value = 1;
                } else if ($(obj).hasClass('yes')) { // 图片点击是否操作
                    $(obj).removeClass('yes').addClass('no');
                    $(obj).html("<i class='fa fa-ban'></i>"+no+"");
                    value = 0;
                }

                $.ajax({
                    url: "/index.php?m=Admin&c=promotion&a=change_group_buy_is_end&id="+id_value,
                    dataType :'JSON',
                    success: function (data) {
                        console.log(data)
                        if (data.status == 1) {
                            if (!$(obj).hasClass('no') && !$(obj).hasClass('yes'))
                                layer.msg('更新成功', {icon: 1});
                            window.location.reload();
                        }else{
                            layer.msg(data.msg, {icon: 2});
                        }
                    }
                });
            },function (index) {
                layer.close(index);
                return false;
            })
        }else{
            var value = $(obj).val();
            if(yes == '' || typeof(yes)== 'undefined')yes='是';
            if(no == '' || typeof(no) == 'undefined')no='否';
            if ($(obj).hasClass('yes')) // 图片点击是否操作
            {
                $(obj).removeClass('no').addClass('yes');
                $(obj).html("<i class='fa fa-check-circle'></i>"+yes+"");
                value = 1;
            } else if ($(obj).hasClass('yes')) { // 图片点击是否操作
                $(obj).removeClass('yes').addClass('no');
                $(obj).html("<i class='fa fa-ban'></i>"+no+"");
                value = 0;
            }

            $.ajax({
                url: "/index.php?m=Admin&c=promotion&a=change_group_buy_is_end&id="+id_value,
                dataType :'JSON',
                success: function (data) {
                    console.log(data)
                    if (data.status == 1) {
                        if (!$(obj).hasClass('no') && !$(obj).hasClass('yes'))
                            layer.msg('更新成功', {icon: 1});
                        window.location.reload();
                    }else{
                        layer.msg(data.msg, {icon: 2});
                    }
                }
            });
        }

    }
</script>
</body>
</html>