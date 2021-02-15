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
 * Date: 2015-09-09
 */

namespace app\admin\controller;
use think\facade\View;

use app\admin\logic\OrderLogic;
use app\common\model\UserLabel;
use think\AjaxPage;
use think\console\command\make\Model;
use think\Page;
use think\Verify;
use think\facade\Db;
use app\admin\logic\UsersLogic;
use app\common\logic\MessageTemplateLogic;
use app\common\logic\MessageFactory;
use app\common\model\Withdrawals;
use app\common\model\Users;
use app\common\model\Moments;
use think\Loader;

class User extends Base
{

    public function index()
    {
        $arr = Db::name('user_level')->column('level_name','level_id');
        if (empty($arr)) {
            $this->error('请先添加会员等级,', url('admin/User/level'));
            exit;
        }
        return View::fetch();
    }

    /**
     * 会员列表
     */
    public function ajaxindex()
    {
        // 搜索条件
        $condition = array();
        $user_id = input('user_id');
        if($user_id){
            $condition['user_id'] = $user_id;
        }else{
            $nickname = input('nickname');
            $account = input('account');
            $account ? $condition['email|mobile'] = ['like', "%$account%"] : false;
            $nickname ? $condition['nickname'] = ['like', "%$nickname%"] : false;
            $begin = input('start_time');
            $end = input('end_time');
            $begin = strtotime($begin);
            $end = strtotime($end) + 86400 - 1;
            if ($begin && $end) {
                $condition['reg_time'] = array('between', "$begin,$end");
            }
        }

        input('first_leader') && ($condition['first_leader'] = input('first_leader')); // 查看一级下线人有哪些
        input('second_leader') && ($condition['second_leader'] = input('second_leader')); // 查看二级下线人有哪些
        input('third_leader') && ($condition['third_leader'] = input('third_leader')); // 查看三级下线人有哪些
        $sort_order = input('order_by') . ' ' . input('sort');

        $usersModel = new Users();
        $count = $usersModel->where($condition)->count();
        $Page = new AjaxPage($count, 20);
        $userList = $usersModel->where($condition)->order($sort_order)->limit($Page->firstRow,$Page->listRows)->select();
        $user_id_arr = get_arr_column($userList, 'user_id');
        if (!empty($user_id_arr)) {
            $first_leader = DB::query("select first_leader,count(1) as count  from __PREFIX__users where first_leader in(" . implode(',', $user_id_arr) . ")  group by first_leader");
            $first_leader = convert_arr_key($first_leader, 'first_leader');

            $second_leader = DB::query("select second_leader,count(1) as count  from __PREFIX__users where second_leader in(" . implode(',', $user_id_arr) . ")  group by second_leader");
            $second_leader = convert_arr_key($second_leader, 'second_leader');

            $third_leader = DB::query("select third_leader,count(1) as count  from __PREFIX__users where third_leader in(" . implode(',', $user_id_arr) . ")  group by third_leader");
            $third_leader = convert_arr_key($third_leader, 'third_leader');
        }
        View::assign('first_leader', $first_leader);
        View::assign('second_leader', $second_leader);
        View::assign('third_leader', $third_leader);
        $show = $Page->show();
        View::assign('userList', $userList);
        View::assign('level', Db::name('user_level')->column('level_name','level_id'));
        View::assign('page', $show);// 赋值分页输出
        View::assign('pager', $Page);
        return View::fetch();
    }


    /**
     * 会员详细信息查看
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        $uid = input('get.id');
//        $user = Db::name('users')->where(array('user_id' => $uid))->find();
        $user = Users::where(['user_id' => $uid])->find();
        if (!$user)
            exit($this->error('会员不存在'));

        if (IS_POST) {
            $data = input('post.');
            //  会员信息编辑
            $password = input('post.password');
            $password2 = input('post.password2');
            if ($password != '' && $password != $password2) {
                exit($this->error('两次输入密码不同'));
            }
            if ($password == '' && $password2 == '') {
                unset($data['password']);
            } else {
                $data['password'] = encrypt($data['password']);
            }

            if (!empty($data['email'])) {
                $email = trim($data['email']);
                $c = Db::name('users')->where("user_id != $uid and email = '$email'")->count();
                $c && exit($this->error('邮箱不得和已有用户重复'));
            }

            if (!empty($data['mobile'])) {
                $mobile = trim($data['mobile']);
                $c = Db::name('users')->where("user_id != $uid and mobile = '$mobile'")->count();
                $c && exit($this->error('手机号不得和已有用户重复'));
            }

            $userLevel = Db::name('user_level')->where('level_id=' . $data['level'])->value('discount');
            if (empty($userLevel)) {
                exit($this->error('请先设置会员等级！'));
            }
            $data['discount'] = $userLevel / 100;
            $row = Db::name('users')->where(array('user_id' => $uid))->save($data);
            if ($row)
                exit($this->success('修改成功'));
            exit($this->error('未作内容修改或修改失败'));
        }

        $user['first_lower'] = Db::name('users')->where("first_leader = {$user['user_id']}")->count();
        $user['second_lower'] = Db::name('users')->where("second_leader = {$user['user_id']}")->count();
        $user['third_lower'] = Db::name('users')->where("third_leader = {$user['user_id']}")->count();

        View::assign('user', $user);
        return View::fetch();
    }

    public function add_user()
    {
        if (IS_POST) {
            $data = input('post.');
            $user_obj = new UsersLogic();
            $res = $user_obj->addUser($data);
            if ($res['status'] == 1) {
                $this->success('添加成功', url('User/index'));
                exit;
            } else {
                $this->error('添加失败,' . $res['msg'], url('User/index'));
            }
        }
        return View::fetch();
    }

    public function export_user()
    {
        $strTable = '<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">会员ID</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">会员昵称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员等级</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">手机号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">邮箱</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">注册时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">最后登陆</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">余额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">积分</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">累计消费</td>';
        $strTable .= '</tr>';
        $user_ids = input('user_ids');
        if ($user_ids) {
            $condition['user_id'] = ['in', $user_ids];
        } else {
            $mobile = input('mobile');
            $email = input('email');
            $mobile ? $condition['mobile'] = $mobile : false;
            $email ? $condition['email'] = $email : false;
        };
        $count = DB::name('users')->where($condition)->count();
        $user_level = Db::name('user_level')->column('level_name','level_id');
                  print_r($user_level);
        $p = ceil($count / 5000);
        for ($i = 0; $i < $p; $i++) {
            $start = $i * 5000;
            $end = ($i + 1) * 5000;
            $userList = Db::name('users')->where($condition)->order('user_id')->limit($start, 5000)->select();
         
            if (!$userList->isEmpty()) {
                foreach ($userList as $k => $val) {
                    $strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">' . $val['user_id'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['nickname'] . ' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $user_level[$val['level']] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['mobile'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['email'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . date('Y-m-d H:i', $val['reg_time']) . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . date('Y-m-d H:i', $val['last_login']) . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['user_money'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['pay_points'] . ' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['total_amount'] . ' </td>';
                    $strTable .= '</tr>';
                }
                unset($userList);
            }
        }
        $strTable .= '</table>';
        downloadExcel($strTable, 'users_' . $i);
        exit();
    }

    /**
     * 用户收货地址查看
     */
    public function address()
    {
        $uid = input('get.id');
        $lists = Db::name('user_address')->where(array('user_id' => $uid))->select();
        $regionList = get_region_list();
        View::assign('regionList', $regionList);
        View::assign('lists', $lists);
        return View::fetch();
    }

