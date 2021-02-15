<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 聂晓克      
 * Date: 2017-12-14
 */
namespace app\admin\controller;
use think\facade\View;
use app\common\logic\ActivityLogic;
use think\AjaxPage;
use think\facade\Db;

class Block extends Base{

	public function index(){
        
        $role=input('get.role');
        $id=input('get.id');
        $type=input('get.type');

        if(!$role){
            if($id){
                $arr=Db::name('mobile_template')->where('id='.$id)->field('template_html,block_info,template_name,thumb')->find();
                $html=htmlspecialchars_decode($arr['template_html']);
                View::assign('html',$html);
                View::assign('info',$arr['block_info']);
                View::assign('template_name',$arr['template_name']);
                View::assign('thumb',$arr['thumb']);
                View::assign('id',$id);
            }
        }

        if($role){
            if($id){
                $arr=Db::name('industry_template')->where('id',$id)->find();
                $html=htmlspecialchars_decode($arr['template_html']);
                $style_ids=Db::name('template_class')->where('parent_id',$arr['industry_id'])->order('sort_order desc')->select();
                View::assign('style_ids',$style_ids);

                View::assign('arr',$arr);
                View::assign('html',$html);
                View::assign('info',$arr['block_info']);
                View::assign('template_name',$arr['template_name']);
                View::assign('thumb',$arr['thumb']);
                View::assign('id',$id);
            }
            View::assign('role',$role);
        }

        $page_list=Db::name('mobile_template')->field('id,template_name,add_time,is_index')->select();
        View::assign('page_list',$page_list);
		
       	$cat_list = Db::name('goods_category')->where("parent_id = 0 and is_show=1")->select(); // 联动菜单第一级

       	$cat_tree=$cat_list;
       	foreach ($cat_tree as $k => $v) {
       		$cat_tree[$k]['son'] = Db::name('goods_category')->where("parent_id =".$v['id']."  and is_show=1")->select(); // 菜单第二级
       	}

       	//商品列表数据
       	$goodsList = Db::name('Goods')->where('is_on_sale=1')->page(1,10)->select();
        set_goods_label_name($goodsList);
       	$count=Db::name('Goods')->where('is_on_sale=1')->count();
        $count=ceil($count/10);

        //新闻分类
//        $newsCat=Db::name('news_cat')->select();
        $ArticleCat = new \app\admin\logic\NewsLogic();
        $newsCat = $ArticleCat->article_cat_list(0, 0, false);
        View::assign('newsCat',$newsCat);


        //新闻列表数据 默认前10个
        $where_news['publish_time'] = ['<=',time()];
        $where_news['is_open'] = 1;
        $count_new=Db::name('news')->where($where_news)->count();
        $count_new=ceil($count_new/10);
        $newsList = Db::view('news')
            ->view('newsCat','cat_name','newsCat.cat_id=news.cat_id','left')
            ->where($where_news)
            ->order('publish_time DESC')
            ->page(1,10)
            ->select();


       	//优惠券列表数据
       	$atype = input('atype', 1);
        $user = session('user');
        $p = input('p','1');
        $activityLogic = new ActivityLogic();
        $result = $activityLogic->getCouponCenterList($cat_id, $this->user_id, $p);
        //halt($result);

        //SaaS分配行业信息
        if($role){
            $industry_ids=Db::name('template_class')->where('parent_id',0)->order('sort_order desc')->select();
            View::assign('industry_ids',$industry_ids);
        }

        View::assign('coupon_list',$result);
       	View::assign('cat_list',$cat_list);
       	View::assign('cat_tree',$cat_tree);
       	View::assign('goodsList',$goodsList);
       	View::assign('count',$count);
        View::assign('newsList',$newsList);
        View::assign('count_new',$count_new>1?$count_new:1);
        View::assign('type',$type);
		return View::fetch();
          
	}

