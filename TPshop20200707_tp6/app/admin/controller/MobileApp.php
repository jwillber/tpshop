<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * 移动app类
 * Date: 2017-6-5
 */

namespace app\admin\controller;
use think\facade\View;

use think\facade\Db;

class MobileApp extends Base 
{
    public function index()
    {
		$inc_type =  'android';
		View::assign('inc_type', $inc_type);
		View::assign('config', tpCache($inc_type));//当前配置项
		return View::fetch();
    }
    
    //IOS app审核判断开关
    public function ios_audit()
    {
        $param = input('post.');
		$inc_type = 'ios';
		//unset($param['__hash__']);
		unset($param['inc_type']);
        unset($param['form_submit']);
        if(IS_POST){
            tpCache($inc_type,$param);
            View::assign('success', 1);
        }
        
        View::assign('inc_type', $inc_type);
        View::assign('config', tpCache($inc_type));//当前配置项
        return View::fetch();
    }
    
    /**
     * 修改配置
     */
    public function handle()
    {
        $param = input('post.');
	$inc_type = $param['inc_type'];
        if($_FILES['app_path']['error'] != 4)
             $file = request()->file('app_path');

        if ($file) {
            
            $upload_max_filesize = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
            $maxsize = 30000000;

            if($file->getSize() > $maxsize)
                $state = '上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件,且系统配置不能超过:'.$upload_max_filesize;
            $originalName = strtolower($file->getOriginalName());
            if(strstr($originalName,'.php') || strstr($originalName,'.js')) 
                    $state = '上错文件格式错误';
            $extension = strtolower($file->extension());
            if(!in_array($extension,['apk','ipa','pxl','deb']))
                    $state = '仅可上传apk,ipa,pxl,deb文件';
            if($state)
                return $this->error($state, url('MobileApp/index'));
            //$savePath = UPLOAD_PATH.'appfile/';
            $savePath = 'appfile/';
                        
            $savename = 'android_'.$param['app_version'].'_'.date('ymd_His').'.'.$file->extension();
            \think\facade\Filesystem::disk('public')->putFileAs($savePath, $file,$savename);            
          
            $return_url = UPLOAD_PATH.$savePath.$savename;
            tpCache($inc_type, ['app_path' => $return_url]);
        }
        
        tpCache($inc_type, ['app_version' => $param['app_version']]);
        tpCache($inc_type, ['app_log' => $param['app_log']]);
        
        if (!$file) {
            return $this->success("保存成功，但是没有文件上传", url('MobileApp/index'));
        }
		return $this->success("操作成功", url('MobileApp/index'));
    }

    /**
     * 小程序管理面板
     * @return \think\mixed
     */
    public function mini_app()
    {
        $param = input('post.');
        $inc_type = 'miniApp';
        unset($param['inc_type']);
        unset($param['form_submit']);

        if(IS_POST){
            tpCache($inc_type,$param);
        }

        View::assign('inc_type', $inc_type);
        View::assign('config', tpCache($inc_type));//当前配置项
        return View::fetch();
    }
}