<?php


namespace app\common\logic;

use think\facade\Db;
use think\Page;
use think\Loader;
use app\common\model\ShoppingCard;
use app\common\model\ShoppingCardDiscount;
use app\common\model\ShoppingCardGoods;
use app\common\model\ShoppingCardList;
use app\common\validate\ShoppingCard as ShoppingCardValidate;
class ShoppingCardLogic
{
    private $sort = array('0'=>'购物卡','1'=>'购物余额卡');
    private $give_sort = array('0'=>'赠送金额','1'=>'折扣','2'=>'原价购买');
    private $data=[];
    /*
     * 获取购物卡
     * @param $data
     */
    public function getCard($data,$num=10){
        $model = new ShoppingCard();
        $condition='';
        $where = array();
        if($data['id']){
            $where['id']=$data['id'];
        }
        if($data['hot']){
            $where['hot']=$data['hot'];
        }
        if($data['recommend']){
            $where['recommend']=$data['recommend'];
        }
        if($data['status']){
            $where['status']=$data['status'];
        }
        if($data['on_sell']){
            $condition.='(repertory=0 OR residue > 0)';
        }
        if($data['sort'] or $data['sort']===0){
            $where['sort']=$data['sort'];
        }
        if($data['hotOrrecommend']){
            if($condition){
                $condition.='and (hot=1 OR recommend=1)';
            }else{
                $condition.='(hot=1 OR recommend=1)';
            }

        }

        $count=$model->where($where)->where($condition)->count();
        $page = new Page($count,$num);
        $card=$model->where($where)->where($condition)->limit($page->firstRow,$page->listRows)->order('add_time','desc')->select();

        foreach($card as $k=>$item)
        {
            if($item['repertory']==0){
                $item['sell_num']=$item['residue'];
            }else{
                $item['sell_num']=$item['repertory']-$item['residue'];
            }
            $card[$k]=$item;
        }

        return ['card'=>$card,'page'=>$page];
    }
    /*
     * 获得轮播图
     */
    public function getAd(){
        $now=time();
        $ad=Db::name('ad')->where(['pid'=>540,'start_time'=>['<',$now],'end_time'=>['>',$now]])->select();
        return $ad;
    }
    /*
     * 添加修改购物卡
     * @param $data
     */
    public function addEditCard($data){
        $validate = new ShoppingCardValidate();
        //$data['validity'] = strtotime($data['validity']);

//        if($data['use_type']==2 and empty($data['cat_id3'])){
//            $data['use_type']=0;
//        }
        if(in_array($data['give'],[0,1])){
//            $data['targer_money']=[$data['face_value']];
//            $data['give_num']=[$data['give_num']];
            $data['recharge']=$data['face_value']."_".$data['recharge'];
            $data['targer_money']=explode('_',$data['recharge']);
            if($data['sort']==0){
                $data['giving']=($data['give_num']?$data['give_num']:0)."_".$data['giving'];
                $data['give_num']=explode('_',$data['giving']);
            }else{
                if(!$data['giving']){
                    return ['status'=>0,'msg'=>'请输入充值金额'];
                }
                if($data['give']==0){
                    $data['giving']="0_".$data['giving'];
                    $data['give_num']=explode('_',$data['giving']);
                }else if($data['give']==1){
                    $data['giving']="100_".$data['giving'];
                    $data['give_num']=explode('_',$data['giving']);
                }
            }
//            $data['give_num'][]=$data['give_num']?$data['give_num']:0;
//            $data['targer_money']=explode('_',$data['face_value']);
//            $data['give_num']=explode('_',$data['give_num']);
            //$data['targer_money']=array_filter($data['targer_money']);
            //$data['give_num']=array_filter($data['give_num']);
            foreach($data['targer_money'] as $k=>$item){
                if($item===''){
                    unset($data['targer_money'][$k]);
                }
            }
            foreach($data['give_num'] as $k=>$item){
                if($item===''){
                    unset($data['give_num'][$k]);
                }
            }
        }else{
            if(empty($data['targer_money'])){
                return ['status'=>0,'msg'=>'价格填写错误'];
            }
            if($data['targer_money']!=$data['face_value']){
                return ['status'=>0,'msg'=>'购买价格要等于面额'];
            }
            $data['targer_money']=[$data['targer_money']];
            $data['give_num']=[0];
//            $data['targer_money']=explode('_',$data['face_value']);
//            $data['give_num']=explode('_',$data['give_num']);
        }

        if($data['id']){
            DB::startTrans();
            if(!$validate->scene('edit')->check($data)){
                $error = $validate->getError();
                return ['status' => 0, 'msg' => $error, 'result' => ''];
            }
            $model = new ShoppingCard();
            if($data['give']==1){
                foreach($data['give_num'] as $item){
                    if($item<=0 or $item>100){
                        return ['status'=>0,'msg'=>'折扣必须在0-100之间'];
                    }
                }
            }

            if($data['give']==='0'){
                foreach($data['give_num'] as $k=> $item){
                    if($data['sort']==1 and $k==0){
                        continue;
                    }
                    if($item<=0){
                        return ['status'=>0,'msg'=>'赠送金额要大于0'];
                    }
                }
            }


            $cost=0;
            foreach($data['targer_money'] as $v){
                if($v){
                    $cost=1;
                }
            }
            if(!$cost){
                $model->rollback();
                return ['status'=>0,'msg'=>'价格填写错误'];
            }
            $row=$model->where(['id'=>$data['id']])->update($data);


            Db::name('shopping_card_discount')->where(['cid'=>$data['id']])->delete();
            $discount = new ShoppingCardDiscount();
            $discounts=array();
            foreach($data['targer_money'] as $k=>$v){
                if($v){
                    $item=array();
                    $item['cid']=$data['id'];
                    $item['targer_money']=$v;
                    $item['give_num']=$data['give_num'][$k];
                    $item['is_face']=$k==0?1:0;
                    $discounts[]=$item;
                }
            }

            $good = new ShoppingCardGoods();
            $goods=array();
            if($data['goods_id']){
                foreach($data['goods_id'] as $item){
                    $goods[]=['card_id'=>$data['id'],'goods_id'=>$item];
                }
            }

            if($data['cat_id3']){
                $goods[]=['card_id'=>$data['id'],'goods_category_id'=>$data['cat_id3']];
            }else if($data['cat_id2']){
                $goods[]=['card_id'=>$data['id'],'goods_category_id'=>$data['cat_id2']];
            }else if($data['cat_id1']){
                $goods[]=['card_id'=>$data['id'],'goods_category_id'=>$data['cat_id1']];
            }



            if($goods){
                $row=$good->where(['card_id'=>$data['id']])->delete() and $row?1:0;
                $row=$good->saveAll($goods) and $row?1:0;
            }

            $row=$discount->saveAll($discounts) and $row?1:0;
            if($row){
                Db::commit();
                return ['status'=>1,'msg'=>'修改成功'];
            }else{
                Db::rollback();
                return ['status'=>0,'msg'=>'修改失败'];
            }
        }else{
            if(!$validate->scene('add')->check($data)){
                $error = $validate->getError();
                return ['status' => 0, 'msg' => $error, 'result' => ''];
            }
            $model = new ShoppingCard();
            $model->startTrans();
            if($data['repertory']>0){
                $data['residue']=$data['repertory'];
            }
            $data['status']=2;
            $data['add_time']=time();
            if($data['give']==1){
                foreach($data['give_num'] as $item){
                    if($item<=0 or $item>100){
                        return ['status'=>0,'msg'=>'折扣必须在0-100之间'];
                    }
                }
            }
            if($data['give']==='0'){
                foreach($data['give_num'] as $k=>$item){
                    if($data['sort']==1 and $k==0){
                        continue;
                    }
                    if($item<=0){
                        return ['status'=>0,'msg'=>'赠送金额要大于0'];
                    }
                }
            }

            $cost=0;
            foreach($data['targer_money'] as $v){
                if($v){
                    $cost=1;
                }
            }
            if(!$cost){
                $model->rollback();
                return ['status'=>0,'msg'=>'价格填写错误'];
            }
            $id = $model->insertGetId($data);

            $discount = new ShoppingCardDiscount();
            $discounts=array();
            foreach($data['targer_money'] as $k=>$v){
                if($v){
                    $item=array();
                    $item['cid']=$id;
                    $item['targer_money']=$v;
                    $item['give_num']=$data['give_num'][$k];
                    $item['is_face']=$k==0?1:0;
                    $discounts[]=$item;
                }
            }


            $row=$discount->saveAll($discounts);
            $row=$row and $id?$row:0;

            $good = new ShoppingCardGoods();
            $goods=array();
            if($data['goods_id']){
                foreach($data['goods_id'] as $item){
                    $goods[]=['card_id'=>$id,'goods_id'=>$item];
                }
            }

            if($data['cat_id3']){
                $goods[]=['card_id'=>$id,'goods_category_id'=>$data['cat_id3']];
            }else if($data['cat_id2']){
                $goods[]=['card_id'=>$id,'goods_category_id'=>$data['cat_id2']];
            }else if($data['cat_id1']){
                $goods[]=['card_id'=>$id,'goods_category_id'=>$data['cat_id1']];
            }

            if($goods){
                $row=$good->saveAll($goods) and $row?1:0;
            }

            if($row){
                $model->commit();
                return ['status'=>1,'msg'=>'添加成功'];
            }else{
                $model->rollback();
                return ['status'=>0,'msg'=>'添加失败'];
            }
        }
    }

