<?php /*a:2:{s:43:"Z:\demo\app\admin\view\goods/goodsList.html";i:1593659384;s:41:"Z:\demo\app\admin\view\public/layout.html";i:1593659384;}*/ ?>
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
<style>
    span.type-virtual {
        background-color: #3598DC;
        line-height: 16px;
        color: #FFF;
        display: inline-block;
        height: 16px;
        padding: 1px 4px;
        margin-right: 2px;
        box-shadow: inset 1px 1px 0 rgba(255,255,255,0.25);
        cursor: default;
    }
    .flexigrid .sDiv2{
        border: none;
    }
    .flexigrid .sDiv2 .select{
        border: 1px solid #d7d7d7;
        margin-right: 10px;
        border-radius: 4px;
    }
    .flexigrid .sDiv2 .qsbox{
        border: 1px solid #d7d7d7;
        border-radius: 4px;
    }
    /*.pagination{*/
        /*padding-bottom: 150px;*/
    /*}*/
    .flexigrid .bDiv{
        padding-bottom: 185px;
    }
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品管理</h3>
        <h5>商城所有商品索引及管理</h5>
          <a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/34.html" style="display: <?php echo tpCache('basic.is_manual')?'block':'none'; ?>" class="manual" target="_blank"><i class="fa fa-calendar"></i>帮助手册</a>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div id="explanation" class="explanation">
  	<div class="bckopa-tips" style="width: 100%">
    	<div class="title">
            <img src="/public/static/images/handd.png" alt="">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
        </div>
        <ul>
        	<li>商品管理注意发布商品后清理缓存.</li>
          	<li>商品缩列图也有缓存.</li>
            <li>商品管理列表可查看所有的在售/下架商品，也可以控制商品是否下架/上架，选择是否设置推荐/热卖/新品商品，只有标注了推荐/热卖/新品才会在前台相关推荐页展示出来。。</li>
            <li>上架商品：只有商品上架状态，才能售卖。</li>
            <li>商品排序：可改变商品的排序位置，数字越小，越靠前。</li>
            <li><strong style="color:red">推荐：设置"推荐"的商品可在手机首页列表和PC首页热门推荐显示</strong></li>
            <li><strong style="color:green">温馨提示：点击"推荐"、"新品"、"热卖"、"上/下架"列中的"是/否"可以直接设置哦</strong></li>
        </ul>
    </div>
    <span title="收起提示" id="explanationZoom" style="display: block;"></span>
  </div>
  <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle"><h3>商品列表</h3></div>
            <div class="fbutton"><a href="<?php echo url('Admin/goods/addEditGoods'); ?>"><div class="add" title="添加商品"><span><i class="fa fa-plus"></i>添加商品</span></div></a></div> 
            <div class="fbutton"><a href="javascript:;" onclick="publicHandleAll('del')"><div class="add" title="批量删除"><span>批量删除</span></div></a></div>
        	<div class="fbutton"><a href="<?php echo url('Admin/Goods/initGoodsSearchWord'); ?>"><div class="add" title="初始化商品搜索关键词"><span>初始化商品搜索关键词</span></div></a></div>
            <a href="" class="refresh-date"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
	<form action="" id="search-form2" class="navbar-form form-inline" method="post" onSubmit="return false">
      <div class="sDiv">
        <div class="sDiv2">     
		  <select name="suppliers_id" id="suppliers_id" class="select">
            <option value="-1">选择供应商</option>
			<option value="0">平台</option>
            <?php if(is_array($supplier_list) || $supplier_list instanceof \think\Collection || $supplier_list instanceof \think\Paginator): if( count($supplier_list)==0 ) : echo "" ;else: foreach($supplier_list as $k=>$vo): ?>
                <option value="<?php echo $k; ?>"> <?php echo $vo; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <select name="cat_id" id="cat_id" class="select">
            <option value="">所有分类</option>
            <?php if(is_array($categoryList) || $categoryList instanceof \think\Collection || $categoryList instanceof \think\Paginator): if( count($categoryList)==0 ) : echo "" ;else: foreach($categoryList as $k=>$v): ?>
                <option value="<?php echo $v['id']; ?>"> <?php echo $v['name']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <select name="brand_id" id="brand_id" class="select">
            <option value="">所有品牌</option>
                <?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): if( count($brandList)==0 ) : echo "" ;else: foreach($brandList as $k=>$v): ?>
                   <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>          
          <select name="is_on_sale" id="is_on_sale" class="select">
            <option value="">全部</option>                  
            <option value="1">上架</option>
            <option value="0">下架</option>
          </select>
            <select name="intro" class="select">
                <option value="0">全部</option>
                <option value="is_new">新品</option>
                <option value="is_recommend">推荐</option>
            </select>     

            <!--排序规则-->
            <input type="hidden" name="orderby1" value="goods_id" />
            <input type="hidden" name="orderby2" value="desc" />
          <input type="text" size="30" name="key_word" class="qsbox" placeholder="搜索词...">
          <input type="button" onClick="ajax_get_table('search-form2',1)" class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th class="sign" axis="col6" onclick="checkAllSign(this)">
                <div style="width: 24px;"><i class="ico-check"></i></div>
              </th>
              <th align="left" abbr="article_title" axis="col6" class="">
                <div style="text-align: center; width:65px;" class="">操作</div>
              </th>              
              <th align="left" abbr="article_title" axis="col6" class="">
                <div style="text-align: left; width:50px;" class="" onClick="sort('goods_id');">id</div>
              </th>
              <th align="left" abbr="ac_id" axis="col4" class="">
                <div style="text-align: left; width: 300px;" class="" onClick="sort('goods_name');">商品名称</div>
              </th>
              <th align="center" abbr="article_show" axis="col6" class="">
                <div style="text-align: center; width: 100px;" class="" onClick="sort('goods_sn');">货号</div>
              </th>
			  <th align="center" abbr="article_show" axis="col6" class="">
                <div style="text-align: center; width: 100px;" class="">供应商</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 100px;" class="" onClick="sort('cat_id');">分类</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('shop_price');">价格</div>
              </th>                  
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 60px;" class="" onClick="sort('is_recommend');">推荐</div>
              </th>                       
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 60px;" class="" onClick="sort('is_new');">新品</div>
              </th>                                     
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 60px;" class="" onClick="sort('is_hot');">热卖</div>
              </th>  
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('is_on_sale');">上/下架</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('store_count');">库存</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('sort');">排序</div>
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
        <div class="fbutton">       
          <a href="<?php echo url('Admin/goods/addEditGoods'); ?>">
          <div class="add" title="添加商品">
            <span><i class="fa fa-plus"></i>添加商品</span>
          </div>
          </a>          
          </div> 
        <div class="fbutton">
            <a href="<?php echo url('Admin/Goods/initGoodsSearchWord'); ?>">
                <div class="add" title="初始化商品搜索关键词">
                    <span><i class="fa fa-plus"></i>初始化商品搜索关键词</span>
                </div>
            </a>
        </div>
        <div class="fbutton">
          <a href="javascript:;" onclick="publicHandleAll('del')">
              <div class="add" title="批量删除">
                  <span>批量删除</span>
              </div>
          </a>
        </div>
      </div>
      <div style="clear:both"></div>
    </div>-->
    <div class="bDiv" style="height: auto;">
     <!--ajax 返回 --> 
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0" data-url="<?php echo url('admin/goods/delGoods'); ?>"></div>
    </div>

     </div>
