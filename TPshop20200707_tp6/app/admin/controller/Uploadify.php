<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-22
 */
 
namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\AjaxPage;

class Uploadify extends Base{
   
    public function upload(){
        $func = input('func');
        $path = input('path','temp');
		$image_upload_limit_size = config('image_upload_limit_size');
        $fileType = input('fileType','Images');  //上传文件类型，视频，图片
		if($fileType == 'undefined'){
			$fileType = 'Images';
		}
         switch ($fileType)  {
             case 'Flash':
                 $upload = url('Admin/Ueditor/index',array('savepath'=>$path,'action'=>'uploadvideo','pictitle'=>'banner','dir'=>'video'));
                 $type = 'mp4,3gp,flv,avi,wmv';
				 $fileList = url('Admin/Uploadify/fileListNew',array('path'=>$path));
                 break;
			 case 'File':
				 $upload = url('Admin/Ueditor/index',array('savepath'=>$path,'action'=>'uploadfile','pictitle'=>'banner','dir'=>'File'));
				 $type = '';
				 $fileList = url('Admin/Uploadify/fileListNew',array('path'=>$path));
				 break;
             default:
                 $upload = url('Admin/Ueditor/index',array('savepath'=>$path,'action'=>'uploadimage','pictitle'=>'banner','dir'=>'images'));
                 $type = 'jpg,png,gif,jpeg';
				 $fileList = url('Admin/Uploadify/fileListNew',array('path'=>$path));
                 break;
         }
		$list = Db::name('album')->where(['status'=>1,'type'=>$fileType])->select();
        $info = array(
        	'num'=> input('num/d'),
        	'fileType'=> $fileType,
            'title' => '',
			'list'=>$list,
            'upload' =>$upload,
        	'fileList'=>$fileList,
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
            'type' =>$type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        View::assign('info',$info);

		return View::fetch('uploadImg');
    }



    //自定义海报专用上传图片
    public function poster_upload(){
        $func = input('func');
        $path = input('path','temp');
        $image_upload_limit_size = config('image_upload_limit_size');
        $fileType = input('fileType','Images');  //上传文件类型，视频，图片
        if($fileType == 'Flash'){
            $upload = url('Admin/Ueditor/videoUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'video'));
            $type = 'mp4,3gp,flv,avi,wmv';
        }else{
            $upload = url('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images'));
            $type = 'jpg,png,gif,jpeg';
        }
        $info = array(
            'num'=> input('num/d'),
            'fileType'=> $fileType,
            'title' => '',
            'upload' =>$upload,
            'fileList'=>url('Admin/Uploadify/fileList',array('path'=>$path)),
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
            'type' =>$type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        View::assign('info',$info);
        return View::fetch();
    }


    /**
     * 删除上传的图片,视频
     */
    public function delupload(){
        $action = input('action','del');
        $filename= input('filename');
        $filename= empty($filename) ? input('url') : $filename;
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if($action=='del' && !empty($filename) && file_exists($filename)){
            $filetype = strtolower(strstr($filename,'.'));
            $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP文件
            $erasable_type = config('erasable_type');  //可删除文件
            if(!in_array($filetype,$erasable_type) || $phpfile){
                exit;
            }
            if(unlink($filename)){
				if(input('url') !==''){
					Db::name('file')->where(['path'=>input('url')])->delete();
				}
                $this->deleteWechatImage(input('url'));
                echo 1;
            }else{
                echo 0;
            }
            exit;
        }
    }
    
    public function fileList()
    {
    	/* 判断类型 */
    	$type = input('type','Images');
    	switch ($type){
    		/* 列出图片 */
    		case 'Images' : $allowFiles = 'png|jpg|jpeg|gif|bmp';break;
    	
    		case 'Flash' : $allowFiles = 'mp4|3gp|flv|avi|wmv|flash|swf';break;
    	
    		/* 列出文件 */
    		default : $allowFiles = '.+';
    	}

    	$path = UPLOAD_PATH.input('path','temp');
    	//echo file_exists($path);echo $path;echo '--';echo $allowFiles;echo '--';echo $key;exit;
    	$listSize = 100000;
    	
    	$key = empty($_GET['key']) ? '' : $_GET['key'];
    	
    	/* 获取参数 */
    	$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
    	$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
    	$end = $start + $size;

    	//区分请求方式为post请求时，修改时功能为商品图片编辑
        if (request()->isPost()){
            $size = isset($_POST['size']) ? htmlspecialchars($_POST['size']) : $listSize;
            $start = isset($_POST['start']) ? htmlspecialchars($_POST['start']) : 0;
            $end = $start + $size;
        }
    	/* 获取文件列表 */
    	$files = $this->getfiles($path, $allowFiles, $key,['public/upload/goods/thumb']);

    	if (!count($files)) {
    		echo json_encode(array(
    				"state" => "没有相关文件",
    				"list" => array(),
    				"start" => $start,
    				"total" => count($files)
    		));
    		exit;
    	}

    	/* 获取指定范围的列表 */

        //排序逻辑较复杂，优化采用二维数组对时间排序，截取指定位置及长度
        $mtime = array_column($files,'mtime');
        array_multisort($mtime,SORT_DESC,$files);
        $list = array_slice($files,$start,$size);

    	/* 返回数据 */
    	$result = json_encode(array(
    			"state" => "SUCCESS",
    			"list" => $list,
    			"start" => $start,
    			"total" => count($files)
    	));
    	echo $result;
    }

	public function fileListNew()
	{
		/* 判断类型 */
		$search      = input('search','');
		$category_id = input('category_id','0');

		/* 获取参数 */
		$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : 12;
		$start = ($page-1)*$size;
		$end = $page*$size;

		//区分请求方式为post请求时，修改时功能为商品图片编辑
		if (request()->isPost()){
			$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
			$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : 12;
			$start = ($page-1)*$size;
			$end = $page*$size;
		}
		$where['status']      = 1;
		$where['category_id'] = $category_id;
		if($search){
			$where['name'] = ['like',"%{$search}%"];
		}
		/* 获取文件列表 */
		$count  = Db::name('file')->where($where)->count();
		$list   = Db::name('file')->where($where)->limit($start,$end)->order('createtime desc')->select();
		$result = array(
				"state" => count($list)<=0?'no match file': 'SUCCESS',
				"list" => $list,
				"page" => $page,
				'count'=> $count
		);
		$this->ajaxReturn($result);
	}

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, $key,$ignore = array(), &$files = array()){
    	if (!is_dir($path)) return null;
		static $step = 0;
		$step++;
		if($step > 100) return $files;
    	if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    	$handle = opendir($path);
    	while (false !== ($file = readdir($handle))) {
    		if ($file != '.' && $file != '..') {
    			$path2 = $path . $file;
    			if (is_dir($path2) && !in_array($path2,$ignore)) {
                    $this->getfiles($path2, $allowFiles, $key,array(), $files);
    			} else {
    				if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
    					$files[] = array(
    						'url'=> '/'.$path2,
    						'name'=> $file,
    						'mtime'=> filemtime($path2)
    					);
    				}
    			}
    		}
    	}
    	return $files;
    }