    /**
     * 删除会员
     */
    public function delete()
    {
        $uid = input('get.id');

        //先删除ouath_users表的关联数据
        Db::name('OuathUsers')->where(array('user_id' => $uid))->delete();
        $row = Db::name('users')->where(array('user_id' => $uid))->delete();
        if ($row) {
            $this->success('成功删除会员');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 删除会员
     */
    public function ajax_delete()
    {
        $uid = input('id');
        if ($uid) {
            $row = Db::name('users')->where(array('user_id' => $uid))->delete();
            if ($row !== false) {
                //把关联的第三方账号删除
                Db::name('OauthUsers')->where(array('user_id' => $uid))->delete();
                $this->ajaxReturn(array('status' => 1, 'msg' => '删除成功', 'data' => ''));
            } else {
                $this->ajaxReturn(array('status' => 0, 'msg' => '删除失败', 'data' => ''));
            }
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => '参数错误', 'data' => ''));
        }
    }

    /**
     * 账户资金记录
     */
    public function account_log()
    {
        $user_id = input('get.id');
        //获取类型
        $type = input('get.type');
        //获取记录总数
        $count = Db::name('account_log')->where(array('user_id' => $user_id))->count();
        $page = new Page($count);
        $lists = Db::name('account_log')->where(array('user_id' => $user_id))->order('change_time desc')->limit($page->firstRow,$page->listRows)->select();

        View::assign('user_id', $user_id);
        View::assign('page', $page->show());
        View::assign('lists', $lists);
        return View::fetch();
    }

