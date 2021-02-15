<?php


namespace app\common\model;
use think\facade\Db;
use think\Model;
class ShoppingCardGoods extends Model
{
    public function getGoodNameAttr($value,$data)
    {
        if($data['goods_id']){
            $goods=Db::name('goods')->where(['goods_id'=>$data['goods_id']])->find();
            return $goods['goods_name'];
        }else if($data['goods_category_id']){
            $category=Db::name('goods_category')->where(['id'=>$data['goods_category_id']])->find();
            return ['name'=>$category['name'],'mobile_name'=>$category['mobile_name']];
        }
    }
}