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
 * Author: 当燃      
 * Date: 2015-09-21
 */ 

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\Page;
use app\admin\logic\GoodsLogic;
use app\common\model\Goods;

class Ad extends Base{
    public function ad(){       
        $act = input('get.act','add');
        $ad_id = input('get.ad_id/d');
        $is_app_ad = input('get.is_app_ad/d');//是否APP广告
        $suggestion = input('get.suggestion');

        $ad_info = array();
        if($ad_id){
            $ad_info = Db::name('ad')->where('ad_id',$ad_id)->find();
            $ad_info['start_time'] = date('Y-m-d',$ad_info['start_time']);
            $ad_info['end_time'] = date('Y-m-d',$ad_info['end_time']);            
        }
        if($act == 'add')          
           $ad_info['pid'] = request()->param('pid');

        // 加上这些，是因为有的服务器会报错
        $ad_info['goods_name'] = '';
        $ad_info['cat_id1'] = '';
        $ad_info['cat_id2'] = '';
        $ad_info['cat_id3'] = '';
        
        if($is_app_ad == 1){
            $cat_list = Db::name('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
            View::assign('cat_list',$cat_list);
            
            if($ad_info && $ad_info['media_type'] == 3){//如果广告类型是商品,则查找商品的名称 
               $ad_info['goods_name'] = Db::name('goods')->where('goods_id' , $ad_info['ad_link'])->value('goods_name');
            }else if($ad_info && $ad_info['media_type'] == 4){//如果广告类型是商品分类,则拆解分类
                $cat_ids = explode('_',$ad_info['ad_link']); 
                $ad_info['cat_id1'] = $cat_ids[0];
                $ad_info['cat_id2'] = $cat_ids[1];
                $ad_info['cat_id3'] = $cat_ids[2];
            }
        }
        
        $position = Db::name('ad_position')->select();
        View::assign('info',$ad_info);
        View::assign('act',$act);
        View::assign('position',$position);
        View::assign('suggestion',$suggestion);
        return View::fetch();
    }
    
    public function adList(){
        //delFile(RUNTIME_PATH.'html'); // 先清除缓存, 否则不好预览
            
        $pid = input('pid',0);
        if($pid){
            $where['pid'] = $pid;
        	View::assign('pid',input('pid'));
        }
        $keywords = input('keywords/s',false,'trim');
        if($keywords){
            $where['ad_name'] = array('like','%'.$keywords.'%');
        }
        $count = Db::name('ad')->where($where)->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $res = Db::name('ad')->where($where)->order('pid desc')->limit($Page->firstRow,$Page->listRows)->select();
        $list = array();
        if($res){
        	$media = array('图片','文字','flash');
        	foreach ($res as $val){
        		$val['media_type'] = $media[$val['media_type']];        		
        		$list[] = $val;
        	}
        }
                                     
        $ad_position_list = Db::name('AdPosition')->column('position_id,position_name,is_open','position_id');                        
        View::assign('ad_position_list',$ad_position_list);//广告位 
        $show = $Page->show();// 分页显示输出
        View::assign('list',$list);// 赋值数据集
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$pager);     

        //判断API模块存在
        if(is_dir(APP_PATH."/api")) View::assign('is_exists_api',1);
       
        
        return View::fetch();
    }
    
    public function position(){
        $act = input('get.act','add');
        $position_id = input('get.position_id/d');
        $info = array();
        if($position_id){
            $info = Db::name('ad_position')->where('position_id',$position_id)->find();
        }
        View::assign('info',$info);
        View::assign('act',$act);
        return View::fetch();
    }

    public function positionList()
    {
        $count = Db::name('ad_position')->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = Db::name('ad_position')->order('position_id DESC')->limit($Page->firstRow,$Page->listRows)->select();
        $show = $Page->show();
        View::assign('list', $list);
        View::assign('page', $show);
        View::assign('pager', $Page);
        return View::fetch();
    }
    