    public function getOneList($param){
        $where['l.id']=$param['id'];
        $model = new ShoppingCardList();
        $list = $model
            ->alias('l')
            ->join('shopping_card c','c.id = l.cid')
            ->field('l.*,name,c.back_img,c.sort')
            ->where($where)
            ->order(['activate'=>'DESC','balance'=>'DESC'])
            ->find();
        return $list;
    }
    /*
     * 获得用户购物卡列表
     * $param['id'] 购物卡id
     * $param['user_id'] 用户id
     * $param['sort'] 购物卡类型
     */
    public function getCardList($param){
        $model = new ShoppingCardList();
        $condition='';
        $now = time();
        $where=array();
        if($param['id']){
            $where['l.id']=$param['id'];
        }
        if($param['card_id']){
            $where['c.id']=$param['card_id'];
        }
        if($param['user_id']){
            $where['l.uid']=$param['user_id'];
        }
        if(!($param['sort']===null)){
            $where['c.sort']=$param['sort'];
        }
        if(($param['password'])){
            $where['password']=['<>','null'];
        }
        if($param['valid']){
            $condition="(l.add_time+c.validity*86400 > $now OR c.validity = 0)";
        }

        $count = $model
            ->alias('l')
            ->join('shopping_card c','c.id = l.cid')
            ->where($where)
            ->where($condition)
            ->count();
        $page=new Page($count,12);
        $list = $model
            ->alias('l')
            ->join('shopping_card c','c.id = l.cid')
            ->field('l.*,name,c.back_img,c.sort,c.use_type as use_sort')
            ->where($where)
            ->where($condition)
            ->limit($page->firstRow,$page->listRows)
            ->order(['l.status'=>'ASC','activate'=>'ASC','balance'=>'DESC'])
            ->select();
//        echo Db::name('users')->getLastSql();die;
        return ['list'=>$list,'page'=>$page,'count'=>$count];
    }
    /*
     * 获取购物卡类型
     */
    public function getSort(){
        return $this->sort;
    }