    /**
     * 账户资金调节
     */
    public function account_edit()
    {
        $user_id = input('user_id');
        if (!$user_id > 0) $this->ajaxReturn(['status' => 0, 'msg' => "参数有误"]);
        $user = Db::name('users')->field('user_id,user_money,frozen_money,pay_points,is_lock,distribut_money,distribut_withdrawals_money,is_distribut')->where('user_id', $user_id)->find();
        if (IS_POST) {
            $desc = input('post.desc');
            if (!$desc)
                $this->ajaxReturn(['status' => 0, 'msg' => "请填写操作说明"]);
            //加减用户资金
            $m_op_type = input('post.money_act_type');
            $user_money = input('post.user_money/f');
            $user_money = $m_op_type ? $user_money : 0 - $user_money;
            if (($user['user_money'] + $user_money) < 0) {
                $this->ajaxReturn(['status' => 0, 'msg' => "用户剩余资金不足！！"]);
            }
            //加减用户积分
            $p_op_type = input('post.point_act_type');
            $pay_points = input('post.pay_points/d');
            $pay_points = $p_op_type ? $pay_points : 0 - $pay_points;
            if (($pay_points + $user['pay_points']) < 0) {
                $this->ajaxReturn(['status' => 0, 'msg' => '用户剩余积分不足！！']);
            }
            //加减冻结资金
            $f_op_type = input('post.frozen_act_type');
            $revision_frozen_money = input('post.frozen_money/f');
            if ($revision_frozen_money != 0) {    //有加减冻结资金的时候
                $frozen_money = $f_op_type ? $revision_frozen_money : 0 - $revision_frozen_money;
                $frozen_money = $user['frozen_money'] + $frozen_money;    //计算用户被冻结的资金
                if ($f_op_type == 1 && $revision_frozen_money > $user['user_money']) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "用户剩余资金不足！！"]);
                }
                if ($f_op_type == 0 && $revision_frozen_money > $user['frozen_money']) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "冻结的资金不足！！"]);
                }
                $user_money = $f_op_type ? 0 - $revision_frozen_money : $revision_frozen_money;    //计算用户剩余资金
                Db::name('users')->where('user_id', $user_id)->update(['frozen_money' => $frozen_money]);
            }
			//加减佣金
            $d_op_type = input('post.distribut_act_type');
            $distribut_money = input('post.distribut_money/f',0);
			if ($distribut_money && $user['is_distribut'] == 0) {
				$this->ajaxReturn(['status' => 0, 'msg' => "用户不是分销商，无法调节佣金！！"]);
			}
			$distribut_money = $d_op_type ? $distribut_money : 0 - $distribut_money;
			if (($user['distribut_money'] + $distribut_money) < $user['distribut_withdrawals_money']) {
				$this->ajaxReturn(['status' => 0, 'msg' => "用户剩余佣金不足！！"]);
			}
			$res1 = accountLog($user_id, $user_money, $pay_points, $desc, 0,0,'',$revision_frozen_money != 0?false:true);
			if ($distribut_money != 0) {
				$res2 = accountDistributLog($user_id, 0, $desc, 0, 0, '',$distribut_money);
			}
            if ($res1 || ($distribut_money != 0 && $res2)) {
                /*$account_log = array(
                    'user_id'       => $user_id,
                    'user_money'    => $user_money,
                    'pay_points'    => $pay_points,
                    'change_time'   => time(),
                    'desc'   => $desc,
                    'order_id' => 0,
                    'order_sn' => 0
                );
                Db::name('account_log')->insert($account_log);*/
                $this->ajaxReturn(['status' => 1, 'msg' => "操作成功", 'url' => url("Admin/User/account_log", array('id' => $user_id))]);
            } else {
                $this->ajaxReturn(['status' => -1, 'msg' => "操作失败"]);
            }
            exit;
        }
        View::assign('user_id', $user_id);
        View::assign('user', $user);
        return View::fetch();
    }

    public function recharge()
    {
        $timegap = urldecode(input('timegap'));
        $nickname = input('nickname');
        $map = array();
        if ($timegap) {
            $gap = explode(',', $timegap);
            $begin = $gap[0];
            $end = $gap[1];
            $map['ctime'] = array('between', array(strtotime($begin), strtotime($end)));
            View::assign('begin', $begin);
            View::assign('end', $end);
        }
        if ($nickname) {
            $map['nickname'] = array('like', "%$nickname%");
            View::assign('nickname', $nickname);
        }
        $count = Db::name('recharge')->where($map)->count();
        $page = new Page($count);
        $lists = Db::name('recharge')->where($map)->order('ctime desc')->limit($page->firstRow,$page->listRows)->select();
        View::assign('page', $page->show());
        View::assign('pager', $page);
        View::assign('lists', $lists);
        return View::fetch();
    }

    public function level()
    {
        $act = input('get.act', 'add');
        View::assign('act', $act);
        $level_id = input('get.level_id');
        if ($level_id) {
            $level_info = Db::name('user_level')->where('level_id=' . $level_id)->find();
            View::assign('info', $level_info);
        }
        return View::fetch();
    }

    public function levelList()
    {
        
        $p = request()->param('p',1);
        $res = Db::name('user_level')->order('level_id')->page($p . ',10')->select();
        if ($res) {
            foreach ($res as $val) {
                $list[] = $val;
            }
        }
        View::assign('list', $list);
        $count = Db::name('user_level')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        View::assign('page', $show);
        return View::fetch();
    }

    /**
     * 会员等级添加编辑删除
     */
    public function levelHandle()
    {
        $data = input('post.');
        $userLevelValidate = validate(\app\admin\validate\UserLevel::class);

        $return = ['status' => 0, 'msg' => '参数错误', 'result' => ''];//初始化返回信息
        if ($data['act'] == 'add') {
            if (!$userLevelValidate->batch(true)->check($data)) {
                $return = ['status' => 0, 'msg' => '添加失败', 'result' => $userLevelValidate->getError()];
            } else {
                $r = Db::name('user_level')->insertGetId($data);
                if ($r !== false) {
                    $return = ['status' => 1, 'msg' => '添加成功', 'result' => $userLevelValidate->getError()];
                } else {
                    $return = ['status' => 0, 'msg' => '添加失败，数据库未响应', 'result' => ''];
                }
            }
        }
        if ($data['act'] == 'edit') {
            if (!$userLevelValidate->scene('edit')->batch(true)->check($data)) {
                $return = ['status' => 0, 'msg' => '编辑失败', 'result' => $userLevelValidate->getError()];
            } else {
                $r = Db::name('user_level')->where('level_id=' . $data['level_id'])->save($data);
                if ($r !== false) {
                    $discount = $data['discount'] / 100;
                    Db::name('users')->where(['level' => $data['level_id']])->save(['discount' => $discount]);
                    $return = ['status' => 1, 'msg' => '编辑成功', 'result' => $userLevelValidate->getError()];
                } else {
                    $return = ['status' => 0, 'msg' => '编辑失败，数据库未响应', 'result' => ''];
                }
            }
        }
        if ($data['act'] == 'del') {
            $r = Db::name('user_level')->where('level_id=' . $data['level_id'])->delete();
            if ($r !== false) {
                $return = ['status' => 1, 'msg' => '删除成功', 'result' => ''];
            } else {
                $return = ['status' => 0, 'msg' => '删除失败，数据库未响应', 'result' => ''];
            }
        }
        $this->ajaxReturn($return);
    }

    /**
     * 搜索用户名
     */
    public function search_user()
    {
        $search_key = trim(input('search_key'));
        if ($search_key == '') $this->ajaxReturn(['status' => -1, 'msg' => '请按要求输入！！']);
        $list = Db::name('users')->where(['nickname' => ['like', "%$search_key%"]])->select();
        if (!$list->isEmpty()) {
            $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $list]);
        }
        $this->ajaxReturn(['status' => -1, 'msg' => '未查询到相应数据！！']);
    }

    /**
     * 分销树状关系
     */
    public function ajax_distribut_tree()
    {
        $list = Db::name('users')->where("first_leader = 1")->select();
        return View::fetch();
    }

    /**
     *
     * @time 2016/08/31
     * @author dyr
     * 发送站内信
     */
    public function sendMessage()
    {
        $user_id_array = input('get.user_id_array');
        $users = array();
        if (!empty($user_id_array)) {
            $users = Db::name('users')->field('user_id,nickname')->where(array('user_id' => array('IN', $user_id_array)))->select();
        }
        View::assign('users', $users);
        return View::fetch();
    }

    /**
     * 发送系统通知消息
     * @author yhj
     * @time  2018/07/10
     */
    public function doSendMessage()
    {
        $call_back = input('call_back');//回调方法
        $message_content = input('post.text', '');//内容
        $message_title = input('post.title', '');//标题
        $message_type = input('post.type', 0);//个体or全体
        $users = input('post.user/a');//个体id
        $message_val = ['name' => ''];
        $send_data = array(
            'message_title' => $message_title,
            'message_content' => $message_content,
            'message_type' => $message_type,
            'users' => $users,
            'type' => 0, //0系统消息
            'message_val' => $message_val,
            'category' => 0,
            'mmt_code' => 'message_notice'
        );

        $messageFactory = new MessageFactory();
        $messageLogic = $messageFactory->makeModule($send_data);
        $messageLogic->sendMessage();

        echo "<script>parent.{$call_back}(1);</script>";
        exit();
    }

    /**
     *
     * @time 2016/09/03
     * @author dyr
     * 发送邮件
     */
    public function sendMail()
    {
        $user_id_array = input('get.user_id_array');
        $users = array();
        if (!empty($user_id_array)) {
            $user_where = array(
                'user_id' => array('IN', $user_id_array),
                'email' => array('<>', '')
            );
            $users = Db::name('users')->field('user_id,nickname,email')->where($user_where)->select();
        }
        View::assign('smtp', tpCache('smtp'));
        View::assign('users', $users);
        return View::fetch();
    }

    /**
     * 发送邮箱
     * @author dyr
     * @time  2016/09/03
     */
    public function doSendMail()
    {
        $call_back = input('call_back');//回调方法
        $message = input('post.text');//内容
        $title = input('post.title');//标题
        $users = input('post.user/a');
        $email = input('post.email');
        if (!empty($users)) {
            $user_id_array = implode(',', $users);
            $users = Db::name('users')->field('email')->where(array('user_id' => array('IN', $user_id_array)))->select();
            $to = array();
            foreach ($users as $user) {
                if (check_email($user['email'])) {
                    $to[] = $user['email'];
                }
            }
            $res = send_email($to, $title, $message);
            echo "<script>parent.{$call_back}({$res['status']});</script>";
            exit();
        }
        if ($email) {
            $res = send_email($email, $title, $message);
            echo "<script>parent.{$call_back}({$res['status']});</script>";
            exit();
        }
    }

    /**
     *  转账汇款记录
     */
    public function remittance()
    {
        $status = input('status', 1);
        $realname = input('realname');
        $bank_card = input('bank_card');
        $where['status'] = $status;
        $realname && $where['realname'] = array('like', '%' . $realname . '%');
        $bank_card && $where['bank_card'] = array('like', '%' . $bank_card . '%');

        $create_time = urldecode(input('create_time'));
        // echo urldecode($create_time);
        // echo $create_time;exit;
        // $create_time = str_replace('+', '', $create_time);

        $create_time = $create_time ? $create_time : date('Y-m-d H:i:s', strtotime('-1 year')) . ',' . date('Y-m-d H:i:s', strtotime('+1 day'));
        $create_time3 = explode(',', $create_time);
        View::assign('start_time', $create_time3[0]);
        View::assign('end_time', $create_time3[1]);
        if ($status == 2) {
            $time_name = 'pay_time';
            $export_time_name = '转账时间';
            $export_status = '已转账';
        } else {
            $time_name = 'check_time';
            $export_time_name = '审核时间';
            $export_status = '待转账';
        }
        $where[$time_name] = array(array('>', strtotime($create_time3[0])), array('<', strtotime($create_time3[1])));
        $withdrawalsModel = new Withdrawals();
        $count = $withdrawalsModel->where($where)->count();
        $Page = new page($count, config('PAGESIZE'));
        $list = $withdrawalsModel->where($where)->limit($Page->firstRow, $Page->listRows)->order("id desc")->select();
        if (input('export') == 1) {
            # code...导出记录
            $selected = input('selected');
            if (!empty($selected)) {
                $selected_arr = explode(',', $selected);
                $where['id'] = array('in', $selected_arr);
            }
            $list = $withdrawalsModel->where($where)->order("id desc")->select();
            $strTable = '<table width="500" border="1">';
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">用户昵称</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">银行机构名称</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">账户号码</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">账户开户名</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">申请金额</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">状态</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $export_time_name . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">备注</td>';
            $strTable .= '</tr>';
            if ($list->toArray()) {
                foreach ($list as $k => $val) {
                    $strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">' . $val['users']['nickname'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['bank_name'] . ' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['bank_card'] . '</td>';
                    $strTable .= '<td style="vnd.ms-excel.numberformat:@">' . $val['realname'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['money'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $export_status . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . date('Y-m-d H:i:s', $val[$time_name]) . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['remark'] . '</td>';
                    $strTable .= '</tr>';
                }
            }
            $strTable .= '</table>';
            unset($remittanceList);
            downloadExcel($strTable, 'remittance');
            exit();
        }

        $show = $Page->show();
        View::assign('show', $show);
        View::assign('status', $status);
        View::assign('Page', $Page);
        View::assign('list', $list);
        return View::fetch();
    }

    /**
     * 提现申请记录
     */
    public function withdrawals()
    {
        $this->get_withdrawals_list();
        View::assign('withdraw_status', config('WITHDRAW_STATUS'));
        return View::fetch();
    }

    public function get_withdrawals_list($status = '')
    {
        $id = input('selected/a');
        $user_id = input('user_id/d');
        $realname = input('realname');
        $bank_card = input('bank_card');
        $create_time = urldecode(input('create_time'));
        $create_time = $create_time ? $create_time : date('Y-m-d H:i:s', strtotime('-1 year')) . ',' . date('Y-m-d H:i:s', strtotime('+1 day'));
        $create_time3 = explode(',', $create_time);
        View::assign('start_time', $create_time3[0]);
        View::assign('end_time', $create_time3[1]);
        $where['w.create_time'] = array(array('>', strtotime($create_time3[0])), array('<', strtotime($create_time3[1])));

        $status = empty($status) ? input('status') : $status;
        if ($status !== null) {
            $where['w.status'] = $status;
        } else {
            $where['w.status'] = ['<', 2];
        }
        if ($id) {
            $where['w.id'] = ['in', $id];
        }
        $user_id && $where['u.user_id'] = $user_id;
        $realname && $where['w.realname'] = array('like', '%' . $realname . '%');
        $bank_card && $where['w.bank_card'] = array('like', '%' . $bank_card . '%');
        $export = input('export');
        if ($export == 1) {
            $strTable = '<table width="500" border="1">';
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">申请人</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">提现金额</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行名称</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行账号</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">开户人姓名</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">申请时间</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">提现备注</td>';
            $strTable .= '</tr>';
            $remittanceList = Db::name('withdrawals')->alias('w')->field('w.*,u.nickname')->join('users u', 'u.user_id = w.user_id', 'INNER')->where($where)->order("w.id desc")->select();
            if (is_array($remittanceList)) {
                foreach ($remittanceList as $k => $val) {
                    $strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">' . $val['nickname'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['money'] . ' </td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['bank_name'] . '</td>';
                    $strTable .= '<td style="vnd.ms-excel.numberformat:@">' . $val['bank_card'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['realname'] . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . date('Y-m-d H:i:s', $val['create_time']) . '</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['remark'] . '</td>';
                    $strTable .= '</tr>';
                }
            }
            $strTable .= '</table>';
            unset($remittanceList);
            downloadExcel($strTable, 'remittance');
            exit();
        }
        $count = Db::name('withdrawals')->alias('w')->join('users u', 'u.user_id = w.user_id', 'INNER')->where($where)->count();
        $Page = new Page($count, 20);
        $list = Db::name('withdrawals')->alias('w')->field('w.*,u.nickname')->join('users u', 'u.user_id = w.user_id', 'INNER')->where($where)->order("w.id desc")->limit($Page->firstRow,$Page->listRows)->select();
        //View::assign('create_time',$create_time2);
        $show = $Page->show();
        View::assign('show', $show);
        View::assign('list', $list);
        View::assign('pager', $Page);
        config('TOKEN_ON', false);
    }

    /**
     * 删除申请记录
     */
    public function delWithdrawals()
    {
        $id = input('del_id/d');
        $res = Db::name("withdrawals")->where(['id' => $id])->delete();
        if ($res !== false) {
            $return_arr = ['status' => 1, 'msg' => '操作成功', 'data' => '',];
        } else {
            $return_arr = ['status' => -1, 'msg' => '删除失败', 'data' => '',];
        }
        $this->ajaxReturn($return_arr);
    }

    /**
     * 修改编辑 申请提现
     */
    public function editWithdrawals()
    {
        $id = input('id');
        $withdrawals = Db::name("withdrawals")->find($id);
        $user = Db::name('users')->where(['user_id' => $withdrawals['user_id']])->find();
        if ($user['nickname'])
            $withdrawals['user_name'] = $user['nickname'];
        elseif ($user['email'])
            $withdrawals['user_name'] = $user['email'];
        elseif ($user['mobile'])
            $withdrawals['user_name'] = $user['mobile'];
        $status = $withdrawals['status'];
        $withdrawals['status_code'] = config('WITHDRAW_STATUS')["$status"];
        View::assign('user', $user);
        View::assign('data', $withdrawals);
        return View::fetch();
    }

    /**
     *  处理会员提现申请
     */
    public function withdrawals_update()
    {
        $id_arr = input('id/a');
        $data['status'] = $status = input('status');
        $data['remark'] = input('remark');
        if ($status == 1) $data['check_time'] = time();
        if ($status != 1) $data['refuse_time'] = time();
        $ids = implode(',', $id_arr);
        $r = Db::name('withdrawals')->whereIn('id', $ids)->update($data);
        if ($r !== false) {
            $this->ajaxReturn(array('status' => 1, 'msg' => "操作成功"), 'JSON');
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => "操作失败"), 'JSON');
        }
    }

    // 用户申请提现
    public function transfer()
    {
        $id = input('selected/a');
        if (empty($id)) $this->error('请至少选择一条记录');
        $atype = input('atype');
        if (is_array($id)) {
            $withdrawals = Db::name('withdrawals')->where('id in (' . implode(',', $id) . ')')->select();
        } else {
            $withdrawals = Db::name('withdrawals')->where(array('id' => $id))->select();
        }

        $messageFactory = new \app\common\logic\MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 0]);

        $alipay['batch_num'] = 0;
        $alipay['batch_fee'] = 0;
        foreach ($withdrawals as $val) {
            $user = Db::name('users')->where(array('user_id' => $val['user_id']))->find();
			//查找用户和微信的绑定关系，优先选择微信客户端的绑定关系
            $oauthUsers = Db::name("OauthUsers")->where(['user_id' => $user['user_id'], 'oauth' => 'weixin'])->find();
			if (!$oauthUsers) {
				$oauthUsers = Db::name("OauthUsers")->where(['user_id' => $user['user_id']])->find();
			}
			
			//与微信的不同绑定方式选择不同的支付类型
			switch ($oauthUsers['oauth']) {
				case 'weixin': $code = 'weixin'; break;
				case 'wx': $code = 'appWeixinPay'; break;
				case 'miniapp': $code = 'miniAppPay';break;
				default: $code = 'weixin';
			}
			
            //获取用户绑定openId
            $user['openid'] = $oauthUsers['openid'];
			$money = $user['distribut_money'] - $user['distribut_withdrawals_money']; //可提现佣金

			 
            if($user['is_lock'] ==1){
                $this->error('账户被冻结，需解冻后方可转账');
            } else if ($val['type'] == 0 && $user['user_money'] < $val['money']) {
                $data = array('status' => -2, 'remark' => '账户余额不足');
                Db::name('withdrawals')->where(array('id' => $val['id']))->save($data);
                $this->error('账户余额不足');
            } else if ($val['type'] == 1 && $money < $val['money']) {
                    $data = array('status' => -2, 'remark' => '账户佣金可用余额不足');
                    Db::name('withdrawals')->where(array('id' => $val['id']))->save($data);
                    $this->error('账户佣金可用余额不足');
            } else {
                  //type 支出类型0用户提现,1订单退款,2其他,3注册,4邀请,5分享,6评论
                $rdata = array('type' => 0, 'money' => $val['money'], 'log_type_id' => $val['id'], 'user_id' => $val['user_id']);
                
				if ($atype == 'online') {
                    header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
                } else if ($atype == 'packet') {
                    if ($val['bank_name'] == '微信') {
                       //type 支出类型0用户提现,1订单退款,2其他,3注册,4邀请,5分享,6评论
                        $rdata = array('type' => 0, 'money' => $val['money'], 'log_type_id' => $val['id'], 'user_id' => $val['user_id']);
                       
                        $wxpay = array(

                            'openid' => $user['openid'],//红包发放微信号对应的 OPENID
                            'total_amount' => $val['money']-$val['taxfee'],//发放金额
                            'sender' => tpCache('shop_info')['store_name']
                        );
                        include_once PLUGIN_PATH . "payment/weixin/weixin.class.php";
                        $wxpay_obj = new \weixin($code);
                        $res = $wxpay_obj->red_packets($wxpay);//微信红包付款转账
                        if ($res && $res['status'] != 0) {
                            if($val['type'] == 1){
                                accountDistributLog($val['user_id'],0,"分销佣金提现申请", $val['money'] , $val['id']);
                            }else{
                                accountLog($val['user_id'], ($val['money'] * -1), 0, "平台处理用户提现申请",0,0,'',false,$val['money']);
                            }
                            Db::name('withdrawals')->where(array('id' => $val['id']))->save(array('status' => 2, 'pay_time' => time(), 'pay_code' => $res['send_listid']));
                            expenseLog($rdata);
                            // 提现通知
                            $messageLogic->withdrawalsNotice($val['id'], $val['user_id'], $val['money'] - $val['taxfee']);

                        } else {
                            $this->error('操作失败');
                        }
                    }
                } else {
                    if($val['type'] == 1){
                        accountDistributLog($val['user_id'],0,"分销佣金提现申请", $val['money'] , $val['id']);
                    }else{
                        accountLog($val['user_id'], ($val['money'] * -1), 0, "管理员处理用户提现申请",0,0,'',false,$val['money']);//手动转账，默认视为已通过线下转方式处理了该笔提现申请
                    }
                    $r = Db::name('withdrawals')->where(array('id' => $val['id']))->save(array('status' => 2, 'pay_time' => time()));
                    expenseLog($rdata);//支出记录日志
                    // 提现通知
                    $messageLogic->withdrawalsNotice($val['id'], $val['user_id'], $val['money'] - $val['taxfee']);
                }
            }
        }
        $this->success("操作成功!", url('remittance'), 3);
    }


    /**
     * 签到列表
     * @date 2017/09/28
     */
    public function signList()
    {
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
    }


    /**
     * 会员签到 ajax
     * @date 2017/09/28
     */
    public function ajaxsignList()
    {
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
    }

    /**
     * 签到规则设置
     * @date 2017/09/28
     */
    public function signRule()
    {
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
    }

    /**
     * 会员标签列表
     */
    public function labels()
    {
        $p = input('p/d');
        $Label = new UserLabel();
        $label_list = $Label->order('label_order')->page($p, 10)->select();
        View::assign('label_list', $label_list);
        $Page = new Page($Label->count(), 10);
        View::assign('page', $Page);
        return View::fetch();
    }

    /**
     * 添加、编辑页面
     */
    public function labelEdit()
    {
        $label_id = input('id/d');
        if ($label_id) {
            $Label = new UserLabel();
            $label = $Label->where('id', $label_id)->find();
            View::assign('label', $label);
        }
        return View::fetch();
    }

    /**
     * 会员标签添加编辑删除
     */
    public function label()
    {
        $label_info = input();
        $return = ['status' => 0, 'msg' => '参数错误', 'result' => ''];//初始化返回信息
        $userLabelValidate = validate(\app\admin\validate\UserLabel::class);

        $UserLabel = new UserLabel();
        if (request()->isPost()) {
            if ($label_info['label_id']) {
                if (!$userLabelValidate->scene('edit')->batch(true)->check($label_info)) {
                    $return = ['status' => 0, 'msg' => '编辑失败', 'result' => $userLabelValidate->getError()];
                } else {
                    $UserLabel->where('id', $label_info['label_id'])->save($label_info);
                    $return = ['status' => 1, 'msg' => '编辑成功', 'result' => ''];
                }
            } else {
                if (!$userLabelValidate->batch(true)->check($label_info)) {
                    $return = ['status' => 0, 'msg' => '添加失败', 'result' => $userLabelValidate->getError()];
                } else {
                    $UserLabel->insert($label_info);
                    $return = ['status' => 1, 'msg' => '添加成功', 'result' => ''];
                }
            }
        }
        if (request()->isDelete()) {
            $UserLabel->where('id', $label_info['label_id'])->delete();
            $return = ['status' => 1, 'msg' => '删除成功', 'result' => ''];
        }
        $this->ajaxReturn($return);
    }

    /**、
     * 检测该下级是否一条线
     * @param $pid
     * @return array
     */
    function check_first($pid)
    {
        static $arr = [];
        $u = Db::name('users')->where(['first_leader' => ['in', $pid]])->column('user_id');
        $arr = array_merge($arr, $u);
        if ($u) {
            $this->check_first($u);
        }
        return $arr;

    }

    /**
     * 检测更改上级
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function check_first_leader()
    {
        $mobile = input('first_leader_mobile');//手机号码
        $user_id = input('user_id');
        $type = input('type');
        if (IS_POST && $mobile && $user_id) {
            $Users = new \app\common\model\Users();
            $first_leader = $Users::where(['mobile' => $mobile, 'is_lock' => 0])->find();
            $users = Db::name('users')->where(['user_id' => $user_id])->find();
            if (in_array($first_leader['user_id'], $this->check_first($user_id)) || $first_leader['user_id'] == $user_id) {
                $this->ajaxReturn(['status' => 0, 'msg' => '该会员不符合更改上级条件', 'result' => []]);
            }
            if ($users['first_leader'] == $first_leader['user_id'] && $users['first_leader'] > 0) {
                $this->ajaxReturn(['status' => 0, 'msg' => '已经是您的上级', 'result' => []]);
            }
            if ($first_leader) {
                if ($type == 0) {
                    $this->ajaxReturn(['status' => 1, 'msg' => '符合更改上级', 'result' => []]);
                }
                //通过符合条件,更改用户上级
                Db::name('users')->where(['user_id' => $user_id])->save(['first_leader' => $first_leader['user_id'], 'second_leader' => $first_leader['first_leader'], 'third_leader' => $first_leader['second_leader']]);
                Db::name('users')->where(['first_leader' => $user_id])->save(['second_leader' => $first_leader['user_id'], 'third_leader' => $first_leader['first_leader']]);
                Db::name('users')->where(['second_leader' => $user_id])->save(['third_leader' => $first_leader['user_id']]);

                //他上级分销的下线人数要加1
                Db::name('users')->where(array('user_id' => $first_leader['user_id']))->inc('underling_number')->update();
                Db::name('users')->where(array('user_id' => $first_leader['first_leader']))->inc('underling_number')->update();
                Db::name('users')->where(array('user_id' => $first_leader['second_leader']))->inc('underling_number')->update();

                //减少以前上级总人数的字段
                Db::name('users')->where(['user_id' => $users['first_leader']])->dec('underling_number')->update();
                Db::name('users')->where(['user_id' => $users['second_leader']])->dec('underling_number')->update();
                Db::name('users')->where(['user_id' => $users['third_leader']])->dec('underling_number')->update();
                $this->ajaxReturn(['status' => 1, 'msg' => '更改上级成功', 'result' => $first_leader]);
            }
            $this->ajaxReturn(['status' => 0, 'msg' => '不存在该会员', 'result' => []]);
        }
    }


    function get_data($key, $authorization, $url)
    {

        $headers = array();
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
    }


    /**
     * 会员动态列表
     * Author: 徐文杨
     * @date 2018/04/27
     */
    public function momentsList()
    {
 
        $count = Db::name('moments')->count();
        $Page = new Page($count);
        $show = $Page->show();
        $list = Db::name('moments')->alias('m')->limit($Page->firstRow, $Page->listRows)
            ->join('users u', 'u.user_id = m.user_id', 'LEFT')
            ->field("m.*,nickname,mobile")
            ->order(['m.is_delete'=>'asc','m.status'=>'asc','m.add_time'=>'desc'])->select();
        foreach ($list as $k => $v) {
            $comment = Db::name('moments_comment')->where('moments_id', $v['moments_id'])->count('comment_id');
            $list[$k]['comment'] = $comment;
        }
        View::assign('page', $show);
        View::assign('pager', $Page);
        View::assign('list', $list);
        View::assign('status', Moments::$STATUS);
        return View::fetch();
    }


    /**
     * 查看某一动态详情
     * Author: 徐文杨
     * @date 2018/04/27
     */
    public function seeMoments()
    {
        $id = input('post.moments_id/d', 0);
        //获取该动态详情
        $this->getFindMoments($id);
        return View::fetch();
    }

    /**
     *  更新动态审核状态
     */
    public function moments_update()
    {
        $status = input('status/d', 0);
        $id = input('id/a');
        $remark = input('remark/s', '');
        $ids = implode(',', $id);
        $where['moments_id'] = array('in', $ids);
        $r = Db::name('moments')->where($where)->update(['status' => $status, 'moments_remark' => $remark]);
        if ($r !== false) {
            $this->ajaxReturn(array('status' => 1, 'msg' => "操作成功"), 'JSON');
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => "操作失败"), 'JSON');
        }
    }

    /**
     * 获取该动态详情
     * @param $id
     */
    public function getFindMoments($id)
    {
        $find = Db::name('moments')->alias('m')->join('users u', 'u.user_id = m.user_id', 'LEFT')->field("m.*,nickname,mobile")->find($id);

        if (strpos($find['moments_imgs'], 'mp4')) {
            $find['mp4'] = explode(',', $find['moments_imgs']);
            $find['img'] = array();
        } else {
            $find['img'] = explode(',', $find['moments_imgs']);
            $find['mp4'] = array();
        }
        View::assign('vo', $find);
    }

    /**
     * 查看某一动态评论列表
     * Author: 徐文杨
     * @date 2018/04/28
     */
    public function seeComment()
    {
        $id = input('post.moments_id');
        //获取该动态详情
        $this->getFindMoments($id);
        //某动态获取评论列表  
        $count = Db::name('moments_comment')->where('moments_id', $id)->count();
        $Page = new Page($count);
        $show = $Page->show();
        $list = Db::name('moments_comment')->alias('m')
            ->where('moments_id', $id)
            ->limit($Page->firstRow, $Page->listRows)
            ->join('users u', 'u.user_id = m.user_id', 'LEFT')
            ->field("m.*,nickname,mobile")
            ->order(['m.is_delete'=>'asc','m.status'=>'asc','m.add_time'=>'asc'])->select();

        View::assign('page', $show);
        View::assign('pager', $Page);
        View::assign('list', $list);
        View::assign('status', Moments::$STATUS);
        return View::fetch();
    }

    /**
     * 查看某一动态点赞列表
     * Author: 徐文杨
     * @date 2018/04/28
     */
    public function seeLike()
    {
        $id = input('post.moments_id');
        //获取该动态详情
        $this->getFindMoments($id);
        //某动态获取评论列表    
        $count = Db::name('moments_like')->count();
        $Page = new Page($count);
        $show = $Page->show();
        $list = Db::name('moments_like')->alias('m')
            ->where('moments_id', $id)
            ->limit($Page->firstRow, $Page->listRows)
            ->join('users u', 'u.user_id = m.user_id', 'LEFT')
            ->field("m.*,nickname,mobile")
            ->order('m.add_time asc')
            ->select();

        View::assign('page', $show);
        View::assign('pager', $Page);
        View::assign('list', $list);
        View::assign('status', Moments::$STATUS);        
        return View::fetch();
    }


    /**
     * 会员评论列表
     * Author: 徐文杨
     * @date 2018/04/28
     */
    public function commentList()
    {
        $count = Db::name('moments_comment')->count();
        $Page = new Page($count);
        $show = $Page->show();
        $list = Db::name('moments_comment')->alias('m')->limit($Page->firstRow, $Page->listRows)
            ->join('users u', 'u.user_id = m.user_id', 'LEFT')
            ->field("m.*,nickname,mobile")
            ->order(['m.is_delete'=>'asc','m.status'=>'asc','m.add_time'=>'asc'])->select();

        View::assign('page', $show);
        View::assign('pager', $Page);
        View::assign('list', $list);
        View::assign('status', Moments::$STATUS);
        return View::fetch();

    }

    /**
     * 查看某一评论详情
     * Author: 徐文杨
     * @date 2018/04/28
     */
    public function editComment()
    {
        $id = input('post.comment_id/d', 0);
        $find = Db::name('moments_comment')->alias('m')->join('users u', 'u.user_id = m.user_id', 'LEFT')->field("m.*,nickname,mobile")->find($id);
        View::assign('vo', $find);
        return View::fetch();
    }

    /**
     *  更新审核状态
     */
    public function comment_update()
    {
        $status = input('status/d', 0);
        $remark = input('remark/s', '');
        $id = input('id/a');
        $ids = implode(',', $id);
        $where['comment_id'] = array('in', $ids);
        $r = Db::name('moments_comment')->where($where)->update(['status' => $status, 'comment_remark' => $remark]);
        if ($r !== false) {
            $this->ajaxReturn(array('status' => 1, 'msg' => "操作成功"), 'JSON');
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => "操作失败"), 'JSON');
        }
    }
    public function askall(){
        $where='';       
        $count = Db::name('askall_question')->count();
        $Page = new Page($count);
        $show = $Page->show();
        $list = Db::name('askall_question')->alias('m')->where($where)->limit($Page->firstRow,$Page->listRows)
            ->join('users s','s.user_id = m.user_id','INNER')
            ->field("m.*,nickname,mobile")
            ->order(['m.status'=>'asc','m.audit'=>'asc','m.createtime'=>'desc'])
            ->select()->toArray();
        if(!empty($list))
        {
            $goods_id_arr = get_arr_column($list, 'goods_id');
            $goods_list = Db::name('Goods')->where("goods_id", "in", implode(',', $goods_id_arr))->column('goods_name','goods_id');
        }
        foreach ($list as $k=>$v){
            $comment_num =  Db::name('askall_comment')->where('qid',$v['qid'])->count('cid');
            $list[$k]['comment'] = $comment_num;
        }
        View::assign('page', $show);
        View::assign('pager', $Page);
        View::assign('list', $list);
        View::assign('goods_list', $goods_list);
        View::assign('status', array(0=>'未审核',1=>'通过',2=>'不通过'));
        return View::fetch();
    }

    public function askall_question(){
        $id = input('qid/d',0);
        //获取该动态详情
        $this->getFindAskallQuestions($id);
        return View::fetch();
    }

    public function getFindAskallQuestions($id){
        $find = Db::name('askall_question')->alias('m')
            ->join('users s','s.user_id = m.user_id','INNER')
            ->field("m.*,nickname,mobile")->find($id);
        if(!empty($find))
        {
            $goods_list = Db::name('Goods')->where(["goods_id"=>$find['goods_id']])->column('goods_name','goods_id');
        }
        View::assign('vo',$find);
        View::assign('goods_list', $goods_list);
    }
    public function askall_comment(){
        $id = input('qid');
        //获取该动态详情
        $this->getFindAskallQuestions($id);
        //某动态获取评论列表
        $count = Db::name('askall_comment')->where('qid',$id)->count();
        $Page = new Page($count);
        $show = $Page->show();
        $list = Db::name('askall_comment')->alias('m')
            ->where('qid',$id)
            ->limit($Page->firstRow,$Page->listRows)
            ->order(['m.status'=>'asc','m.audit'=>'asc','m.createtime'=>'asc'])->select();
        if(!empty($list))
        {
            $uid_arr = get_arr_column($list, 'user_id');
            $touid_arr = get_arr_column($list, 'to_user_id');
            $all_uid_arr = array_unique(array_merge($uid_arr,$touid_arr));
            $uid_list = Db::name('users')->where("user_id", "in", implode(',', $all_uid_arr))->column("user_id,nickname,mobile",'user_id');
        }
        View::assign('page', $show);
        View::assign('pager', $Page);
        View::assign('list', $list);
        View::assign('uid_list', $uid_list);
        View::assign('status', array(0=>'未审核',1=>'通过',2=>'不通过'));
        return View::fetch();
    }
    /**
     *  更新问大家问题审核状态
     */
    public function askall_question_update()
    {
        $audit = input('audit/d', 0);
        $id = input('id/a');
        $ids = implode(',', $id);
        $where['qid'] = array('in', $ids);
        $r = Db::name('askall_question')->where($where)->update(['audit' => $audit]);
        if ($r !== false) {
            $this->ajaxReturn(array('status' => 1, 'msg' => "操作成功"), 'JSON');
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => "操作失败"), 'JSON');
        }
    }
    /**
     *  更新问大家评论审核状态
     */
    public function askall_comment_update()
    {
        $audit = input('audit/d', 0);
        $id = input('id/a');
        $ids = implode(',', $id);
        $where['cid'] = array('in', $ids);
        $r = Db::name('askall_comment')->where($where)->update(['audit' => $audit]);
        if ($r !== false) {
            $this->ajaxReturn(array('status' => 1, 'msg' => "操作成功"), 'JSON');
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => "操作失败"), 'JSON');
        }
    }
    /**
     *  更新问大家评论审核状态
     */
    public function askall_sensitive()
    {
        $inc_type =  'askall_sensitive';
        View::assign('inc_type',$inc_type);
        $config = tpCache($inc_type);
        View::assign('config',$config);//当前配置项
        return View::fetch();
    }
}