<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: yhj
 * Date: 2018.9.28
 */

namespace app\common\logic;

use think\Model;
use think\facade\Db;

/**
 * 自定义接口
 * Class Block
 * @package app\common\logic
 */
class Block 
{

    /**
     * 商品列表板块参数设置
     * @param $data | ids 分类id|label 商品标签 | order 排序 | goods goods_ids
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function goods_list_block($data){
        $p = input('p/d',1);
        if(!isset($data['num']) or empty($data['num']) or $data['num'] < 2){
            $count = 4;
        }else{
            $count = $data['num'];
        }

        $where['a.is_on_sale'] = 1;
        if($data['ids']){
            $ids_arr = explode(',', $data['ids']);
            foreach($ids_arr as $k=>$v){
                if(empty($v)) unset($ids_arr[$k]);
            }
            if($ids_arr){
                $where_cat['is_show'] = 1;
                $where_cat['id'] = ['in', $ids_arr];
                $where_cat['level'] = 2; //查2级分类
                $ids_arr2 = Db::name('goods_category')->where($where_cat)->column('id');
                // 有2级以2级为主，否则以1级为主
                if($ids_arr2){
                    $where['cat_id2'] = ['in', $ids_arr2];
                }else{
                    $where['cat_id1'] = ['in', $ids_arr];
                }
            }
        }
        $data['label'] = trim($data['label']);
        if(!empty($data['label'])){
            $where[$data['label']] = 1;
        }
        if($data['max_price'] && $data['min_price'] < $data['max_price']){
            $where['a.shop_price'] = [['>=', $data['min_price']],['<=', $data['max_price']]];
        }
        if($data['goods']){
            $goods_id_arr = explode(',', $data['goods']);
            $where['a.goods_id'] = ['in', $goods_id_arr];
            $count = count($goods_id_arr);
        }
        if($data['prom_type']){
            $where['a.prom_type'] = $data['prom_type'];
        }
        switch ($data['order']) {
            case '0':
                $order_str="a.sales_sum DESC";
                break;

            case '1':
                $order_str="a.sales_sum ASC";
                break;

            case '2':
                $order_str="a.shop_price DESC";
                break;

            case '3':
                $order_str="a.shop_price ASC";
                break;

            case '4':
                $order_str="a.last_update DESC";
                break;

            case '5':
                $order_str="a.last_update ASC";
                break;
            case '6':
                $order_str="a.sort DESC";
                break;
            case '7':
                $order_str="a.sort ASC";
                break;
            default:
                $order_str="a.sales_sum DESC";
                break;
        }

        if($count>10 && empty($data['prom_type'])){
            $goodsList = Db::name('goods')->where($where)->alias('a')
                ->join('goods_label b','a.label_id = b.label_id','left')->order($order_str)
                ->page($p,10)
                ->select()->toArray();
        }else{
            // 活动商品一次查出，有新要求待改。
            if(!isset($_GET['p']) || $p < 2 || !empty($data['prom_type'])){
                $goodsList = Db::name('goods')->where($where)->alias('a')
                    ->join('goods_label b','a.label_id = b.label_id','left')->order($order_str)
                    ->limit(0,$count)
                    ->select()->toArray();
            }else{
                $goodsList = [];
            }
        }

        foreach ($goodsList as $k => $v) {
            if(strpos($v['original_img'],'/public') === 0 ){
                if(!file_exists('.'.$v['original_img'])){
                    $goodsList[$k]['original_img'] = '/public/images/icon_goods_thumb_empty_300.png';
                }
                if($data['block_type'] == '13'){
                    $goodsList[$k]['original_img'] = SITE_URL .$goodsList[$k]['original_img'];
                }
            }elseif(empty($v['original_img'])){
                $goodsList[$k]['original_img'] = '/public/images/icon_goods_thumb_empty_300.png';
                if($data['block_type'] == '13'){
                    $goodsList[$k]['original_img'] = SITE_URL .$goodsList[$k]['original_img'];
                }
            }
            $goodsList[$k]['comment_count'] +=  $goodsList[$k]['virtual_comment_count'];
        }
        return $goodsList;
    }

    /**
     * 提交智能表单数据
     * @param $data
     * @return array
     */
    public function add_form($data){
        if(empty($data['timeid'])) return ['status'=>0,'msg'=>'timeid不能为空'];
        if(empty($data['form_name'])) return ['status'=>0,'msg'=>'form_name不能为空'];
        $arr = Db::name('form_config')->where('tpl_timeid', $data['timeid'])->find();
        if($arr){
            // 验证必填项
            $config_value = json_decode($arr['config_value'],true);
            if(empty($config_value['nav'])){
                return ['status'=>0,'msg'=>'表单未配置 config_value'];
            }
            $all_empty = true;
            foreach($config_value['nav'] as $k=>$v){
                $name = 'name'.$k;
                if($v['required'] == 1 && empty($data[$name])){
                    return ['status'=>0,'msg'=>$v['title'].'不能为空'];
                }
                if(isset($data[$name]) && !empty($data[$name])) $all_empty=false;
                // 输入框
                if($v['type'] == 0){
                    if($v['verify_type'] == 1 && !check_mobile($data[$name])){
                        // 手机号验证
                        return ['status'=>0,'msg'=>'手机号码格式不对'];
                    }elseif($v['verify_type'] == 2 && !check_email($data[$name])){
                        // 邮箱验证
                        return ['status'=>0,'msg'=>'邮箱格式不对'];
                    }
                    if($v['verify_type'] == 1 && !isset($data['mobile'])){
                        $data['mobile'] = $data[$name];
                    }
                }
                $title = 'title'.$k;
                $data[$title] = $v['title'];
            }
            if($all_empty) return ['status'=>0,'msg'=>'不能全部为空'];
            $data['submit_value'] = json_encode($data,JSON_UNESCAPED_UNICODE);
            if(!isset($data['tpl_timeid'])){
                $data['tpl_timeid'] = $data['timeid'];
            }
            $data['submit_time'] = time();
            $re = Db::name('form')->insertGetId($data);
            if($re){
                return ['status'=>1,'msg'=>'提交成功'];
            }else{
                return ['status'=>0,'msg'=>'提交失败'];
            }
        }else{
            return ['status'=>0,'msg'=>'表单不存在'];
        }
    }
}