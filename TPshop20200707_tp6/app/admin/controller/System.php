<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-10-09
 */

namespace app\admin\controller;
use think\facade\View;

use app\admin\logic\GoodsLogic;
use app\common\logic\ModuleLogic;
use think\facade\Db;
use think\facade\Cache;
use think\Page;

class System extends Base
{
	/*
	 * 配置入口
	 */
	public function index()
	{
		/*配置列表*/
		$group_list = [
            'shop_info' => '店铺信息',
            'basic'     => '基本设置',
            'sms'       => '短信设置',
            'shopping'  => '购物流程设置',
            'smtp'      => '邮件设置',
            'water'     => '水印设置',
            'push'      => '推送设置',
            'oss'       => '对象存储',
            'poster'	=> '海报设置',
            'category'	=> '分类页面设置'
        ];		
		View::assign('group_list',$group_list);
		$inc_type =  input('get.inc_type','shop_info');
		View::assign('inc_type',$inc_type);
		$config = tpCache($inc_type);
		if($inc_type == 'shop_info'){
			$province = Db::name('region')->where(array('parent_id'=>0))->select();
			$city =  Db::name('region')->where(array('parent_id'=>$config['province']))->select();
			$area =  Db::name('region')->where(array('parent_id'=>$config['city']))->select();
			View::assign('province',$province);
			View::assign('city',$city);
			View::assign('area',$area);
		}
        if($inc_type == 'poster'){
            View::assign('poster', DB::name('poster')->where(" 1 = 1")->find());
        }
		View::assign('config',$config);//当前配置项
		return View::fetch($inc_type);
	}

    public function cash()
    {
        $config = tpCache('cash');
        View::assign('config',$config);//当前配置项
        return View::fetch();
    }
    public function distribut()
    {
        $config = tpCache('distribut');
        View::assign('inc_type','distribut');//当前配置项
        View::assign('config',$config);//当前配置项
        return View::fetch();
    }

    /**
     * 会员中心自定义
     * @return mixed
     */
    public function user_center_menu()
    {
        $menu_list = Db::name('menu_cfg')->where('')->select();
        View::assign('menu_list', $menu_list);
        return View::fetch();
    }

