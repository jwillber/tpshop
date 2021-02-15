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
 * Date: 2018/5/31
 * Time: 14:18
 */

namespace app\common\model;
use think\Model;
use think\facade\Db;

class RebateLog extends Model
{

    protected $pk = 'id';

    public function getUser(){
        return $this->hasOne('users','user_id','user_id')->bind(['mobile']);
    }
    public function buyUser(){
        return $this->hasOne('users','user_id','buy_user_id')->bind(['mobile','email']);
    }

    public function getAccountLogAttr($value, $data){
        $list = Db::name('account_log')->where(['user_id'=>$data['buy_user_id'],'order_id'=>$data['order_id'],'desc'=>['like','%分佣%']])->select();
        return $list;
    }
}