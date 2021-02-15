<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */ 
namespace app\mobile\controller;
use think\facade\View;
use think\facade\Db;

class Topic extends MobileBase {
	/*
	 * 专题列表
	 */
	public function topicList(){
		$topicList = Db::name('topic')->where("topic_state=2")->select();
		View::assign('topicList',$topicList);
		return View::fetch();
	}
	
	/*
	 * 专题详情
	 */
	public function detail(){
		$topic_id = input('topic_id/d',1);
		$topic = Db::name('topic')->where("topic_id", $topic_id)->find();
		View::assign('topic',$topic);
		return View::fetch();
	}
	
	public function info(){
		$topic_id = input('topic_id/d',1);
		$topic = Db::name('topic')->where("topic_id", $topic_id)->find();
        echo htmlspecialchars_decode($topic['topic_content']);                
        exit;
	}
}