    public function user_center_menu_save(){
        $menu_list = input('menu/a', []);
        $header_background = input('header_background/s');
        if ($header_background) {
            tpCache('basic', ['header_background'=>$header_background]);
            Cache::clear('config');
        }
        foreach($menu_list as $menu){
            Db::name('menu_cfg')->where('menu_id', $menu['menu_id'])->update($menu);
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'操作成功']);
    }
	
	/*
	 * 新增修改配置
	 */
	public function handle()
	{
		$param = input('post.');
		$inc_type = $param['inc_type'];
		//unset($param['__hash__']);
		unset($param['inc_type']);
        if(strpos($param['domain_name'],'http://') !== false){
            $param['domain_name'] = substr($param['domain_name'],7);
        }

        tpCache($inc_type,$param);

        // 设置短信商接口
        /*
        if($param['sms_platform'] == 2 &&  !empty($param['sms_appkey'])  && !empty($param['sms_secretKey']))
        {
            $sms_appkey = trim($param['sms_appkey']);
            $sms_secretKey = trim($param['sms_secretKey']);
            $url = 'http://open.1cloudsp.com:8090/api/admin/setParentId?parentId=14257&accesskey='.urlencode($sms_appkey).'&secret='.urlencode($sms_secretKey);
            httpRequest($url);
        }
        */
        switch ($inc_type){
            case 'cash':
                $this->success("操作成功",url('System/cash'));
                break;
            case 'distribut':
                $this->success("操作成功",url('System/distribut'));
                break;
            case 'askall_sensitive':
                $this->success("操作成功",url('user/askall_sensitive'));
                break;
            default:
                $this->success("操作成功",url('System/index',array('inc_type'=>$inc_type)));
        }
	}
        
       /**
        * 自定义导航
        */
    public function navigationList(){
           
           $navigationList = Db::name("Navigation")->order("id desc")->select();            
           View::assign('navigationList',$navigationList);
           return View::fetch('navigationList');
     }

    /**
     * 添加修改编辑 前台导航
     */
    public  function addEditNav(){
        if(IS_POST)
        {
            if (input('id'))
                DB::name("Navigation")->update(input('post.'));
            else
                DB::name("Navigation")->insert(input('post.'));

            $this->success("操作成功!!!",url('Admin/System/navigationList'));
            exit;
        }
        // 点击过来编辑时
        $id = input('id',0);
        $navigation = DB::name('navigation')->where('id',$id)->find();
        //导航位置数组
        $system_position = array(
            'top' => '导航顶部',
            'bottom' => '导航底部'
        );
        // 系统菜单 顶部
        $GoodsLogic = new GoodsLogic();
        $cat_list = $GoodsLogic->goods_cat_list();
        $select_option = array();
        if(!empty($cat_list))
        {
            foreach ($cat_list AS $key => $value)
            {
                $strpad_count = $value['level']*4;
                $select_val = url("/Home/Goods/goodsList",array('id'=>$key));
                $select_option[$select_val] = str_pad('',$strpad_count,"-",STR_PAD_LEFT).$value['name'];
            }
        }
        $config = config('shop_info');
        $system_nav = array(
            'http://'.$config['domain_name'] => $config['copyright'].'官网',
            'http://'.$config['domain_name'].'/' => $config['company'],
            '/index.php?m=Home&c=Activity&a=promoteList' => '促销活动',
            '/index.php?m=Home&c=Activity&a=flash_sale_list' => '限时抢购',
            '/index.php?m=Home&c=Activity&a=group_list' => '团购',
            '/index.php?m=Home&c=Index&a=street' => '店铺街',
            '/index.php?m=Home&c=Goods&a=integralMall' => '积分商城',
        );
        $system_nav = array_merge($system_nav,$select_option);
        View::assign('system_nav',$system_nav);

        //地下菜单文章
        $system_bottom = array();
        $article = Db::name('article')->where('is_open',1)->select();
        if(!empty($article)){
            foreach($article as $value){
                $system_bottom['/index.php/Home/Article/detail/article_id/'.$value['article_id']] = $value['title'];
            }
        }

        //分配底部文章
        View::assign('system_bottom',$system_bottom);

        //分配位置数组
        View::assign('position',$system_position);

        View::assign('navigation',$navigation);
        return View::fetch('_navigation');
    }

    /**
     * 添加修改编辑 前台导航
     */
    public function navHandle()
    {
        $data = input('post.');
        empty($data) && $this->ajaxReturn(['status'=>-1,'msg'=>"参数有误！！"]);
        $validate = validate('\app\admin\validate\Navigation.'.$data['act']);
         if (!$validate->batch(true)->check($data)) {
            // 验证失败 输出错误信息
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => -1, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);            
        }
        if ($data['id']){
            DB::name("Navigation")->update($data);
        }else{
            DB::name("Navigation")->insert($data);
        }
        $this->ajaxReturn(['status'=>1,'msg'=>"操作成功！！",'url'=>url('Admin/System/navigationList')]);
    }
    
    /**
     * 删除前台 自定义 导航
     */
    public function delNav()
    {     
        // 删除导航
        Db::name('Navigation')->where("id",input('id'))->delete();
        $this->success("操作成功!!!",url('Admin/System/navigationList'));
    }

    public function delNavigations(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $navigationsIds = rtrim($ids);
        Db::name('Navigation')->whereIn('id',$navigationsIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/System/navigationList")]);
    }


        /**
         * 清空系统缓存
         */
        public function cleanCache(){
            clearCache();
            $quick = input('quick',0);
			if($quick == 1){
			    if (input('ajax') == 'ajax') {
                    $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>$quick]);
                }
				$script = "<script>parent.layer.msg('缓存清除成功', {time:3000,icon: 1});window.parent.location.reload();</script>";
			}else{
				$script = "<script>parent.layer.msg('缓存清除成功', {time:3000,icon: 1});window.location='/index.php?m=Admin&c=Index&a=welcome';</script>";
			}
           	exit($script);
        }
	    
    /**
     * 清空静态商品页面缓存
     */
      public function ClearGoodsHtml(){
            $goods_id = input('goods_id');            
            if(unlink("./Application/Runtime/Html/Home_Goods_goodsInfo_{$goods_id}.html"))
            {
                // 删除静态文件                
                $html_arr = glob("./Application/Runtime/Html/Home_Goods*.html");
                foreach ($html_arr as $key => $val)
                {            
                    strstr($val,"Home_Goods_ajax_consult_{$goods_id}") && unlink($val); // 商品咨询缓存
                    strstr($val,"Home_Goods_ajaxComment_{$goods_id}") && unlink($val); // 商品评论缓存
                }
                $json_arr = array('status'=>1,'msg'=>'清除成功','result'=>'');
            }
            else 
            {
                $json_arr = array('status'=>-1,'msg'=>'未能清除缓存','result'=>'' );
            }                                                    
            $json_str = json_encode($json_arr);            
            exit($json_str);            
      } 
    /**
     * 商品静态页面缓存清理
     */
    public function ClearGoodsThumb()
    {
        $goods_id = input('goods_id');
        delFile(UPLOAD_PATH . "goods/thumb/" . $goods_id); // 删除缩略图
        Cache::clear('original_img_cache');
        $json_arr = array('status' => 1, 'msg' => '清除成功,请清除对应的静态页面', 'result' => '');
        $json_str = json_encode($json_arr);
        exit($json_str);
    }
    /**
     * 清空 文章静态页面缓存
     */
      public function ClearAritcleHtml(){
            $article_id = input('article_id');            
            unlink("./Application/Runtime/Html/Index_Article_detail_{$article_id}.html"); // 清除文章静态缓存
            unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_api.html"); // 清除文章静态缓存
            unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_phper.html"); // 清除文章静态缓存
            unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_android.html"); // 清除文章静态缓存
            unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_ios.html"); // 清除文章静态缓存
            $json_arr = array('status'=>1,'msg'=>'操作完成','result'=>'' );                                                          
            $json_str = json_encode($json_arr);            
            exit($json_str);            
      }
      
	//发送测试邮件
	public function send_email(){
		$param = input('post.');
//		tpCache($param['inc_type'],$param); //注释掉，不注释会出现重复写入数据库
        	$res = send_email($param['test_eamil'],'后台测试','测试发送验证码:'.mt_rand(1000,9999));
        	exit(json_encode($res));
      }
	        
    /**
     *  管理员登录后 处理相关操作
     */        
     public function login_task()
     {
        set_time_limit(0);
        /*** 随机清空购物车的垃圾数据*/                     
        $time = time() - 3600; // 删除购物车数据  1小时以前的
        Db::name("Cart")->where("user_id = 0 and  add_time < $time")->delete();            
        $today_time = time();
		
		// 删除 cart表垃圾数据 删除一个月以前的 
		$time = time() - 2592000; 
        Db::name("cart")->where("add_time < $time")->delete();		
		// 删除 tp_sms_log表垃圾数据 删除一个月以前的短信
        Db::name("sms_log")->where("add_time < $time")->delete();				
        
        // 发货后满多少天自动收货确认
        $auto_confirm_date = tpCache('shopping.auto_confirm_date');
        $auto_confirm_date = $auto_confirm_date * (60 * 60 * 24); // 7天的时间戳
		$time = time() - $auto_confirm_date; // 比如7天以前的可用自动确认收货
        $order_id_arr = Db::name('order')->where("order_status = 1 and shipping_status = 1 and shipping_time < $time and pay_status = 1")->limit(10)->column('order_id');       
        foreach($order_id_arr as $k => $v)
        {
            confirm_order($v);
        }      
        
        // 多少天后自动分销记录自动分成
         $switch = tpCache('distribut.switch');         
         if($switch == 1 && file_exists(APP_PATH.'common/logic/DistributLogic.php')){
            $distributLogic = new \app\common\logic\DistributLogic();
            $distributLogic->auto_confirm(); // 自动确认分成
         }         
     }
     
     function ajax_get_action()
     {
         $control = input('controller');
         $type = input('type',0);
         $module = (new ModuleLogic)->getModule($type);
         if (!$module) {
             exit('模块不存在或不可见');
         }

         $selectControl = [];
         $className = "app\\".$module['name']."\\controller\\".$control;
         $methods = (new \ReflectionClass($className))->getMethods(\ReflectionMethod::IS_PUBLIC);
         foreach ($methods as $method) {
             if ($method->class == $className) {
                 if ($method->name != '__construct' && $method->name != '_initialize') {
                     $selectControl[] = $method->name;
                 }
             }
         }

         $html = '';
         foreach ($selectControl as $val){
             $html .= "<li><label><input class='checkbox' name='act_list' value=".$val." type='checkbox'>".$val."</label></li>";
             if($val && strlen($val)> 18){
                 $html .= "<li></li>";
             }
         }
         exit($html);
     }
     
    function right_list()
    {
        $type = input('type',0);
        $moduleLogic = new ModuleLogic;
        if (!$moduleLogic->isModuleExist($type)) {
            $this->error('权限类型不存在');
        }
        $modules = $moduleLogic->getModules();
        $group = $moduleLogic->getPrivilege($type);

        $condition['type'] = $type;
        $name = input('name');
        if(!empty($name)){
            $condition['name|right'] = array('like',"%$name%");
        }
        $right_list = Db::name('system_menu')->where($condition)->order('id desc')->select();
        View::assign('right_list',$right_list);
        View::assign('group',$group);
        View::assign('modules',$modules);
        return View::fetch();
    }

    public function edit_right()
    {
        $type = input('type',0);  //0:平台权限资源;1:商家权限资源
        $moduleLogic = new ModuleLogic;
        if (!$moduleLogic->isModuleExist($type)) {
            $this->error('模块不存在或不可见');
        }

        if(IS_POST){
            $data = input('post.');
            if(!$data['right']){
                $this->ajaxReturn(['status' => -1,'msg' => '请添加权限码']);
            }
            //去空格
            $data['name'] = trim($data['name']);
            
            $data['right'] = implode(',',$data['right']);
            if(!empty($data['id'])){
                Db::name('system_menu')->where(array('id'=>$data['id']))->save($data);
            }else{
                if(Db::name('system_menu')->where(array('type'=>$data['type'],'name'=>$data['name']))->count()>0){
                    $this->ajaxReturn(['status' => -1,'msg' => '该权限名称已添加，请检查']);
                }
                unset($data['id']);
                Db::name('system_menu')->insert($data);
            }
            $this->ajaxReturn(['status' => 1,'msg' => '操作成功']);
            exit;
        }
        $id = input('id');
        if($id){
            $info = Db::name('system_menu')->where(array('id'=>$id))->find();
            $info['right'] = explode(',', $info['right']);
            View::assign('info',$info);
        }

        $modules = $moduleLogic->getModules();
        $group = $moduleLogic->getPrivilege($type);
        $planPath = APP_PATH.$modules[$type]['name'].'/controller';
        $planList = array();
        $dirRes   = opendir($planPath);
        while($dir = readdir($dirRes))
        {
            if(!in_array($dir,array('.','..','.svn')))
            {
                $planList[] = basename($dir,'.php');
            }
        }
        sort($planList);//排序
        View::assign('modules', $modules);
        View::assign('planList',$planList);
        View::assign('group',$group);
        return View::fetch();
    }
     
     public function right_del(){
     	$id = input('del_id');
     	if(is_array($id)){
     		$id = implode(',', $id); 
     	}
     	if(!empty($id)){
     		$r = Db::name('system_menu')->where("id in ($id)")->delete();
     		if($r){
     			respose(1);
     		}else{
     			respose('删除失败');
     		}
     	}else{
     		respose('参数有误');
     	}
     }

    public function delList(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $listIds = rtrim($ids);
        Db::name('system_menu')->whereIn('id',$listIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/System/right_list")]);
    }
	//清除所有活动数据
	public function clearProm()
	{
		Db::name('flash_sale')->where('1=1')->delete();
		Db::name('group_buy')->where('1=1')->delete();
		Db::name('prom_goods')->where('1=1')->delete();
		Db::name('prom_order')->where('1=1')->delete();
		Db::name('coupon')->where('1=1')->delete();
		Db::name('coupon_list')->where('1=1')->delete();
		Db::name('goods_coupon')->where('1=1')->delete();
		Db::name('goods')->where('prom_type', '<>', 0)->whereOr('prom_id', '<>', 0)->update(['prom_type' => 0, 'prom_id' => 0]);
		Db::name('spec_goods_price')->where('prom_type', '<>', 0)->whereOr('prom_id', '<>', 0)->update(['prom_type' => 0, 'prom_id' => 0]);
		Db::name('cart')->where('prom_type', '<>', 0)->whereOr('prom_id', '<>', 0)->update(['prom_type' => 0, 'prom_id' => 0]);
		Db::name('order_goods')->where('prom_type', '<>', 0)->whereOr('prom_id', '<>', 0)->update(['prom_type' => 0, 'prom_id' => 0]);
		$this->success('清除活动数据成功');
	}

	//清楚拼团活动数据
	public function clearTeam(){
		Db::name('team_activity')->where('1=1')->delete();
		Db::name('team_follow')->where('1=1')->delete();
		Db::name('team_found')->where('1=1')->delete();
		Db::name('team_lottery')->where('1=1')->delete();
		Db::name('team_goods_item')->where('1=1')->delete();
		Db::name('goods')->where('prom_type',6)->update(['prom_type' => 0, 'prom_id' => 0]);
		Db::name('spec_goods_price')->where('prom_type',6)->update(['prom_type' => 0, 'prom_id' => 0]);
		Db::name('order')->where('prom_type',6)->update(['prom_type' => 0, 'prom_id' => 0]);
		Db::name('order_goods')->where('prom_type',6)->update(['prom_type' => 0, 'prom_id' => 0]);
		$this->success('清除拼团活动数据成功');
	}

    //添加自定义海报模板
    public function poster_add(){
        //halt($_POST);
        $data = input('post.');
        if($data['enabled'] == 1 && ($id = Db::name('poster')->where(['enabled'=>1])->value('id'))){
            Db::name('poster')->where(['id'=>$id])->update(['enabled'=>0]);
        }
        if ($data['id'] >0){
            unset($data['id']);
            $data['update_time'] = time();
            Db::name('poster')->where(['id'=>input('id')])->save($data);
            $this->ajaxReturn(['status'=>1,'msg'=>'更新成功','url'=>url('Admin/System/posterList')]);
        }else{
            $data['add_time'] = time();
            Db::name('poster')->insert($data);
            $this->ajaxReturn(['status'=>1,'msg'=>'添加成功','url'=>url('Admin/System/posterList')]);
        }
    }


    public function slogan(){
         $count=Db::name('slogan')->count();
         $page = new Page($count,10);
         $slogan=Db::name('slogan')->limit($page->firstRow,$page->listRows)->select();
         View::assign('slogan',$slogan);
         View::assign('page',$page->show());
         return View::fetch();
    }

    public function delSlogan(){
         $id = input('id/a');
         $res=Db::name('slogan')->where(['id'=>['in',$id]])->delete();
         if($res){
             $this->ajaxReturn(['status'=>1,'msg'=>'删除成功']);
         }else{
             $this->ajaxReturn(['status'=>0,'msg'=>'删除失败']);
         }
    }

    public function addEditSlogan(){
         $id = input('id');
         $is_ajax=input('is_ajax');
         if($is_ajax){
             $data = $_POST;
             if($id) {
                 $res=Db::name('slogan')->where(['id'=>$id])->update($data);
                 if($res){
                     $this->ajaxReturn(['status'=>1,'msg'=>'修改成功','data'=>['url'=>url('system/slogan')]]);
                 }else{
                     $this->ajaxReturn(['status'=>0,'msg'=>'修改失败']);
                 }
             }else{
                 $count = Db::name('slogan')->count();
                 if($count>=4){
                     $this->ajaxReturn(['status'=>0,'msg'=>'最多只能有四个标语']);
                 }
                 if(strlen($data['remark'])>50){
                     $this->ajaxReturn(['status'=>0,'msg'=>'输入的标语过长']);
                 }
                 $res=Db::name('slogan')->insertGetId($data);
                 if($res){
                     $this->ajaxReturn(['status'=>1,'msg'=>'添加成功','data'=>['url'=>url('system/slogan')]]);
                 }else{
                     $this->ajaxReturn(['status'=>0,'msg'=>'添加失败']);
                 }
             }
         }else if($id){
            $slogan=Db::name('slogan')->where(['id'=>$id])->find();
            View::assign('slogan',$slogan);
         }

         return View::fetch();
    }
    /**
     * 清空演示数据 用完切记删除
     * http://www.xxx.com/Admin/system/truncate_demo_data
     */
    public function truncate_demo_data(){
        /*
        $result = Db::query('show tables');        
        $prefix   = config('database.connections.mysql.prefix');
        $database = config('database.database');
        $tables = array();        
        foreach($result as $key => $val){
                $tables[] = array_shift($val);
        }	 			    
         
        $bl_table = array('tp_admin','tp_config','tp_region','tp_system_module','tp_admin_role','tp_system_menu','tp_article_cat','tp_article','tp_wx_user');
        foreach($bl_table as $k => $v)
        {
                $bl_table[$k] = str_replace('tp_',$prefix,$v); 
        }			      
        
        foreach($tables as $key => $val)
        {					
                if(!in_array($val, $bl_table))
                {
                     Db::execute("truncate table ".$val); 
                }		
        }   	
        delFile('../public/upload/goods'); // 清空测试图片			
               
        header("Content-type: text/html; charset=utf-8");  
        echo "数据已清空,请立即删除这个方法";
        */ 
         
    }
    /**
     * 公共删除数据方法 （慎用）
     * @param  name  string   表名称
     * @param  ids  string   id集合
     */
    public function deleteData(){
        $tableName = input('name','');
        $ids       = input('ids','');
        $field_name= input('field_name','');
        if(empty($tableName) ){
            $this->ajaxReturn(['status'=>0,'msg'=>'删除失败']);
        }
        //删除数据
        $result  = Db::name($tableName)->where([$field_name=>array('in',$ids)])->delete();
        if(!$result)  $this->ajaxReturn(['status'=>0,'msg'=>'删除失败','ss'=>Db::getlastsql()]);

        $this->ajaxReturn(['status'=>1,'msg'=>'删除成功']);
    }
        
}