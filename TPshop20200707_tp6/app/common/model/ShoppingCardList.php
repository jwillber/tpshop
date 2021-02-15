<?php


namespace app\common\model;
use think\facade\Db;
use think\Model;

class ShoppingCardList extends  Model
{
    public function user()
    {
        return $this->hasOne('users', 'user_id', 'uid');
    }

    public function ShoppingCard()
    {
        return $this->hasOne('shopping_card', 'id', 'cid');
    }

    public function getDiscountAttr($value,$data)
    {
        $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['cid']])->order(['is_face'=>'DESC','targer_money'=>'ASC'])->select();
        $card = Db::name('shopping_card')->where(['id'=>$data['id']])->find();
        $result=[];
        foreach($discount as $k=>$item)
        {
            if($card['give']==0){
                $result[]=['id'=>$item['id'],'balance'=>($item['targer_money']+$item['give_num']),'selling'=>$item['targer_money']];
            }else if($card['give']==1){
                $selling_price=round($item['targer_money']*$item['give_num']/100,2);
                $result[]=['id'=>$item['id'],'balance'=>$item['targer_money'],'selling'=>$selling_price];
            }else{
                $result[]=['id'=>$item['id'],'balance'=>$item['targer_money'],'selling'=>$item['targer_money']];
            }
        }
        return $result;
    }

    public function getDiscountDatilAttr($value,$data){
        $discount=Db::name('shopping_card_discount')->where(['cid'=>$data['cid'],'is_face'=>0])->select();
        $card =Db::name('shopping_card')->where(['id'=>$data['cid']])->find();
        $reduced=[];
        if($card['give']==0){
            foreach($discount as $value){
                $str="充值{$value['targer_money']}送{$value['give_num']}元";
                $item=['id'=>$value['id'],'datil'=>$str];
                $reduced[]=$item;
            }
        }else if($card['give']==1){
            foreach($discount as $value){
                $num=$value['give_num']/10;
                $str="充值{$value['targer_money']}享受{$num}折";
                $item=['id'=>$value['id'],'datil'=>$str];
                $reduced[]=$item;
            }
        }

        foreach($discount as $k=>$item)
        {
            if($card['give']==0){
                $result[]=['id'=>$item['id'],'balance'=>($item['targer_money']+$item['give_num']),'selling'=>$item['targer_money']];
            }else if($card['give']==1){
                $selling_price=round($item['targer_money']*$item['give_num']/100,2);
                $result[]=['id'=>$item['id'],'balance'=>$item['targer_money'],'selling'=>$selling_price];
            }else{
                $result[]=['id'=>$item['id'],'balance'=>$item['targer_money'],'selling'=>$item['targer_money']];
            }
        }

        return ['discount'=>$discount,'reduced'=>$reduced];
    }


    public function getIndateAttr($value,$data)
    {
        $card=Db::name('shopping_card')->where(['id'=>$data['cid']])->find();
        if($card['validity']==0){
            return '永久有效';
        }
        $validity=$data['add_time']+$card['validity']*86400;

        $end_time=date('Y年m月d日',$validity);
        return "有效期至$end_time";
    }

    public function getUseTypeAttr($value,$data)
    {
//        $goods_category_id=Db::name('shopping_card_goods')->where(['card_id'=>$data['cid'],'goods_id'=>0])->value('goods_category_id');
//        $goods_id=Db::name('shopping_card_goods')->where(['card_id'=>$data['cid'],'goods_category_id'=>0])->value('goods_id');
        $card=Db::name('shopping_card')->where(['id'=>$data['cid']])->find();

        $goods=Db::name('shopping_card_goods')->where(['card_id'=>$data['cid'],'goods_category_id'=>['<>',0]])->find();
        $describe="指定商品分类可用";
        if($goods){
            $category=Db::name('goods_category')->where(['id'=>$goods['goods_category_id']])->find();
            if($category){
                $describe="仅可用于{$category['name']}类商品";
            }
        }


        $usable=['0'=>'全店商品可用','1'=>'指定商品可用','2'=>$describe];
        return $usable[$card['use_type']];
    }

    public function getCostAttr($value,$data)
    {
        $model= new ShoppingCard();
        $card=$model->where(['id'=>$data['cid']])->find();
        return $card['price'][0];
    }

    public function getEndTimeAttr($value,$data)
    {
        $card=Db::name('shopping_card')->where(['id'=>$data['cid']])->find();
        if($card['validity']==0){
            return 0;
        }
        $end_time=$data['add_time']+($card['validity']*86400);
        return $end_time;
    }

    public function getIsContinueAttr($value,$data){
        $log=Db::name('shopping_card_present_log')->where(['card_list_id'=>$data['id']])->count();
        return $log;
    }

    public function getCategoryIdAttr($value,$data){
        $goods=Db::name('shopping_card_goods')->where(['card_id'=>$data['cid'],'goods_category_id'=>['<>',0]])->find();
        return $goods['goods_category_id'];
    }
}