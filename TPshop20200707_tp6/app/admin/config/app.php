<?php
return array(
     'URL_HTML_SUFFIX'       =>  '',  // URL伪静态后缀设置
    // 'OUTPUT_ENCODE' =>  true, //页面压缩输出支持   配置了 没鸟用
 
	'SHOW_PAGE_TRACE' => true,
	'CFG_SQL_FILESIZE'=>5242880,
    //'URL_MODEL'=>1, //
	
    'PAYMENT_PLUGIN_PATH' => PLUGIN_PATH . 'payment',
    'LOGIN_PLUGIN_PATH' => PLUGIN_PATH . 'login',
    'FUNCTION_PLUGIN_PATH' => PLUGIN_PATH . 'function',

    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => 'public:dispatch_jump',
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => 'public:dispatch_jump',
    'DATA_BACKUP_PATH' => UPLOAD_PATH.'sqldata/', //数据库备份根路径
    'DATA_BACKUP_PART_SIZE' => 20971520, //数据库备份卷大小
    'DATA_BACKUP_COMPRESS' => 0, //数据库备份文件是否启用压缩
    'DATA_BACKUP_COMPRESS_LEVEL' => 9, //数据库备份文件压缩级别
    // URL伪静态后缀
    'url_html_suffix'        => '',    
    'NEWS_TAG'=>array(
        '0'=>'最新',
        '1'=>'热门',
        '2'=>'推荐',
        '3'=>'精品'
    )   	
	
);