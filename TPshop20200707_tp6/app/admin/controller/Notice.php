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
 * Author: yjp
 * Date: 2018-11-15
 */
namespace app\admin\controller;
use think\facade\View;

use think\Page;
use \app\common\model\saas\Article;
use \app\common\model\saas\Users;
use \app\common\model\saas\AppService;

class Notice extends Base {

    public function Message(){
        $Article = new Article();
        $count = $Article->count();
        $page = new Page($count, 10);        
        $notice_list = $Article->limit($page->firstRow,$page->listRows)->select();    
        if($notice_list){            
            $AppService = AppService::find($GLOBALS['SAAS_CONFIG']['service_id']);
            $users = Users::find($AppService['user_id']);
            $is_read = explode(',',$users['read_noticls']) ;
            foreach ($notice_list as &$v){
                $v['is_read'] = 0;
                if(in_array($v['article_id'], $is_read)){
                    $v['is_read'] = 1;
                }
            } 
        }
  
        View::assign('page', $page);      
        View::assign('num',count($notice_list));
        View::assign('notice',$notice_list);
        return View::fetch();
    }
    
     
    public function detail(){
        $id = input('id');
        $params = http_build_query([
            'article_id' => $id,
            'service_id' => $GLOBALS['SAAS_CONFIG']['service_id'],
        ]);
        $verifyUrl = 'http://'.$GLOBALS['SAAS']['saas_domain'].'/client/sso/noticeDetail';
        $result = httpRequest($verifyUrl,'POST',$params);
        $result = json_decode($result,true);
        $result['data']['content'] = htmlspecialchars_decode($result['data']['content']);
 
        View::assign('notice',$result['data']);
        return View::fetch();
    }
}