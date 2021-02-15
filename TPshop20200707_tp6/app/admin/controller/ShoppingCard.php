<?php


namespace app\admin\controller;
use think\facade\View;


use app\common\logic\ShoppingCardLogic;
use app\common\model\ShoppingCardDiscount;
use think\facade\Db;
class ShoppingCard extends Base
{
    /*
     * 购物卡列表
     */
    public function index(){
        $data=input('');
        $cardlogic=new ShoppingCardLogic();
        $result=$cardlogic->getCard($data);

        View::assign('lists',$result['card']);
        View::assign('page',$result['page']);
        View::assign('sort',$cardlogic->getSort());
        View::assign('give_sort',$cardlogic->getGiveSort());
        return View::fetch();
    }
    /*
     * 添加或修改购物卡
     */
    public function card_info(){
        $data=input('');
        if(IS_POST){
            // 永久有效
            if($data['period'] == 0)
                $data['validity'] = 0;
            $cardlogic=new ShoppingCardLogic();
            $result=$cardlogic->addEditCard($data);
            $this->ajaxReturn($result);
        }else if($data['id']){

            $cardlogic=new ShoppingCardLogic();
            $result=$cardlogic->getCard($data);
            if($result['card'][0]['use_type'] == 1){
                $goods_ids = Db::name('shopping_card_goods')->where(['card_id'=>$data['id']])->column('goods_id');
                $enable_goods = Db::name('goods')->where(["goods_id"=>[ "in", $goods_ids]])->select();
                View::assign('enable_goods',$enable_goods);
            }

            View::assign('card',$result['card'][0]);
        }
        $cat_list = Db::name('goods_category')->where(['parent_id' => 0])->select();//自营店已绑定所有分类
        View::assign('cat_list',$cat_list);
        return View::fetch();
    }

    /*
     * 购物卡列表
     */
    public function card_list(){
        $param = input('');
        $cardlogic = new ShoppingCardLogic();
        $result=$cardlogic->getCardList($param);
        View::assign('lists',$result['list']);
        View::assign('page',$result['page']);
        View::assign('sort',$cardlogic->getSort());
        View::assign('give_sort',$cardlogic->getGiveSort());
        return View::fetch();
    }

    public function ajax_update_status()
    {
        $param=input('');
        $cardlogic = new ShoppingCardLogic();
        $result=$cardlogic->Putaway($param);
        $this->ajaxReturn($result);
    }

    public function del_card()
    {
        $param = input('');
        $cardlogic = new ShoppingCardLogic();
        $result=$cardlogic->delCard($param);
        $this->ajaxReturn($result);
    }

    public function create_card(){
        $param = input('');
        if(input('num')){
            if (!preg_match('/^[_0-9a-z]*$/i',input('prefix'))){
                $this->error('前缀只能输入字母，数字');
            }
            $cardLogic = new ShoppingCardLogic();
            $param['num']=(int)$param['num'];
            $result = $cardLogic->entityCard($param);
            if($result['status']==1){
                $data=$cardLogic->getData();
                $strTable ='<table width="500" border="1">';
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;width:130px;">卡号</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">卡密</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="100">有效期</td>';
                $strTable .= '</tr>';
                foreach($data as $item){
                    $strTable.='<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">'.$item['sn'].'</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$item['password'].' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">'.$item['validity'].' </td>';
                    $strTable.='</tr>';
                }
                $strTable.='</table>';
                unset($data);
                downloadExcel($strTable,'card');
                exit();
            }else{
                $this->error($result['msg']);
            }
        }else{
            return View::fetch();
        }
    }

}