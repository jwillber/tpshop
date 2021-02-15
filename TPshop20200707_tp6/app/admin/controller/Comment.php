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
 * 评论管理控制器
 * Date: 2015-10-20
 */

namespace app\admin\controller;
use think\facade\View;

use think\AjaxPage;
use think\Page;
use think\facade\Db;

class Comment extends Base {


    public function index(){
        return View::fetch();
    }

    public function detail(){
        $id = input('get.id/d');
        $res = Db::name('comment')->where(['comment_id'=>$id])->find();
        if(!$res){
            exit($this->error('不存在该评论'));
        }
        if(IS_POST){
            $admin_name = Db::name('admin')->where(['admin_id'=>$this->admin_id])->value('user_name');
            $add['parent_id'] = $id;
            $add['content'] = trim(input('post.content'));
            $add['goods_id'] = $res['goods_id'];
            $add['add_time'] = time();
            $add['username'] = $admin_name;
            $add['is_show'] = 1;
            empty($add['content']) && $this->error('请填写回复内容');
            $row =  Db::name('comment')->insertGetId($add);
            if($row){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
            exit;

        }
        $reply = Db::name('comment')->where(array('parent_id'=>$id))->select(); // 评论回复列表
        $res['img']=unserialize($res['img']);
        View::assign('comment',$res);
        View::assign('reply',$reply);
        return View::fetch();
    }


    /***
     *操作商品评论
     */
    public function commentHandle(){
        $type = input('post.type');
        $ids = input('post.ids','');
        if(!in_array($type, array('del', 'show', 'hide')) || empty($ids)){
            $this->ajaxReturn(['status' => -1,'msg' => '非法操作！']);
        }
        $comment_ids = rtrim($ids,",");
        $row = false;
        $goods_ids = $row = Db::name('comment')->where('comment_id', 'IN', $comment_ids)->whereOr('parent_id', 'IN', $comment_ids)->value('goods_id');
        $goods_ids = is_array($goods_ids) ? $goods_ids : [$goods_ids];
        if ($type == 'del') {
            //删除咨询
            $row = Db::name('comment')->where('comment_id', 'IN', $comment_ids)->whereOr('parent_id', 'IN', $comment_ids)->delete();
            Db::name('goods')->where('goods_id','IN',$goods_ids)->dec('comment_count')->update();

        }
        if ($type == 'show') {
//            $row = Db::name('comment')->where('comment_id', 'IN', $comment_ids)->save(['is_show' => 1]);
            Db::name('goods')->where('goods_id','IN',$goods_ids)->inc('comment_count')->update();
        }
        if ($type == 'hide') {
//            $row = Db::name('comment')->where('comment_id', 'IN', $comment_ids)->save(['is_show' => 0]);
            Db::name('goods')->where('goods_id','IN',$goods_ids)->dec('comment_count')->update();
        }
        if($row !== false){
            $this->ajaxReturn(['status' => 1,'msg' => '操作完成','url'=>url('Admin/Comment/index')]);
        }else{
            $this->ajaxReturn(['status' => -1,'msg' => '操作失败','url'=>url('Admin/Comment/index')]);
        }
    }

    public function ajaxindex(){
        
        $username = input('nickname','','trim');
        $content = input('content','','trim');
        $where['parent_id'] = 0;
        if($username){
            $where['username'] = $username;
        }
        if ($content) {
            $where['content'] = ['like', '%' . $content . '%'];
        }
        $count = Db::name('comment')->where($where)->count();
        $Page = $pager = new AjaxPage($count,16);
        $show = $Page->show();
                
        $comment_list = Db::name('comment')->where($where)->order(['sort'=>'asc','add_time'=>'DESC'])->limit($Page->firstRow,$Page->listRows)->select();
        if(!$comment_list->isEmpty())
        {
            $goods_id_arr = get_arr_column($comment_list, 'goods_id');
            $goods_list = Db::name('Goods')->where("goods_id", "in" , implode(',', $goods_id_arr))->column('goods_name','goods_id');
        }
        View::assign('goods_list',$goods_list);
        View::assign('comment_list',$comment_list);
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$pager);// 赋值分页输出
        return View::fetch();
    }
    