    /*
     * 获得购物卡赠送类型
     */
    public function getGiveSort(){
        return $this->give_sort;
    }
    /*
     * 获得赠送记录
     */
    public function getDonateLog($param)
    {
        $log=Db::name('shopping_card_present_log')->where(['card_list_id'=>$param['cid']])->order('donate_time','DESC')->find();
        return $log;
    }
    /**
     * 购物卡下单
     * $param   id int 购物卡的Id
     * $param   money ['demo']   支持的金额
     * $param    ['demo']   支持的金额
     * $param   num ['int']   支持的数量
     *
     * @param unknown $param  */
    static function addOrder($param)
    {
        if(empty($param['id']))return ['status'=>0,'msg'=>'缺少参数'];

        $discountLogic = new ShoppingCardDiscount();
        $cardInfo = $discountLogic->where(['id'=>$param['id']])->find();
        if(!$cardInfo)  return ['status'=>0,'msg'=>'暂无数据'];
        $data = [];
        $data['order_sn']       = get_order_sn();

        $data['user_id']        = $param['user_id'];

        $data['remark']         = empty($param['remark'])?'':$param['remark'];
        $data['order_status']   = 0;
        $data['pay_status']     = 0;
        $data['address_id']     = empty($param['address_id'])?0:$param['address_id'];

        $data['order_amount']   = $cardInfo['targer_money']*$param['num'];
        $data['order_prom_amount']=0;
        if($cardInfo['shopping_card']['give']==1){
            $data['order_amount']   = round(($cardInfo['give_num']*$cardInfo['targer_money']*$param['num'])/100,2);
            $data['order_prom_amount']=$data['order_amount']-($cardInfo['targer_money']*$param['num']);
        }
        $data['price'] = $cardInfo['targer_money'];
        $data['add_time']    = time();
        $data['shipping_price'] = 0;
        $data['prom_type']      = 10;
        $data['num']            = empty($param['num'])?1:$param['num'];


        $data['total_amount'] = $data['order_amount'];
        $data['goods_price']=$data['total_amount'];
        $data['user_note']=$param['user_note'];
        if($param['act'] == 'submit_order')
        {//提交订单
            $cart_validate = validate(\app\common\validate\Cart::class);

            $cart_validate->scene('is_virtual');
            if($cardInfo['shopping_card']['repertory']!=0){
                if($cardInfo['shopping_card']['residue']-$data['num']<0){
                    return ['status'=>0,'msg'=>'库存不足'];
                }
            }

            if (!$cart_validate->check($data)) {
                $error = $cart_validate->getError();
                return (['code' => 0, 'msg' => array_values($error)[0], 'data' => []]);
            }
            $result =  Db::name('order')->insertGetId($data);
            if(!$result){
                return ['status'=>'0','msg'=>'下单失败！'];
            }

            logOrder($result, '订单下单成功', '创建成功', $param['user_id'], 2);


            $card_data['shopping_card_id']        =  $cardInfo['cid'];
            $card_data['shopping_card_discount_id'] = $cardInfo['id'];
            $card_data['num']            = empty($param['num'])?0:$param['num'];
            $card_data['order_id']=$result;
            Db::name('order_shopping_card')->save($card_data);
            return  ['status'=>1,'order_id'=>$result,'master_order_sn'=>$data['master_order_sn'],'order_sn'=>$data['order_sn']];
        }
        return  ['code'=>200,'data'=>$data];
    }
    /*
     * 为用户添加购物卡
     * $order 订单
     *
     */
    public function addShoppingCardList($order)
    {
        Db::name('order')->where(['order_id'=>$order['order_id']])->update(['order_status'=>4]);

        if($order){
            if($order['prom_type']==10){
                $DiscountModel=new ShoppingCardDiscount();
                $order_shopping_card = Db::name('order_shopping_card')->where(['order_id'=>$order['order_id']])->find();
                //create_list($discount_id,$num);
                $discount=$DiscountModel->where(['id'=>$order_shopping_card['shopping_card_discount_id']])->find();

                if($discount){
                    $blance=$discount['targer_money'];
                    if($discount['shopping_card']['give']==0){
                        $blance+=$discount['give_num'];
                    }
                    $data=array();
                    $sn_item=get_card_sn([],$order_shopping_card['num']);
                    for($i=0;$i<$order_shopping_card['num'];$i++){
                        $item=array();
                        $item['cid']=$order_shopping_card['shopping_card_id'];
                        $item['uid']=$order['user_id'];
                        $item['balance']=$blance;
                        $item['activate']=0;
                        $item['add_time']=time();
                        $item['sn']=$sn_item[$i];
                        $item['order_id']=$order['order_id'];
                        $data[]=$item;
//                        $sn_item[]=$sn;
                    }

                    $list_model= new ShoppingCardList();
                    $list_model->startTrans();
                    $res=$list_model->insertAll($data);
                    if($res){
                        if($discount['shopping_card']['repertory']>0){
                            if($discount['shopping_card']['residue']-$order_shopping_card['num']<0){
                                $list_model->rollback();
                                return ['status'=>0,'msg'=>'库存不足'];
                            }
                            $result=Db::name('shopping_card')->where(['id'=>$discount['cid']])->dec('residue',$order_shopping_card['num'])->update();
                            if($result){
                                $list_model->commit();
                                return ['status'=>1,'msg'=>'添加成功'];
                            }else{
                                $list_model->rollback();
                                return ['status'=>0,'msg'=>'添加失败'];
                            }
                        }else{//无限制执行
                            $result=Db::name('shopping_card')->where(['id'=>$discount['cid']])->inc('residue',$order_shopping_card['num'])->update();
                        }
                    }else{
                        $list_model->rollback();
                        return ['status'=>0,'msg'=>'添加失败'];
                    }

                }else{
                    return ['status'=>0,'msg'=>'购物卡不存在'];
                }
            }else{
                return ['status'=>0,'msg'=>'订单不是购物卡订单'];
            }
        }else{
            return ['status'=>0,'msg'=>'订单不存在'];
        }
    }

