<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tpshop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 个人学习免费, 如果商业用途务必到TPshop官网购买授权.
 * 采用最新Thinkphp6
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 *
 */
namespace app\home\controller;
use think\facade\View;
use think\Page;
use think\Verify;
use think\Image;
use think\facade\Db;
class Index extends Base {
 public function test(){
	 print_r($_GET);
     echo 'test';
 }
    public function index(){
        
        
        // 如果是手机跳转到 手机模块
        if(isMobile()){
            header("Location:".url('Mobile/Index/index'));
        }
        $edit_ad = input('edit_ad');
        $hot_goods = $hot_cate = $cateList = $recommend_goods = array();
        $sql = "select a.goods_name,a.goods_id,a.shop_price,a.market_price,a.cat_id,b.parent_id_path,b.name from ".config('database.connections.mysql.prefix')."goods as a left join ";
        $sql .= config('database.connections.mysql.prefix')."goods_category as b on a.cat_id=b.id where a.is_hot=1 and a.is_on_sale=1 order by a.sort";//二级分类下热卖商品
        $index_hot_goods = config('index_hot_goods');
        if(empty($index_hot_goods))
        {
            $index_hot_goods = Db::query($sql);//首页热卖商品
            config($index_hot_goods,'index_hot_goods',TPSHOP_CACHE_TIME);
        }

        if($index_hot_goods){
              foreach($index_hot_goods as $val){
                  $cat_path = explode('_', $val['parent_id_path']);
                  $hot_goods[$cat_path[1]][] = $val;
              }
        }

        $sql2 = "select a.goods_name,a.goods_id,a.shop_price,a.market_price,a.cat_id,b.parent_id_path,b.name from ".config('database.connections.mysql.prefix')."goods as a left join ";
        $sql2 .= config('database.connections.mysql.prefix')."goods_category as b on a.cat_id=b.id where a.is_recommend=1 and a.is_on_sale=1 order by a.sort";//二级分类下热卖商品
        $index_recommend_goods = config('index_recommend_goods');
        if(empty($index_recommend_goods))
        {
        	$index_recommend_goods = Db::query($sql2);//首页推荐商品
        	config('index_recommend_goods',$index_recommend_goods,TPSHOP_CACHE_TIME);
        }

        if($index_recommend_goods){
        	foreach($index_recommend_goods as $va){
        		$cat_path2 = explode('_', $va['parent_id_path']);
        		$recommend_goods[$cat_path2[1]][] = $va;
        	}
        }

        $hot_category = Db::name('goods_category')->where("is_hot=1 and level=3 and is_show=1")->cache(true,TPSHOP_CACHE_TIME)->select();//热门三级分类
        foreach ($hot_category as $v){
        	$cat_path = explode('_', $v['parent_id_path']);
        	$hot_cate[$cat_path[1]][] = $v;
        }
        foreach ($this->cateTrre as $k=>$v){
            if($v['is_hot']==1){
        		$v['hot_goods'] = empty($hot_goods[$k]) ? '' : $hot_goods[$k];
        		$v['recommend_goods'] = empty($recommend_goods[$k]) ? '' : $recommend_goods[$k];
        		$v['hot_cate'] = empty($hot_cate[$k]) ? array() : $hot_cate[$k];
        		$cateList[]=$goods_category_tree[] = $v;
        	}else{
                $goods_category_tree[] = $v;
            }
        }
        $thumb_empty = tpCache('shop_info.thumb_empty');
        View::assign('thumb_empty',$thumb_empty);
        View::assign('edit_ad', $edit_ad);
        View::assign('cateList',$cateList);
        View::assign('goods_category_tree',$goods_category_tree);
        return View::fetch();
    }

    /**
     *  公告详情页
     */
    public function notice(){
        return View::fetch();
    }

    // 二维码
    public function qr_code_raw(){
        ob_end_clean();
        // 导入Vendor类库包 Library/Vendor/Zend/Server.class.php
        //http://www.tp-shop.cn/Home/Index/erweima/data/www.99soubao.com
         //require_once '../vendor/phpqrcode/phpqrcode.php';
         include_once '../vendor/phpqrcode/phpqrcode.php';
          //import('Vendor.phpqrcode.phpqrcode');
            error_reporting(E_ERROR);
            $url = urldecode($_GET["data"]);
            \QRcode::png($url);
			exit;
    }