    public function ask_list(){
    	return View::fetch();
    }
    
    public function ajax_ask_list(){
    	$username = input('nickname','','trim');
    	$content = input('content','','trim');
    	$where['parent_id']= 0;
    	if($username){
    		$where['username']= $username;
    	}
    	if($content){
    		$where['content'] = ['like', "%$content%"];
    	}
        $count = Db::name('goods_consult')->where($where)->count();
        $Page  = $pager = new AjaxPage($count,10);
        $show  = $Page->show();            	
    	
        $comment_list = Db::name('goods_consult')->where($where)->order('add_time DESC')->limit($Page->firstRow,$Page->listRows)->select();
    	if(!$comment_list->isEmpty())
    	{
    		$goods_id_arr = get_arr_column($comment_list, 'goods_id');
    		$goods_list = Db::name('Goods')->where("goods_id", "in", implode(',', $goods_id_arr))->column('goods_name','goods_id');
    	}
    	$consult_type = array(0=>'默认咨询',1=>'商品咨询',2=>'支付咨询',3=>'配送',4=>'售后');
    	View::assign('consult_type',$consult_type);
    	View::assign('goods_list',$goods_list);
    	View::assign('comment_list',$comment_list);
    	View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$pager);// 赋值分页输出
    	return View::fetch();
    }
    
    public function consult_info(){
    	$id = input('id/d',0);
    	$res = Db::name('goods_consult')->where(array('id'=>$id))->find();
    	if(!$res){
    		exit($this->error('不存在该咨询'));
    	}
    	if(IS_POST){
    		$add['parent_id'] = $id;
    		$add['content'] = input('post.content');
    		$add['goods_id'] = $res['goods_id'];
            $add['consult_type'] = $res['consult_type'];
    		$add['add_time'] = time();    		
    		$add['is_show'] = 1;   	
    		$row =  Db::name('goods_consult')->insertGetId($add);
            if ($row) {
                $add['add_time']=date('Y-m-d H:i',$add['add_time']);
                $this->ajaxReturn(['status'=>1,'msg'=>'添加成功','resault'=>$add]);
            } else {
                $this->ajaxReturn(['status'=>1,'msg'=>'添加成功']);
            }
    		exit;    	
    	}
    	$reply = Db::name('goods_consult')->where(array('parent_id'=>$id))->select(); // 咨询回复列表
        View::assign('id', $id);
    	View::assign('comment',$res);
    	View::assign('reply',$reply);
    	return View::fetch();
    }

    public function ask_handle()
    {
        $type = input('post.type');
        $ids = input('ids','');
        if(!in_array($type, array('del', 'show', 'hide')) || empty($ids)){
            $this->ajaxReturn(['status' => -1,'msg' => '非法操作！']);
        }
        $selected_id = rtrim($ids,",");
        $row = false;
        if ($type == 'del') {
            //删除咨询
            $row = Db::name('goods_consult')->where('id', 'IN', $selected_id)->whereOr('parent_id', 'IN', $selected_id)->delete();
        }
        if ($type == 'show') {
            $row = Db::name('goods_consult')->where('id', 'IN', $selected_id)->save(array('is_show' => 1));
        }
        if ($type == 'hide') {
            $row = Db::name('goods_consult')->where('id', 'IN', $selected_id)->save(array('is_show' => 0));
        }
        if($row !== false){
            $this->ajaxReturn(['status' => 1,'msg' => '操作完成','url'=>url('Admin/Comment/ask_list')]);
        }else{
            $this->ajaxReturn(['status' => -1,'msg' => '操作失败','url'=>url('Admin/Comment/ask_list')]);
        }
    }
}