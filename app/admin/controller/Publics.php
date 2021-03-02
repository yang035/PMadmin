<?php
namespace app\admin\controller;
use app\common\controller\Common;
use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminCompany;
use app\admin\model\Category;
use app\admin\model\AdminDepartment;
use think\Db;
use think\Exception;

class Publics extends Common
{
    public function index()
    {
        $model = model('AdminUser');
        if ($this->request->isPost()) {
            $username = input('post.username/s');
            $password = input('post.password/s');
            $company_id = input('post.company_id');
            if (!$model->login($username, $password,$company_id)) {
                return $this->error($model->getError(), url('index'));
            }

            $role_id = session('admin_user.role_id');
            $cid = session('admin_user.cid');
            if ($role_id == 8 || $role_id == 9) {
                $this->assign('_admin_menu_current', array('url'=>'company/edit'));
                return $this->success('登陆成功，页面跳转中...', url('company/edit', ['id' => $cid]),'',1);
            }
//            if ($role_id > 3) {
//                $this->assign('_admin_menu_current', array('url'=>'project/mytask'));
//                return $this->success('登陆成功，页面跳转中...', url('Project/mytask', ['type' => 1]),'',1);
//            }
//            $this->assign('_admin_menu_current', array('url'=>'Assignment/mytask'));
//            if ($role_id <= 3) {
//                return $this->success('登陆成功，页面跳转中...', url('Assignment/index'), '', 1);
//            }
        }

        if ($model->isLogin()) {
            $role_id = session('admin_user.role_id');
            if ($role_id == 8 || $role_id == 9) {
                $this->redirect(url('company/index', '', true, true));
            }
            $this->redirect(url('Shop/enter', '', true, true));
        }
        return $this->fetch();
    }

