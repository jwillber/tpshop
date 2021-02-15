<?php
namespace app\admin\validate;
use think\facade\Db;
use think\Validate;
class Spec extends Validate
{       
    // 验证规则
    protected $rule = [
        'name' =>'require',
        'type_id' =>'require',
        'items' =>'require',
        'order' =>'number',
     ];

    protected $scene = [
        'edit'  =>  ['name','type_id','order'],
    ];
      
}