	//自定义页面列表页
	public function pageList(){
            
		$list=Db::name('mobile_template')->field('id,template_name,add_time,is_index')->where('type=0 and is_index=0')->select();
		View::assign('list',$list);
		return View::fetch();
                
	}

	public function ajaxGoodsList(){
            
        $page=input('page');
        $where = 'is_on_sale=1'; // 搜索条件                
        // 关键词搜索               
        $key_word = input('keywords') ? trim(input('keywords')) : '';
        if($key_word){
            $where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')" ;
        }
        
        $count = Db::name('Goods')->where($where)->count();
        $goodsList = Db::name('Goods')->where($where)->page($page,8)->select();
        set_goods_label_name($goodsList);
        $html='';
        foreach ($goodsList as $k => $v) {
        	$html.='<ul class="p-goods-item">';
        	$html.='<li class="pi-li0"><input type="checkbox" value="'.$v['goods_id'].'" /></li>';
        	$html.='<li class="pi-li1">'.$v['goods_id'].'</li>';
        	$html.='<li class="pi-li2">'.$v['goods_name'].'</li>';
        	$html.='<li class="pi-li3"><img src="'.$v['original_img'].'" alt="" /></li>';
        	$html.='<li class="pi-li4">'.$v['cat_id'].'</li>';
        	$html.='<li class="pi-li4">'.$v['shop_price'].'</li>';
        	$html.='<li class="pi-li4">'.$v['store_count'].'</li>';
        	$html.='</ul>';
        }
        $result['html']=$html;
        $result['count']=ceil($count/10);

        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$result]);
        
    }


    //商品列表板块参数设置
    public function goods_list_block(){
        
        $data = input('post.');
        // // 13时轮播，传的是sql_where
        if(isset($data['sql_where'])){
            $sql_where = $data['sql_where'];
            if(!empty($sql_where['label']) && !isset($data['label'])){
                $data['label'] = $sql_where['label'];
            }
            if(!empty($sql_where['ids']) && !isset($data['ids'])){
                $data['ids'] = $sql_where['ids'];
            }
            if(!empty($sql_where['min_price']) && !empty($sql_where['max_price']) && $sql_where['min_price'] < $sql_where['max_price']){
                $data['min_price'] = $sql_where['min_price'];
                $data['max_price'] = $sql_where['max_price'];
            }
        }

        $block = new \app\common\logic\Block();
        $goodsList = $block->goods_list_block($data);

        $html='';
        if($data['block_type']==13){
            foreach ($goodsList as $k => $v) {
                $html.='<div class="containers-slider-item">';
                $html.='<div class="seckill-item-img">';
                $html.='<a href="/Mobile/Goods/goodsInfo/id/'.$v["goods_id"].'"><img src="'.$v["original_img"].'" /></a>';
                $html.='</div>';
                $html.='<div class="seckill-item-name"><p>'.$v["goods_name"].'</p></div>';
                $html.='<div class="seckill-item-price" class="p"><span class="fl">￥<em>'.$v['shop_price'].'</em></span>';
                $html.='</div></div>';
            }
        }else{
            foreach ($goodsList as $k => $v) {
                $num = $v['sales_sum']+$v['virtual_sales_sum'];
                $html.='<li>';
                $html.='<a class="tpdm-goods-pic" href="/Mobile/Goods/goodsInfo/id/'.$v["goods_id"].'"><img src="'.$v["original_img"].'" alt="" /></a>';
                $html.='<a href="/Mobile/Goods/goodsInfo/id/'.$v["goods_id"].'" class="tpdm-goods-name">'.$v["goods_name"].'</a>';
                $html.= $v['label_name'] ? '<span class="rx-sp">'.$v['label_name'].'</span>' :  '<span class="rx-sp"  style="border:none"></span>';
                $html.='<div class="tpdm-goods-des">';
                $html.='<div class="tpdm-goods-price">￥<em>'.explode_price($v['shop_price'],0).'.</em>.<em>'.explode_price($v['shop_price'],1).'</em>'.'</div>';
                $html.='<a class="tpdm-goods-like">已售出'.$num.'件</a>';
                $html.='</div>';
                $html.='</li>';
            }
        }

        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$html,'goodsList'=>$goodsList]);
         
    }

    //新闻列表 浏览
    public function get_news_list(){
        $data=input('post.');
        $num=input('post.num',2); 
        $ids=$data['news'];

        if($ids){
            $ids = substr($ids,0,strlen($ids)-1);
//            $ids="(".$ids.")";
            $ids_arr = explode(',', $ids);
            $where_news['article_id'] = ['in', $ids_arr];
        }
        $where_news['publish_time'] = ['<=',time()];
        $where_news['is_open'] = 1;
        $list = Db::view('news')
            ->view('newsCat','cat_name','newsCat.cat_id=news.cat_id','left')
            ->where($where_news)
            ->order('publish_time DESC')
            ->limit(0,$num)
            ->select();

        $html='';
        foreach ($list as $k => $v) {
            if(strpos($v['thumb'],'/public') === 0 ){
                if(!file_exists('.'.$v['thumb'])){
                    $v['thumb'] = '/public/images/icon_goods_thumb_empty_300.png';
                    $list[$k]['thumb'] = $v['thumb'];
                }
            }elseif(empty($v['thumb'])){
                $v['thumb'] = '/public/images/icon_goods_thumb_empty_300.png';
                $list[$k]['thumb'] = $v['thumb'];
            }
            $html.='<li><a href="'.'/api/news/news_detail.html?article_id='.$v['article_id'].'"><div class="carlist-img fl">';
            $html.='<img src="'.$v['thumb'].'"></div>';
            $html.='<div class="carlist-txt fr"><b>'.$v['title'].'</b>';
            $html.='<p>'.$v['description'].'</p>';
            $html.='<span><em>'.$v['cat_name'].'</em><img src="/public/static/images/icon-fire.png">';
            $html.='<i>'.date("Y-m-d",$v['publish_time']).'</i></span></div></a></li>';
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$html]);
    }

    //ajax获取新闻 修改
    public function ajaxNewsList(){

        $page = input('page/d',1);
        $cat_id = input('cat');
        if($cat_id){
            $where['cat_id'] = $cat_id;
        }
        $where['publish_time'] = ['<=',time()];
        $where['is_open'] = 1;
        $count_new=Db::name('news')->where($where)->count();
        if($cat_id){
            unset($where['cat_id']);
            $where['news.cat_id'] = $cat_id;
        }

        $list= Db::view('news')
            ->view('newsCat','cat_name','newsCat.cat_id=news.cat_id','left')
            ->where($where)
            ->order('publish_time DESC')
            ->page($page,10)
            ->select();

        $html='';
        foreach ($list as $k => $v) {
            if(strpos($v['thumb'],'/public') === 0 ){
                if(!file_exists('.'.$v['thumb'])){
                    $v['thumb'] = '/public/images/icon_goods_thumb_empty_300.png';
                    $list[$k]['thumb'] = $v['thumb'];
                }
            }elseif(empty($v['thumb'])){
                $v['thumb'] = '/public/images/icon_goods_thumb_empty_300.png';
                $list[$k]['thumb'] = $v['thumb'];
            }
            $html.='<ul class="p-goods-item">';
            $html.='<li class="pi-li0"><input type="checkbox" value="'.$v['article_id'].'" /></li>';
            $html.='<li class="pi-li1">'.$v['article_id'].'</li>';
            $html.='<li class="pi-li2">'.$v['title'].'</li>';
            if($v['thumb']){
                $html.='<li class="pi-li3"><img src="'.$v['thumb'].'" alt="" /></li>';
            }else{
                $html.='<li class="pi-li3"></li>';
            }
            $html.='<li class="pi-li4">'.$v['cat_name'].'</li>';
            $html.='<li class="pi-li4">'.date("Y-m-d",$v['publish_time']).'</li>';
            $html.='</ul>';
        }
        
        $count_new=ceil($count_new/10);

        $result['html']=$html;
        $result['count_new']=$count_new;
        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$result,'list'=>$list]);
    }

	/**
	 * 查门店列表,默认3个后台编缉显示
	 */
	public function shopList(){
		$where['deleted'] = 0;
		$where['shop_status'] = 1;
//        $shop = new \app\common\model\Shop();
//        $shop_list = $shop->with(['shop_images'])->where($where)->limit(3)->select();
		$shop_list = Db::name('shop')->field('shop_id,shop_name,province_id,city_id,district_id,shop_address,longitude,latitude,deleted,shop_desc')->where($where)->limit(3)->select();
		$this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$shop_list]);
	}
    
	/*
	*保存编辑完成后的信息
	*/
	public function add_data(){
        
 		$param=input('post.');
 		$html=$param['html'];
 		$html=str_replace("\n"," ",$html);

        if($param['type']){
            $data['type']=1;
        }
 		$data['add_time']=time();
 		$data['template_html']=$html;
 		$data['block_info']=$param['info'];
 		$data['template_name']=$param['template_name'];
        if(!empty($param['footmenu'])){
            $data['footmenu'] = json_encode($param['footmenu']);
            $data['footmenu_html'] = $param['footmenu_html'];
        }
 		$id=input('post.edit_id');

        if($param['role']){
            $data['industry_id']=$param['industry_id'];
            $data['style_id']=$param['style_id'];
            $data['thumb']=$param['thumb'];
            if($id){    //若传递过来的有id则作更新操作
                $res=Db::name('industry_template')->where('id='.$id)->save($data);
                $this->save_form($param['info'],0,$id); //保存智能表单
            }else{
                $res=Db::name('industry_template')->insertGetId($data);
                $this->save_form($param['info'],0,$res);
            }
        }else{
            $data['thumb']=$param['thumb'];
            if($id){    //若传递过来的有id则作更新操作
                $res=Db::name('mobile_template')->where('id='.$id)->save($data);
                $this->save_form($param['info'],$id);
            }else{
                $res=Db::name('mobile_template')->insertGetId($data);
                $this->save_form($param['info'],$res);
            }
        }


 		//传递id回去防止重复添加 
 		if($res){
 			if($id){
 				echo $id;
 			}else{
 				echo $res;
 			}
 		}else{
 			echo 0;
 		}
            
	}

	//设置首页
	public function set_index(){
        
        $data=input('post.');
        $where['id'] = $data['id'];
        $arr = Db::name('mobile_template')->where($where)->find();
 
        if($arr){
            if($data['status']==0 && $arr['is_index'] == 0){
                $update_data = [
                    'is_index'=> Db::Raw("if(id={$data['id']}, 1, 0)")                     
                ];                
                // UPDATE `tp_mobile_template` SET `is_index`=IF(id=13, 1, 0) WHERE ( 1=1 )                         
                $s = Db::name('mobile_template')->where('1 = 1')->update($update_data);                                  
            }elseif($data['status']==1 && $arr['is_index'] == 1){
                $s = Db::name('mobile_template')->where($where)->update(['is_index'=>0]);
            }
            if($s){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }else{
                $this->ajaxReturn(['status' => 0, 'msg' => '失败','result' => 0]);
            }
        }else{

            $this->ajaxReturn(['status' => -1, 'msg' => '模板不存在','result' => 1]);
        }

        
	}

	//删除页面
	public function delete(){
		$id=input('post.id');
		if($id){
            if(input('post.role')){
                $r = Db::name('industry_template')->where('id', $id)->delete();
            }else{
                $r = Db::name('mobile_template')->where('id', $id)->delete();
            }
    		exit(json_encode(1));
		}
	}

    public function delLists(){
        $ids = input('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $listsIds = rtrim($ids);
        Db::name('mobile_template')->whereIn('id',$listsIds)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>url("Admin/Block/pageList")]);
    }

    // 添加智能表单的配置
    private function save_form($json, $tpl_id=0,$industry_id=0){
        if(!empty($tpl_id)){
            Db::name('form_config')->where('tpl_id',$tpl_id)->delete();
        }
        $data = json_decode(htmlspecialchars_decode($json),true);
        foreach($data as $k=>$v){
            if(isset($v['block_type']) && $v['block_type'] == '19'){
                $arr['tpl_timeid'] = $v['timeid'];
                $arr['tpl_id']=$tpl_id;
                $arr['industry_id'] = $industry_id;
                $arr['form_name'] = $v['form_name'];
                $arr['config_value'] = json_encode($v,JSON_UNESCAPED_UNICODE);
                $this->save_form_config($arr);
            }
        }
    }
    private function save_form_config($data){
        $tpl_timeid = Db::name('form_config')->where('tpl_timeid',$data['tpl_timeid'])->value('tpl_timeid');
        if($tpl_timeid){
            Db::name('form_config')->where('tpl_timeid',$data['tpl_timeid'])->save($data);
        }else{
            $data['create_time'] = time();
            Db::name('form_config')->insert($data);
        }
    }
	public function form_list(){
        // 所有表单名称
        $form_config = Db::name('form_config')->select();
        // 当前表单
        $tpl_timeid = input('tpl_timeid',0);
        if(empty($tpl_timeid) && !$form_config->isEmpty()){
            $tpl_timeid = $form_config[0]['tpl_timeid'];
        }

        $where['tpl_timeid'] = $tpl_timeid;
        $arr = Db::name('form_config')->where($where)->find();
        $form_config_list = json_decode($arr['config_value'],true);
        $name_list = $form_config_list['nav'];

        // 要查看的项
        View::assign('name_list', $name_list);
        View::assign('tpl_timeid', $tpl_timeid);
        View::assign('form_config', $form_config);
        return View::fetch();
    }
    public function ajax_form_list(){
        // 搜索条件
        $condition = [];
        $condition['tpl_timeid'] = input('tpl_timeid',0);
        $sort = input('sort','desc');
        $order_by = input('order_by','form_id');
        if(!in_array($order_by,['form_id','submit_time'])){
            $order_by = 'form_id';
        }
        $sort_order = $order_by . ' ' . ($sort == 'asc' ? 'asc':'desc');//exit($sort_order);
        $mobile = input('mobile');
        if($mobile){
            $condition['mobile'] = ['like','%'.$mobile.'%'];
        }
        $start_time = input('start_time');
        $end_time = input('end_time');
        if($start_time && $end_time){
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);
            if($start_time <= $end_time){
                $condition['submit_time'] = [
                    ['>=', $start_time],
                    ['<=', $end_time]
                ];
            }
        }

        $usersModel = (new \app\common\model\Form());
        $count = $usersModel->where($condition)->count();
        $Page = new AjaxPage($count, 10);
        $userList = $usersModel->where($condition)->order($sort_order)->limit($Page->firstRow,$Page->listRows)->select();
        $name_list=[];
        $name_data = $userList[0]['submit_value'] ? $userList[0]['submit_value'] : [];
        for ($i=0; $i < count($name_data); $i++) {
            if(isset($name_data['name'.$i])){
                $arr['name'] = 'name'.$i;
                $arr['title'] = $name_data['title'.$i];
                $name_list[] = $arr;
            }
        }
        $show = $Page->show();
        View::assign('userList', $userList);
        View::assign('page', $show);// 赋值分页输出
        View::assign('pager', $Page);
        View::assign('name_list', $name_list);
        return View::fetch();
    }
    public function delete_form(){
        $form_id = input('post.form_id/d',0);
        $s = Db::name('form')->delete($form_id);
        if($s){
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '删除失败']);
        }


    }
	//获取秒杀活动数据
	public function get_flash(){
            
        //秒杀商品
        $now_time = time();  //当前时间
        if(is_int($now_time/7200)){      //双整点时间，如：10:00, 12:00
            $start_time = $now_time;
        }else{
            $start_time = floor($now_time/7200)*7200; //取得前一个双整点时间
        }
        $end_time = $start_time+7200;   //结束时间
        $flash_sale_list = Db::name('goods')->alias('g')
            ->field('g.goods_id,f.price,s.item_id')
            ->join('flash_sale f','g.goods_id = f.goods_id','LEFT')
            ->join('spec_goods_price s','s.prom_id = f.id AND g.goods_id = s.goods_id','LEFT')
            ->where("start_time = $start_time and end_time = $end_time")
            ->limit(3)->select();
        //dump($flash_sale_list);exit();
        
	}


    //添加行业模板及风格入口页
    public function template_class(){
        $list=Db::name('template_class')->order('sort_order DESC')->select();

        $list = $this->filter_data($list);
        View::assign('list',$list);
        return View::fetch();
    }
    function filter_data($list){
        $data = [];
        foreach ($list as $k => $v) {
            if($v['parent_id']==0){
                $v['level']=0;
                $data[] = $v;
                foreach($list as $kk => $vv) {
                    if($v['id'] == $vv['parent_id']){
                        $vv['level']=1;
                        $data[] = $vv;
                    }
                }
            }
        }
        return $data;
    }

    //添加页面
    public function class_info(){
        if(input('id')){
            $info=Db::name('template_class')->where('id='.input('id'))->find();
            View::assign('info',$info);
        }
        if(input('parent_id')){
            $info['parent_id'] = input('parent_id/d', 0);
            View::assign('info',$info);
        }
        $list=Db::name('template_class')->where('parent_id=0')->order('sort_order DESC')->select();
        View::assign('list',$list);
        $act=input('get.act');
        View::assign('act',$act);
        return View::fetch();
    }

    //添加行业及风格处理
    public function class_handle(){
        $data=input('post.');
        if(empty($data['name']) && ($data['act']=='add' || $data['act']=='edit')){
            $this->ajaxReturn(['status' => -1, 'msg' => '名称不能为空','result' => 1]);
        }
        // 行业时，没有父节点 提交的是type 还是class_type ?
        if($data['type'] == 1 || $data['class_type'] == 1){
            $data['parent_id'] = 0;
        }
        if($data['act']=='add'){
            $data['add_time']=time();
            $res=Db::name('template_class')->insertGetId($data);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }
        }
        if($data['act']=='edit'){
            $param['add_time']=time();
            $param['parent_id']=$data['parent_id'];
            $param['name']=$data['name'];
            $param['sort_order']=$data['sort_order'];

            $res=Db::name('template_class')->where('id='.$data['id'])->save($param);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }
        }elseif($data['act']=='del'){
            $id = input('cat_id/d', 0);
            $res=Db::name('template_class')->delete($id);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }
        }
        $this->ajaxReturn(['status' => 0, 'msg' => '失败','result' => 0]);
    }

    //我的模板展示(用户)
    public function templateList(){
        $p_id=Db::name('template_class')->where('parent_id=0')->order('sort_order ASC')->select();//行业

        View::assign('p_id',$p_id);

        $templates=Db::name('mobile_template')->where('type=1')->select();
        View::assign('templates',$templates);
        View::assign('is_saas',IS_SAAS);
//
//        if($p_id){
//            $styles=Db::name('template_class')->where('parent_id='.$p_id[0]['id'])->order('add_time DESC')->select();//第一个行业的风格
//        }else{
//            $styles=array();
//        }
//
//        View::assign('styles',$styles);
        return View::fetch();
    }

    //行业模板展示(系统模板)
    public function templateList2(){
        $p_id=Db::name('template_class')->where('parent_id=0')->order('sort_order ASC')->select();//行业
        View::assign('p_id',$p_id);

        $templates=Db::name('industry_template')->order('add_time DESC')->select();
        View::assign('templates',$templates);
//        if($p_id){
//            $styles=Db::name('template_class')->where('parent_id='.$p_id[0]['id'])->order('sort_order desc')->select();//第一个行业的风格
//        }else{
//            $styles=array();
//        }
//        View::assign('styles',$styles);
        return View::fetch();
    }

    public function get_style(){
        $industry_id = input('post.industry_id/d');//行业id
        $style_id = input('post.style_id/d');//风格id
        //所有行业名称
        $industry_list = Db::name('template_class')->field('id as industry_id,name')->where('parent_id=0')->order('sort_order desc')->select();//行业

        // 所有风格名称
        $style_list = Db::name('template_class')->where('parent_id',$industry_id)->field('id as style_id,name')->order('sort_order desc')->select();

        // 风格展示条件
        if(!$industry_id){
            $industry_id = '0';//默认筛选全部
//            $industry_id = $industry_list[0]['industry_id'];
        }else{
            $where['industry_id'] = $industry_id;
        }
        if($style_id){
            $where['style_id'] = $style_id;
        }
        // 所有风格展示
        $template_list = Db::name('industry_template')->where($where)->order('id DESC')->select();
        $result['industry_id'] = $industry_id;
        $result['style_id'] = $style_id;
        $result['industry_list'] = $industry_list;
        $result['style_list'] = $style_list;
        $result['template_list'] = $template_list;
        //halt($result);
        $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => $result]);
    }
    public function select_style(){
        $industry_id = input('post.industry_id/d');//行业id
        // 所有风格名称
        $style_list = Db::name('template_class')->where('parent_id',$industry_id)->field('id ,name')->order('sort_order desc')->select();
        $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => $style_list]);
    }

    public function add_template(){
        //$data=input('post.');
        //halt($data);
        $id=input('post.id');
        $data=Db::name('industry_template')->where('id',$id)->find();
        $data['add_time']=time();
        $data['type']=1;
        unset($data['id']);
        $re = Db::name('mobile_template')->where('style_id', $data['style_id'])->find();
        if($re){
            $this->ajaxReturn(['status' => -1, 'msg' => '该模板已加入！']);
        }else{
            $res=Db::name('mobile_template')->insertGetId($data);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功']);
            }
        }
        $this->ajaxReturn(['status' => -1, 'msg' => '模板加入失败']);

    }

    public function creatimg(){
        return View::fetch();
    }

    /**
     * 删除，多余的组件数据
     * http://192.168.0.146:1001/Admin/Block/del_timeid?id=157&timeid=1
     */
    public function del_timeid(){
        $id = input('id/d');
        $timeid = input('timeid');
        if(!$id || !$timeid){
            echo 'id or timeid empty';
        }else{
            $data=Db::name('mobile_template')->where('id',$id)->find();
            if($data){
                echo 'find id=',$id;dump($data);
                $block_info = htmlspecialchars_decode($data['block_info']);
                $arr = json_decode($block_info,256);
                $flag = false;
                foreach($arr as $k=>$v){
                    if($k == $timeid){
                        unset($arr[$k]);
                        echo 'delete ',$timeid,'<br>';
                        $flag = true;
                    }
                }
                //dump($arr);
                $str = htmlspecialchars(json_encode($arr));
                $str =str_replace('\\/','/',$str);
                //dump($str);
                if($flag){
                    $save_data['block_info'] = $str;
                    $re = Db::name('mobile_template')->where('id',$id)->save($save_data);
                    echo 'save info:';dump($re);
                }else{
                    echo 'not find timeid=',$timeid;
                }
            }else{
                echo 'not find id=',$id;
            }
        }
    }
}
?>