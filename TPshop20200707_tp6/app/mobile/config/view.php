<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型使用Think
    'type'          => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'     => 3,
	'view_path'    => '../template/mobile/rainbow/',
    // 模板目录名
    'view_dir_name' => 'template',
    // 模板后缀
    'view_suffix'   => 'html',
    // 模板文件名分隔符
    'view_depr'     => '/',
    // 模板引擎普通标签开始标记
    'tpl_begin'     => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'       => '}',
    // 标签库标签开始标记
    'taglib_begin'  => '<',
    // 标签库标签结束标记
    'taglib_end'    => '>',
    'default_filter'     => '',
	'tpl_replace_string' => [
	        '__PUBLIC__'=>'/public',
            '__STATIC__' => '/template/mobile/rainbow/static',
            '__ROOT__'=>''
	],
    'dispatch_success_tmpl' => app()->getRootPath().'template/mobile/rainbow/public/dispatch_jump.html',
    'dispatch_error_tmpl'   => app()->getRootPath().'template/mobile/rainbow/public/dispatch_jump.html',    
];
