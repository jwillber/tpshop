<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */
namespace think;

 
if (extension_loaded('zlib')){
    ob_end_clean();
    ob_start('ob_gzhandler');
}
//防止iframe框架攻击
header('X-Frame-Options: SAMEORIGIN');
// 检测PHP环境
if(version_compare(PHP_VERSION,'7.1.0','<'))
{
    header("Content-type: text/html; charset=utf-8");  
    die('PHP 版本必须 7.1以上 !');
}

//检测是否已安装TPshop系统
if(file_exists("./install/") && !file_exists("./install/install.lock")){
	if($_SERVER['PHP_SELF'] != '/index.php'){
		header("Content-type: text/html; charset=utf-8");         
		exit("请在域名根目录下安装,如:<br/> www.xxx.com/index.php 正确 <br/>  www.xxx.com/www/index.php 错误,域名后面不能圈套目录, 但项目没有根目录存放限制,可以放在任意目录,apache虚拟主机配置一下即可");
	}  
	header('Location:/install/index.php');
	exit(); 
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);//报告运行时错误
define('PLUGIN_PATH', dirname(__DIR__) . '/plugins/');
defined('UPLOAD_PATH') or define('UPLOAD_PATH','public/upload/'); // 编辑器图片上传路径
define('TPSHOP_CACHE_TIME',1); // TPshop 缓存时间  31104000
$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
define('SITE_URL',$http.'://'.$_SERVER['HTTP_HOST']); // 网站域名
define('HTTP',$http); // 网站域名
//define('HTML_PATH','./Application/Runtime/Html/'); //静态缓存文件目录，HTML_PATH可任意设置，此处设为当前项目下新建的html目录
define('INSTALL_DATE',1463741583);
define('SERIALNUMBER','20160520065303oCWIoa');
// 定义时间
define('NOW_TIME',$_SERVER['REQUEST_TIME']);
define('IS_SAAS',0);

// [ 应用入口文件 ]
require __DIR__ . '/../vendor/autoload.php';
// 定义应用目录
//define('APP_PATH', __DIR__ . '../app/');
//echo APP_PATH;
// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