    public function adHandle(){
    	$data = input('post.');
    	
    	$data['start_time'] = strtotime($data['begin']);
    	$data['end_time'] = strtotime($data['end']);
    	$media_type = $data['media_type'];
    	if($media_type == 3){//商品
    	    $data['ad_link'] = $data['goods_id'];
    	}else if($media_type == 4){//分类
            if($data['cat_id3']>0){
                $data['ad_link'] = $data['cat_id3'];
            }elseif($data['cat_id2']>0){
                $data['ad_link'] = $data['cat_id2'];
            }elseif($data['cat_id1']>0) {
                $data['ad_link'] = $data['cat_id1'];
            }
    	}


        if($data['act'] != 'del'){
            if($data['ad_name']=='' ||$data['ad_link']==''){
                $this->error("广告名称或者广告链接不能为空");
            }
        }
        if($data['end_time'] < $data['start_time']){
            $this->error("广告结束日期必须大于开始日期");
        }

        if($data['act'] == 'add'){
    		$r = Db::name('ad')->insertGetId($data);
    	}
    	if($data['act'] == 'edit'){
    		$r = Db::name('ad')->where('ad_id', $data['ad_id'])->save($data);
    	}
    	
    	if($data['act'] == 'del'){
            $r = Db::name('ad')->where('ad_id', $data['del_id'])->delete();
            if($r){
                $this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>url('Admin/Ad/adList')]);
            }else{
                $this->ajaxReturn(['status'=>-1,'msg'=>"操作失败"]);
            }
    	}
    	$referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('Admin/Ad/adList');
        // 不管是添加还是修改广告 都清除一下缓存
        //delFile(RUNTIME_PATH.'html'); // 先清除缓存, 否则不好预览
        clearCache();
    	if($r){
    	    $redirect_url = session("ad_request_url");
            $img_url= session('img_url');
    	    $redirect_url && $this->success("操作成功",url('Admin/Ad/editAd' , ['request_url'=>urlencode($redirect_url),'pid'=>$data['pid'],'img_url'=>$img_url]));
    		$this->success("操作成功",url('Admin/Ad/adList'));
    	}else{
    		$this->error("操作失败",$referurl);
    	}
    }

    public function delList(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $listIds = rtrim($ids);
        Db::name('ad')->whereIn('ad_id',$listIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Ad/adList")]);
    }
    
    public function positionHandle(){
        $data = input('post.');
        if($data['act'] == 'add'){
            $r = Db::name('ad_position')->insertGetId($data);
        }
        
        if($data['act'] == 'edit'){
        	$r = Db::name('ad_position')->where('position_id',$data['position_id'])->save($data);
        }
        
        if($data['act'] == 'del'){
        	if(Db::name('ad')->where('pid',$data['position_id'])->count()>0){
        		$this->error("此广告位下还有广告，请先清除",url('Admin/Ad/positionList'));
        	}else{
        		$r = Db::name('ad_position')->where('position_id', $data['position_id'])->delete();
        		if($r) exit(json_encode(1));
        	}
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('Admin/Ad/positionList');
        if($r){
        	$this->success("操作成功",$referurl);
        }else{
        	$this->error("操作失败",$referurl);
        }
    }
    
/**
     * APP端编辑广告需要选择的商品
     * @return \think\mixed
     */
    public function search_goods()
    {
        $goods_id = input('goods_id/d');
        $brand_id = input('brand_id/d');
        $keywords = input('keywords');
        $goods_id = input('goods_id');
        $cat_id = input('cat_id/d'); 
        $intro = input('intro');//推荐/新品
    
        $GoodsLogic = new GoodsLogic();
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
          
        $where = ['is_on_sale' => 1,
            'prom_type' => 0,
            'is_virtual'=>0,
            'store_count'=>['>',0] 
        ];  //搜索条件
        
        if (!empty($goods_id)) {
            $where['goods_id'] = array('notin', $goods_id);
        }
        
        if ($cat_id) {
            View::assign('cat_id', $cat_id);
            $grandson_ids = getCatGrandson($cat_id);
            $where['cat_id'] = ['in',implode(',', $grandson_ids)];
        }
    
        if ($brand_id) {
            View::assign('brand_id', $brand_id);
            $where['brand_id'] = $brand_id;
        }
        if ($keywords) {
            View::assign('keywords', $keywords);
            $where['goods_name|keywords'] = array('like', '%' . $keywords . '%');
        }
        if($intro){
            $where[input('intro')] = 1;
        }
        $Goods = new Goods();
        $count = $Goods->where($where)->count();
        $Page = new Page($count, 10);
        $goodsList = $Goods->where($where)->order('goods_id DESC')->limit($Page->firstRow,$Page->listRows)->select();
        $show = $Page->show();//分页显示输出
        View::assign('page', $show);//赋值分页输出
        View::assign('goodsList', $goodsList);
        View::assign('categoryList', $categoryList);
        View::assign('brandList', $brandList);
        return View::fetch();
    }
    
    public function changeAdField(){
        $field = input('field');
    	$data[$field] = input('get.value');
    	$data['ad_id'] = input('get.ad_id');
    	Db::name('ad')->save($data); // 根据条件保存修改的数据
    }
    
    public function ad_app_home(){
       
        return View::fetch();
    }
    
	
    
    
    /**
     * 编辑广告中转方法
     */
    public function editAd()
    {
        $img_url = input('img_url');
        $pid = input('pid/d',0);
        clearCache();
        $request_url = input('request_url');
        //缓存请求的编辑广告URL
        session('ad_request_url' , $request_url);
        session('img_url' , $img_url);
        $request_url = urldecode(input('request_url'));
        $request_url = urldecode($request_url);
        $request_url = url($request_url,array('edit_ad'=>1,'img_url'=>$img_url,'pid'=>$pid));

        echo "<script>location.href='".$request_url."';</script>";
        exit;                
    }

    /**
     *  商品广告位列表
     */
    public function adGoodsList()
    {
 
        $keywords = input('keywords/s',false,'trim');
        if($keywords){
            $where['ad_name'] = array('like','%'.$keywords.'%');
        }
        $count = Db::name('ad_goods')->where($where)->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $res = Db::name('ad_goods')->where($where)->order('ad_goods_id desc')->limit($Page->firstRow,$Page->listRows)->select();
        $list = array();
        if($res){
            $media = array('图片','文字','flash');
            foreach ($res as $val){
                $val['media_type'] = $media[$val['media_type']];
                $list[] = $val;
            }
        }

        $show = $Page->show();// 分页显示输出
        View::assign('list',$list);// 赋值数据集
        View::assign('page',$show);// 赋值分页输出
        View::assign('pager',$pager);
        return View::fetch();
    }

    /**
     *  批量删除商品广告位
     */
    public function  adGoods()
    {
        $act = input('get.act','add');
        $ad_id = input('get.ad_goods_id/d');

        $ad_info = array();
        if($ad_id){
            $ad_info = Db::name('ad_goods')->where('ad_goods_id',$ad_id)->find();
            $ad_info['start_time'] = date('Y-m-d',$ad_info['start_time']);
            $ad_info['end_time'] = date('Y-m-d',$ad_info['end_time']);
        }

        if($ad_info && $ad_info['media_type'] == 3){//如果广告类型是商品,则查找商品的名称
            $ad_info['goods_name'] = Db::name('goods')->where('goods_id' , $ad_info['ad_link'])->value('goods_name');
            $goodsConfig = Db::name('ad_goods_config')->alias('c')->join('goods g','g.goods_id = c.goods_id ')->where('c.ad_goods_id',$ad_id)->select();
            View::assign('goodsConfig',$goodsConfig);
        }


        View::assign('info',$ad_info);
        View::assign('act',$act);
        return View::fetch();
    }

    /**
     *  获取绑定商品广告位的商品数据
     */
    public function search_goods_array()
    {
        $goods_id = input('goods_id');
        $intro = input('intro');
        $cat_id = input('cat_id');
        $brand_id = input('brand_id');
        $keywords = input('keywords');
        $prom_id = input('prom_id');
        $where = ['is_on_sale' => 1, 'store_count' => ['>', 0],'exchange_integral'=>0];
        $prom_type = input('prom_type/d',0);
        if ($prom_type != 0) {//指定商品优惠券 可以看到虚拟商品
            $where = ['is_on_sale' => 1, 'store_count' => ['>', 0],'is_virtual'=>0,'exchange_integral'=>0];
        }
        if($goods_id){
            $where['goods_id'] = ['notin',trim($goods_id,',')];
        }
        if($intro){
            $where[$intro] = 1;
        }
        if($cat_id){
            $grandson_ids = getCatGrandson($cat_id);
            $where['cat_id'] = ['in',implode(',', $grandson_ids)];
        }
        if ($brand_id) {
            $where['brand_id'] = $brand_id;
        }
        if($keywords){
            $where['goods_name|keywords'] = ['like','%'.$keywords.'%'];
        }
        $Goods = new Goods();
        $count = $Goods->where($where)->where(function ($query) use ($prom_type, $prom_id) {
            if(in_array($prom_type,[3,6])){
                //优惠促销,拼团
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => $prom_type])->whereor('prom_id', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if($prom_type == 7){
                //
                $query->where([ 'prom_type' => $prom_type])->whereor('prom_type', 0);
            }else if($prom_type == 8){
                //砍价
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => $prom_type])->whereor('prom_id', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if(in_array($prom_type,[1,2])){
                //抢购，团购
                $query->where('prom_type','in' ,[0,$prom_type])->where('prom_type',0);
            }else{
                $query->where('prom_type',0);
            }
        })->count();
        $Page = new Page($count, 10);
        $goodsList = $Goods->with(['specGoodsPrice'])->where($where)->where(function ($query) use ($prom_type, $prom_id) {
            if(in_array($prom_type,[3,6])){
                //优惠促销
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => $prom_type])->whereor('prom_id', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if($prom_type == 7){
                //
                $query->where([ 'prom_type' => $prom_type])->whereor('prom_type', 0);
            }else if($prom_type == 8){
                //砍价
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => $prom_type])->whereor('prom_id', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if(in_array($prom_type,[1,2])){
                //抢购，团购
                $query->where('prom_type','in' ,[0,$prom_type])->where('prom_type',0);
            }else{
                $query->where('prom_type',0);
            }
        })->order('goods_id DESC')->limit($Page->firstRow,$Page->listRows)->select();
        $GoodsLogic = new GoodsLogic;
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        View::assign('brandList', $brandList);
        View::assign('categoryList', $categoryList);
        View::assign('page', $Page);
        View::assign('goodsList', $goodsList);
        return View::fetch();
    }

    /**
     *  商品广告位更新
     */
    public function  adGoodsHandle()
    {
        $data = input('post.');

        $data['start_time'] = strtotime($data['begin']);
        $data['end_time'] = strtotime($data['end']);
        $media_type = $data['media_type'];
        if($media_type == 3){//商品
            $data['ad_link'] = $data['goods_id'];
            if(empty($data['combination_goods'])){
                $this->error("必须选择商品！");
            }
            $goodsArray = $data['combination_goods'];
            unset($data['combination_goods']);
        }else{
            $data['goods_id'] = '';
        }


        if($data['act'] != 'del'){
            if($data['ad_name']=='' ||$data['ad_link']==''){
                $this->error("广告名称或者广告链接不能为空");
            }
        }
        if($data['end_time'] < $data['start_time']){
            $this->error("广告结束日期必须大于开始日期");
        }

        if($data['act'] == 'add'){
            $r = Db::name('ad_goods')->insertGetId($data);
        }
        if($data['act'] == 'edit'){
            $r = Db::name('ad_goods')->where('ad_goods_id', $data['ad_goods_id'])->save($data);
        }
        if($data['act'] == 'del'){
            $r = Db::name('ad_goods')->where('ad_goods_id', $data['ad_goods_id'])->delete();
            Db::name('ad_goods_config')->where('ad_goods_id', $data['ad_goods_id'])->delete();
            if($r){
                $this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>url('Admin/Ad/adList')]);
            }else{
                $this->ajaxReturn(['status'=>-1,'msg'=>"操作失败"]);
            }
        }
        if($media_type == 3){//商品
            //配置数据处理
            if($data['ad_goods_id']){
                Db::name('ad_goods_config')->where('ad_goods_id', $data['ad_goods_id'])->delete();
            }
            foreach($goodsArray as $k=>$v){
                $dataConfig[$k]['ad_goods_id'] = empty($data['ad_goods_id'])?$r:$data['ad_goods_id'];
                $dataConfig[$k]['goods_id']    = $data['goods_id'];
            }
            Db::name('ad_goods_config')->insertAll($dataConfig);
        }
        if($r){
            $this->success("操作成功",url('Admin/Ad/adGoodsList'));
        }else{
            $this->error("操作失败");
        }
    }
    /**
     *  批量删除商品广告位
     */
    public function delGoodsList(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $listIds = rtrim($ids);
        Db::name('ad_goods')->whereIn('ad_goods_id',$listIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Ad/adGoodsList")]);
    }


}