    public function pwd1()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $username = $data['username'];
            $company_id = $data['company_id'];
            $model = model('AdminUser');
            $user = $model->getUserRow($username,$company_id);
            if (!$user) {
                return false;
            }
            $mobile = $user->mobile;
            $mobile = substr($mobile, 0, 3).'****'.substr($mobile, 7);
            return $this->redirect('pwd2',['mobile' => $mobile,'username' => $username,'company_id' => $company_id]);
        }
        return $this->fetch();
    }

    public function pwd2()
    {
        if ($this->request->isPost()){
            $model = model('AdminUser');
            $data = $this->request->post();
            $username = $data['username'];
            $company_id = $data['company_id'];
            $user = $model->getUserRow($username,$company_id);
            if (!$user) {
                return $this->error('用户不存在');
            }
            $mobile = $user->mobile;
            $redis = service('Redis');
            $checkcode = $redis->get("pm:checkcode:{$mobile}");
            if ($data['checkcode'] == $checkcode){
                return $this->redirect('pwd3',['username' => $username,'company_id' => $company_id]);
            }else{
                return $this->error('验证码错误');
            }
        }
        return $this->fetch();
    }

    public function pwd3()
    {
        if ($this->request->isPost()){
            $data = $this->request->post();
            if ($data['password'] != $data['re_password']){
                return $this->error('两次输入密码不一致');
            }
            $model = model('AdminUser');
            $username = $data['username'];
            $company_id = $data['company_id'];
            $user = $model->getUserRow($username,$company_id);
            if (!$user) {
                return $this->error('用户不存在');
            }
            $res = $model->where(['id'=>$user['id']])->update(['password'=>password_hash($data['password'], PASSWORD_DEFAULT)]);
            if ($res){
                return $this->success('修改密码成功,返回登录','index');
            }else{
                return $this->error('修改密码失败');
            }
        }
        return $this->fetch();
    }

    public function sendCode(){
        if ($this->request->isAjax()){
            $data = $this->request->post();
            $model = model('AdminUser');
            $username = $data['username'];
            $company_id = $data['company_id'];
            $user = $model->getUserRow($username,$company_id);
            if (!$user) {
                return false;
            }
            $mobile = $user->mobile;
            $code = mt_rand(100000,999999);

            $redis = service('Redis');
            $redis->set("pm:checkcode:{$mobile}",$code,300);

            $args = [
                'phoneNumbers'=>$mobile,
                'signName'=>'麦粒谷粒',
                'templateCode'=>'SMS_212135092',
                'templateParam'=>json_encode(['code'=>$code]),
            ];
            $c = new Common();
            $res = $c->sendSms($args,1);
            if ($res){
                return true;
            }else{
                return false;
            }
        }
    }

    public function logout(){
        model('AdminUser')->logout();
        return $this->success('退出成功', url('publics/index', '', true, true),'',1);
    }


    public function icon() {
        return $this->fetch();
    }

    public function unlocked()
    {
        $_pwd = input('post.password');
        $model = model('AdminUser');
        $login = $model->isLogin();
        if (!$login) {
            return $this->error('登录信息失效，请重新登录！');
        }
        $password = $model->where('id', $login['uid'])->value('password');
        if (!$password) {
            return $this->error('登录异常，请重新登录！');
        }
        if (!password_verify($_pwd, $password)) {
            return $this->error('密码错误，请重新输入！');
        }
        return $this->success('解锁成功');
    }
    public function register(){
        $p = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!isset($data['sys_type'])){
                $data['sys_type'] = 1;
            }
            switch ($data['sys_type']){
                case 1:
                    $data['sys_type'] = 1;
                    break;
                case 2:
                    $data['sys_type'] = 2;
                    $data['role_id'] = 8;
                    break;
                case 3:
                    $data['sys_type'] = 3;
                    $data['role_id'] = 9;
                    break;
                case 4:
                    $data['sys_type'] = 1;
                    break;
                default:
                    $data['sys_type'] = 1;
            }

            $data['last_login_ip'] = '';
            $data['auth'] = '';
            $data['status'] = 0;

            if (0 == $data['type']) {
                //事务开始
                Db::startTrans();
                try {
                    $tmp = [
                        'name' => $data['name'],
                    ];
                    $f1 = AdminCompany::where($tmp)->find();
                    if (!$f1) {
                        $tmp1 = [
                            'name' => $data['name'],
                            'register_mobile' => $data['mobile'],
                            'tuijianren' => $data['tuijianren'],
                            'sys_type' => $data['sys_type'],
                            'gys_type' => (2 == $data['sys_type']) ? $data['gys_type'] : 1,
                        ];
                        $result = AdminCompany::create($tmp1);
                        $dep_data = [
                            'code' => $result['id'] . 'd',
                            'cid' => $result['id'],
                            'user_id' => 1,
                            'name' => $result['name'],
                            'remark' => $result['name']
                        ];
                        $d = AdminDepartment::create($dep_data);

                        $categoty_data = [
                            'code' => $result['id'] . 'g',
                            'cid' => $result['id'],
                            'user_id' => 1,
                            'name' => $result['name'],
                            'remark' => $result['name']
                        ];
                        Category::create($categoty_data);

                        $score_rule = [
                            'code' => $result['id'].'r',
                            'pid' => 0,
                            'cid' => $result['id'],
                            'name' => $result['name'],
                            'create_time' => time(),
                            'update_time' => time(),
                        ];
                        db('score_rule')->insert($score_rule);
                    } else {
                        $result = $f1;
                        $d = AdminDepartment::where($tmp)->find();
                    }

                    $data['company_id'] = $result['id'];
                    $data['role_id'] = isset($data['role_id']) ? $data['role_id'] : 3;
                    $data['department_id'] = $d['id'];
                    $data['status'] = 1;
                    // 验证
                    $r = $this->validate($data, 'AdminUser.register');
                    if ($r !== true){
                        $u = false;
                    }else{
                        unset($data['password_confirm'],$data['__token__'],$data['type'], $data['name']);
                        $u = UserModel::create($data);

                        $t = [
                            'id' => $u['id'],
                            'id_card' => date('Y').$u['id'],
                        ];
                        UserModel::update($t);

                        $score = [
                            'subject_id' => 0,
                            'project_id' => 0,
                            'cid' => $data['company_id'],
                            'project_code' => '',
                            'user' => $u['id'],
                            'gl_add_score' => config('other.gl_give'),
                            'remark' => "新用户注册所得GL，总计超过10000斗可用",
                            'user_id' => 0,
                            'is_lock' => 1,
                            'create_time' => time(),
                            'update_time' => time(),
                        ];
                        db('score')->insert($score);
                    }

                    //事务提交
                    Db::commit();
                } catch (\Exception $e) {
//                  事务回滚
                    Db::rollback();
                }
                if ($u){
                    return $this->success('注册成功',url('index'));
                }else{
                    return $this->error($r);
                }
            } else {
                unset($data['type'], $data['name']);
                $data['company_id'] = 4;
                $data['role_id'] = isset($data['role_id']) ? $data['role_id'] : 3;
                $data['department_id'] = 27;
                $data['status'] = 1;

                // 验证
                $r = $this->validate($data, 'AdminUser.register');
                if($r !== true) {
                    return $this->error($r);
                }
                unset($data['password_confirm'],$data['__token__']);

                $u = UserModel::create($data);
                if (!$u) {
                    return $this->error('注册失败');
                }

                $tmp = [
                    'id' => $u['id'],
                    'id_card' => date('Y').$u['id'],
                ];
                UserModel::update($tmp);

                $score = [
                    'subject_id' => 0,
                    'project_id' => 0,
                    'cid' => $data['company_id'],
                    'project_code' => '',
                    'user' => $u['id'],
                    'gl_add_score' => config('other.gl_give'),
                    'remark' => "新用户注册所得GL，总计超过10000斗可用",
                    'user_id' => 0,
                    'is_lock' => 1,
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                db('score')->insert($score);
            }
            return $this->success('注册成功',url('index'));
        }
        $type = isset($p['t']) ? $p['t'] : 1;
        $this->assign('company_option', AdminCompany::getOption());
        $this->assign('sys_type', AdminCompany::getSysType($type));
        $this->assign('gys_type', AdminCompany::getGysType());
        $this->assign('c_p', '麦粒谷粒公司'.mt_rand(10000,1000000));
        return $this->fetch();
    }

    public function checkUser($username){
        $str = UserModel::checkUser($username);
        return json($str);
    }
}
