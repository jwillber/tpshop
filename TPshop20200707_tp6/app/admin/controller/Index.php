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
 * Date: 2015-09-09
 */
namespace app\admin\controller;
use think\facade\View; 
use think\AjaxPage;
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use app\common\logic\MessageFactory;
use think\facade\Db;
class Index extends Base {

    public function index(){
        $this->pushVersion();        
        $admin_info = getAdminInfo(session('admin_id'));
        $order_amount = Db::name('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();
        View::assign('order_amount',$order_amount);
        View::assign('admin_info',$admin_info);             
        View::assign('menu',getMenuArr());   //view2
        return View::fetch();
    }
   
    public function welcome(){
        $warning_storage = tpCache('basic.warning_storage') ?: 10;
    	View::assign('sys_info',$this->get_sys_info());
        View::assign('is_saas',IS_SAAS);
//    	$today = strtotime("-1 day");
    	$today = strtotime(date("Y-m-d"));
    	$count['handle_order'] = Db::name('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();//待处理订单
    	$count['new_order'] = Db::name('order')->where("add_time >= $today")->count();//今天新增订单
    	$count['goods'] =  Db::name('goods')->where("1=1")->count();//商品总数
    	$count['article'] =  Db::name('article')->where("1=1")->count();//文章总数
    	$count['users'] = Db::name('users')->where("1=1")->count();//会员总数
    	$count['today_login'] = Db::name('users')->where("last_login >= $today")->count();//今日访问
    	$count['new_users'] = Db::name('users')->where("reg_time >= $today")->count();//新增会员
    	$count['comment'] = Db::name('comment')->where("is_show=0")->count();//最新评论
        $count['stock'] = Db::name('spec_goods_price')->where("store_count < $warning_storage")->count();
    	View::assign('count',$count);
        return View::fetch();
    }
    
    public function get_sys_info(){
		$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off		
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
		$sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';	
		$sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();
		$sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		$sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
		$sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
		$sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
		$sys_info['memory_limit']   = ini_get('memory_limit');	                                
        $sys_info['version']   	    = file_get_contents(APP_PATH.'admin/config/version.html');
		$mysqlinfo = Db::query("SELECT VERSION() as version");
		$sys_info['mysql_version']  = $mysqlinfo[0]['version'];
		if(function_exists("gd_info")){
			$gd = gd_info();
			$sys_info['gdinfo'] 	= $gd['GD Version'];
		}else {
			$sys_info['gdinfo'] 	= "未知";
		}
		return $sys_info;
    }
    
    // 在线升级系统
    public function pushVersion()
    {            
        if(session('isset_push'))
            return false;    
        session('isset_push',1);
        error_reporting(0);//关闭所有错误报告
	    $app_path = app_path();
    	$version_txt_path = $app_path.'config/version.html';		
        $curent_version = file_get_contents($version_txt_path);

        $vaules = array(            
                'domain'=>$_SERVER['SERVER_NAME'], 
                'last_domain'=>$_SERVER['SERVER_NAME'], 
                'key_num'=>$curent_version, 
                'install_time'=>INSTALL_DATE,
                'serial_number'=>SERIALNUMBER,
         );     
         $url = "http://service.tp-shop.cn/index.php?m=Home&c=Index&a=user_push&".http_build_query($vaules);
         stream_context_set_default(array('http' => array('timeout' => 3)));
         file_get_contents($url);         
    }
    
    /**
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal(){  
            $table = input('table'); // 表名
            $id_name = input('id_name'); // 表主键id名
            $id_value = input('id_value'); // 表主键id值
            $field  = input('field'); // 修改哪个字段
            $value  = input('value'); // 修改字段值

            Db::name($table)->where([$id_name => $id_value])->save(array($field=>$value)); // 根据条件保存修改的数据

            // 是否启动拼团，设置发拼团站内消息
            if ($table == 'team_activity') {
                $where_message_activity = [
                    'prom_id' => $id_value,
                    'mmt_code' => 'team_activity'
                ];
                $message_id = Db::name('message_activity')->where($where_message_activity)->value('message_id');
                if (!$message_id && ($value == 1)) {
                    $team_activity = Db::name('team_activity')->where('team_id', $id_value)->find();
                    $send_data = [
                        'message_title' => $team_activity['act_name'],
                        'message_content' => $team_activity['share_desc'],
                        'img_uri' => $team_activity['share_img'],
                        'end_time' => 0,
                        'send_time' => time(),
                        'mmt_code' => 'team_activity',
                        'prom_type' => 6,
                        'users' => [],
                        'message_val' => [],
                        'category' => 1,
                        'prom_id' => $id_value
                    ];
                    $send_data['end_time'] = $send_data['send_time'] + $team_activity['time_limit'];
                    $messageFactory = new MessageFactory();
                    $messageLogic = $messageFactory->makeModule($send_data);
                    $messageLogic->sendMessage();
                }
            }
            //正在参与活动，一下架，上架，活动不会恢复的，虽然显示活动在进行，但是实际没有，需要删除活动，重新添加。
            if($table == 'goods' && $field == 'is_on_sale' && empty($value)){
                // 下架，则清除该 活动。
                $goods = Db::name('goods')->where('goods_id',$id_value)->find();
                if(!empty($goods['prom_type']) || !empty($goods['prom_id'])){
                    Db::name('goods')->where('goods_id',$id_value)->update(['prom_type'=>0,'prom_id'=>0]);
                }
                Db::name('spec_goods_price')->where('goods_id',$id_value)->update(['prom_type'=>0,'prom_id'=>0]);
            }
            // 拼团是否启动,防止同一个商品，参与多次拼团活动有矛盾,后台操作补丁
            if($table == 'team_activity' && $field == 'status'){
                if(empty($value)){
                    //取消
                    Db::name('team_goods_item')->where('team_id',$id_value)->update(['deleted'=>1]);
                    $team_goods_item =  Db::name('team_goods_item')->where('team_id',$id_value)->field('goods_id,item_id')->select();
                    if($team_goods_item){
                        foreach($team_goods_item as $team){
                            if($team['item_id']==0){
                                unset($team['item_id']);
                            }
                            Db::name('spec_goods_price')->where($team)->update(['prom_type'=>0,'prom_id'=>0]);
                        }
                    }
                }else{
                    //启动,
                    Db::name('team_goods_item')->where('team_id',$id_value)->update(['deleted'=>0]);
                    $team_goods_item =  Db::name('team_goods_item')->where('team_id',$id_value)->field('goods_id,item_id')->select();
                    if($team_goods_item){
                        foreach($team_goods_item as $team){
                            if($team['item_id']==0){
                                unset($team['item_id']);
                            }
                            Db::name('spec_goods_price')->where($team)->update(['prom_type'=>6,'prom_id'=>$id_value]);
                        }
                    }
                }
            }
        // 活动类型3
        if($table == 'prom_goods'){
            // 启动时，判断时间有没有过，过了就失效，
            if($value==1){
                $prom_goods = Db::name('prom_goods')->where('id',$id_value)->find();
                if($prom_goods['end_time'] < time()){
                    // 把商品活动3原本没有失效的失效
                    $goods_id = Db::name('prom_goods_item')->where(['prom_id'=>$prom_goods['id']])->column('goods_id');
                    if($goods_id){
                        $goods_id = array_values($goods_id);
                        // 找出对应商品的活动还在进行中的
                        $id_goods = Db::name('goods')->where(['goods_id'=>['in',$goods_id],'prom_type'=>3])->column('goods_id');
                        if($id_goods){
                            $id_goods = array_values($id_goods);
                            // 失效活动
                            Db::name('goods')->where(['goods_id'=>['in',$id_goods]])->update(['prom_type'=>0,'prom_id'=>0]);
                        }
                        // 规格同理
                        $id_spec_goods_price = Db::name('spec_goods_price')->where(['goods_id'=>['in',$goods_id],'prom_type'=>3])->column('goods_id');
                        if($id_spec_goods_price){
                            $id_spec_goods_price = array_values($id_spec_goods_price);
                            Db::name('spec_goods_price')->where(['goods_id'=>['in',$id_spec_goods_price]])->update(['prom_type'=>0,'prom_id'=>0]);
                        }
                    }
                }
            }else{
                // 如果启动，判断时间有没有过，没有过，就开启，
                $prom_goods = Db::name('prom_goods')->find($id_value);
                if($prom_goods['end_time'] > time()){
                    // 把商品活动3原本没有失效的失效
                    $goods_id = Db::name('prom_goods_item')->where(['prom_id'=>$prom_goods['id']])->column('goods_id');
                    if($goods_id){
                        $goods_id = array_values($goods_id);
                        // 找出对应商品的活动还在进行中的
                        $id_goods = Db::name('goods')->where(['goods_id'=>['in',$goods_id],'prom_type'=>0])->column('goods_id');
                        if($id_goods){
                            $id_goods = array_values($id_goods);
                            // 失效活动
                            Db::name('goods')->where(['goods_id'=>['in',$id_goods]])->update(['prom_type'=>3,'prom_id'=>$prom_goods['id']]);
                        }
                        // 规格同理
                        $id_spec_goods_price = Db::name('spec_goods_price')->where(['goods_id'=>['in',$goods_id],'prom_type'=>0])->column('goods_id');
                        if($id_spec_goods_price){
                            $id_spec_goods_price = array_values($id_spec_goods_price);
                            Db::name('spec_goods_price')->where(['goods_id'=>['in',$id_spec_goods_price]])->update(['prom_type'=>3,'prom_id'=>$prom_goods['id']]);
                        }
                    }
                }
            }
        }
    }
    public function about(){
    	return View::fetch();
    }
}