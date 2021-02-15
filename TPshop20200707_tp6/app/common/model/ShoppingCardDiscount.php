<?php


namespace app\common\model;
use think\facade\Db;

use think\Model;
class ShoppingCardDiscount extends Model
{
    public function ShoppingCard(){
        return $this->hasOne('ShoppingCard','id','cid');
    }
}