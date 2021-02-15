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
 * Date: 2019-10-11
 */

namespace app\common\logic;

use think\Model;
use think\facade\Db;

/**
 * 用户画像，购物车转化率，收藏转化率，订单支付率
 * Class Block
 * @package app\common\logic
 */
class Portrait
{
    /**
     * 收藏记录 tp_portrait_collect 只记录第一次收藏商品时间
     * 点一下收藏，记录或取消
     * http://192.168.0.146:1001/index.php?m=mobile&c=goods&a=collect_goods&goods_id=370
     */
    public function add_collect($user_id, $goods_id, $is_collect = 1)
    {
        if (empty($user_id) or empty($goods_id)) {
            return;
        }
        $where = [
            'user_id' => $user_id,
            'goods_id' => $goods_id
        ];
        $collect = Db::name('portrait_collect')->where($where)->field('id,is_collect')->find();
        if ($collect) {
            // 收藏过该商品，如果取消收藏设置is_collect
            if ($is_collect != $collect['is_collect']) {
                Db::name('portrait_collect')->where($where)->update(['is_collect' => $is_collect]);
            }
        } else {
            $id = Db::name('portrait_collect')->where('user_id', $user_id)->value('id');
            $is_first = empty($id) ? 1 : 0;
            $data = [
                'is_first' => $is_first,
                'is_collect' => 1,
                'add_time' => time(),
                'user_id' => $user_id,
                'goods_id' => $goods_id
            ];
            Db::name('portrait_collect')->insert($data);
        }
    }

    /**
     * 访问记录
     * @param $user
     * @param $type | 0:pc,1:wap,2:安卓，3：苹果，4：小程序
     * @param int $goods_id
     * @throws \think\Exception
     */
    public function add_visit($user, $type, $goods_id = 0)
    {
        if (empty($user)) {
            return;
        }
        $time = strtotime(date("Y-m-d"));
        $where = [
            'user_id' => $user['user_id'],
            'type' => $type,
            'goods_id' => $goods_id,
            ['add_time' , '>', $time]
        ];

        $id = Db::name('portrait_visit')->where($where)->value('id');
        if ($id) {
            Db::name('portrait_visit')->where('id', $id)->update(['last_time' => time(), 'num' =>Db::raw('num+1')]);
        } else {
            if ($user['birthday'] = 0) {
                $user['birthday'] = 0;
            }
            $is_reg = ($user['reg_time'] < $time) ? 0 : 1;
            $order_id = Db::name('order')->where('user_id', $user['user_id'])->where('pay_status', '>', 0)->value('order_id');
            $is_buy = empty($order_id) ? 0 : 1;
            $data = [
                'type' => $type,
                'num' => 1,
                'is_reg' => $is_reg,
                'is_buy' => $is_buy,
                'add_time' => time(),
                'user_id' => $user['user_id'],
                'goods_id' => $goods_id
            ];
            $data['last_time'] = $data['add_time'];
            Db::name('portrait_visit')->insert($data);
        }
    }

    /**
     * 设置已支付
     */
    public function collect_pay($user_id, $order_id)
    {
        $goods_id = Db::name('order_goods')->where('order_id', $order_id)->column('goods_id');
        if ($goods_id) {
            $where = [
                'user_id' => $user_id,
                'order_id' => 0,
                ['goods_id' , 'in', $goods_id]
            ];
            if (count($goods_id) == 1) {
                $where['goods_id'] = $goods_id[0];
            }
            Db::name('portrait_collect')->where($where)->update(['order_id' => $order_id, 'pay_time' => time()]);
            Db::name('portrait_cart')->where($where)->update(['order_id' => $order_id, 'pay_time' => time()]);
        }

    }

    /**
     * 某天收藏人数
     */
    public function collect_num($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('portrait_collect')->where($where)->group('user_id')->count();
        return $num;
    }

    /**
     * 某天收藏次数
     */
    public function collect_times($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('portrait_collect')->where($where)->count();
        return $num;
    }

    /**
     * 某天收藏转化率
     */
    public function collect_ok($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('portrait_collect')->where($where)->count();
        $ok = Db::name('portrait_collect')->where($where)->where('order_id', '>', 0)->count();
        if (empty($ok) or empty($num)) return 0;
        return round($ok / $num, 2) * 100;
    }

    /**
     * 某天的条件
     * @param $time
     * @return array
     */
    function where_day($time, $type = 1)
    {
        if ($type == 1) {
            $start_time = strtotime(date("Y-m-d", $time));
            $end_time = strtotime('+1 day', $start_time);
        } elseif ($type == 2) {
            $week = $this->getWeekMyActionAndEnd($time);
            $start_time = strtotime($week['start_time']);
            $end_time = strtotime($week['end_time']);
        } elseif ($type == 3) {
            $start_time = strtotime(date('Y-m-1', $time));
            $end_time = strtotime('+1 months', $start_time);
        } else {
            $start_time = strtotime(date("Y-m-d", $time));
            $end_time = strtotime('+1 day', $start_time);
        }

        $where = [
            'add_time' => [['>', $start_time], ['<', $end_time]]
        ];
        return $where;
    }