    // 二维码
    public function qr_code()
    {
        ob_end_clean();
        include_once '../vendor/topthink/think-image/src/Image.php';
        include_once '../vendor/phpqrcode/phpqrcode.php';

        error_reporting(E_ERROR);
        $url = isset($_GET['data']) ? $_GET['data'] : '';
        $url = urldecode($url);
        $head_pic = input('get.head_pic', '');
        $back_img = input('get.back_img', '');
        $valid_date = input('get.valid_date', 0);

        $qr_code_path = UPLOAD_PATH.'qr_code/';
        if (!file_exists($qr_code_path)) {
            mkdir($qr_code_path);
        }

        /* 生成二维码 */
        $qr_code_file = $qr_code_path.time().rand(1, 10000).'.png';
        \QRcode::png($url, $qr_code_file, QR_ECLEVEL_M);

        /* 二维码叠加水印 */
        $QR = \think\image\Image::open($qr_code_file);
        $QR_width = $QR->width();
        $QR_height = $QR->height();

        /* 添加背景图 */
        if ($back_img && file_exists($back_img)) {
            $back = \think\image\Image::open($back_img);
            $back->thumb($QR_width, $QR_height, \think\Image::THUMB_CENTER)
             ->water($qr_code_file, \think\Image::WATER_NORTHWEST, 60);//->save($qr_code_file);
            $QR = $back;
        }

        /* 添加头像 */
        if ($head_pic) {
            //如果是网络头像
            if (strpos($head_pic, 'http') === 0) {
                //下载头像
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $head_pic);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $file_content = curl_exec($ch);
                curl_close($ch);
                //保存头像
                if ($file_content) {
                    $head_pic_path = $qr_code_path.time().rand(1, 10000).'.png';
                    file_put_contents($head_pic_path, $file_content);
                    $head_pic = $head_pic_path;
                }
            }
            //如果是本地头像
            if (file_exists($head_pic)) {
                $logo = \think\image\Image::open($head_pic);
                $logo_width = $logo->height();
                $logo_height = $logo->width();
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $logo_file = $qr_code_path.time().rand(1, 10000);
                $logo->thumb($logo_qr_width, $logo_qr_height)->save($logo_file, null, 100);
                $QR = $QR->thumb($QR_width, $QR_height)->water($logo_file, \think\image\Image::WATER_CENTER);
                unlink($logo_file);
            }
            if ($head_pic_path) {
                unlink($head_pic_path);
            }
        }

        if ($valid_date && strpos($url, 'weixin.qq.com') !== false) {
            $QR = $QR->text('有效时间 '.$valid_date, "../vendor/topthink/think-captcha/assets/zhttfs/1.ttf", 7, '#00000000', Image::WATER_SOUTH);
        }
        $QR->save($qr_code_file, null, 100);

        $qrHandle = imagecreatefromstring(file_get_contents($qr_code_file));
        unlink($qr_code_file); //删除二维码文件
        header("Content-type: image/png");
        imagepng($qrHandle);
        imagedestroy($qrHandle);
        exit;
    }

    // 验证码
    public function verify()
    {
        //验证码类型
        $type = input('get.type') ? input('get.type') : '';
        $fontSize = input('get.fontSize') ? input('get.fontSize') : '40';
        $length = input('get.length') ? input('get.length') : '4';

        $config = array(
            'fontSize' => $fontSize,
            'length' => $length,
            'useCurve' => true,
            'useNoise' => false,
        );
        $Verify = new Verify($config);
        $Verify->entry($type);
		exit();
    }

    function truncate_tables (){
        $tables = DB::query("show tables");
        $table = array('tp_admin','tp_config','tp_region','tp_admin_role','tp_system_menu','tp_article_cat','tp_wx_user');
        foreach($tables as $key => $val)
        {
            if(!in_array($val['Tables_in_tpshop2.0'], $table))
                echo "truncate table ".$val['Tables_in_tpshop2.0'].' ; ';
                echo "<br/>";
        }
    }

    /**
     * 猜你喜欢
     * @author lxl
     * @time 17-2-15
     */
    public function ajax_favorite(){
        $p = input('p/d',1);
        $i = input('i',5); //显示条数
        $time = time();
        $where = [['is_on_sale','=',1] , ['is_virtual','exp' ,Db::raw("=0 or virtual_indate > $time")]];
        $favourite_goods = Db::name('goods')->where($where)->order('goods_id DESC')->page($p,$i)->cache(true,TPSHOP_CACHE_TIME)->select();//首页推荐商品
        View::assign('favourite_goods',$favourite_goods);
        return View::fetch();
    }


}