    /*
     * 获取购物卡
     * $param['id'] 用户购物卡id
     */
    public function getCardDiscount($param){
        $model=new ShoppingCardDiscount();
        $where=array();
        if($param['id']){
            $where['id']=$param['id'];
        }
        $discount=$model->where($where)->select();
        if(!$discount->isEmpty()){
            $discount=$discount
                ->append(["id","cid","targer_money","give_num",'shopping_card'])
                ->visible(['shopping_card'])->toArray();
        }
        return $discount;
    }
    /*
     * 余额卡充值
     */
    public function recharge($recharge){
        $distribut=Db::name('shopping_card_discount')->where(['id'=>$recharge['shopping_card_discount_id']])->find();
        $card=Db::name('shopping_card')->where(['id'=>$distribut['cid']])->find();
        if($distribut){
            if($card['give']==0){
                Db::name('shopping_card_list')->where(['id'=>$recharge['card_list_id']])->inc('balance',($distribut['targer_money']+$distribut['give_num']));
            }else if($card['give']==1){
                Db::name('shopping_card_list')->where(['id'=>$recharge['card_list_id']])->inc('balance',$distribut['targer_money'])->update();
            }
        }else{
            Db::name('shopping_card_list')->where(['id'=>$recharge['card_list_id']])->inc('balance',$recharge['account'])->update();
        }
    }
    /*
     * 激活购物卡
     * $param['user_id'] 用户ID
     * $param['id'] 购物卡ID
     */
    public function activateCard($param){
        if($param['id']){
            $card = Db::name('shopping_card_list')->where(['id'=>$param['id'],'status'=>0])->find();
            if(empty($card)){
                return ['status'=>0,'msg'=>'这张购物卡不存在'];
            }
            if($card['uid']==$param['user_id']){
                if($card['activate']==0){
                    $res=$card = Db::name('shopping_card_list')->where(['id'=>$param['id']])->update(['activate'=>1]);
                    if($res){
                        return ['status'=>1,'msg'=>'您的卡片:'.$card['sn'].'已经激活成功'];
                    }else{
                        return ['status'=>0,'msg'=>'激活失败'];
                    }
                }else{
                    return ['status'=>0,'msg'=>'该卡已激活不能重复激活'];
                }
            }else{
                return ['status'=>0,'msg'=>'你不是这张购物卡的主人，不能激活他'];
            }
        }else{
            return ['status'=>0,'msg'=>'参数错误'];
        }
    }

    /*
     * 赠送购物卡
     * $param['cid'] 用户购物卡id
     * $param['get_id'] 领取者id
     * $param['user_id'] 用户id
     */
    public function ObtainCard($param)
    {
        $card = Db::name('shopping_card_list')->where(['sn'=>$param['sn'],'status'=>0])->find();
        if($card){
            $log=Db::name('shopping_card_present_log')->where(['card_list_id'=>$card['id']])->order('donate_time','desc')->find();
            if(empty($log['get_id'])){
                if($card['activate']==0){
                    if($card and $card['card_password']==$param['card_password']){
                        Db::name('shopping_card_present_log')
                            ->where(['id'=>$log['id']])
                            ->update(['get_id'=>$param['user_id'],'add_time'=>time()]);
                        $res=Db::name('shopping_card_list')->where(['id'=>$log['card_list_id']])->update(['uid'=>$param['user_id'],'activate'=>1]);
                        if($res){
                            return ['status'=>1,'msg'=>'领取成功'];
                        }else{
                            return ['status'=>0,'msg'=>'领取失败'];
                        }
                    }else{
                        return ['status'=>0,'msg'=>'卡密错误,无法领取'];
                    }
                }
                else{
                    return ['status'=>0,'msg'=>'该购物卡已激活不能领取'];
                }
            }else{
                return ['status'=>0,'msg'=>'无法领取'];
            }
        }else{
            return ['status'=>0,'msg'=>'购物卡不存在'];
        }
//        $card=Db::name('shopping_card_list')->where(['id'=>$param['cid']])->find();
//        if($card){
//            //$log=Db::name('shopping_card_present_log')->where(['card_list_id'=>$param['cid']])->order('add_time','desc')->find();
//            $log=Db::name('shopping_card_present_log')->where(['card_list_id'=>$param['cid']])->order('donate_time','desc')->find();
//
//            if(empty($log['get_id'])){
//                if($card['activate']==0){
//                        $card=Db::name('shopping_card_list')->where(['id'=>$param['cid'],'sn'=>$param['sn']])->find();
//                        if($card and $card['card_password']==md5($param['card_password'])){
//                            Db::name('shopping_card_present_log')
//                                ->where(['id'=>$log['id']])
//                                ->update(['get_id'=>$param['user_id'],'add_time'=>time()]);
//                            $res=Db::name('shopping_card_list')->where(['id'=>$log['id']])->update(['uid'=>$param['user_id']]);
//                            if($res or $card['user_id']==$param['user_id']){
//                                return ['status'=>1,'msg'=>'领取成功'];
//                            }else{
//                                return ['status'=>0,'msg'=>'领取失败'];
//                            }
//                        }else{
//                            return ['status'=>0,'msg'=>'卡密错误,无法领取'];
//                        }
//                    }
//                else{
//                    return ['status'=>0,'msg'=>'该购物卡已激活不能领取'];
//                }
//            }else{
//                return ['status'=>0,'msg'=>'无法领取'];
//            }
//        }else{
//            return ['status'=>0,'msg'=>'购物卡不存在'];
//        }
    }