    /*
     * 获取某星期的开始时间和结束时间
     * time 时间
     * first 表示每周星期一为开始日期 0表示每周日为开始日期
     */
    function getWeekMyActionAndEnd($time = '', $first = 1)
    {
        //当前日期
        if (!$time) $time = time();
        $sdefaultDate = date("Y-m-d", $time);
        //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        $week_end = date('Y-m-d', strtotime("$week_start +7 days"));
        return array("start_time" => $week_start, "end_time" => $week_end);
    }

    /**
     * 购物车记录 tp_portrait_cart，只记录第一次添加购物车的商品
     * 点一下收藏，记录或取消
     */
    public function add_cart($user_id, $goods_id, $goods_num, $session_id = '')
    {
        if (empty($goods_num) or empty($goods_id)) {
            return;
        }
        $where = [
            'user_id' => $user_id,
            'goods_id' => $goods_id
        ];
        if (!empty($session_id)) {
            $where['session_id'] = $session_id;
        }
        if (empty($user_id)) {
            unset($where['user_id']);
            $user_id = 0;
        }
        $collect = Db::name('portrait_cart')->where($where)->field('id,goods_num')->find();
        if ($collect) {
            // 有该商品，
            if ($goods_num != $collect['goods_num']) {
                Db::name('portrait_cart')->where($where)->update(['goods_num' => $goods_num]);
            }
        } else {
            if (!empty($user_id)) {
                $id = Db::name('portrait_cart')->where('user_id', $user_id)->value('id');
            } elseif (!empty($session_id)) {
                $id = Db::name('portrait_cart')->where('session_id', $session_id)->value('id');
            } else {
                return;
            }

            $is_first = empty($id) ? 1 : 0;
            $data = [
                'session_id' => $session_id,
                'is_first' => $is_first,
                'goods_num' => $goods_num,
                'add_time' => time(),
                'user_id' => $user_id,
                'goods_id' => $goods_id
            ];
            Db::name('portrait_cart')->insert($data);
        }
    }

    // 未登录才处理
    function edit_cart($user_id, $session_id)
    {
        if (empty($user_id) or empty($session_id)) return;
        $id = Db::name('portrait_cart')->where(['user_id' => $user_id])->value('id');
        if ($id) {
            // 找到了说明有is_first=1
            Db::name('portrait_cart')->where('session_id', $session_id)->where('user_id', 0)->update(['user_id' => $user_id, 'is_first' => 0]);
        } else {
            Db::name('portrait_cart')->where('session_id', $session_id)->where('user_id', 0)->update(['user_id' => $user_id]);
        }
    }

    /**
     * 加购人数
     */
    public function cart_num($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('portrait_cart')->where($where)->group('user_id')->count();
        return $num;
    }

    /**
     * 加购商品件数
     */
    public function cart_times($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('portrait_cart')->where($where)->sum('goods_num');
        return $num;
    }

    /**
     * 加购转化率，
     */
    public function cart_ok($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('portrait_cart')->where($where)->count(); // 某天加入购物车多少件商品
        $ok = Db::name('portrait_collect')->where($where)->where('order_id', '>', 0)->count(); //已支付的商品
        if (empty($ok) or empty($num)) return 0;
        return round($ok / $num, 2) * 100;
    }

    /**
     * 支付买家数
     */
    public function order_num($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $num = Db::name('order')->where($where)->where('pay_status', '>', 0)->group('user_id')->count();
        return $num;
    }

    /**
     * 支付商品数
     * @param $time
     * @return bool
     */
    public function order_goods($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $where['pay_status'] = ['>', 0];
        $num = Db::view('order_goods', 'goods_num')
            ->view('order', 'pay_status,add_time', 'order.order_id=order_goods.order_id', 'LEFT')
            ->where($where)
            ->sum('goods_num');
        return $num;
    }

    /**
     * 支付率
     */
    public function order_ok($time, $type = 1)
    {
        $where = $this->where_day($time, $type);
        $ok = Db::name('order')->where($where)->where('pay_status', '>', 0)->count();
        $num = Db::name('order')->where($where)->count();
        if (empty($ok) or empty($num)) return 0;
        return round($ok / $num, 2) * 100;
    }

