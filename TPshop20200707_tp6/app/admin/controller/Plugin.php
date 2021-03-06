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
 * 插件管理类
 * Date: 2015-10-20
 */

namespace app\admin\controller;
use think\facade\View;

use think\AjaxPage;
use think\facade\Db;

class Plugin extends Base {

    public function __construct()
    {
        parent::__construct();
        //  更新插件
        $this->insertPlugin($this->scanPlugin());
    }

    public function index(){

        $plugin_list = Db::name('plugin')->select();
        $plugin_list = group_same_key($plugin_list,'type');
        View::assign('payment',$plugin_list['payment']);
        View::assign('login',$plugin_list['login']);
        View::assign('function',$plugin_list['function']);
        View::assign('type',input('type'));
        return View::fetch();
    }

    /**
     * 插件安装卸载
     */
    public function install(){
        $condition['type'] = input('get.type');
        $condition['code'] = input('get.code');
        $update['status'] = input('get.install');
     
        //如果是功能插件
        if($condition['type'] == 'function')
        {            
            include_once  "../plugins/function/{$condition['code']}/plugins.class.php";         
            $plugin = new \plugins();            
            if($update['status'] == 1) // 安装
            {
                $execute_sql = $plugin->install_sql(); // 执行安装sql 语句
                $info = $plugin->install();  // 执行 插件安装代码                    
            }
            else // 卸载
            {
                $execute_sql = $plugin->uninstall_sql(); // 执行卸载sql 语句
                $info = $plugin->uninstall(); // 执行插件卸载代码              
            }
            // 如果安装卸载 有误则不再往下 执行
            if($info['status'] === 0)
                exit(json_encode($info));
            // 程序安装没错了, 再执行 sql
            DB::execute($execute_sql);
        }
        //卸载插件时 删除配置信息
        if($update['status']==0){
            $row = DB::name('plugin')->where($condition)->delete();
        }else{
            $row = Db::name('plugin')->where($condition)->save($update);
        }
//        $row = $model->where($condition)->save($update);
        //安装时更新配置信息(读取最新的配置)
        if($condition['type'] == 'payment' && $update['status']){
            $file = PLUGIN_PATH.$condition['type'].'/'.$condition['code'].'/config.php';
            $config = include $file;
            $add['bank_code'] = serialize($config['bank_code']);
            $add['config'] = serialize($config['config']);
            $add['config_value'] = '';
            Db::name('plugin')->where($condition)->save($add);
        }
 
        if($row){
            $info['status'] = 1;
            $info['msg'] = $update['status'] ? '安装成功!' : '卸载成功!';
        }else{
            $info['status'] = 0;
            $info['msg'] = $update['status'] ? '安装失败' : '卸载失败';
        }        
        exit(json_encode($info));
    }


    /**
     * 插件目录扫描
     * @return array 返回目录数组
     */
    private function scanPlugin(){
        $plugin_list = array();
        $plugin_list['payment'] = $this->dirscan(config('PAYMENT_PLUGIN_PATH'));
        $plugin_list['login'] = $this->dirscan(config('LOGIN_PLUGIN_PATH'));
         
        foreach($plugin_list as $k=>$v){
            foreach($v as $k2=>$v2){
 
                if(!file_exists(PLUGIN_PATH.$k.'/'.$v2.'/config.php'))
                    unset($plugin_list[$k][$k2]);
                else
                {
                    $plugin_list[$k][$v2] = include(PLUGIN_PATH.$k.'/'.$v2.'/config.php');
                    unset($plugin_list[$k][$k2]);                    
                }
            }
        }
        return $plugin_list;
    }

