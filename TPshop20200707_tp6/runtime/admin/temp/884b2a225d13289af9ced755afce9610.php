<?php /*a:2:{s:80:"C:\Users\admin\Desktop\TPshop20200701_tp6\app\admin\view\block/templateList.html";i:1593659384;s:75:"C:\Users\admin\Desktop\TPshop20200701_tp6\app\admin\view\public/layout.html";i:1593659384;}*/ ?>
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
<script type="text/javascript" src="/public/static/js/layui/layui.js?v=2.3.0"></script>
<script src="/public/static/js/layuifun.js"></script>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>自定义页面管理</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a onclick="template(0)" class="current"><span>我的模板</span></a></li>

                    <li><a onclick="template(1)"><span>模板库</span></a></li>
               
            </ul>
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
                <li>使用中的模板只能编辑、不可删除</li>
                <li>未使用的模板可编辑、可删除</li>
            </ul>
        </div>
        <span title="收起提示" id="explanationZoom" style="display: block;"></span>
    </div>

    <div class="container-body" id="my-template">
        <div class="tpl-body">
            <?php if(is_array($templates) || $templates instanceof \think\Collection || $templates instanceof \think\Paginator): $i = 0; $__LIST__ = $templates;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['is_index'] == 1): ?>
                    <div class="tpl-item" id="del<?php echo $vo['id']; ?>">
                        <img src="../../../../public/static/images/checkedphone.png" alt=""
                             style="width: 226px; height: 458px;">

                        <div class="tpl-img"><img
                                src="<?php echo (isset($vo['thumb']) && ($vo['thumb'] !== '')?$vo['thumb']:'https://weapp-1253522117.image.myqcloud.com//image/20171207/6c79a4121d22bf90.png'); ?>"
                                alt=""></div>
                        <div class="tpl-hover mytpl-hover">
                            <div class="tpl-btn tpl-btn-add" style="top:15px;background: none">
                                <a><?php echo $vo['template_name']; ?></a></div>
                            <div class="icon-opa">

                                <div class="icon-box icon-box1"><a
                                        href="<?php echo url('Admin/Block/index',array('id'=>$vo['id'])); ?>" target="__black"><i
                                        class="iconfont"></i></a></div>

                                <?php if($vo['is_index'] == 1): ?>
                                    <div class="icon-box icon-box2"><i class="iconfont iconfont-noedit"></i></div>
                                    <?php else: ?>
                                    <div class="icon-box icon-box2" onclick="dele(<?php echo $vo['id']; ?>)"><i class="iconfont"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="tpl-btn tpl-btn-active" onClick="changeStatus(<?php echo $vo['id']; ?>,<?php echo $vo['is_index']; ?>)">取消使用
                            </div>

                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; endif; else: echo "" ;endif; if(is_array($templates) || $templates instanceof \think\Collection || $templates instanceof \think\Paginator): $i = 0; $__LIST__ = $templates;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['is_index'] == 0): ?>
                    <div class="tpl-item" id="del<?php echo $vo['id']; ?>">
                        <img src="../../../../public/static/images/phone.png" alt=""
                             style="width: 226px; height: 458px;">

                        <div class="tpl-img"><img
                                src="<?php echo (isset($vo['thumb']) && ($vo['thumb'] !== '')?$vo['thumb']:'https://weapp-1253522117.image.myqcloud.com//image/20171207/6c79a4121d22bf90.png'); ?>"
                                alt=""></div>
                        <div class="tpl-hover mytpl-hover">
                            <div class="tpl-btn tpl-btn-add" style="top:15px;background: none">
                                <a><?php echo $vo['template_name']; ?></a></div>
                            <div class="icon-opa">

                                <div class="icon-box icon-box1"><a
                                        href="<?php echo url('Admin/Block/index',array('id'=>$vo['id'])); ?>" target="__black"><i
                                        class="iconfont"></i></a></div>

                                <?php if($vo['is_index'] == 1): ?>
                                    <div class="icon-box icon-box2"><i class="iconfont iconfont-noedit"></i></div>
                                    <?php else: ?>
                                    <div class="icon-box icon-box2" onclick="dele(<?php echo $vo['id']; ?>)"><i class="iconfont"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tpl-btn" onClick="changeStatus(<?php echo $vo['id']; ?>,<?php echo $vo['is_index']; ?>)">使用</div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="tpl-item">
                <img src="../../../../public/static/images/phone.png" alt="">

                <div class="tpl-add">
                    <div class="icon-add"><i class="iconfont"></i></div>
                    <div class="tpl-btn"><a href="<?php echo url('Admin/Block/index',array('type'=>1)); ?>" target="_blank">添加模板</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-body" id="template-library" style="display:none;">
        <div class="body-title">
            <div class="title-item">
                <span class="title-left title-left1"><i class="iconfont"></i><i class="iconname"> 行业 </i></span>

                <span class="title-right" id="industry_select" data-id="0">
                    <span data-val="" class="item-contant"> 电商 </span>
                    <span data-val="" class="item-contant"> 美食 </span>
                    <span data-val="" class="item-contant item-contant-checked"> 婚庆 </span>
                    <span data-val="" class="item-contant"> 鲜花 </span>
                </span>
            </div>
            <div class="title-item">
                <span class="title-left title-left2"><i class="iconfont"></i><i class="iconname"> 风格 </i></span> 
                <span class="title-right" id="style_select" data-id="0">
                    <span class="item-contant"> 现代 </span>
                    <span class="item-contant"> 田园 </span>
                    <span class="item-contant"> 后现代 </span>
                    <span class="item-contant"> 中式简约 </span>
                    <span class="item-contant"> 欧美古典 </span>
                    <span class="item-contant"> 地中海 </span>
                    <span class="item-contant"> 欧式 </span>
                    <span class="item-contant"> 全部 </span>
                    <span class="item-contant"> 现代 </span>
                    <span class="item-contant"> 田园 </span>
                    <span class="item-contant"> 后现代 </span>
                    <span class="item-contant"> 中式简约 </span>
                    <span class="item-contant"> 欧美古典 </span>
                    <span class="item-contant"> 地中海 </span>
                    <span class="item-contant"> 欧式 </span>
                    <span class="item-contant"> 全部 </span>
                    <span class="item-contant"> 现代 </span>
                    <span class="item-contant"> 田园 </span>
                    <span class="item-contant"> 后现代 </span>
                    <span class="item-contant"> 中式简约 </span>
                    <span class="item-contant"> 欧美古典 </span>
                    <span class="item-contant"> 地中海 </span>
                    <span class="item-contant"> 欧式 </span>
                </span>
            </div>
        </div>
        <div class="tpl-body" id="template-lib-body">
            <div class="tpl-item">
                <img src="../../../../public/static/images/phone.png" alt="">

                <div class="tpl-img"><img src="../../../../public/static/images/2051bc943c913f54.png" alt=""></div>
                <div class="tpl-hover">
                    <div id="QRCode0" class="tpl-QRcode"><img src="../../../../public/static/images/code.png"></div>
                    <div class="tpl-btn tpl-btn-add">添加模板</div>
                    <div class="tpl-btn tpl-btn-preview">预览模板</div>
                </div>
            </div>
            <div class="tpl-item">
                <img src="../../../../public/static/images/phone.png" alt="">

                <div class="tpl-img"><img src="../../../../public/static/images/5bd4751fcbf9b67d.png" alt=""></div>
                <div class="tpl-hover">
                    <div id="QRCode1" class="tpl-QRcode"><img src="../../../../public/static/images/code.png" alt="">
                    </div>
                    <div class="tpl-btn tpl-btn-add">添加模板</div>
                    <div class="tpl-btn tpl-btn-preview">预览模板</div>
                </div>
            </div>
        </div>
        <div class="tpl-item" style="display: none">
            <select id="industry_id" name="industry_id" onchange="select_next(this)"
                    data-url="/index.php/Admin/Block/select_style" data-name="name" data-val="id"
                    data-forid="style_selects">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            <select name="style_id" id="style_selects">
                <option value=""></option>
            </select>
        </div>
    </div>
