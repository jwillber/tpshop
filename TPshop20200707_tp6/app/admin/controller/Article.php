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
 * 采用最新Thinkphp6
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-09
 */
namespace app\admin\controller;
use think\facade\View;

use think\Page;
use think\facade\Db;
use app\admin\logic\ArticleCatLogic;
use think\Request;
class Article extends Base {

    public function categoryList(){
        $ArticleCat = new ArticleCatLogic(); 
        $cat_list = $ArticleCat->article_cat_list(0, 0, false);
        View::assign('cat_list',$cat_list);
        return View::fetch('categoryList');
    }

    public function category()
    {
        $ArticleCat = new ArticleCatLogic();
        $act = input('get.act', 'add');
        $cat_id = input('get.cat_id/d');
        $parent_id = input('get.parent_id/d');
        if ($cat_id) {
            $cat_info = Db::name('article_cat')->where('cat_id=' . $cat_id)->find();
            $parent_id = $cat_info['parent_id'];
            View::assign('cat_info', $cat_info);
        }
        $cats = $ArticleCat->article_cat_list(0, $parent_id, true);
        View::assign('act', $act);
        View::assign('cat_select', $cats);
        return View::fetch();
    }
    
    public function articleList(){
        
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];
        
        $where = " 1 = 1 ";
        $keywords = trim(input('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";
        $cat_id = input('cat_id',0);
        $cat_id && $where.=" and cat_id = $cat_id ";
        $count = Db::name('Article')->where($where)->count();// 查询满足要求的总记录数		
        $res = Db::name('Article')->where($where)->order('article_id desc')->page($p,$size)->select();
        $pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        //$page = $pager->show();//分页显示输出
        
        $ArticleCat = new ArticleCatLogic();
        $cats = $ArticleCat->article_cat_list(0,0,false);
        if($res){
        	foreach ($res as $val){
        		$val['category'] = $cats[$val['cat_id']]['cat_name'];
        		$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
        		$list[] = $val;
        	}
        }
        View::assign('cats',$cats);
        View::assign('cat_id',$cat_id);
        View::assign('list',$list);// 赋值数据集
        View::assign('pager',$pager);// 赋值分页输出        
		return View::fetch('articleList');
    }
    
    public function article(){
        $ArticleCat = new ArticleCatLogic();
 		$act = input('get.act','add');
        $info = array();
        $info['publish_time'] = time()+3600*24;
        if(input('get.article_id/d')){
           $article_id = input('get.article_id/d');
           $info = Db::name('article')->where('article_id='.$article_id)->find();
        }
        $cats = $ArticleCat->article_cat_list(0,$info['cat_id']);
        View::assign('cat_select',$cats);
        View::assign('act',$act);
        View::assign('info',$info);
        return View::fetch();
    }
    
    
    public function categoryHandle()
    {
    	$data = input('post.');


        $validate = validate('\app\admin\validate\ArticleCategory.'.$data['act']);
         if (!$validate->batch(true)->check($data)) {
            // 验证失败 输出错误信息
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => -1, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);            
        }        
        
        
        if ($data['act'] == 'add') {
            $r = Db::name('article_cat')->insertGetId($data);
        } elseif ($data['act'] == 'edit') {
        	$cat_info = Db::name('article_cat')->where("cat_id",$data['cat_id'])->find();
        	if($cat_info['cat_type'] == 1 && $data['parent_id'] > 1){
        		$this->ajaxReturn(['status' => -1, 'msg' => '可更改系统预定义分类的上级分类']);
        	}
        	$r = Db::name('article_cat')->where("cat_id",$data['cat_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	if($data['cat_id']<9){
        		$this->ajaxReturn(['status' => -1, 'msg' => '系统默认分类不得删除']);
        	}
        	if (Db::name('article_cat')->where('parent_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '还有子分类，不能删除']);
        	}
        	if (Db::name('article')->where('cat_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '该分类下有文章，不允许删除，请先删除该分类下的文章']);
        	}
        	$r = Db::name('article_cat')->where('cat_id', $data['cat_id'])->delete();
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        } 
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }
    
    public function aticleHandle()
    {
        $data = input('post.');
        $data['publish_time'] = strtotime($data['publish_time']);
 
        $validate = validate('Article.'.$data['act']);
         if (!$validate->batch(true)->check($data)) {
            // 验证失败 输出错误信息
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => -1, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);            
        }        
        
        $where['cat_id']=$data['cat_id'];
        $where['title']=$data['title'];
        if($data['act']=='edit'){
            $where['article_id']=array('<>',$data['article_id']);
        }
        $find = Db::name('article')->where($where)->find();
        if($find){
            $this->ajaxReturn(['status' => 0, 'msg' => '此分类下已存在该标题']);
        }
        if ($data['act'] == 'add') {
            $data['click'] = mt_rand(1000,1300);
        	$data['add_time'] = time(); 
            $r = Db::name('article')->insertGetId($data);
        } elseif ($data['act'] == 'edit') {
            $r = Db::name('article')->where('article_id='.$data['article_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	$r = Db::name('article')->where('article_id='.$data['article_id'])->delete(); 	
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
            
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }

    public function delList(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $listIds = rtrim($ids);
        Db::name('article')->whereIn('article_id',$listIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Article/articleList")]);
    }
    
    
    public function link(){
    	$act = input('get.act','add');
    	View::assign('act',$act);
    	$link_id = input('get.link_id/d');
    	$link_info = array();
    	if($link_id){
    		$link_info = Db::name('friend_link')->where('link_id='.$link_id)->find();
    		View::assign('info',$link_info);
    	}
    	return View::fetch();
    }
    
    public function linkList(){
    	
        //$p = request()->param('p',1);
        $p = request()->param('p',1);
        $count = Db::name('friend_link')->count();// 查询满足要求的总记录数
    	$res = Db::name('friend_link')->order('orderby')->page($p,10)->select();        
    	if($res){
    		foreach ($res as $val){
    			$val['target'] = $val['target']>0 ? '开启' : '关闭';
    			$list[] = $val;
    		}
    	}
    	View::assign('list',$list);// 赋值数据集
    	$count = Db::name('friend_link')->count();// 查询满足要求的总记录数
    	$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show = $Page->show();// 分页显示输出
        View::assign('pager',$Page);
    	View::assign('page',$show);// 赋值分页输出
    	return View::fetch();
    }
    
    public function linkHandle(){
        $data = input('post.');
    	if($data['act'] == 'del'){
    		$r = Db::name('friend_link')->where(['link_id'=>$data['link_id']])->delete();
    		if($r) exit(json_encode(1));
    	}
    	if($r){
    		$this->success("操作成功",url('Admin/Article/linkList'));
    	}else{
            $this->error("操作失败");
    	}
    }
    
    public function  addEdit(){
        $data = input('post.');

        $validate = validate('\app\admin\validate\FriendLink.'.$data['act']);
         if (!$validate->batch(true)->check($data)) {
            // 验证失败 输出错误信息
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => -1, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);            
        }
        if($data['link_id']){
            $link_id=$data['link_id'];
            unset($data['link_id']);
            $res = Db::name('friend_link')->where(['link_id'=>$link_id])->save($data);
        }else{
            $res = Db::name('friend_link')->insertGetId($data);
        }
        if($res){
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>url('Admin/Article/linkList')]);
        }else{
            $this->ajaxReturn(['status'=>-1,'msg'=>'操作失败']);
        }
    }
    
    public function agreement(){
    	$agreement = Db::name('system_article')->select();
    	View::assign('agreement',$agreement);
    	return View::fetch();
    }
    
    public function edit_agreement(){
    	$doc_id = input('doc_id');
    	if(IS_POST){
    		$data = input('post.');
            if(!empty($doc_id)) {
                Db::name('system_article')->where('doc_id',$doc_id)->save($data);
            }
    		else {
                $data['doc_time'] = time();
                Db::name('system_article')->insert($data);
            }
            $this->success('更新成功!',url('Article/agreement'));
    	}
    	if (!empty($doc_id)) {
            $info = Db::name('system_article')->where('doc_id',$doc_id)->find();
            if(empty($info)) $this->error('该协议不存在');
            View::assign('info',$info);
        }
    	return View::fetch();
    }

    public function delLinks(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $link_ids = rtrim($ids);
        Db::name('friend_link')->whereIn('link_id',$link_ids)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/article/linkList")]);
    }
    
}