    /*
     * 获得分享图片
     */
    public function getDonatePhoto($param){
        $card = Db::name('shopping_card_list')->where(['id'=>$param['id'],'uid'=>$param['user_id'],'status'=>0])->find();
        if($card){
            $data['card_list_id']=$card['id'];
            $data['give_id']=$param['user_id'];
            $data['remark']=$param['remark']?$param['remark']:'';
            $data['donate_name']=$param['name'];
            $data['donate_time']=time();
            Db::name('shopping_card_list')->where(['id'=>$param['id']])->update(['card_password'=>$this->getPassword()]);
            $id=Db::name('shopping_card_present_log')->insertGetId($data);
            if($id){
                if($param['type']=='is_mobile' or $param['type']=='is_mini'){
                    $this->exportMobilePoster($param);
                }else{
                    $this->exportPcPoster($param);
                }
            }else{
                return ['status'=>0,'生成图片失败'];
            }
        }else{
            return ['status'=>0,'生成图片失败'];
        }
    }
    /*
     * 生成手机端分享图片
     */
    public function exportMobilePoster($data)
    {
        $back_width=650;
        $back_height=720;
        ob_end_clean();
        header("content-type: image/png");
        $back_path= ROOT_PATH.'/public/public/images/shoppingcard/back.png';//背景图片
        $img_type = getimagesize($back_path);
        $img_func = 'imagecreatefrom'.image_type_to_extension($img_type[2],false);
        $back = $img_func($back_path);
        $font = ROOT_PATH.'/public/public/static/font/ztc.ttf';

        $back_img = imagecreatetruecolor($back_width,$back_height);  //创建底图
        $white = imagecolorallocate($back_img , 255,255,255);//白色
        $gray = imagecolorallocate($back_img , 102,102,102);//灰色
        $black = imagecolorallocate($back_img , 51,51,51);//黑色
        imagefill($back_img , 0, 0, $white);
        imagecopyresampled($back_img,$back,0,0,0,0,$back_width,$back_height,imagesx($back),imagesy($back)); //背景
        for($i=0;$i<6;$i++){
            $name=$data['name']?$data['name']:$data['user']['nickname'];
            imagettftext($back_img,22,0,41,546,$black,$font,"{$name}赠予您一张购物卡");
            imagettftext($back_img,22,0,41,602,$black,$font,"赠语:");
        }


        imagettftext($back_img,18,0,483,682,$black,$font,"扫码领取");
        for($i=0;$i<2;$i++){
            imagettftext($back_img,20,0,41,640+(35*$i),$gray,$font,mb_substr($data['remark'],$i*12,12));
        }
        if($data['type']=='is_mini'){
            $wxacode = imagecreatefromstring($this->getGoodsWxacode($data));
        }else{
            $wxacode = imagecreatefromstring($this->getMobileUrlcode($data));
        }

        imagecopyresampled($back_img,$wxacode,440,478,0,0,180,180,imagesx($wxacode),imagesy($wxacode));

        imagepng($back_img);
        imagedestroy($back_img);
        imagedestroy($wxacode);
        exit();
    }

    /*
     * 生成PC端分享图片
     */
    public function exportPcPoster($data)
    {
        $back_width=580;
        $back_height=400;
        ob_end_clean();
        header("content-type: image/png");
        $back_path= ROOT_PATH.'/public/public/images/shoppingcard/pc_back.jpg';//背景图片
        $img_type = getimagesize($back_path);
        $img_func = 'imagecreatefrom'.image_type_to_extension($img_type[2],false);
        $back = $img_func($back_path);
        $font = ROOT_PATH.'/public/public/static/font/ztc.ttf';

        $back_img = imagecreatetruecolor($back_width,$back_height);  //创建底图
        $white = imagecolorallocate($back_img , 255,255,255);//白色
        $gray = imagecolorallocate($back_img , 153,153,153);//灰色
        $black = imagecolorallocate($back_img , 51,51,51);//黑色
        imagefill($back_img , 0, 0, $white);
        imagecopyresampled($back_img,$back,0,0,0,0,$back_width,$back_height,imagesx($back),imagesy($back)); //背景

        for($i=0;$i<6;$i++){
            $name=$data['name']?$data['name']:$data['user']['nickname'];
            imagettftext($back_img,'16px',0,65,195,$black,$font,"{$name}赠予您一张购物卡");
            imagettftext($back_img,'16px',0,65,240,$black,$font,"赠语:");
        }

//        for($i=0;$i<2;$i++){
//            for($j=0;$j<3;$j++){
//                imagettftext($back_img,10,0,115,240+(35*$i),$gray,$font,mb_substr($data['remark'],$i*12,12));
//            }
//        }
        $str ='           '.$data['remark'];
        $this->draw_txt_to($back_img,['fontsize'=>'12px','left'=>65,'top'=>240,'width'=>286,'bold'=>3],$str,$gray,$font);

        $wxacode = imagecreatefromstring($this->getMobileUrlcode($data));
        imagecopyresampled($back_img,$wxacode,'383px','166px',0,0,'132px','132px',imagesx($wxacode),imagesy($wxacode));

        imagepng($back_img);
        imagedestroy($back_img);
        exit();


        imagettftext($back_img,18,0,483,682,$black,$font,"扫码领取");
        for($i=0;$i<2;$i++){
            imagettftext($back_img,20,0,41,640+(35*$i),$gray,$font,mb_substr($data['remark'],$i*12,12));
        }
        $wxacode = imagecreatefromstring($this->getMobileUrlcode($data));
        imagecopyresampled($back_img,$wxacode,440,578,0,0,180,180,imagesx($wxacode),imagesy($wxacode));

        imagepng($back_img);
        imagedestroy($back_img);
        imagedestroy($wxacode);
        exit();
    }