    /**
     * 访问次数统计
     * @param $start_time
     * @param $end_time
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function visit_ok($start_time, $end_time)
    {

        $where_time = [
            'add_time' => [['>', $start_time], ['<', $end_time]]
        ];

        // 每天总次数
        $visit_day = $this->visit_num($where_time);
        if ($visit_day) {
            // 每天未知性别的总次数
            $visit_sex0 = $this->visit_sex($where_time, ['users.sex' => 0]);
            // 每天男的总次数
            $visit_sex1 = $this->visit_sex($where_time, ['users.sex' => 1]);
            // 每天女的总次数
            $visit_sex2 = $this->visit_sex($where_time, ['users.sex' => 2]);
            // 每天新注册的访问次数
            $visit_reg = $this->visit_num($where_time, ['is_reg' => 1]);
            // 购买过的访问次数
            $visit_buy = $this->visit_num($where_time, ['is_buy' => 1]);

            foreach ($visit_day as $key => $value) {
                $visit_day[$key]['sex0'] = $this->get_day_num($visit_sex0, $value['gap']);
                $visit_day[$key]['sex1'] = $this->get_day_num($visit_sex1, $value['gap']);
                $visit_day[$key]['sex2'] = $this->get_day_num($visit_sex2, $value['gap']);
                $visit_day[$key]['reg'] = $this->get_day_num($visit_reg, $value['gap']);
                $visit_day[$key]['buy'] = $this->get_day_num($visit_buy, $value['gap']);
                $visit_day[$key]['time'] = strtotime($value['gap']);
            }
        } else {
            $visit_day = [];
        }
        return $visit_day;
    }

    /**
     * 按年龄统计,访问
     * @param $time
     * @param int $type
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function visit_age($time, $type = 1)
    {
        $where_time = $this->where_day($time, $type);

        $portrait_visit = Db::view('portrait_visit', 'id')
            ->view('users', 'user_id', 'users.user_id=portrait_visit.user_id', 'LEFT')
            ->field('sum(portrait_visit.num) as nums,FROM_UNIXTIME(users.birthday,\'%Y\') as gap')
            ->where($where_time)->where('users.birthday', '>', 0)
            ->group('gap')->order('gap asc')
            ->select();

        $nums = Db::view('portrait_visit', 'id')
            ->view('users', 'user_id', 'users.user_id=portrait_visit.user_id', 'LEFT')
            ->where($where_time)->where('users.birthday', '<=', 0)
            ->sum('portrait_visit.num');

        if ($nums && $portrait_visit) {
            $portrait_visit = array_merge([['nums' => $nums, 'gap' => 0]], $portrait_visit);
        } elseif ($nums) {
            $portrait_visit = [['nums' => $nums, 'gap' => 0]];
        } elseif ($portrait_visit) {

        } else {
            $portrait_visit = [];
        }
        return $portrait_visit;
    }

    // 数据处理
    protected function get_day_num($list, $val)
    {
        if (empty($list)) return 0;
        foreach ($list as $k => $arr) {
            if ($arr['gap'] == $val) {
                return $arr['nums'];
            }
        }
        return 0;
    }

    /**
     * 统计每天访问数
     * @param $where_time
     * @param string $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    protected function visit_num($where_time, $where = '')
    {
        $portrait_visit = Db::name('portrait_visit')->field('sum(num) as nums,FROM_UNIXTIME(add_time,\'%Y-%m-%d\') as gap')
            ->where($where_time)->where($where)
            ->group('gap')->order('gap asc')
            ->select();
        return $portrait_visit;
    }

    /**
     * 按性别统计访问次数
     * users.sex=1
     * @param $where_time
     * @param string $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    protected function visit_sex($where_time, $where = '')
    {
        $portrait_visit = Db::view('portrait_visit', 'type')
            ->view('users', 'user_id', 'users.user_id=portrait_visit.user_id', 'LEFT')
            ->field('sum(portrait_visit.num) as nums,FROM_UNIXTIME(portrait_visit.add_time,\'%Y-%m-%d\') as gap')
            ->where($where_time)->where($where)
            ->group('gap')->order('gap asc')
            ->select();
        return $portrait_visit;
    }

    /**
     * 数据组合
     * type:1按天，2按周，3按月，4按年
     * @param $time
     */
    public function portrait_data($time, $type = 1)
    {
        if ($type == 1) {
            $ago_time = strtotime('-1 day', $time);
        } elseif ($type == 2) {
            $ago_time = strtotime('-7 days', $time);
        } elseif ($type == 3) {
            $ago_time = strtotime('-1 months', $time);
        }

        $data['collect_num'] = $this->collect_num($time, $type);
        $data['collect_times'] = $this->collect_times($time, $type);
        $data['collect_ok'] = $this->collect_ok($time, $type);
        $data['collect_ok_ago'] = $this->collect_ok($ago_time, $type);

        $data['cart_num'] = $this->cart_num($time, $type);
        $data['cart_times'] = $this->cart_times($time, $type);
        $data['cart_ok'] = $this->cart_ok($time, $type);
        $data['cart_ok_ago'] = $this->cart_ok($ago_time, $type);

        $data['order_num'] = $this->order_num($time, $type);
        $data['order_goods'] = $this->order_goods($time, $type);
        $data['order_ok'] = $this->order_ok($time, $type);
        $data['order_ok_ago'] = $this->order_ok($ago_time, $type);

        return $data;
    }
}