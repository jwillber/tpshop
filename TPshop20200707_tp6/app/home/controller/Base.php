<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 个人学习免费, 如果商业用途务必到TPshop官网购买授权.
 * 采用最新Thinkphp6
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */ 
namespace app\home\controller;
use think\facade\View;
use think\Controller;
use think\facade\Db;
use think\facade\Session;
use app\BaseController;
use liliuwei\think;
class Base  extends BaseController{
use \liliuwei\think\Jump;
    public $session_id;
    public $cateTrre = array();
    protected $config = null;
    /*
     * 初始化操作
     */
    public function __construct() {
       //echo 100/0;
//Db::name('Goods')->where("cat_id = 1")->count('*'); 

        //$array = Db::name('goods')->where("goods_id < 0")->select();
        //count($array);
       // $array = Db::name('goods')->where("goods_id = 1")->select();
        //$array = \app\common\model\Goods::select([1]);
       // $array[0]['aaaa'] = 'bbbbbbbbbbbbbb';
        //$array[3]['aaaa'] = 'bbbbbbbbbbbbbb';
       //echo $array[0]['goods_name'];
 			
        //print_r($array);
        //exit('aaaaaaa');
   // exit;
        $this->config = config('shop_info');
        if (input("unique_id")) {           // 兼容手机app
            session_id(input("unique_id"));
            
        }
        header("Cache-control: private");  // history.back返回后输入框值丢失问题 参考文章 http://www.tp-shop.cn/article_id_1465.html  http://blog.csdn.net/qinchaoguang123456/article/details/29852881
    	$this->session_id = session_id(); // 当前的 session_id
        define('SESSION_ID',$this->session_id); //将当前的session_id保存为常量，供其它方法调用
        
        // 判断当前用户是否手机                
        if(isMobile())
            cookie('is_mobile','1',3600); 
        else 
            cookie('is_mobile','0',3600);

        $this->public_assign();
    }
    /**
     * 保存公告变量到 smarty中 比如 导航 
     */
    public function public_assign()
    {
       $tpshop_config = array();
       $tp_config = Db::name('config')->cache(true,TPSHOP_CACHE_TIME)->select();
       if($tp_config){
           foreach($tp_config as $k => $v)
           {
               if($v['name'] == 'hot_keywords'){
                   $tpshop_config['hot_keywords'] = explode('|', $v['value']);
               }
               $tpshop_config[$v['inc_type'].'_'.$v['name']] = $v['value'];
           }
       }
       if(empty($tpshop_config["integral_point_rate"]))  $tpshop_config["integral_point_rate"] = 10;
       $goods_category_tree = get_goods_category_tree();    
       $this->cateTrre = $goods_category_tree;
       View::assign('goods_category_tree', $goods_category_tree);                     
       $brand_list = Db::name('brand')->cache(true)->field('id,name,parent_cat_id,logo,is_hot')->where("parent_cat_id > 0")->select();
       View::assign('brand_list', $brand_list);
       View::assign('tpshop_config', $tpshop_config);
        $user = session('user');
        View::assign('username',$user['nickname']);

        //PC端首页"手机端、APP二维码"
        $store_logo = tpCache('shop_info.store_logo');
        if(file_exists('./'.$store_logo)){
            $head_pic = $store_logo;
        }else{
            $head_pic ='/public/static/images/logo/pc_home_logo_default.png';
        }
        $wx_qr = Db::name('wx_user')->value('qr');
        $mobile_url = SITE_URL . url('Mobile/index/app_down');
        View::assign('head_pic', SITE_URL.$head_pic);
        View::assign('mobile_url', $mobile_url);
        View::assign('wx_qr', $wx_qr);
    }

    /*
     * 
     */
    public function ajaxReturn($data)
    {
        exit(json_encode($data));
    }
}