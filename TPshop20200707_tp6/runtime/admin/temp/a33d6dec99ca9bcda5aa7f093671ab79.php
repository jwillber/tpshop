<?php /*a:2:{s:48:"Z:\demo\app\admin\view\block/template_class.html";i:1593659384;s:41:"Z:\demo\app\admin\view\public/layout.html";i:1593659384;}*/ ?>
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
  
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default;">
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>行业及风格分类</h3>
        <h5>行业及风格添加与管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    	<div class="bckopa-tips">
            <div class="title">
                <img src="/public/static/images/handd.png" alt="">
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            </div>
            <ul>
              <li>新增风格时，可选择行业分类。</li>
            </ul>
        </div>
        <span title="收起提示" id="explanationZoom" style="display: block;"></span>
  </div>

  <form method="post">
    <input type="hidden" name="form_submit" value="ok">
    <div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
          		<h3>行业列表</h3>
          		<h5></h5>
        	</div>
        	<a href="<?php echo url('block/class_info',array('act'=>'add')); ?>"><div class="fbutton"><div class="add" title="新增分类"><span><i class="fa fa-plus"></i>新增行业</span></div></div></a> 
        	<div class="fbutton"><div class="add" title="收缩分类"><span onclick="tree_open(this);"><i class="fa fa-angle-double-up"></i>收缩行业</span></div></div>
      </div>
      <div class="hDiv">
        <div class="hDivBox">
          <table cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th align="center" class="sign" axis="col0">
                  <!--<div style="text-align: center; width: 24px;"><i class="ico-check"></i></div>-->
                </th>
                <th align="center" class="handle" axis="col1">
                  <div style="text-align: center; width: 150px;">操作</div>
                </th>
                <th align="center" axis="col2">
                  <div style="text-align: center; width: 60px;">排序</div>
                </th>
                <th align="center" axis="col3" class="">
                  <div class="sundefined" style="text-align: center; width: 250px;">名称</div>
                </th>
                <th axis="col4">
                  <div></div>
                </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>     
      <div class="bDiv" style="height: auto;">
        <table class="flex-table autoht" cellpadding="0" cellspacing="0" border="0" id="article_cat_table">
          <tbody id="treet1">
          <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
            <tr nctype="0" class="parent_id_<?php echo $vo['parent_id']; ?>" data-level="<?php echo $vo['level']; ?>"  id="<?php echo $vo['level']; ?>_<?php echo $vo['id']; ?>">
              <td class="sign">
                <div style="text-align: center; width: 24px;"> 
                	<img src="/public/static/images/tv-collapsable-last.gif" fieldid="2" status="open" id="icon_<?php echo $vo['level']; ?>_<?php echo $vo['id']; ?>" onClick="treeClicked(this,<?php echo $vo['id']; ?>)">                    
                </div>
              </td>
              <td class="handle">
                <div style="text-align:center;   min-width:150px !important; max-width:inherit !important;">
                  <span class="btn" style="padding-left:<?php echo ($vo['level'] * 4); ?>em"><em><?php if($vo['level'] != 0): ?>|-<?php endif; ?><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
                  <ul>
                     <li><a href="<?php echo url('block/class_info',array('act'=>'edit','id'=>$vo['id'])); ?>">编辑信息</a></li> 

                     <?php if($vo['parent_id'] == 0): ?>          
                     	<li><a href="<?php echo url('block/class_info',array('act'=>'add','parent_id'=>$vo['id'])); ?>">新增信息</a></li>
                     <?php endif; ?>
	                   
                     <li><a href="javascript:void(0)" data-url="<?php echo url('block/class_handle'); ?>" data-id="<?php echo $vo['id']; ?>" onClick="delfun(this)">删除信息</a></li>                                   
                  </ul>
                  </span>
                </div>
              </td>
              <td class="sort">
                <div style="text-align: center; width: 60px;">
                  <input type="text" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onblur="changeTableVal('template_class','id','<?php echo $vo['id']; ?>','sort_order',this)" size="4" value="<?php echo $vo['sort_order']; ?>" />
                </div>
              </td>
              <td class="name">
                <div style="text-align: center; width: 250px;">
                  <!--<input type="text" value="<?php echo $vo['name']; ?>" onblur="changeTableVal('article_cat','cat_id',<?php echo $vo['cat_id']; ?>,'cat_name',this)" <?php if(in_array(($vo['id']), is_array($article_system_id)?$article_system_id:explode(',',$article_system_id))): ?>readonly="readonly"<?php endif; ?>/>-->
                    <?php echo $vo['name']; ?>
                </div>
              </td>
            <td class="name">
              <div style="text-align: center; width: 350px;">
                <span><?php echo $vo['cat_desc']; ?></span>
              </div>
            </td>
              <td style="width: 100%;">
                <div>&nbsp;</div>
              </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>   

          </tbody>
        </table>        
      </div>
    </div>
  </form>
  <script type="text/javascript" src="/public/static/js/admincp.js"></script>
  <script>
      // 全选
      $('.ico-check').on('click',function () {
          if(!$(this).hasClass('ico-hcheck')){
              $(this).removeClass().addClass('ico-hcheck')
              $('#treet1 .sign').find('img').attr('src','/public/static/images/tv-expandable.gif')
          }else{
              $(this).removeClass().addClass('ico-check')
              $('#treet1 .sign').find('img').attr('src','/public/static/images/tv-collapsable-last.gif')
          }
      })
	function  tree_open(obj)
	{
		var tree = $('#article_cat_table tr[id^="1_"],#article_cat_table tr[id^="2_"], #list-table tr[id^="3_"] '); //,'table-row'
		if(tree.css('display')  == 'table-row')
		{
			$(obj).html("<i class='fa fa-angle-double-down'></i>展开分类");
			tree.css('display','none');
			//$("span[id^='icon_']").removeClass('glyphicon-minus');
			$("img[id^='icon_']").attr('src','/public/static/images/tv-expandable.gif');
		}else
		{
			$(obj).html("<i class='fa fa-angle-double-up'></i>收缩分类");
			tree.css('display','table-row');
			$("img[id^='icon_']").attr('src','/public/static/images/tv-collapsable-last.gif');
		}
	}
     // 点击展开 收缩节点
     function treeClicked(obj,cat_id){
		 var src = $(obj).attr('src');
		 if(src == '/public/static/images/tv-expandable.gif')
		 {
			 $(".parent_id_"+cat_id).show();
			 $(obj).attr('src','/public/static/images/tv-collapsable-last.gif');
		 }else{			 
			 $(obj).attr('src','/public/static/images/tv-expandable.gif');			 
			 
			 // 如果是点击减号, 遍历循环他下面的所有都关闭
			 var tbl = document.getElementById("article_cat_table");
			 cur_tr = obj.parentNode.parentNode.parentNode;
			 var fnd = false;
			  for (i = 0; i < tbl.rows.length; i++)
			  {
				  var row = tbl.rows[i];
				  
				  if (row == cur_tr)
				  {
					  fnd = true;         
				  }
				  else
				  {
					  if (fnd == true)
					  {
						 
						  var level = parseInt($(row).data('level'));
						  var cur_level = $(cur_tr).data('level');
						 
						  if (level > cur_level)
						  {
							  $(row).hide();		
							  $(row).find('img').attr('src','/public/static/images/tv-expandable.gif');
						  }
						  else
						  {
							  fnd = false;
							  break;
						  }
					  }
				  }
			  }			 
		 }		 
	 }

     function delfun(obj) {
       layer.confirm('确认删除？', {
                 btn: ['确定', '取消'] //按钮
               }, function () {
                //alert($(obj).attr('data-url'));return false;
                 // 确定
                 $.ajax({
                   type: 'post',
                   url : $(obj).attr('data-url'),
                   data : {act:'del',cat_id:$(obj).attr('data-id')},
                   dataType: 'json',
                   success: function (data) {
                     layer.closeAll();
                     if (data.status === 1) {
                       layer.msg('操作成功', {icon: 1});
                       $(obj).parent().parent().parent().parent().parent().parent().remove();
                       location.reload();
                     } else {
                       layer.msg(data.msg, {icon: 2, time: 2000});
                     }
                   }
                 })
               }, function (index) {
                 layer.close(index);
               }
       );
     }
  </script>
</div>
</body>
</html>