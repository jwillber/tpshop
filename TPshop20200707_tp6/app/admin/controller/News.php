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
 * Author: 聂晓克      
 * Date: 2018-05-03
 */
namespace app\admin\controller;
use think\facade\View;

use think\Page;
use app\admin\logic\NewsLogic;
use think\facade\Db;

class News extends Base {

    public function categoryList(){
        $ArticleCat = new NewsLogic(); 
        $cat_list = $ArticleCat->article_cat_list(0, 0, false);
        View::assign('cat_list',$cat_list);
        return View::fetch('categoryList');
    }

    public function category()
    {  
        $ArticleCat = new NewsLogic();
        $act = input('get.act', 'add');
        $cat_id = input('get.cat_id/d');

        $parent_id = input('get.parent_id/d');
        if ($cat_id) {
            $cat_info = Db::name('news_cat')->where('cat_id=' . $cat_id)->find();
            $parent_id = $cat_info['parent_id'];
            View::assign('cat_info', $cat_info);
        }
        $cats = $ArticleCat->article_cat_list(0, $parent_id, true);
        View::assign('act', $act);
        View::assign('cat_select', $cats);
        return View::fetch();
    }
    
    public function newsList(){
     
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];
        
        $where = " 1 = 1 ";
        $keywords = trim(input('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";
        $cat_id = input('cat_id',0);
        $cat_id && $where.=" and cat_id = $cat_id ";
        $res = Db::name('news')->where($where)->order('article_id desc')->page($p,$size)->select();
        $count = Db::name('news')->where($where)->count();// 查询满足要求的总记录数
        $pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        //$page = $pager->show();//分页显示输出
        
        $ArticleCat = new NewsLogic();
        $cats = $ArticleCat->article_cat_list(0,0,false);
        if($res){
        	foreach ($res as $val){
        		$val['category'] = $cats[$val['cat_id']]['cat_name'];
        		$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
        		$list[] = $val;
        	}
        }

        $news_tag=config('NEWS_TAG');
        foreach ($list as $k => $v) {

            if($v['tags'] !=''){
                $str='';
                $tmp=explode(',', $v['tags']);
                foreach ($tmp as $k2 => $v2) {
                    $str.='['.$news_tag[$v2].']'.' ';
                }
                $list[$k]['tags']=$str;
            }
        }

        $admin_info=getAdminInfo(session('admin_id'));
        View::assign('cats',$cats);
        View::assign('cat_id',$cat_id);
        View::assign('list',$list);// 赋值数据集
        View::assign('pager',$pager);// 赋值分页输出        
		return View::fetch('newsList');
    }
    
    public function article(){
        $ArticleCat = new NewsLogic();
 		$act = input('get.act','add');
        $info = array();
        $info['publish_time'] = time()+3600*24;
        if(input('get.article_id')){
           $article_id = input('get.article_id');
           $info = Db::name('news')->where('article_id='.$article_id)->find();
        }
        if($info['tags']){
            $info['tags_arr']=explode(',', $info['tags']);
        }
        //dump($info);exit();


        $tag=config('NEWS_TAG');
        $admin_info=getAdminInfo(session('admin_id'));

        $cats = $ArticleCat->article_cat_list(0,$info['cat_id']);
        View::assign('cat_select',$cats);
        View::assign('act',$act);
        View::assign('info',$info);
        View::assign('tags',$tag);
        View::assign('role_id',$admin_info['role_id']);
        return View::fetch();
    }
    
    
    public function categoryHandle()
    {
    	$data = input('post.');
 
        $validate = validate('\app\admin\validate\NewsCategory.'.$data['act']);
         if (!$validate->batch(true)->check($data)) {
            // 验证失败 输出错误信息
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => -1, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);            
        }

        if ($data['act'] == 'add') {
            $r = Db::name('news_cat')->insertGetId($data);
        } elseif ($data['act'] == 'edit') {
        	$cat_info = Db::name('news_cat')->where("cat_id",$data['cat_id'])->find();
        	if($cat_info['cat_type'] == 1 && $data['parent_id'] > 1){
        		$this->ajaxReturn(['status' => -1, 'msg' => '可更改系统预定义分类的上级分类']);
        	}
        	$r = Db::name('news_cat')->where("cat_id",$data['cat_id'])->save($data);
        } elseif ($data['act'] == 'del') {
/*        	if($data['cat_id']<9){
        		$this->ajaxReturn(['status' => -1, 'msg' => '系统默认分类不得删除']);
        	}*/
        	if (Db::name('news_cat')->where('parent_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '还有子分类，不能删除']);
        	}
        	if (Db::name('news')->where('cat_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '该分类下有文章，不允许删除，请先删除该分类下的文章']);
        	}
        	$r = Db::name('news_cat')->where('cat_id', $data['cat_id'])->delete();
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
        
//        $result = $this->validate($data, 'News.'.$data['act'], [], true);
//        if ($result !== true) {
//            $this->ajaxReturn(['status' => 0, 'msg' => array_shift($result), 'result' => $result]);
//        }
        $validate = validate('\app\admin\validate\News.'.$data['act']);
         if (!$validate->batch(true)->check($data)) {
            // 验证失败 输出错误信息
            $error = $validate->getError();
            $error_msg = array_values($error);
            $return_arr = ['status' => -1, 'msg' => $error_msg[0], 'result' => $error];
            $this->ajaxReturn($return_arr);            
        }

        

        if($data['tags']){
            $data['tags']=implode(',', $data['tags']);
        }else{
            $data['tags']='';
        }
        
        if ($data['act'] == 'add') {
            $data['click'] = mt_rand(1000,1300);
        	$data['add_time'] = time();
            $r = Db::name('news')->insertGetId($data);
        } elseif ($data['act'] == 'edit') {
            $r = Db::name('news')->where('article_id='.$data['article_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	$r = Db::name('news')->where('article_id='.$data['article_id'])->delete(); 	
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }

        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }

    public function delNewsList() {
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $newsListIds = rtrim($ids);
        Db::name('news')->whereIn('article_id',$newsListIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/News/newsList")]);
    }

}