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
 * 专题管理
 * Date: 2015-09-09
 */

namespace app\admin\controller;
use think\facade\View;
use think\Page;
use think\facade\Db;

class Topic extends Base {

    public function index(){
        return View::fetch();
    }
    
    public function topic(){
    	$act = input('get.act','add');
    	View::assign('act',$act);
    	$topic_id = input('get.topic_id');
    	if($topic_id){
    		$topic_info = Db::name('topic')->where('topic_id='.$topic_id)->find();
    		View::assign('info',$topic_info);
    	}
    	
    	View::assign("URL_upload", url('Admin/Ueditor/imageUp',array('savepath'=>'topic')));
    	View::assign("URL_fileUp", url('Admin/Ueditor/fileUp',array('savepath'=>'topic')));
    	View::assign("URL_scrawlUp", url('Admin/Ueditor/scrawlUp',array('savepath'=>'topic')));
    	View::assign("URL_getRemoteImage", url('Admin/Ueditor/getRemoteImage',array('savepath'=>'topic')));
    	View::assign("URL_imageManager", url('Admin/Ueditor/imageManager',array('savepath'=>'topic')));
    	View::assign("URL_imageUp", url('Admin/Ueditor/imageUp',array('savepath'=>'topic')));
    	View::assign("URL_getMovie", url('Admin/Ueditor/getMovie',array('savepath'=>'topic')));
    	View::assign("URL_Home", "");
    	return View::fetch();
    }
    
    public function topicList(){
    	 
	$p = request()->param('p',1);
    	$res = Db::name('topic')->order('ctime')->page($p.',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$val['topic_state'] = $val['topic_state']>1 ? '已发布' : '未发布';
    			$val['ctime'] = date('Y-m-d H:i',$val['ctime']);
    			$list[] = $val;
    		}
    	}
    	View::assign('list',$list);// 赋值数据集
    	$count = Db::name('topic')->count();// 查询满足要求的总记录数
    	$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show = $Page->show();// 分页显示输出
	View::assign('pager',$Page);
    	View::assign('page',$show);// 赋值分页输出
    	return View::fetch();
    }
    
    public function topicHandle(){
    	$data = input('post.');
        $data['topic_content'] = $_POST['topic_content']; // 这个内容不做转义
    	if($data['act'] == 'add'){
    		$data['ctime'] = time();
    		$find = Db::name('topic')->where(['topic_title'=>$data['topic_title']])->find();
    		if($find){$this->ajaxReturn(['status'=>0,'msg'=>'已存在改标题','result'=>'']);}
    		$r = Db::name('topic')->insertGetId($data);
    	}
    	if($data['act'] == 'edit'){
    	    $where['topic_title'] = $data['topic_title'];
    	    $where['topic_id'] = array('<>',$data['topic_id']);
            $find = Db::name('topic')->where($where)->find();
            if($find){$this->ajaxReturn(['status'=>0,'msg'=>'已存在改标题','result'=>'']);}
    		$r = Db::name('topic')->where('topic_id='.$data['topic_id'])->save($data);
    	}
    	 
    	if($data['act'] == 'del'){
    		$r = Db::name('topic')->where('topic_id='.$data['topic_id'])->delete();
    		if($r) exit(json_encode(1));
    	}
    	 
    	if($r !== false){
			$this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
    	}else{
			$this->ajaxReturn(['status'=>0,'msg'=>'操作失败','result'=>'']);
    	}
    }

	public function delTopicList(){
		$ids = input('post.ids','');
		empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
		$topicListIds = rtrim($ids);
		Db::name('topic')->whereIn('topic_id',$topicListIds)->delete();
		$this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Topic/topicList")]);
	}
}