</div>
<script>
    function select_next(obj) {
        var url = $(obj).attr('data-url');
        var industry_id = $(obj).val();
        var forid = $(obj).attr('data-forid');
        var name = $(obj).attr('data-name');
        var val = $(obj).attr('data-val');
        $.post(url,
                {'industry_id': industry_id,},
                function (res) {
                    var str = '<option value="">请选择</option>';
                    if (res.status) {
                        var data = res.result;
                        for (var i = 0; i < data.length; i++) {
                            str += '<option value="' + data[i][val] + '">' + data[i][name] + '</option>'
                        }
                    }
                    $("#" + forid).html(str);
                }, 'JSON');
    }
    // 点击行业或风格，返回模板
    function getTpl(industry_id, style_id) {
        $.post("/index.php/Admin/Block/get_style",
                {
                    'industry_id': industry_id,
                    'style_id': style_id
                },
                function (res) {
                    if (res.status == 1) {
                        html_tpl('#template-lib-body-tpl', '#template-lib-body', res.result);
                        if (style_id == 0) {
                            show_style_select(res.result);
                        }
                        if (industry_id == 0) {
                            show_industry_select(res.result);
                        }
                    } else {
                        layer.msg(res.msg, {icon: 2, time: 2000});
                    }
                }, 'JSON');
    }
    // 添加模板
    function add_tpl(obj) {
        var id = $(obj).attr('data-id')
        $(".tpl-lib" + id).hide();
        $.post("/index.php/Admin/Block/get_style",
                {
                    'industry_id': industry_id,
                    'style_id': style_id
                },
                function (res) {
                    html_tpl('#template-lib-body-tpl', '#template-lib-body', res);
                }, 'JSON');
    }

    // 显示风格
    function show_style_select(res) {
        html_tpl('#style-select-tpl', '#style_select', res);
        $("#style_select .item-contant").unbind('click');
        // 风格选择
        $("#style_select .item-contant").click(function () {
            $("#style_select .item-contant").removeClass('item-contant-checked')
            $(this).addClass('item-contant-checked');
            var style_id = $(this).attr('data-id');
            getTpl($("#industry_select").attr('data-id'), style_id)
        })

    }
    // 显示行业
    function show_industry_select(res) {
        html_tpl('#industry-select-tpl', '#industry_select', res);
        $("#industry_select .item-contant").unbind('click')
        // 行业选择
        $("#industry_select .item-contant").click(function () {
            var industry_id = $(this).attr('data-id');
            $("#industry_select").attr('data-id', industry_id)
            getTpl(industry_id, 0)
            $("#industry_select .item-contant").removeClass('item-contant-checked')
            $(this).addClass('item-contant-checked');
        })
    }
    $(document).ready(function () {
        getTpl(0, 0)
        $("#industry_id").val(2)


        // 表格行点击选中切换
        $('#flexigrid > table>tbody >tr').click(function () {
            $(this).toggleClass('trSelected');
        });

        // 点击刷新数据
        $('.fa-refresh').click(function () {
            location.href = location.href;
        });

    });

    function template(i) {
        if (i == 0) {
            $('.tab-base').find('li').eq(0).find('a').addClass('current');
            $('.tab-base').find('li').eq(1).find('a').removeClass('current');
            $('#my-template').show();
            $('#template-library').hide();
        }
        if (i == 1) {
            $('.tab-base').find('li').eq(1).find('a').addClass('current');
            $('.tab-base').find('li').eq(0).find('a').removeClass('current');
            $('#my-template').hide();
            $('#template-library').show();
        }
    }

    // 有值返回
    function isempty(v) {
        if (v) return v;
        return '';
    }

    function add_tpl(a) {
        var t_id = $(a).attr('data-id');
        $.post('<?php echo url("block/add_template"); ?>', {'id': t_id}, function (res) {
            if (res.status == 1) {
                layer.msg('模板加入成功!', {icon: 1, time: 1000}, function () {
                    location.reload();
                });
            } else {
                layer.msg(res.msg, {icon: 2, time: 1000});
            }
        }, 'JSON');
    }

    function changeStatus(id, status) {
        layer.confirm((status == 1) ? '确认不使用模板？' : '确认使用该模板？', {
                    btn: ['确认', '取消']
                }, function () {
                    $.post('<?php echo url("Admin/Block/set_index"); ?>', {'id': id, 'status': status}, function (res) {
                        if (res.status == 1) {
                            window.location.href = "/index.php/Admin/Block/templateList";
                        } else {
                            layer.msg(res.msg, {icon: 2, time: 1000});
                        }
                    }, 'JSON')
                }, function (index) {
                    layer.close(index);
                }
        );

    }

    function dele(id) {
        layer.confirm( '确认删除该模板？', {
                    btn: ['确认', '取消']
                }, function () {
                    $.post('<?php echo url("Admin/Block/delete"); ?>', {'id': id}, function (res) {
                        if (res == 1) {
                            layer.msg('删除成功!', {icon: 1, time: 1000}, function () {
                                window.location.href = "/index.php/Admin/Block/templateList";
                            });
                        }
                    }, 'JSON')
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>
<script id="industry-select-tpl" type="text/html" txt="行业标题显示">
    {{#  if(d.industry_id == 0){ }}
    <span class="item-contant item-contant-checked"
          data-id="0"> 全部 </span>
    {{#  } }}
    {{#  layui.each(d.industry_list, function(index, item){ }}
    <span class="item-contant {{d.industry_id==item.industry_id?'item-contant-checked':''}}"
          data-id="{{item.industry_id}}"> {{item.name}} </span>
    {{#  }); }}
</script>
<script id="style-select-tpl" type="text/html" txt="风格标题显示">
    {{#  if(d.industry_id == 0){ }}
    <span class="item-contant item-contant-checked"
          data-id="0"> 全部 </span>
    {{#  } }}
    {{#  if(d.industry_id != 0){ }}
        {{#  layui.each(d.style_list, function(index, item){ }}
        <span class="item-contant {{d.style_id==item.style_id?'item-contant-checked':''}}" data-id="{{item.style_id}}"> {{item.name}} </span>
        {{#  }); }}

    {{#  } }}

</script>
<script id="template-lib-body-tpl" type="text/html" txt="显示该行业的风格，样式">
    {{#  layui.each(d.template_list, function(index, item){ }}
    <div class="tpl-item tpl-lib{{item.id}}">
        <img src="../../../../public/static/images/phone.png" alt="">

        <div class="tpl-img" txt="{{item.thumb}}"><img src="{{ isempty(item.thumb)}}" alt=""></div>
        <div class="tpl-hover">
            <div class="tpl-btn tpl-btn-add" style="top:15px;background: none"><a>{{item.template_name}}</a></div>
            <div class="tpl-QRcode"><img src="/index.php?m=Home&c=Index&a=qr_code&data=<?php echo SITE_URL; ?>/mobile/index/index2/role/saas/id/{{item.id}}
" dsrc="../../../../public/static/images/code.png"></div>
            <div class="tpl-btn tpl-btn-add" onclick="add_tpl(this)" data-id="{{item.id}}"
                 data-industry="{{item.industry_id}}" data-style="{{item.style_id}}">添加模板
            </div>
            <div class="tpl-btn tpl-btn-preview" data-id="{{item.id}}" data-industry="{{item.industry_id}}"
                 data-style="{{item.style_id}}"><a href="/mobile/index/index2/role/saas/id/{{item.id}}"
                                                   target="__black">预览模板</a></div>
        </div>
    </div>
    {{#  }); }}

</script>

</body>
</html>