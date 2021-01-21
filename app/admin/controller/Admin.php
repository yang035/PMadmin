<?php
namespace app\admin\controller;

use app\admin\model\Region;
use app\common\controller\Common;
use app\admin\model\AdminMenu as MenuModel;
use app\admin\model\AdminRole as RoleModel;
use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminLog as LogModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\NoticeItem as NoticeModel;
use app\admin\model\Project as ProjectModel;
use app\admin\model\AdminDepartment;
use think\Controller;
use think\Db;
/**
 * 后台公共控制器
 * @package app\admin\controller
 */
class Admin extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
        $model = new UserModel();
        // 判断登陆
        $login = $model->isLogin();
        if (!$login['uid']) {
            return $this->error('请登陆之后在操作！', 'Publics/index');
        }
        $this->score_value = '';
        if (!defined('ADMIN_ID')){
            define('ADMIN_ID', $login['uid']);
            define('ADMIN_ROLE', $login['role_id']);
        }

        $c_menu = MenuModel::getInfo();
//        print_r($c_menu);exit();
        if (!$c_menu) {
            return $this->error('节点不存在或者已禁用！');
        }

        // 检查权限
        if (!RoleModel::checkAuth($c_menu['id'])) {
            $url = input('server.http_referer');
            // 如果没有后台首页的登录权限，直接退出，避免出现死循环跳转
            if ($c_menu['url'] == 'admin/index/index') {
                $url = ROOT_DIR.config('sys.admin_path');
                model('AdminUser')->logout();
            }
            return $this->error('['.$c_menu['title'].'] 访问权限不足', $url);
        }

        // 系统日志记录
        $log = [];
        $log['uid'] = ADMIN_ID;
        $log['title'] = $c_menu['title'];
        $log['url'] = $c_menu['url'];
        $log['remark'] = '浏览数据';
        if ($this->request->isPost()) {
            $log['remark'] = '保存数据';

            if (strpos($c_menu['url'],'add') !== false) {
                $score_add_edit_del = config('score.score_add_edit_del');
                $sc = [
                    'project_id' => 0,
                    'cid' => session('admin_user.cid'),
                    'user' => session('admin_user.uid'),
                    'ml_add_score' => 0,
                    'ml_sub_score' => 0,
                    'gl_add_score' => $score_add_edit_del['score_add'],
                    'gl_sub_score' => 0,
                    'remark' => $c_menu['title'],
                    'url' => $c_menu['url'],
                ];
                if (ScoreModel::addScore($sc)) {
                    $this->score_value = ",鼓励{$score_add_edit_del['score_add']}GL";
                }
            }
            //暂时只算添加鼓励分
//            elseif (strpos($c_menu['url'],'edit') !== false){
//                $score_add_edit_del = config('score.score_add_edit_del');
//                $sc = [
//                    'project_id' => 0,
//                    'user' => session('admin_user.uid'),
//                    'ml_add_score' => 0,
//                    'ml_sub_score' => 0,
//                    'gl_add_score' => $score_add_edit_del['score_edit'],
//                    'gl_sub_score' => 0,
//                    'remark' => $c_menu['title'],
//                    'url' => $c_menu['url'],
//                ];
//
//                if (ScoreModel::addScore($sc)) {
//                    $this->score_value = ",鼓励{$score_add_edit_del['score_edit']}GL";
//                }
//            }elseif (strpos($c_menu['url'],'del') !== false){
//                $score_add_edit_del = config('score.score_add_edit_del');
//                $sc = [
//                    'project_id' => 0,
//                    'user' => session('admin_user.uid'),
//                    'ml_add_score' => 0,
//                    'ml_sub_score' => 0,
//                    'gl_add_score' => $score_add_edit_del['score_del'],
//                    'gl_sub_score' => 0,
//                    'remark' => $c_menu['title'],
//                    'url' => $c_menu['url'],
//                ];
//                if (ScoreModel::addScore($sc)) {
//                    $this->score_value = ",鼓励{$score_add_edit_del['score_del']}GL";
//                }
//            }

        }
//        $log_result = LogModel::where($log)->find();
        $log['param'] = json_encode(input('param.'));
        $log['ip'] = $this->request->ip();
        $log['computer_name'] = '';
        $log['os'] = '';
        $log['user_agent'] = '';
//        if (!$log_result) {
            LogModel::create($log);