    public function draw_txt_to($card,$pos,$string,$color,$font)
    {

        $font_color = $color;
        $font_file = $font;
        $_string='';
        $__string='';

        for($i=0;$i<mb_strlen($string);$i++)
        {
            $box=imagettfbbox($pos['fontsize'],0,$font_file,$_string);
            $_string_length=$box[2]-$box[0];
            $box=imagettfbbox($pos['fontsize'],0,$font_file,mb_substr($string,$i,1));

            if( $_string_length+$box[2]-$box[0]<$pos['width'])
            {
                $_string.=mb_substr($string,$i,1);
            }
            else
            {
                $__string.=$_string."\n";
                $_string=mb_substr($string,$i,1);
            }
        }
        $__string.=$_string;
        $box=imagettfbbox($pos['fontsize'],0,$font_file,mb_substr($__string,0,1));
        for($i=0;$i<$pos['bold'];$i++){
            imagettftext($card, $pos['fontsize'], 0, $pos['left'], $pos['top']+($box[3]-$box[7]), $font_color, $font_file, $__string);
        }

    }


    /*555
     * 获取名片分享小程序码
     */
    private function getGoodsWxacode($data){
        if(isset($data['path'])){
            $path=$data['path'];
        }else {
            $path='pages/index/index/index';//微信二维码跳转链接
        }
        $card=Db::name('shopping_card_list')->where(['id'=>$data['id']])->find();
        $post_data = json_encode(['page' => $path,'scene' =>'cid='.$data['id'].'&sn='.$card['sn'].'&card_password='.$card['card_password']]);
        $minapp = new \app\common\logic\wechat\MiniAppUtil();
        $assecc_token = $minapp->getMinAppAccessToken();
        if($assecc_token == false){
            ajaxReturn(['status'=>0,'msg'=>$minapp->getError()]);
        }
        $result = $minapp->getWXACodeUnlimit($assecc_token,$post_data);
        if($result == false){
            ajaxReturn(['status'=>0,'msg'=>$minapp->getError()]);
        }
        return $result;
    }

    /**
     * 获取分享二维码
     */
    private function getMobileUrlcode($data){
        include_once '../vendor/phpqrcode/phpqrcode.php';
        $card=Db::name('shopping_card_list')->where(['id'=>$data['id']])->find();
        $url = SITE_URL . "/mobile/ShoppingCard/confirm_giving/cid/{$data['id']}/sn/{$card['sn']}/card_password/".$card['card_password'];
        $qr_code_path = UPLOAD_PATH.'qr_code/';
        if (!file_exists($qr_code_path)) {
            mkdir($qr_code_path,777,true);
        }
        /* 生成二维码 */
        $qr_code_file = $qr_code_path.time().rand(1, 10000).'.png';
        $size = floor(250/37*100)/100 + 0.01;
        \QRcode::png($url, $qr_code_file, QR_ECLEVEL_M,$size,2);
        $str = file_get_contents($qr_code_file);
        unlink($qr_code_file);
        return $str;
    }
    /*
     * 购物卡获取
     * $param['cid'] 用户购物卡id
     */
//    public function DonateCard($param)
//    {
//        $log = Db::name('shopping_card_present_log')->where(['card_list_id'=>$param['cid']])->order('add_time','desc')->find();
//        if($log or $log['get_id']==$param['user_id']){
//            $res=Db::name('shopping_card_discount')->update(['uid'=>$param['user_id']]);
//            if($res){;
//                return ['status'=>0,'msg'=>'领取成功'];
//            }else{
//                return ['status'=>0,'msg'=>'领取失败'];
//            }
//        }else{
//            return ['status'=>0,'msg'=>'不能领取'];
//        }
//    }

    /*
     * 购物卡购买记录
     */
    public function getOrder($param)
    {
        $where=array();
        if($param['user_id']){
            $where['o.user_id']=$param['user_id'];
        }
        $count=Db::name('order')
            ->alias('o')
            ->join('shopping_card_list l','l.order_id=o.order_id','LEFT')
            ->where($where)
            ->count();
        $page = new Page($count,10);
        $order=Db::name('order')
            ->alias('o')
            ->join('shopping_card_list l','l.order_id=o.order_id','LEFT')
            ->where($where)
            ->limit($page->firstRow,$page->listRows)
            ->select();

        return ['order'=>$order,'page'=>$page];
    }
    /*
     * 获得我的购物卡
     * $param['user_id'] 用户id
     * $param['usable'] 用户卡是否可用
     */
    public function getMyCard($param)
    {
        $now = time();
        $where=array();
        $model = new ShoppingCardList();

        $cat_info = Db::name('goods_category')
            ->where(['id'=>$param['goods_category_id']])
            ->find();
        $cat_path = explode('_', $cat_info['parent_id_path']);
        unset($cat_path[0]);
        if($param['usable']){
            $shopping_card_goods=Db::name('shopping_card_goods')
                ->where(['goods_id'=>$param['goods_id']])
                ->whereOr(['goods_category_id'=>['in',$cat_path]])
                ->select();

            $ids='-1';
            foreach($shopping_card_goods as $good){
                $ids.=','.$good['card_id'];
            }

            if($param['usable']==1){//可用
                $condition = "(l.add_time+c.validity*86400 > $now OR c.validity = 0) AND (c.use_type=0 OR c.id IN ($ids)) AND l.balance >0";
                $where['l.activate']=1;
                $where['l.status']=0;
            }else if($param['usable']==2){//不可用
                $condition = "(l.add_time+c.validity*86400 > $now OR c.validity = 0) AND ((c.use_type <> 0 AND c.id NOT IN ($ids)) OR l.balance <=0) OR l.activate=0 OR l.status=1";
            }
        }
        $where['l.uid']=$param['user_id'];

        $count=$model
            ->alias('l')
            ->join('shopping_card c','l.cid=c.id ')
            ->where($where)
            ->where($condition)
            ->count();
        $page = new Page($count,10);
        $card=$model
            ->alias('l')
            ->field('l.*')
            ->join('shopping_card c','l.cid=c.id ')
            ->where($where)
            ->where($condition)
//            ->limit($page->firstRow,$page->listRows)
            ->select();
        return ['card'=>$card,'page'=>$page];
    }

