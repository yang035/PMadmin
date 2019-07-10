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
            if (!$model->login($username, $password)) {
                return $this->error($model->getError(), url('index'));
            }
            return $this->success('登陆成功，页面跳转中...', url('index/index'),'',1);
        }

        if ($model->isLogin()) {
            $this->redirect(url('index/index', '', true, true));
        }
//        echo 123;exit();
//        $this->assign('_admin_menu_current', array('url'=>''));
        return $this->fetch();
    }

    public function logout(){
        model('AdminUser')->logout();
        $this->redirect(ROOT_DIR.'admin.php');
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
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['last_login_ip'] = '';
            $data['auth'] = '';
            $data['status'] = 0;
            // 验证
            $result = $this->validate($data, 'AdminUser.register');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['password_confirm'],$data['__token__']);

            if (0 == $data['type']) {
                //事务开始
                Db::startTrans();
                try {
                    $tmp = [
                        'name' => $data['name'],
                    ];
                    $f1 = AdminCompany::where($tmp)->find();
                    if (!$f1) {
                        $result = AdminCompany::create($tmp);
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
                    } else {
                        $result = $f1;
                        $d = AdminDepartment::where($tmp)->find();
                    }
                    unset($data['type'], $data['name']);
                    $data['company_id'] = $result['id'];
                    $data['role_id'] = 3;
                    $data['department_id'] = $d['id'];
                    $data['status'] = 1;
                    $u = UserModel::create($data);

                    $score = [
                        'subject_id' => 0,
                        'project_id' => 0,
                        'cid' => $data['company_id'],
                        'project_code' => '',
                        'user' => $u['id'],
                        'gl_add_score' => 1000,
                        'remark' => "新用户注册所得GL",
                        'user_id' => 0,
                        'create_time' => time(),
                        'update_time' => time(),
                    ];
                    db('score')->insert($score);

                    //事务提交
                    Db::commit();
                } catch (\Exception $e) {
                    //事务回滚
                    Db::rollback();
                    return $this->error('注册失败');
                }
            } else {
                unset($data['type'], $data['name']);
                $data['company_id'] = 3;
                $data['role_id'] = 3;
                $data['department_id'] = 25;
                $data['status'] = 1;
                $u = UserModel::create($data);
                if (!$u) {
                    return $this->error('注册失败');
                }
                $score = [
                    'subject_id' => 0,
                    'project_id' => 0,
                    'cid' => $data['company_id'],
                    'project_code' => '',
                    'user' => $u['id'],
                    'gl_add_score' => 1000,
                    'remark' => "新用户注册所得GL",
                    'user_id' => 0,
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                db('score')->insert($score);
            }
            return $this->success('注册成功',url('index'));
        }
        $this->assign('company_option', AdminCompany::getOption());
        return $this->fetch();
    }
}