//        } else {
//            $log['id'] = $log_result->id;
//            $log['count'] = $log_result->count+1;
//            LogModel::update($log);
//        }

        // 如果不是ajax请求，则读取菜单
        if (!$this->request->isAjax()) {
            // 获取当前访问的节点信息
            $this->assign('_admin_menu_current', $c_menu);
            $_bread_crumbs = MenuModel::getBrandCrumbs($c_menu['id']);
            $this->assign('_bread_crumbs', $_bread_crumbs);
            // 获取当前访问的节点的顶级节点
            $this->assign('_admin_menu_parents', current($_bread_crumbs));
            // 获取导航菜单
            $this->assign('_admin_menu', MenuModel::getMainMenu());
            // 分组切换类型 0单个分组[有链接]，1分组切换[有链接]，2分组切换[无链接]，3无需分组切换，具体请看后台layout.php
            $this->assign('tab_type', 0);
            // tab切换数据
            // $tab_data = [
            //     ['title' => '后台首页', 'url' => 'admin/index/index'],
            // ];
            // current 可不传
            // $this->assign('tab_data', ['menu' => $tab_data, 'current' => 'admin/index/index']);
            $this->assign('tab_data', '');
            // 列表页默认数据输出变量
            $this->assign('data_list', '');
            $this->assign('pages', '');
            // 编辑页默认数据输出变量
            $this->assign('data_info', '');
            $this->assign('form_data', '');
            $this->assign('admin_user', $login);
            $this->assign('languages', model('AdminLanguage')->lists());

            $free_space = byte_format(disk_free_space($_SERVER['DOCUMENT_ROOT']));
            $total_space = byte_format(disk_total_space ($_SERVER['DOCUMENT_ROOT']));
            $per_space = ($total_space-$free_space).'/'.$total_space;
            $this->assign('per_space', $per_space);

            $notice = NoticeModel::getItem1();
            $this->assign('notice', $notice);
        }
    }

    protected function getActUrl() {
        $model      = request()->module();
        $controller = request()->controller();
        $action     = request()->action();
        return $model.'/'.$controller.'/'.$action;
    }

    public function status() {
        $val   = input('param.val');
        $ids   = input('param.ids/a') ? input('param.ids/a') : input('param.id/a');
        $table = input('param.table');
        $f = input('param.f');
        $f = empty($f) ? 'status' : $f;
        $field = input('param.field', $f);

        if (empty($ids)) {
            return $this->error('参数传递错误[1]！');
        }
        if (empty($table)) {
            return $this->error('参数传递错误[2]！');
        }
        // 以下表操作需排除值为1的数据
        if ($table == 'admin_menu' || $table == 'admin_user' || $table == 'admin_role' || $table == 'admin_module') {
            if (in_array('1', $ids) || ($table == 'admin_menu' && in_array('2', $ids))) {
                return $this->error('系统限制操作');
            }
        }
         // 获取主键
        $pk = Db::name($table)->getPk();
        $map = [];
        $map[$pk] = ['in', $ids];

        $res = Db::name($table)->where($map)->setField($field, $val);
        if ($res === false) {
            return $this->error('状态设置失败');
        }
        return $this->success('状态设置成功');
    }

    public function del() {
        $ids   = input('param.ids/a') ? input('param.ids/a') : input('param.id/a');
        $table = input('param.table');
        if (empty($ids)) {
            return $this->error('无权删除(原因：可能您选择的是系统菜单)');
        }
        // 禁止以下表通过此方法操作
        if ($table == 'admin_user' || $table == 'admin_role') {
            return $this->error('非法操作');
        }

        // 以下表操作需排除值为1的数据
        if ($table == 'admin_menu' || $table == 'admin_module') {
            if ((is_array($ids) && in_array('1', $ids))) {
                return $this->error('禁止操作');
            }
        }
            
        // 获取主键
        $pk = Db::name($table)->getPk();
        $map = [];
        $map[$pk] = ['in', $ids];

        $res = Db::name($table)->where($map)->delete();
        if ($res === false) {
            return $this->error('删除失败');
        }
        return $this->success('删除成功');
    }

    public function sort() {
        $ids   = input('param.ids/d') ? input('param.ids/d') : input('param.id/d');
        $table = input('param.table');
        $field = input('param.field/s', 'sort');
        $val   = input('param.val/d');
        // 获取主键
        $pk = Db::name($table)->getPk();
        $map = [];
        $map[$pk] = ['in', $ids];
        $res = Db::name($table)->where($map)->setField($field, $val);
        if ($res === false) {
            return $this->error('排序设置失败');
        }
        return $this->success('排序设置成功');
    }

    public function scoreConfig(){
        return config('config_score');
    }

    public function setKV(){
        if ($this->request->isAjax()){
            $data = $this->request->post();
//            $r = Db::name($data['t'])->where($data['k'],$data['v'])->find();
//            $res = false;
//            switch ($data['k']){
//                case 'mobile':
//                    if (!$r){
//                        $res = Db::name($data['t'])->where('id',$data['id'])->setField($data['k'],$data['v']);
//                    }
//                    break;
//                default:
//                    break;
//            }
            $res = Db::name($data['t'])->where('id',$data['id'])->setField($data['k'],$data['v']);
            if ($res){
                return $this->success('操作成功');
            }else{
                return $this->success('操作失败');
            }
        }
    }

    public function getFlowUser($id,$p=0){
        $res = ProjectModel::getRowJoinSubject1($id);
        $uid_arr = json_decode($res['leader_user'],true);
        $tmp = [];
        $uid = session('admin_user.uid');
        if ($uid_arr){
            foreach ($uid_arr as $k=>$v){
                $u_row = UserModel::getUserById1($k);
                if ($u_row){
                    $tmp[$k] = $u_row['realname'];
                }
            }
            if (array_key_exists($uid,$tmp)){
                $tmp = [];
            }
        }

//        $row['manager_user_id'] = $this->deal_data($res['manager_user']);
//        $row['manager_user'] = $this->deal_data_id($res['manager_user']);
        $chain_arr = AdminDepartment::getChainUser();
        $chain_sub = array_slice($chain_arr,0,2);
        array_push($chain_sub,$tmp);
        $new_arr = array_filter($chain_sub);
        $new_arr = array_reverse($new_arr);
        $row['manager_user'] = user_array2($new_arr);
        $s = '';
        if ($new_arr){
            foreach ($new_arr as $k=>$v) {
                $k++;
                $s .= "[{$k}]".implode(',',$v).' ';
            }
        }
        $row['manager_user_id'] = $s;
        return $row;
    }

    public function getFlowUser1(){
        $chain_arr = AdminDepartment::getChainUser();
        $chain_sub = array_slice($chain_arr,0,2);
        $new_arr = array_filter($chain_sub);
        $new_arr = array_reverse($new_arr);
        $row['manager_user'] = user_array2($new_arr);
        $s = '';
        if ($new_arr){
            foreach ($new_arr as $k=>$v) {
                $k++;
                $s .= "[{$k}]".implode(',',$v).' ';
            }
        }
        $row['manager_user_id'] = $s;
        return $row;
    }

    public function getFlowUser2(){
        $chain_arr = AdminDepartment::getChainUser();
        $chain_sub = array_slice($chain_arr,0,2);

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user,true);
            $user_insert = [0=>[$user['hr_user']=>$user['hr_user_id']]];
        }
        array_splice($chain_sub,1,0,$user_insert);

        $new_arr = array_filter($chain_sub);
        $new_arr = array_reverse($new_arr);
        $row['manager_user'] = user_array2($new_arr);
        $s = '';
        if ($new_arr){
            foreach ($new_arr as $k=>$v) {
                $k++;
                $s .= "[{$k}]".implode(',',$v).' ';
            }
        }
        $row['manager_user_id'] = $s;
        return $row;
    }

    public function getFlowUser3($id){
        $res = ProjectModel::getRowJoinSubject($id);
        $uid_arr = json_decode($res['leader_user'],true);
        $tmp = [];
        $uid = session('admin_user.uid');
        if ($uid_arr){
            foreach ($uid_arr as $k=>$v){
                $u_row = UserModel::getUserById1($k);
                if ($u_row){
                    $tmp[$k] = $u_row['realname'];
                }
            }
            if (array_key_exists($uid,$tmp)){
                $tmp = [];
            }
        }

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user,true);
            $user_insert = [0=>[$user['accountant_user']=>$user['accountant_user_id']]];
        }

        array_push($user_insert,$tmp);
        $new_arr = array_filter($user_insert);
        $new_arr = array_reverse($new_arr);
        $row['manager_user'] = user_array2($new_arr);
        $s = '';
        if ($new_arr){
            foreach ($new_arr as $k=>$v) {
                $k++;
                $s .= "[{$k}]".implode(',',$v).' ';
            }
        }
        $row['manager_user_id'] = $s;
        return $row;
    }

    public function getFlowUser4()
    {
        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $default_user = json_decode($default_user, true);
            $tmp1 = [
                0 => [$default_user['finance1_user'] => ''],
                1 => [$default_user['finance2_user'] => ''],
                2 => [$default_user['finance3_user'] => ''],
            ];
            $tmp2 = [
                $default_user['finance1_user'] => '',
                $default_user['finance2_user'] => '',
                $default_user['finance3_user'] => '',
            ];
            return [$tmp1, $tmp2];
        } else {
            return [];
        }
    }

    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        $x_user = [];
        if ($x_user_arr) {
            foreach ($x_user_arr as $key => $val) {
                $real_name = UserModel::getUserById($key)['realname'];
                if ('a' == $val) {
                    $real_name = "<font style='color: orangered'>" . $real_name . "</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',', $x_user);
        }
    }

    public function deal_data_id($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        if ($x_user_arr) {
            $tmp = array_keys($x_user_arr);
            return implode(',', $tmp);
        }
        return '';
    }

    public function getCity($province,$type=0){
        return Region::getCity($province,$type);
    }
}
