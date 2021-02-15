<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */
namespace app\common\model;
use think\facade\Db;
use think\Model;
class PromGoods extends Model {

    protected $pk = 'id';


    public function PromGoodsItem()
    {
        return parent::hasMany('PromGoodsItem', 'prom_id', 'id');
    }

    public function getPromDetailAttr($value,$data)
    {
        switch ($data['type']){
            case 1:
                $title = '优惠￥'.$data['expression'];
                break;
            case 2:
                $title = '促销价￥'.$data['expression'];
                break;
            case 3:
                $coupon = Db::name('coupon')->where('id', $data['expression'])->find();
                $title = '买就送'.$coupon['name'].'优惠券￥'.$coupon['money'];
                break;
            default:
                $discount = $data['expression']/10;
                $title = $discount.'折';
        }
        return $title;
    }

    public function getPromDescAttr($value,$data)
    {
        $parse_type = array('0' => '直接打折', '1' => '减价优惠', '2' => '固定金额出售', '3' => '买就赠优惠券');
        return $parse_type[$data['type']];
    }
    //状态描述
    public function getStatusDescAttr($value, $data)
    {
        if($data['is_end'] == 1){
            // 得设置商品未参与活动
            $result = Db::name('goods')->where(['prom_type'=>3,'prom_id'=>$data['id']])->find();
            if($result){
                Db::name('goods')->where(['prom_type'=>3,'prom_id'=>$data['id']])->update(['prom_type'=>0,'prom_id'=>0]);
            }
            return '已结束';
        }else{
            if($data['start_time'] > time()){
                return '未开始';
            }else if ($data['start_time'] < time() && $data['end_time'] > time()) {
                return '进行中';
            }else{
                return '已过期';
            }
        }
    }

    /**
     * 是否编辑
     * @param $value
     * @param $data
     * @return int
     */
    public function getIsEditAttr($value, $data)
    {
        if ($data['is_end'] == 1 || $data['start_time'] < time()){
            return 0;
        }
        return 1;
    }
}
