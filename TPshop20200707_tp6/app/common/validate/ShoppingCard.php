<?php


namespace app\common\validate;

use think\Validate;
use think\facade\Db;
class ShoppingCard extends Validate
{
    // 验证规则
    protected $rule = [
        'id'        => 'require',
        'name'      => 'require|max:20|checkName',
        'sort'      => 'require',
        'give'      => 'require',
        'custom'    => 'checkCustom',
        'face_value'=> 'number|checkFaceValue',
        'validity'  => 'checkValidity|number|egt:0',
        'residue'   => 'checkResidue',
        'targer_money'=>'checkTargerMoney',
        'give_num[]'  => 'gt:0|checkGiveNum',
        'repertory' => 'require|gt:0'
    ];
    protected $scene = [
        'edit' => ['id','name','sort','give','custom','face_value','validity','residue','give_num','repertory'],
        'add'  => ['name','sort','give','custom','face_value','validity','residue','give_num','repertory'],
    ];
    //错误信息
    protected $message = [
        'id.require'        => '参数错误',
        'name.require'      => '购物卡名称不能为空',
        'name.max'          => '名称过长',
        'sort.require'      => '购物卡类型不能为空',
        'give.require'       => '购物卡赠送类型不能为空',
        'face_value.number'  => '面值只能为数字',
        'targer_money'=>'购买价格不能为空',
        'give_num.gt'       => '金额必须大于0',
        'validity.number'   => '有效期请填入数字',
        'validity.egt'       => '有效期必须大于0',
        'repertory.gt'      => '库存必须大于0',
        'repertory.require' => '请输入库存',
    ];

    protected function checkCustom($value, $rule, $data){
        if($data['sort']==0 and $value==1){
            return '购物卡不能选择自定义储值';
        }
        return true;
    }

    protected function checkValidity($value, $rule, $data){
        $num=floor($value);
        if($num!=$value){
            return '有效期请输入整数';
        }
        if($data['sort']==1 and $value>0){
            return '购物余额卡不能选择有效期';
        }
        if($data['sort']==0 and $value<0){
            return '有效期填写错误';
        }

        return true;
    }

    protected function checkResidue($value, $rule, $data){
        if($value>0){
            return '请填写库存';
        }
        return true;
    }

    protected function checkTargerMoney($value, $rule, $data){
        if($value){
            return '购买价格不能为空';
        }
        foreach($value as $item){
            if($item<=0){
                return '购买价格填写错误';
            }
        }
        return true;
    }

    protected function checkGiveNum($value, $rule, $data){
        if($data['give']==1){
            foreach($value as $item){
                if($item<0 or $item>100){
                    return '折扣必须在0-100之间';
                }
            }
        }
        return true;
    }
    protected function checkName($value,$rule,$data)
    {
        if($data['id']){
            $card=Db::name('shopping_card')->where([['id','<>',$data['id']],['name','=',$value]])->find();
        }else{
            $card=Db::name('shopping_card')->where(['name'=>$value])->find();
        }
        if($card){
            return '购物卡名字不能重复';
        }
        return true;
    }

    protected function checkFaceValue($value,$rule,$data)
    {
        if($data['give']==2){
            if($data['face_value']>$data['targer_money'])
            {
                return '购物原价不能小于面额';
            }
        }
        return true;
    }
}