</div>
<script>

    $(document).ready(function(){
		// 刷选条件 鼠标 移动进去 移出 样式
		$(".hDivBox > table > thead > tr > th").mousemove(function(){
			$(this).addClass('thOver');
		}).mouseout(function(){
			$(this).removeClass('thOver');
		});

        // 表格行点击选中切换
        $(document).on('click','#flexigrid > table>tbody >tr',function(){
            $(this).toggleClass('trSelected');
            var checked = $(this).hasClass('trSelected');
            $(this).find('input[type="checkbox"]').attr('checked',checked);
        });
	});

    $(document).ready(function () {
        // ajax 加载商品列表
        ajax_get_table('search-form2', 1);

    });

    // ajax 抓取页面 form 为表单id  page 为当前第几页
    function ajax_get_table(form, page) {
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type: "POST",
            url: "/index.php?m=Admin&c=goods&a=ajaxGoodsList&p=" + page,//+tab,
            data: $('#' + form).serialize(),// 你的formid
            success: function (data) {
                $("#flexigrid").html('');
                $("#flexigrid").append(data);
            }
        });
    }
	
	// 点击排序
	function sort(field)
	{
	   $("input[name='orderby1']").val(field);
	   var v = $("input[name='orderby2']").val() == 'desc' ? 'asc' : 'desc';             
	   $("input[name='orderby2']").val(v);
	   ajax_get_table('search-form2',cur_page);
	}
	
	//导出库存盘点excel模板
    function export_goods() {
        var ids='';
        $("tr[class='trSelected']").each(function(){
            ids+=$(this).attr('data-id')+',';
        });
        var tmp_url = "/Admin/Goods/export_goods?ids=" + ids;
        window.open(tmp_url, "_blank");
    }
</script>
</body>
</html>