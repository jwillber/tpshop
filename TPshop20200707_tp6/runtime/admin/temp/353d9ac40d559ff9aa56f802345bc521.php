<?php /*a:2:{s:42:"Z:\demo\app\admin\view\supplier/index.html";i:1593659384;s:41:"Z:\demo\app\admin\view\public/layout.html";i:1593659384;}*/ ?>
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
				<h3>供应商管理</h3>
				<h5>网站系统供应商索引与管理</h5>
			</div>
		</div>
	</div>
	<!-- 操作说明 -->
	<div id="explanation" class="explanation">
        <div class="bckopa-tips">
            <div class="title">
                <img src="/public/static/images/handd.png" alt="">
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            </div>
            <ul>
                <li>供应商管理, 由平台设置管理.</li>
            </ul>
        </div>
        <span title="收起提示" id="explanationZoom" style="display: block;"></span>
	</div>
	<div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
				<h3>供应商列表</h3>
				<h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
			</div>
            <div class="fbutton"> <a href="<?php echo url('Supplier/supplier_info'); ?>"><div class="add" title="添加供应商"><span><i class="fa fa-plus"></i>添加供应商</span></div></a> </div>
			<a href="" class="refresh-date"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
			<form action="" id="search-form2" class="navbar-form form-inline" method="post" action="<?php echo url('Admin/Supplier/index'); ?>">
			  <div class="sDiv">
				<div class="sDiv2">       
				  <select name="is_check" id="is_on_sale" class="select">
					<option value="">全部状态</option>                  
					<option value="1" <?php if(app('request')->param('is_check')=='1') echo  'selected="selected"'; ?>>正常</option>
					<option value="0" <?php if(app('request')->param('is_check')=='0') echo  'selected="selected"'; ?>>冻结</option>
				  </select>
					<select name="key_type" class="select">
						<option value="suppliers_name">供应商</option>
						<option value="nickname" <?php if(app('request')->param('key_type')=='nickname') echo  'selected="selected"'; ?>>会员</option>
					</select>
				  <input type="text" size="30" name="key_word" class="qsbox" placeholder="搜索词..." value="<?php echo app('request')->param('key_word'); ?>">
				  <input type="submit" class="btn" value="搜索">
				</div>
			  </div>
			</form>
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
							<div style="text-align: left; width: 100px;" class="">ID</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">供应商名称</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">供应商描述</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">供应商联系人</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">供应商电话</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 120px;" class="">所属会员账号</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">上架商品数</div>
						</th>
						<th align="center" abbr="article_show" axis="col5" class="">
							<div style="text-align: center; width: 80px;" class="">状态</div>
						</th>
						<th align="center" axis="col1" class="handle">
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
		<div class="bDiv" style="height: auto;">
			<div id="flexigrid" cellpadding="0" cellspacing="0" border="0" data-url="<?php echo url('admin/Supplier/del'); ?>">
				<table>
					<tbody>
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
						<tr data-id="<?php echo $vo['suppliers_id']; ?>">
							<td class="sign">
								<div style="width: 24px;"><i class="ico-check"></i></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;"><?php echo $vo['suppliers_id']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;"><?php echo $vo['suppliers_name']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;"><?php echo $vo['suppliers_desc']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;"><?php echo $vo['suppliers_contacts']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;"><?php echo $vo['suppliers_phone']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 120px;"><?php echo !empty($vo['mobile']) ? $vo['mobile'] : $vo['email']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;" id="goods-count-<?php echo $vo['suppliers_id']; ?>"><?php echo (isset($vo['count']) && ($vo['count'] !== '')?$vo['count']:'0'); ?></div>
							</td>
							<td align="center" class="">
								<div style="text-align: center; width: 80px;">
									<?php if($vo['is_check'] == 1): ?>
										<span class="yes" onClick="changeSupplierIsCheck('<?php echo $vo['suppliers_id']; ?>',this, '正常', '冻结')" ><i class="fa fa-check-circle"></i>正常</span>
										<?php else: ?>
										<span class="no" onClick="changeSupplierIsCheck('<?php echo $vo['suppliers_id']; ?>',this, '正常', '冻结')" ><i class="fa fa-ban"></i>冻结</span>
									<?php endif; ?>
								</div>
							</td>
							<td align="center" class="handle">
								<div style="text-align: center; width: 170px; max-width:170px;">
									<a href="<?php echo url('Supplier/supplier_info',array('suppliers_id'=>$vo['suppliers_id'])); ?>" class="btn blue">编辑</a>
									<a class="btn red" href="javascript:void(0)" data-id="<?php echo $vo['suppliers_id']; ?>" onClick="delfun(this)">删除</a>
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
		<?php echo $pager->show(); ?> </div>
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


	function delfun(obj) {
		// 删除按钮
		layer.confirm('确认删除？', {
			btn: ['确定', '取消'] //按钮
		}, function () {
			$.ajax({
				type: 'post',
				url: "<?php echo url('Supplier/del'); ?>",
				data : {id:$(obj).attr('data-id')},
				dataType: 'json',
				success: function (data) {
					layer.closeAll();
					if (data.status == 1) {
						$(obj).parent().parent().parent().remove();
						layer.msg(data.msg, {icon: 1});
					} else {
						layer.alert(data.msg, {icon: 2});
					}
				}
			})
		}, function () {
			layer.closeAll();
		});
	}
	
	// 改变供应商的is_check状态
	function changeSupplierIsCheck(id,obj,yes,no) {
		var value = $(obj).val();
		if(yes == '' || typeof(yes)== 'undefined')yes='是';
		if(no == '' || typeof(no) == 'undefined')no='否';

		$.ajax({
			url: "<?php echo url('Admin/Supplier/changeSupplierIsCheck'); ?>?suppliers_id=" + id,
			dataType: 'json',
			success: function (data) {
				if (data.status == 1) {
					if ($(obj).hasClass('no')) // 图片点击是否操作
					{
						//src = '/public/images/yes.png';
						$(obj).removeClass('no').addClass('yes');
						$(obj).html("<i class='fa fa-check-circle'></i>"+yes+"");
						value = 1;
					} else if ($(obj).hasClass('yes')) { // 图片点击是否操作
						$(obj).removeClass('yes').addClass('no');
						$(obj).html("<i class='fa fa-ban'></i>"+no+"");
						value = 0;
					}
					layer.msg('更新成功', {icon: 1});
					$('#goods-count-' + data.result.suppliers_id).text(data.result.goods_count);
				}
			}
		});
	}
</script>
</body>