	public function preview(){

		// 此页面用来协助 IE6/7 预览图片，因为 IE 6/7 不支持 base64
		$DIR = 'preview';
		// Create target dir
		if (!file_exists($DIR)) {
			@mkdir($DIR);
		}

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		if ($cleanupTargetDir) {
			if (!is_dir($DIR) || !$dir = opendir($DIR)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;
				// Remove temp file if it is older than the max age and is not the current file
				if (@filemtime($tmpfilePath) < time() - $maxFileAge) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}

		$src = file_get_contents('php://input');
		if (preg_match("#^data:image/(\w+);base64,(.*)$#", $src, $matches)) {
			$previewUrl = sprintf(
					"%s://%s%s",
					isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
					$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']
			);
			$previewUrl = str_replace("preview.php", "", $previewUrl);
			$base64 = $matches[2];
			$type = $matches[1];
			if ($type === 'jpeg') {
				$type = 'jpg';
			}

			$filename = md5($base64).".$type";
			$filePath = $DIR.DIRECTORY_SEPARATOR.$filename;

			if (file_exists($filePath)) {
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			} else {
				$data = base64_decode($base64);
				$filePathLower = strtolower($filePath);
				if (strstr($filePathLower, '../') || strstr($filePathLower, '..\\') || strstr($filePathLower, '.php')) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "文件上传格式错误 error ！"}}');
				}
				// 文件格式判断
				strstr(strtolower($filePath),'.php') && exit('文件格式不对');
				file_put_contents($filePath, $data);
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			}
		} else {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "un recoginized source"}}');
		}
	}

    public function wechatImageList($listSize, $get)
    {
        $size = isset($get['size']) ? intval($get['size']) : $listSize;
        $start = isset($get['start']) ? intval($get['start']) : 0;

        $logic = new \app\common\logic\WechatLogic;
        return $logic->getPluginImages($size, $start);
    }

    public function deleteWechatImage($file_path)
    {
        $logic = new \app\common\logic\WechatLogic;
        $logic->deleteImage($file_path);
    }

	/**
	 *相册列表
	 */
	public function photoAlbumList(){
		 
		$list = Db::name('album')->order('category_id desc')->select();
		View::assign('list',$list);
		return View::fetch();
	}

	/**
	 * 获取文件夹列表
	 *
	 */
	public function  albumList()
	{
		$type = input('type/d','1');
		$where['status'] 	= 1;
		$where['type'] 		= $type;
		$list = Db::name('album')->where($where)->select();
		$result = json_encode(array("list" => $list));
		return $result;
	}

	/**
	 * 文件夹新增
	 *
	 */
	public function  CreateAlbum()
	{
		$type 		= input('type','logo');
		$name 		= input('name','');
		$explain 	= input('explain','');
		$category_id 		= input('category_id','0');
		$data['name'] 		= $name ;
		$data['explain'] 	= $explain ;
		$data['updatetime'] = time() ;
		if($category_id>0){
			$result  = Db::name('album')->where(['category_id'=>$category_id])->save($data);
		}else{
			$data['type'] =$type ;
			$data['createtime'] =time() ;
			$result = Db::name('album')->insertGetId($data);
			if($result){
				$file_id = Db::name('file')->where('category_id',0)->value('file_id');
				if($file_id){
					Db::name('file')->where('category_id',0)->update(['category_id'=>$result]);
				}
			}
		}
		if(!$result)  return $this->ajaxReturn(['status'=>0,'msg'=>'操作失败！']);
		return $this->ajaxReturn(['status'=>200,'msg'=>'操作成功！']);
	}

	public function deleteFile(){
		$file_id = input('file_id/d');
		if($file_id && $file_id >0){

			$category_id = Db::name('file')->where(['file_id'=>$file_id])->value('category_id');
			if($category_id){
				$number = Db::name('file')->where('category_id',$category_id)->count();
				Db::name('album')->where('category_id',$category_id)->update(['number'=> --$number]);
			}


			Db::name('file')->where(['file_id'=>$file_id])->delete();
		}
	}

	public function photoList(){
		$category_id = $_GET['category_id'];
		$count = Db::name('file')->where(['category_id'=>$category_id])->count();
		View::assign('category_id', $category_id);
		return View::fetch();
	}

	public function ajaxPhotoList(){
		$category_id = $_GET['category_id'];
		$count = Db::name('file')->where(['category_id'=>$category_id])->count();
		$Page  = new AjaxPage($count, 48);
		$list  = Db::name('file')->where(['category_id'=>$category_id])->limit($Page->firstRow,$Page->listRows)->order('createtime desc')->select();
		View::assign('list',$list);
		$show = $Page->show();
		View::assign('page', $show);// 赋值分页输出
		View::assign('pager', $Page);
		return View::fetch();
	}


}