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
 * Date: 2015-09-09
 */

namespace app\supplier\controller;
use think\facade\View;

use think\Controller;
use think\facade\Db;
use think\facade\Session;
use app\BaseController;
use liliuwei\think;
class Base extends BaseController{

use \liliuwei\think\Jump;

    public $begin;
    public $end;
    public $page_size = 0;
    public $supplier;

    /**
     * 析构函数
     */
    function __construct()
    {
        
        header("Cache-control: private");  // history.back返回后输入框值丢失问题 参考文章 http://www.tp-shop.cn/article_id_1465.html  http://blog.csdn.net/qinchaoguang123456/article/details/29852881
        
        //过滤不需要登陆的行为
        if (!in_array(ACTION_NAME, array('login','logon' ,'verify'))) {
            if (session('suppliers_id') <= 0) {
                if(ACTION_NAME == 'index')  return redirect(url('Supplier/Admin/login'));
                $this->error('请先登录', url('Supplier/Admin/login'), null, 1);
            }
        }
        $this->public_assign();
    }

    /**
     * 保存公告变量到 smarty中 比如 导航
     */
    public function public_assign()
    {
        $this->supplier = session('supplier');
        $tpshop_config = array();
        $tp_config = Db::name('config')->cache(true)->select();
        foreach ($tp_config as $k => $v) {
            $tpshop_config[$v['inc_type'] . '_' . $v['name']] = $v['value'];
        }
        if (input('start_time')) {
            $begin = $begin = input('start_time');
            $end = input('end_time');
        } else {
            $begin = date('Y-m-d', strtotime("-3 month"));//30天前
            $end = date('Y-m-d', strtotime('+1 days'));
        }
        View::assign('start_time', $begin);
        View::assign('end_time', $end);
        $this->begin = strtotime($begin);
        $this->end = strtotime($end) + 86399;
        $this->page_size = config('PAGESIZE');
        View::assign('tpshop_config', $tpshop_config);
    }


    public function ajaxReturn($data, $type = 'json')
    {
        exit(json_encode($data));
    }
}