    /*
     * 获得我的赠送记录
     * $param['user_id']
     */
    public function getMyDonateList($param)
    {
        $condition="give_id={$param['user_id']} AND get_id IS NOT NULL";
        $count = Db::name('shopping_card_present_log')->where($condition)->count();
        $page = new Page($count,10);
        $logs = Db::name('shopping_card_present_log')
            ->alias('l')
            ->join('shopping_card_list li','l.card_list_id=li.id','left')
            ->join('shopping_card c','li.cid=c.id','left')
            ->join('users u','l.get_id=u.user_id')
            ->field("l.*,c.name,li.sn,u.nickname")
            ->where($condition)
            ->limit($page->firstRow,$page->listRows)
            ->order(['donate_time'=>'DESC'])
            ->select();

        foreach($logs as $k=>$v){
            $logs[$k]['time']=date('Y-m-d H:i:s',$v['donate_time']);
        }
        if($logs){
            return ['status'=>1,'logs'=>$logs];
        }else{
            return ['status'=>0,'logs'=>[]];
        }
    }
    /*
     * 获得我的领取
     */
    public function getMyObtainList($param)
    {
        $condition="get_id={$param['user_id']}";
        $count = Db::name('shopping_card_present_log')->where($condition)->count();
        $page = new Page($count,10);
        $logs = Db::name('shopping_card_present_log')
            ->alias('l')
            ->join('shopping_card_list li','l.card_list_id=li.id','left')
            ->join('shopping_card c','li.cid=c.id','left')
            ->field("l.*,c.name,li.sn, donate_name as nickname")
            ->where($condition)
            ->limit($page->firstRow,$page->listRows)
            ->order(['donate_time'=>'DESC'])
            ->select();
        foreach($logs as $k=>$v){
            $logs[$k]['time']=date('Y-m-d H:i:s',$v['add_time']);
        }
        if($logs){
            return ['status'=>1,'logs'=>$logs];
        }else{
            return ['status'=>0,'logs'=>[]];
        }
    }
    /*
     * 购物卡停售
     */
    public function Putaway($param)
    {
        $card=Db::name('Shopping_card')->where(['id'=>$param['id'],'residue'=>['<>',0]])->find();
        if(empty($card)and $param['status']==1){
            return array('status'=>0,'msg'=>'库存不足不能发售');
        }
        $res=DB::name('Shopping_card')
            ->where(['id'=>$param['id']])
            ->update(['status'=>$param['status']]);
        if($res){
            return array('status'=>1,'msg'=>'修改成功');
        }else{
            return array('status'=>0,'msg'=>'修改失败');
        }
    }

    /*
     * 删除购物卡
     */
    public function delCard($param){
        $card=$this->getCard($param)['card'][0];
        if($card['sort']==1){
            return ['status'=>0,'msg'=>'购物余额卡不能删除'];
        }
        $end_time=($card['validity']*86400);
        $end_time=time()-$end_time;
        $card_list=Db::name('shopping_card_list')->where(['cid'=>$param['id'],'balance'=>['>',0],'add_time'=>['>',$end_time]])->select();
        Db::startTrans();
        $res=Db::name('shopping_card')->where(['id'=>$param['id']])->delete();
        if(!$card_list->isEmpty()){
            return ['status'=>0,'msg'=>'删除失败，有用户持有有效的该购物卡'];
        }
        if($res) {
            Db::name('shopping_card_list')->where(['cid'=>$param['id']])->delete();
            Db::commit();
            return ['status' => 1, 'msg' => '删除成功'];
        }else{
            Db::rollback();
            return ['status'=>0,'msg'=>'删除失败'];
        }
    }
    /*
     * 获得可用的购物卡数量
     */
    public function getUsableNum($param)
    {
        $now = time();
        $where=array();
        $model = new ShoppingCardList();

        $cat_info = Db::name('goods_category')->where(array('id'=>$param['goods_category_id']))->find();
        $cat_path = explode('_', $cat_info['parent_id_path']);

        unset($cat_path[0]);
        $shopping_card_goods=Db::name('shopping_card_goods')
        ->where(['goods_id'=>$param['goods_id']])
        ->whereOr(['goods_category_id'=>['in',$cat_path]])
        ->select();
        $ids='-1';
        $where['activate']=1;
        $where['l.status']=0;
        foreach($shopping_card_goods as $good){
            $ids.=','.$good['card_id'];
        }
        $condition = "(l.add_time+c.validity*86400 > $now OR c.validity = 0) AND (c.use_type=0 OR c.id IN ($ids)) AND l.balance >0";
        $where['l.uid']=$param['user_id'];
        $count=$model
            ->alias('l')
            ->join('shopping_card c','l.cid=c.id ')
            ->where($where)
            ->where($condition)
            ->count();
        return $count;
    }

