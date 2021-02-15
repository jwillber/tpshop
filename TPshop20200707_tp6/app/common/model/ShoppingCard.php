<?php


namespace app\common\model;
use think\facade\Db;
use think\Model;
class ShoppingCard extends Model
{


    public function getCatIdAttr($value,$data)
    {
        if($data['sort']==0){
            if($data['use_type']==2){
                $goods_card = Db::name('shopping_card_goods')->where('card_id',$data['id'])->find();
                $cat_info = Db::name('goods_category')->where(array('id'=>$goods_card['goods_category_id']))->find();
                $cat_path = explode('_', $cat_info['parent_id_path']);
                $item['cat_id1'] = $cat_path[1];
                $item['cat_id2'] = $cat_path[2];
                $item['cat_id3'] = $cat_path[3];
                return $item;
            }
        }
        $item['cat_id1'] = '';
        $item['cat_id2'] = '';
        $item['cat_id3'] = '';
        return $item;
    }

    public function getEnableGoodsAttr($value,$data)
    {
        if($data['give']==0){
            if($data['use_type'] == 1){
                $goods_ids = Db::name('shopping_card_goods')->where('id',$data['id'])->column('goods_id');
                $enable_goods = Db::name('goods')->where("goods_id", "in", $goods_ids)->select();
                return $enable_goods;
            }
        }
        return '';
    }

    public function getPercentAttr($value,$data)
    {
        if($data['repertory']>0){
            $sell = $data['repertory']-$data['residue'];
            $percent = ($sell/$data['repertory'])*100;
            //$num=(int)(($data['repertory']-$data['residue'])/$data['repertory'])*100;
            return $percent;
        }
        return '';
    }

    public function getUseRangeAttr($value,$data)
    {
        if($data['use_type']==0){
            return '全店通用';
        }else if($data['use_type']==1){
            return '仅可用于指定商品';
        }else if($data['use_type']==2){
            return '仅可用于指定商品分类';
        }
    }

    public function getSellAttr($value,$data)
    {
        return $data['repertory']-$data['residue'];
    }

    public function getValidAttr($value,$data)
    {
        if($data['validity']==0){
            return "永久有效";
        }
        $year=(int) ($data['validity']/368);
        if($year<1){
            $day = $data['validity'];
            return "购买之日起{$day}天有效";
        }
        return "购买之日起{$year}年内有效";
    }

    public function getDiscountDetailAttr($value,$data){

        if($data['give']==0 and $data['sort']==0){
            $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['id'],'is_face'=>1])->find();
            return "购物卡购买即赠送{$discount['give_num']}元";
        }
        if($data['give']==0 and $data['sort']==1){
            $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['id'],'is_face'=>0])->order(['targer_money'=>'ASC'])->find();
            return "购物卡充值{$discount['targer_money']}即赠送{$discount['give_num']}元";
        }
        return '';
    }

    public function getPriceAttr($value,$data)
    {
        $price=[];
        $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['id']])->order(['is_face'=>'DESC'])->select();
        if($data['give']==1){
            foreach($discount as $item){
                $price[]=round(($item['give_num']*$item['targer_money'])/100,2);
            }
        }else{
            foreach($discount as $item){
                $price[]=$item['targer_money'];
            }
        }
        return $price;
    }

    public function getBalanceAttr($value,$data)
    {
        $balance=[];
        $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['id']])->order(['is_face'=>'DESC'])->select();
        if($data['give']==0){
            foreach($discount as $item){
                $balance[]=$item['targer_money']+$item['give_num'];
            }
        }else{
            foreach($discount as $item){
                $balance[]=$item['targer_money'];
            }
        }
        return $balance;
    }

    public function getRemainAttr($value,$data){
        $num=0;
        $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['id'],'is_face'=>1])->find();
        if($data['give']==0){
            $num=$discount['targer_money']+$discount['give_num'];
        }else{
            $num=$discount['targer_money'];
        }
        return ['id'=>$discount['id'],'num'=>$num];
    }

    public function getDiscountAttr($value,$data)
    {
        return Db::name('shopping_card_discount')->where(['cid'=>$data['id']])->order(['is_face'=>'DESC','targer_money'=>"ASC"])->select();
        //return $this->hasMany('ShoppingCardDiscount','cid','id');
    }

    public function getCategoryIdAttr($value,$data){
        $goods=Db::name('shopping_card_goods')->where(['card_id'=>$data['id'],'goods_category_id'=>['<>',0]])->find();
        return $goods['goods_category_id'];
    }

    public function getGoodsIdsAttr($value,$data){
        $goods_id=Db::name('shopping_card_goods')->where(['card_id'=>$data['id'],'goods_category_id'=>0])->column('goods_id');
        return $goods_id;
    }
}