    /**
     * 获取插件目录列表
     * @param $dir
     * @return array
     */
    private function dirscan($dir){
        $dirArray = array();
        if (false != ($handle = opendir ( $dir ))) {
            $i=0;
            while ( false !== ($file = readdir ( $handle )) ) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".."&&!strpos($file,".")) {
                    $dirArray[$i]=$file;
                    $i++;
                }
            }
            //关闭句柄
            closedir ( $handle );
        }
        return $dirArray;
    }

    /**
     * 更新插件到数据库
     * @param $plugin_list array 本地插件数组
     */
    private function insertPlugin($plugin_list){
       
        $save_path = UPLOAD_PATH.'logistics/';
        $source_path = PLUGIN_PATH . 'shipping/';

        $new_arr = array(); // 本地
        //插件类型
        foreach($plugin_list as $pt=>$pv){
            //  本地对比数据库
            foreach($pv as $t=>$v){
                $tmp['code'] = $v['code'];
                $tmp['type'] = $pt;
                $new_arr[] = $tmp;
                // 对比数据库 本地有 数据库没有
                $is_exit = Db::name('plugin')->where(array('type'=>$pt,'code'=>$v['code']))->find();
                if(empty($is_exit)){
                   if($pt == 'shipping'){
                       @copy($source_path.$v['code'].'/'.$v['icon'], $save_path.$v['code'].'.jpg');
                       $add['icon'] = $v['icon'];
                   }else{ 
                        $add['icon'] = $v['icon'];
                    } 
                    $add['code'] = $v['code'];
                    $add['name'] = $v['name'];
                    $add['version'] = $v['version'];
                    $add['author'] = $v['author'];
                    $add['desc'] = $v['desc'];
                    $add['bank_code'] = serialize($v['bank_code']);
                    $add['type'] = $pt;
                    $add['scene'] = $v['scene'];
                    $add['config'] = empty($v['config']) ? '' : serialize($v['config']);
                    Db::name('plugin')->insert($add);
                }
            }
        }
        //数据库有 本地没有
//        foreach($d_list as $k=>$v){
//            if(!in_array($v,$new_arr)){
//                Db::name('plugin')->where($v)->delete();
//            }
//        }

    }

    /*
     * 插件信息配置
     */
    public function setting(){

        $condition['type'] = input('get.type');
        $condition['code'] = input('get.code');
       
        if(($condition["code"] == "unionpay")){ header("Content-type: text/html; charset=utf-8");exit("请联系TPshop官网客服购买高级版支持此功能"); } 	
        
	//
        	
        $row = Db::name('plugin')->where($condition)->find();
        if(!$row){
            exit($this->error("不存在该插件"));
        }
        $row['config'] = unserialize($row['config']);

        if(IS_POST){ 
            $config = input('post.config/a');
            //空格过滤
            $config = trim_array_element($config);
            // 新支付宝登录时，要去掉换行符
            if($condition['code'] == 'alipaynew'){
                $config['app_rsa_private_key'] = str_replace(PHP_EOL, '', $config['app_rsa_private_key']);
                $config['alipay_rsa_public_key'] = str_replace(PHP_EOL, '', $config['alipay_rsa_public_key']);
            }
            if($config){
                $config = serialize($config);

            }
            $row = Db::name('plugin')->where($condition)->save(array('config_value'=>$config));
            if($row){
                exit($this->success("操作成功"));
            }
            exit($this->error("操作失败"));
        }
        View::assign('plugin',$row);
        View::assign('config_value',unserialize($row['config_value']));

        return View::fetch();
    }

    /**
     * 调试开关
     * @return \think\mixed
     */
    public  function debug_switch(){
        
        $inc_type =  'debug';
        $param = input('post.');
        if(IS_POST){
            tpCache($inc_type,$param);
        }
        
        View::assign('type', 'debug');
        View::assign('inc_type', $inc_type);
        View::assign('config', tpCache($inc_type));//当前配置项
        
        return View::fetch('index');
        
    }

    /**
     * 检查插件是否存在
     * @return mixed
     */
    private function checkExist(){
        $condition['type'] = input('get.type');
        $condition['code'] = input('get.code');
 
        $row = Db::name('plugin')->where($condition)->find();
        if(!$row && false){
            exit($this->error("不存在该插件"));
        }
        return $row;
    }
    
    public function check_str($str){
        //$pat ='/[a-zA-Z\x{4e00}-\x{9fa5}]*$/u';// '/^[a-zA-Z0-9_]*$/';
		$pat ='/[a-zA-Z\x{4e00}-\x{9fa5}]/u';// '/^[a-zA-Z0-9_]*$/';
        if(!preg_match( $pat, $str )){
            return  false;
        }
        return true;
    }

    /**
     * 上传商户证书文件
     */
    public function uploadCert(){
        $code = request()->param('code');
        if($code){
            //根据配置的code来区别文件放置的位置
            if($code=='miniAppPay'){
                $save_url = '../plugins/payment/weixin/cert/mini';
            }elseif ($code=='weixin'){
                $save_url = '../plugins/payment/weixin/cert/mp';
            }elseif ($code=='appWeixinPay'){
                $save_url = '../plugins/payment/weixin/cert/app';
            }else {
                $save_url = '../plugins/payment/weixin/cert/'.$code;  //TODO 更换到不同类型cert  app、mini、mp
            } 
        }
        //设置目录是否有写的权限
        if (!is_writable($save_url)) {
            $this->ajaxReturn(['status' =>-1,'msg' =>'文件上传的目录:'.$save_url.'没有写入权限！！']);
        }
        $file = request()->file('cert_file');
        if($file) {
            $image_upload_limit_size = config('image_upload_limit_size');
            $info = $file->validate(['size' => $image_upload_limit_size, 'ext' => 'pem'])->move($save_url,'');
            if ($info) {
                $file_path = '/' . $save_url . '/' . $info->getFilename();
            } else {
                // 上传失败获取错误信息
                $this->ajaxReturn(['status' =>-1,'msg' =>$file->getError()]);
            }
        }
        if (isset($file_path)) {
            $this->ajaxReturn(['status' => 1,'msg' =>'上传成功','data' =>$file_path]);
        }
        $this->ajaxReturn(['status' => -1,'msg' =>'上传失败']);
    }

}