    public function getBuyLog($param)
    {
        if($param['user_id']){
            $where['o.user_id']=$param['user_id'];
        }


        $model = new ShoppingCardList();
        $count = $model
            ->alias('l')
            ->join('shopping_card c','c.id = l.cid')
            ->join('order o','l.order_id=o.order_id')
            ->field('l.*,c.name')
            ->where($where)->count();

        $page = new Page($count,10);

        $list = $model
            ->alias('l')
            ->join('shopping_card c','c.id = l.cid')
            ->join('order o','l.order_id=o.order_id')
            ->field('l.*,c.name')
            ->where($where)
            ->order(['add_time'=>'DESC'])
            ->limit($page->firstRow,$page->listRows)
            ->select();


        foreach($list as $k=>$v){
            $list[$k]['time']=date('Y-m-d H:i:s',$v['add_time']);
        }

        return ['list'=>$list,'page'=>$page];
    }
    /*
     * 获得购物卡的充值记录
     */
    public function getRechargeLog($param)
    {
        $count=Db::name('recharge')
            ->where(['card_list_id'=>$param['id']])
            ->count();
        $page = new Page($count,10);
        $log=Db::name('recharge')
            ->where(['card_list_id'=>$param['id']])
            ->order('ctime','DESC')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        return ['log'=>$log,'page'=>$page];
    }

    public function ChangeBalance($order){
        $card=Db::name('shopping_card_variation')->alias('v')
            ->join('shopping_card_list l','v.card_list_id=l.id')
            ->where(['v.order_id'=>$order['order_id'],'is_return'=>0])
            ->select();

        $card_ids=Db::name('shopping_card_variation')->where(['order_id'=>$order['order_id'],'is_return'=>0])->column('id');
        $card_data=[];
        foreach($card as $item){
            $sum=$item['balance']+$item['varuatuib_price'];
            $card_data[]=['id'=>$item['card_list_id'],'balance'=>$item['balance']+$item['varuatuib_price']];
        }
        $card_list_model = new ShoppingCardList();
        $res=$card_list_model->saveAll($card_data);

        Db::name('shopping_card_variation')->where(['id'=>['in',$card_ids]])->update(['is_return'=>1]);
    }

    /*
     * 批量生成实体卡卡号卡密
     */
    public function entityCard($param)
    {
        if ($param['id'] && $param['num']) {
            $model = new ShoppingCardDiscount();
            $discount = $model->where(['cid' => $param['id']])->find();
            if($discount['shopping_card']['status']!=2){
                return ['status'=>0,'msg'=>'请在停售状态执行'];
            }
            if ($discount) {
                if($discount['shopping_card']['repertory']>0) {
                    if ($discount['shopping_card']['residue'] - $param['num'] < 0) {
                        return ['status' => 0, 'msg' => '库存不足'];
                    }
                }
                $blance = $discount['targer_money'];
                if ($discount['shopping_card']['give'] == 0) { //贈送增加余额
                    $blance += $discount['give_num'];
                }
                $data = array();
                if($discount['shopping_card']['validity']==0){
                    $validity='永久有效';
                }else{
                    $end_time = time()+(86400*$discount['shopping_card']['validity']);
                    $validity=date('Y-m-d H:i:s',strtotime(date('Y-m-d',$end_time)));
                }
                $sn_item=get_card_sn([],$param['num']);
                for ($i = 0; $i < $param['num']; $i++) {
                    if($param['prefix']){
                        
                        $sn=$param['prefix']." ".$sn_item[$i];
                    }else{
                        $sn=$sn_item[$i];
                    }

                    $password=$this->getPassword();
                    $item = array();
                    $item['cid'] = $discount['shopping_card']['id'];
                    $item['uid'] = 0;
                    $item['balance'] = $blance;
                    $item['activate'] = 0;
                    $item['add_time'] = time();
                    $item['sn'] = $sn;
                    $item['order_id'] = 0;
                    $item['card_password']=$password;
                    $data[] = $item;

                    $sn_item[]=$sn;
                    $this->data[]=['sn'=>$sn,'password'=>$password,'validity'=>$validity];
                }

                $list_model = new ShoppingCardList();
                $list_model->startTrans();
                $res = $list_model->insertAll($data);

                $card_list_ids=Db::name('shopping_card_list')->where(['sn'=>['in',$sn_item]])->select();
                $log_data=[];
                foreach($card_list_ids as $item){
                    $log_data[]=['card_list_id'=>$item['id'],'give_id'=>0,'donate_time'=>time(),'donate_name'=>'','remark'=>''];
                }

                Db::name('shopping_card_present_log')->insertAll($log_data);


                if($res){
                        $result=Db::name('shopping_card')->where(['id'=>$discount['cid']])->dec('residue',$param['num'])->update();
                        if($result){
                            return ['status'=>1,'msg'=>'添加成功'];
                        }else{
                            return ['status'=>0,'msg'=>'添加失败'];
                        }
                }else{
                    return ['status'=>0,'msg'=>'生成失败'];
                }
            } else {
                return ['status' => 0];
            }
        }
    }
    /*
     * 获得卡号和卡密
     */
    public function getData(){
        return $this->data;
    }

    /**
     * 生产8位卡密
     * @return array
     */
    public function getPassword()
    {
        $p_len = input('p_len/d',6);//卡密长度
        if($p_len<6 or $p_len>12){
            $p_len=6;
        }
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $p_len; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

}