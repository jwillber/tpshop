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
namespace app\home\controller;
use think\facade\View;
use think\facade\Db;

class Article extends Base {
    
    public function index(){       
        $article_id = input('article_id/d',38);
    	$article = Db::name('article')->where("article_id", $article_id)->find();
    	View::assign('article',$article);
        return View::fetch();
    }
 
    /**
     * 文章内列表页
     */
    public function articleList(){
        $article_cat = Db::name('ArticleCat')->where("parent_id  = 0")->select();
        View::assign('article_cat',$article_cat);
        return View::fetch();
    }    
    /**
     * 文章内容页
     */
    public function detail(){
    	$article_id = input('article_id/d',1);
    	$article = Db::name('article')->where("article_id", $article_id)->find();
        if(strstr($article['link'],'http'))
        {
            header("Location: {$article['link']}");
            exit;
        }
    	if($article){
    		$parent = Db::name('article_cat')->where("cat_id",$article['cat_id'])->find();
    		View::assign('cat_name',$parent['cat_name']);
    		View::assign('article',$article);
    	}
        return View::fetch();
    } 
    
    /**
     * 获取服务协议
     * @return mixed
     */
    public function agreement(){
    	$doc_code = input('doc_code','agreement');
    	$article = Db::name('system_article')->where('doc_code',$doc_code)->find();
    	if(empty($article)) $this->error('抱歉，您访问的页面不存在！');
    	View::assign('article',$article);
    	return View::fetch();
    }

}